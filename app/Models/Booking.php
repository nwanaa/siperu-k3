<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'room_id',
    'date',
    'start_time',
    'end_time',
    'participants',
    'purpose',
    'letter_path',
    'status',
    'verified_by',
    'verified_at',
    'verification_note',
])]
#[Hidden(['letter_path'])]
class Booking extends Model
{

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'verified_at' => 'datetime',
            'participants' => 'integer',
            'status' => BookingStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->withTrashed();
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function scopeStatus($query, BookingStatus ...$statuses)
    {
        return $query->whereIn(
            'status',
            array_map(fn (BookingStatus $status) => $status->value, $statuses)
        );
    }

    public function scopeHistory($query)
    {
        return $query->whereIn('status', [
            BookingStatus::Approved->value,
            BookingStatus::Rejected->value,
            BookingStatus::Cancelled->value,
        ]);
    }

    public function scopeOverlapping(
        $query,
        int $roomId,
        string $date,
        string $startTime,
        string $endTime
    ) {
        return $query
            ->where('room_id', $roomId)
            ->whereDate('date', $date)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime);
    }

    protected function queuePosition(): Attribute
    {
        return Attribute::get(function () {
        if ($this->status !== BookingStatus::Queued) {
        return null;
        }
            return static::query()
                ->where('room_id', $this->room_id)
                ->whereDate('date', $this->date)
                ->where('status', BookingStatus::Queued->value)
                ->where('start_time', '<', $this->end_time)
                ->where('end_time', '>', $this->start_time)
                ->where(function ($query) {
                    $query
                        ->where('created_at', '<', $this->created_at)
                        ->orWhere(function ($query) {
                            $query
                                ->where('created_at', $this->created_at)
                                ->where('id', '<', $this->id);
                        });
                })
                ->count() + 1;
        });
    }

    public function getStatusLabelAttribute(): string
    {
        return BookingStatus::tryFrom($this->status)?->label() ?? ucfirst($this->status);
    }

    public function getStatusBadgeAttribute(): string
    {
        return BookingStatus::tryFrom($this->status)?->badge() ?? 'secondary';
    }
}