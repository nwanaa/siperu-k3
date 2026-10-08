<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending   = 'pending';
    case Queued    = 'queued';
    case Approved  = 'approved';
    case Rejected  = 'rejected';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'Pending',
            self::Queued    => 'Antrean',
            self::Approved  => 'Disetujui',
            self::Rejected  => 'Ditolak',
            self::Cancelled => 'Dibatalkan',
        };
    }

    /** Kelas Tailwind untuk badge. Satu tempat, dipakai semua halaman. */
    public function badge(): string
    {
        return match ($this) {
            self::Pending   => 'bg-amber-100 text-amber-700',
            self::Queued    => 'bg-orange-100 text-orange-700',
            self::Approved  => 'bg-emerald-100 text-emerald-700',
            self::Rejected  => 'bg-red-100 text-red-700',
            self::Cancelled => 'bg-slate-200 text-slate-600',
        };
    }

    /** Status yang masih "menahan" slot dan belum final. */
    public static function open(): array
    {
        return [self::Pending, self::Queued];
    }
}