<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncRoomStatus extends Command
{
    protected $signature = 'siperu:sync-room-status';

    protected $description = 'Memperbarui status ruangan berdasarkan jadwal peminjaman yang sedang berlangsung';

    public function handle(): int
    {
        $now = now();

        Room::query()
            ->where('is_active', true)
            ->get()
            ->each(function (Room $room) use ($now) {
                $inUse = Booking::query()
                    ->where('room_id', $room->id)
                    ->where('status', BookingStatus::Approved->value)
                    ->whereDate('date', $now->toDateString())
                    ->whereTime('start_time', '<=', $now->format('H:i:s'))
                    ->whereTime('end_time', '>', $now->format('H:i:s'))
                    ->exists();

                $room->update([
                    'status' => $inUse ? 'in_use' : 'available',
                ]);
            });

        Room::query()
            ->where('is_active', false)
            ->update([
                'status' => 'disabled',
            ]);

        return self::SUCCESS;
    }
}