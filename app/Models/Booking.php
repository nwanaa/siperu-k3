<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'room_id', 'date', 'start_time', 'end_time', 'participants',
    'purpose', 'letter_path', 'status', 'verified_by', 'verified_at', 'verification_note',
])]
class Booking extends Model
{
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'status' => BookingStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }

    /** Nomor tampilan, mis. SP-0012 */
    public function getCodeAttribute(): string
    {
        return 'SP-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    /** Dua jadwal bentrok jika mulai_A < selesai_B dan selesai_A > mulai_B. */
    public function scopeOverlapping(Builder $q, int $roomId, string $date, string $start, string $end): Builder
    {
        return $q->where('room_id', $roomId)
            ->whereDate('date', $date)
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start);
    }

    public function scopeStatus(Builder $q, BookingStatus ...$statuses): Builder
    {
        return $q->whereIn('status', array_map(fn ($s) => $s->value, $statuses));
    }

    /** Posisi antrean (1 = terdepan), null jika bukan antrean. */
    public function getQueuePositionAttribute(): ?int
    {
        if ($this->status !== BookingStatus::Queued) {
            return null;
        }

        return static::overlapping($this->room_id, $this->date->toDateString(),
                $this->start_time, $this->end_time)
            ->status(BookingStatus::Queued)
            ->where('id', '<', $this->id)
            ->count() + 1;
    }

    /** "08:00 - 10:00" */
public function getTimeRangeAttribute(): string
{
    return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
}

/** Riwayat = sudah ditolak/dibatalkan, atau disetujui tapi tanggalnya sudah lewat. */
public function scopeHistory(Builder $q): Builder
{
    return $q->where(function ($q) {
        $q->whereIn('status', [BookingStatus::Rejected->value, BookingStatus::Cancelled->value])
          ->orWhere(fn ($q) => $q->where('status', BookingStatus::Approved->value)
                                 ->whereDate('date', '<', today()));
    });
}
}