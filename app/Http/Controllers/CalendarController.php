<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'gedung' => ['nullable', 'string', 'max:100'],
            'jenis' => ['nullable', 'in:kelas,laboratorium'],
        ]);

        $date = Carbon::parse($filters['tanggal'] ?? today()->toDateString())->startOfDay();

        $open = (int) substr(config('siperu.open_time'), 0, 2);
        $close = (int) substr(config('siperu.close_time'), 0, 2);
        $hours = range($open, $close - 1);

        $rooms = Room::where('is_active', true)
            ->when($filters['gedung'] ?? null, fn ($q, $v) => $q->where('building', $v))
            ->when($filters['jenis'] ?? null, fn ($q, $v) => $q->where('type', $v))
            ->orderBy('type')->orderBy('name')
            ->get();

        $bookings = Booking::whereIn('room_id', $rooms->pluck('id'))
            ->whereDate('date', $date->toDateString())
            ->status(BookingStatus::Approved, BookingStatus::Pending, BookingStatus::Queued)
            ->get()
            ->groupBy('room_id');

        $now = now();
        $grid = [];

        foreach ($rooms as $room) {
            $roomBookings = $bookings->get($room->id, collect());

            foreach ($hours as $h) {
                $from = sprintf('%02d:00', $h);
                $to = sprintf('%02d:00', $h + 1);

                $overlaps = $roomBookings->filter(
                    fn ($b) => substr($b->start_time, 0, 5) < $to && substr($b->end_time, 0, 5) > $from
                );

                $state = match (true) {
                    $room->status === 'disabled' => 'unavailable',
                    $overlaps->contains(fn ($b) => $b->status === BookingStatus::Approved) => 'booked',
                    $overlaps->isNotEmpty() => 'pending',
                    $date->copy()->setTime($h, 0)->lt($now) => 'past',
                    default => 'available',
                };

                $grid[$room->id][$h] = ['state' => $state, 'from' => $from, 'to' => $to];
            }
        }

        $buildings = Room::where('is_active', true)->whereNotNull('building')
            ->distinct()->orderBy('building')->pluck('building');

        return view('kalender', [
            'date' => $date,
            'rooms' => $rooms,
            'hours' => $hours,
            'grid' => $grid,
            'buildings' => $buildings,
            'filters' => $filters,
        ]);
    }
}
