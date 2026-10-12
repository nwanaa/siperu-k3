<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        // Gunakan function untuk membuat query builder baru setiap kali dipanggil
        $activeRooms = fn () => Room::query()->where('is_active', true);

        $data = [
            'stats' => [
                'rooms'    => $activeRooms()->count(),
                'users'    => User::count(),
                'bookings' => Booking::where('status', 'approved')->count(),
                'groups'   => $activeRooms()->whereNotNull('pj_group')->distinct()->count('pj_group'),
            ],
            'previewRooms' => $activeRooms()->latest()->take(2)->get(),
            'groups'       => $activeRooms()->whereNotNull('pj_group')
                                ->selectRaw('pj_group, COUNT(*) as rooms_count')
                                ->groupBy('pj_group')
                                ->orderBy('pj_group')
                                ->get(),
        ];

        // ==========================================
        // BARIS DEBUGGING: Buka komentar di bawah ini
        // ==========================================
        // dd($data['previewRooms']);

        return view('welcome', $data);
    }
}