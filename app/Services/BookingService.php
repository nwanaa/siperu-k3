<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Notifications\BookingStatusUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Seluruh aturan antrean, deteksi bentrok, dan verifikasi ada di sini,
 * supaya form peminjam, halaman PJ, dan tes memakai logika yang sama.
 *
 * Konkurensi: setiap perubahan mengunci baris ruangan lebih dulu, sehingga
 * dua pengajuan/persetujuan ke ruangan yang sama selalu diproses bergantian.
 */
class BookingService
{
    /** Peminjam mengajukan. Status Pending jika slot kosong, Antrean jika sudah ada yang menunggu. */
    public function submit(User $user, array $data, UploadedFile $letter): Booking
    {
        $path = $letter->store('letters'); // disk privat, tidak bisa diakses lewat URL publik

        try {
            $booking = DB::transaction(function () use ($user, $data, $path) {
                $this->lockRoom((int) $data['room_id']);

                $overlap = fn () => Booking::overlapping(
                    (int) $data['room_id'], $data['date'], $data['start_time'], $data['end_time']
                );

                if ($overlap()->status(BookingStatus::Approved)->exists()) {
                    throw ValidationException::withMessages([
                        'start_time' => 'Slot ini sudah disetujui untuk peminjam lain. Silakan pilih waktu lain.',
                    ]);
                }

                $taken = $overlap()->status(...BookingStatus::open())->exists();

                return Booking::create([
                    'user_id' => $user->id,
                    'room_id' => $data['room_id'],
                    'date' => $data['date'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'participants' => $data['participants'],
                    'purpose' => $data['purpose'],
                    'letter_path' => $path,
                    'status' => $taken ? BookingStatus::Queued : BookingStatus::Pending,
                ]);
            });
        } catch (\Throwable $e) {
            Storage::delete($path);
            throw $e;
        }

        $booking->load('room.pj', 'user');

        if ($booking->status === BookingStatus::Queued) {
            $this->notify($booking->user, $booking,
                "Pengajuan {$booking->code} masuk antrean ke-{$booking->queue_position} karena ada pengajuan lain pada jam yang sama. Anda akan diberi tahu jika statusnya berubah.");
        } else {
            $this->notify($booking->user, $booking,
                "Pengajuan {$booking->code} berhasil dikirim dan menunggu verifikasi PJ.");
            $this->notify($booking->room->pj, $booking,
                "Pengajuan baru {$booking->code} dari {$booking->user->name} untuk {$booking->room->name} menunggu verifikasi Anda.");
        }

        return $booking;
    }

    /** PJ menyetujui. Pengajuan lain yang bentrok otomatis ditolak. */
    public function approve(Booking $booking, User $pj, ?string $note = null): void
    {
        $this->authorizePj($booking, $pj);

        $autoRejected = DB::transaction(function () use ($booking, $pj, $note) {
            $this->lockRoom($booking->room_id);
            $booking->refresh();

            $this->ensureCanDecide($booking);

            if ($this->overlapOf($booking)->status(BookingStatus::Approved)->where('id', '!=', $booking->id)->exists()) {
                throw ValidationException::withMessages([
                    'booking' => 'Slot ini sudah disetujui untuk peminjam lain. Muat ulang halaman.',
                ]);
            }

            $booking->update([
                'status' => BookingStatus::Approved,
                'verified_by' => $pj->id,
                'verified_at' => now(),
                'verification_note' => $note,
            ]);

            $others = $this->overlapOf($booking)
                ->status(...BookingStatus::open())
                ->where('id', '!=', $booking->id)
                ->get();

            foreach ($others as $other) {
                $other->update([
                    'status' => BookingStatus::Rejected,
                    'verified_at' => now(),
                    'verification_note' => 'Otomatis ditolak: slot waktu telah disetujui untuk pengajuan lain.',
                ]);
            }

            return $others;
        });

        $booking->load('room', 'user');
        $this->notify($booking->user, $booking, 'Pengajuan ' . $booking->code . ' DISETUJUI.' . ($note ? " Catatan PJ: {$note}" : ''));

        foreach ($autoRejected as $other) {
            $other->load('room', 'user');
            $this->notify($other->user, $other,
                "Pengajuan {$other->code} tidak dapat diproses karena ruangan telah disetujui untuk pengajuan lain pada jam yang sama.");
        }
    }

    /** PJ menolak (catatan wajib). Antrean terdepan naik menjadi Pending. */
    public function reject(Booking $booking, User $pj, string $note): void
    {
        $this->authorizePj($booking, $pj);

        if (trim($note) === '') {
            throw ValidationException::withMessages(['note' => 'Alasan penolakan wajib diisi.']);
        }

        $promoted = DB::transaction(function () use ($booking, $pj, $note) {
            $this->lockRoom($booking->room_id);
            $booking->refresh();

            $this->ensureCanDecide($booking);

            $booking->update([
                'status' => BookingStatus::Rejected,
                'verified_by' => $pj->id,
                'verified_at' => now(),
                'verification_note' => $note,
            ]);

            return $this->promoteQueued($booking);
        });

        $booking->load('room', 'user');
        $this->notify($booking->user, $booking, "Pengajuan {$booking->code} DITOLAK. Alasan: {$note}");
        $this->notifyPromoted($promoted);
    }

    /** Peminjam membatalkan pengajuannya sendiri selama masih Pending/Antrean. */
    public function cancel(Booking $booking, User $user): void
    {
        abort_unless($booking->user_id === $user->id, 403);

        $promoted = DB::transaction(function () use ($booking) {
            $this->lockRoom($booking->room_id);
            $booking->refresh();

            if (! in_array($booking->status, BookingStatus::open(), true)) {
                throw ValidationException::withMessages([
                    'booking' => 'Hanya pengajuan berstatus Pending atau Antrean yang bisa dibatalkan.',
                ]);
            }

            $wasPending = $booking->status === BookingStatus::Pending;
            $booking->update(['status' => BookingStatus::Cancelled]);

            return $wasPending ? $this->promoteQueued($booking) : collect();
        });

        $this->notifyPromoted($promoted);
    }

    // ------------------------------------------------------------------

    /**
     * Setelah sebuah pengajuan Pending gugur, antrean di slot yang bertabrakan
     * naik (urut waktu pengajuan) selama tidak ada Pending/Disetujui lain yang menghalangi.
     *
     * @return Collection<int, Booking>
     */
    private function promoteQueued(Booking $freed): Collection
    {
        $promoted = collect();

        foreach ($this->overlapOf($freed)->status(BookingStatus::Queued)->orderBy('id')->get() as $candidate) {
            $blocked = $this->overlapOf($candidate)
                ->status(BookingStatus::Approved, BookingStatus::Pending)
                ->where('id', '!=', $candidate->id)
                ->exists();

            if (! $blocked) {
                $candidate->update(['status' => BookingStatus::Pending]);
                $promoted->push($candidate);
            }
        }

        return $promoted;
    }

    private function notifyPromoted(Collection $promoted): void
    {
        foreach ($promoted as $booking) {
            $booking->load('room.pj', 'user');
            $this->notify($booking->user, $booking,
                "Giliran Anda: pengajuan {$booking->code} kini berstatus Pending dan menunggu verifikasi PJ.");
            $this->notify($booking->room->pj, $booking,
                "Pengajuan {$booking->code} dari {$booking->user->name} menunggu verifikasi Anda.");
        }
    }

    private function overlapOf(Booking $b): Builder
    {
        return Booking::overlapping(
            $b->room_id,
            $b->date->toDateString(),
            substr($b->start_time, 0, 5),
            substr($b->end_time, 0, 5),
        );
    }

    private function lockRoom(int $roomId): void
    {
        Room::withTrashed()->whereKey($roomId)->lockForUpdate()->firstOrFail();
    }

    private function authorizePj(Booking $booking, User $pj): void
    {
        abort_unless(
            $pj->isPj() && $booking->room->pj_user_id === $pj->id,
            403,
            'Anda bukan Penanggung Jawab ruangan ini.'
        );
    }

    private function ensureCanDecide(Booking $booking): void
    {
        if ($booking->status !== BookingStatus::Pending) {
            throw ValidationException::withMessages([
                'booking' => 'Pengajuan ini sudah diproses atau masih dalam antrean.',
            ]);
        }
    }

    /** Kegagalan notifikasi (mis. email) tidak boleh menggagalkan proses utama. */
    private function notify(?User $to, Booking $booking, string $message): void
    {
        if (! $to) {
            return;
        }

        rescue(fn () => $to->notify(new BookingStatusUpdated($booking, $message)));
    }
}
