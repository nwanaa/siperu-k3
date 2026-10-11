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

class BookingService
{
    public function submit(User $user, array $data, UploadedFile $letter): Booking
    {
        $path = $letter->store('letters');

        try {
            $booking = DB::transaction(function () use ($user, $data, $path) {
                $room = $this->lockRoom((int) $data['room_id']);

                if ($room->trashed() || ! $room->is_active) {
                    throw ValidationException::withMessages([
                        'room_id' => 'Ruangan sudah tidak aktif atau tidak tersedia.',
                    ]);
                }

                if ((int) $data['participants'] > (int) $room->capacity) {
                    throw ValidationException::withMessages([
                        'participants' => "Jumlah peserta melebihi kapasitas ruangan ({$room->capacity} orang).",
                    ]);
                }

                $overlap = fn () => Booking::overlapping(
                    $room->id,
                    $data['date'],
                    $data['start_time'],
                    $data['end_time']
                );

                if ($overlap()->status(BookingStatus::Approved)->exists()) {
                    throw ValidationException::withMessages([
                        'start_time' => 'Slot ini sudah disetujui untuk peminjam lain. Silakan pilih waktu lain.',
                    ]);
                }

                $taken = $overlap()->status(...BookingStatus::open())->exists();

                return Booking::create([
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                    'date' => $data['date'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'participants' => $data['participants'],
                    'purpose' => $data['purpose'],
                    'letter_path' => $path,
                    'status' => $taken
                        ? BookingStatus::Queued
                        : BookingStatus::Pending,
                ]);
            });
        } catch (\Throwable $e) {
            Storage::delete($path);
            throw $e;
        }

        $booking->load('room', 'user');

        if ($booking->status === BookingStatus::Queued) {
            $this->notify(
                $booking->user,
                $booking,
                "Pengajuan {$booking->code} masuk antrean ke-{$booking->queue_position} karena ada pengajuan lain pada jam yang sama. Anda akan diberi tahu jika statusnya berubah."
            );
        } else {
            $this->notify(
                $booking->user,
                $booking,
                "Pengajuan {$booking->code} berhasil dikirim dan menunggu verifikasi PJ."
            );

            $this->notifyPjGroup(
                $booking,
                "Pengajuan baru {$booking->code} dari {$booking->user->name} untuk {$booking->room->name} menunggu verifikasi Anda."
            );
        }

        return $booking;
    }

    public function approve(Booking $booking, User $pj, ?string $note = null): void
    {
        $this->authorizePj($booking, $pj);

        $autoRejected = DB::transaction(function () use ($booking, $pj, $note) {
            $this->lockRoom($booking->room_id);
            $booking->refresh();
            $this->ensureCanDecide($booking);

            if (
                $this->overlapOf($booking)
                    ->status(BookingStatus::Approved)
                    ->where('id', '!=', $booking->id)
                    ->exists()
            ) {
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
                    'verified_by' => $pj->id,
                    'verified_at' => now(),
                    'verification_note' => 'Otomatis ditolak: slot waktu telah disetujui untuk pengajuan lain.',
                ]);
            }

            return $others;
        });

        $booking->load('room', 'user');

        $this->notify(
            $booking->user,
            $booking,
            'Pengajuan ' . $booking->code . ' DISETUJUI.' .
                ($note ? " Catatan PJ: {$note}" : '')
        );

        foreach ($autoRejected as $other) {
            $other->load('room', 'user');

            $this->notify(
                $other->user,
                $other,
                "Pengajuan {$other->code} tidak dapat diproses karena ruangan telah disetujui untuk pengajuan lain pada jam yang sama."
            );
        }
    }

    public function reject(Booking $booking, User $pj, string $note): void
    {
        $this->authorizePj($booking, $pj);

        if (trim($note) === '') {
            throw ValidationException::withMessages([
                'note' => 'Alasan penolakan wajib diisi.',
            ]);
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

        $this->notify(
            $booking->user,
            $booking,
            "Pengajuan {$booking->code} DITOLAK. Alasan: {$note}"
        );

        $this->notifyPromoted($promoted);
    }

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

            $booking->update([
                'status' => BookingStatus::Cancelled,
            ]);

            return $wasPending
                ? $this->promoteQueued($booking)
                : collect();
        });

        $this->notifyPromoted($promoted);
    }

    /**
     * Promosikan satu pengajuan antrean yang tidak berbenturan
     * dengan pengajuan Pending atau Approved lainnya.
     *
     * @return Collection<int, Booking>
     */
    private function promoteQueued(Booking $freed): Collection
    {
        $promoted = collect();

        foreach (
            $this->overlapOf($freed)
                ->status(BookingStatus::Queued)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get() as $candidate
        ) {
            $blocked = $this->overlapOf($candidate)
                ->status(
                    BookingStatus::Approved,
                    BookingStatus::Pending
                )
                ->where('id', '!=', $candidate->id)
                ->exists();

            if (! $blocked) {
                $candidate->update([
                    'status' => BookingStatus::Pending,
                ]);

                $promoted->push($candidate);
                break;
            }
        }

        return $promoted;
    }

    private function notifyPromoted(Collection $promoted): void
    {
        foreach ($promoted as $booking) {
            $booking->load('room', 'user');

            $this->notify(
                $booking->user,
                $booking,
                "Giliran Anda: pengajuan {$booking->code} kini berstatus Pending dan menunggu verifikasi PJ."
            );

            $this->notifyPjGroup(
                $booking,
                "Pengajuan {$booking->code} dari {$booking->user->name} menunggu verifikasi Anda."
            );
        }
    }

    private function overlapOf(Booking $booking): Builder
    {
        return Booking::overlapping(
            $booking->room_id,
            $booking->date->toDateString(),
            substr((string) $booking->start_time, 0, 5),
            substr((string) $booking->end_time, 0, 5)
        );
    }

    private function lockRoom(int $roomId): Room
    {
        return Room::withTrashed()
            ->whereKey($roomId)
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function authorizePj(Booking $booking, User $pj): void
    {
        $booking->loadMissing('room');

        abort_unless(
            $pj->isPj()
                && $pj->pj_group
                && $booking->room
                && $booking->room->pj_group === $pj->pj_group,
            403,
            'Anda bukan Penanggung Jawab grup ruangan ini.'
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

    private function notifyPjGroup(Booking $booking, string $message): void
    {
        $booking->loadMissing('room', 'user');

        if (! $booking->room?->pj_group) {
            return;
        }

        User::query()
            ->where('role', User::PJ)
            ->where('pj_group', $booking->room->pj_group)
            ->where('is_active', true)
            ->get()
            ->each(function (User $pj) use ($booking, $message) {
                $this->notify($pj, $booking, $message);
            });
    }

    private function notify(
        ?User $to,
        Booking $booking,
        string $message
    ): void {
        if (! $to) {
            return;
        }

        rescue(
            fn () => $to->notify(
                new BookingStatusUpdated($booking, $message)
            )
        );
    }
}
