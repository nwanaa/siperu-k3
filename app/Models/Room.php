<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'building', 'type', 'capacity', 'facilities', 'pj_user_id', 'status', 'is_active'])]
class Room extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return ['facilities' => 'array', 'is_active' => 'boolean'];
    }

    public function pj(): BelongsTo { return $this->belongsTo(User::class, 'pj_user_id'); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }
}