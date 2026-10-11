<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'identifier', 'email', 'password', 'role', 'pj_group', 'category', 'phone', 'program_studi', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const ADMIN = 'admin';
    public const PJ = 'pj_ruangan';
    public const PEMINJAM = 'peminjam';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ADMIN;
    }

    public function isPj(): bool
    {
        return $this->role === self::PJ;
    }

    public function isPeminjam(): bool
    {
        return $this->role === self::PEMINJAM;
    }

    public function roleLabel(): string
    {
        return match (true) {
            $this->isAdmin() => 'Admin Utama',
            $this->isPj() => 'PJ Ruangan',
            default => ucfirst($this->category ?? 'peminjam'),
        };
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isPjGroup(string $group): bool
    {
        return $this->isPj() && $this->pj_group === $group;
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