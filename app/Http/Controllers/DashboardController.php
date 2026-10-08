<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match (true) {
            $user->isAdmin() => view('admin.dashboard'),
            $user->isPj() => view('pj.dashboard'),
            default => $this->peminjam($request, $user),
        };
    }

    private function peminjam(Request $request, User $user)
    {
        // Kartu ringkasan
        $stats = [
            // belum selesai: pending, antrean, dan disetujui yang tanggalnya belum lewat
            'aktif' => $user->bookings()->where(function ($q) {
                $q->whereIn('status', [BookingStatus::Pending->value, BookingStatus::Queued->value])
                  ->orWhere(fn ($q) => $q->where('status', BookingStatus::Approved->value)
                                         ->whereDate('date', '>=', today()));
            })->count(),
            'pending' => $user->bookings()->status(BookingStatus::Pending)->count(),
            'disetujui' => $user->bookings()->status(BookingStatus::Approved)
                ->whereDate('date', '>=', today())->count(),
            'riwayat' => $user->bookings()->history()->count(),
        ];

        $latest = $user->bookings()->with('room')->latest()->take(5)->get();

        // Kalender mini
        $month = rescue(
            fn () => Carbon::createFromFormat('!Y-m', $request->query('bulan', now()->format('Y-m'))),
            now()->startOfMonth(),
            report: false
        );
        $gridStart = $month->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $busyDates = $user->bookings()
            ->whereBetween('date', [$gridStart->toDateString(), $gridEnd->toDateString()])
            ->whereIn('status', [BookingStatus::Pending->value, BookingStatus::Queued->value, BookingStatus::Approved->value])
            ->pluck('date')
            ->map(fn ($d) => $d->toDateString())
            ->flip();

        $days = collect(CarbonPeriod::create($gridStart, $gridEnd))->map(fn ($d) => [
            'date' => $d,
            'inMonth' => $d->month === $month->month,
            'isToday' => $d->isToday(),
            'hasBooking' => $busyDates->has($d->toDateString()),
        ]);

        return view('peminjam.dashboard', [
            'stats' => $stats,
            'latest' => $latest,
            'days' => $days,
            'monthLabel' => $month->copy()->locale('id')->translatedFormat('F Y'),
            'prevMonth' => $month->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $month->copy()->addMonth()->format('Y-m'),
        ]);
    }
}