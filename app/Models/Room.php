<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'building',
    'type',
    'pj_group',
    'capacity',
    'facilities',
    'status',
    'is_active',
])]
class Room extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'facilities' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function pjUsers()
    {
        return User::query()
            ->where('role', User::PJ)
            ->where('pj_group', $this->pj_group);
    }

    public function scopeForPjGroup($query, string $group)
    {
        return $query->where('pj_group', $group);
    }

    public function pjGroupLabel(): string
    {
        return match ($this->pj_group) {
            'informatika' => 'Jurusan Informatika',
            'sipil' => 'Jurusan Sipil',
            'pwk' => 'Jurusan PWK',
            'lab_ict' => 'Laboratorium ICT',
            'lab_terpadu' => 'Laboratorium Terpadu',
            default => '-',
        };
    }
}