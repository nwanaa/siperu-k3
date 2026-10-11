<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class PjController extends Controller
{
    public function requests(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with(['user', 'room'])
            ->whereHas('room', function ($query) use ($user) {
                $query->where('pj_group', $user->pj_group);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%' . $request->q . '%';

                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('name', 'like', $keyword)
                            ->orWhere('identifier', 'like', $keyword);
                    })
                    ->orWhereHas('room', function ($roomQuery) use ($keyword) {
                        $roomQuery->where('name', 'like', $keyword);
                    })
                    ->orWhere('purpose', 'like', $keyword);
                });
            })
            ->orderByRaw("
                CASE status
                    WHEN 'pending' THEN 1
                    WHEN 'queued' THEN 2
                    WHEN 'approved' THEN 3
                    WHEN 'rejected' THEN 4
                    WHEN 'cancelled' THEN 5
                    ELSE 6
                END
            ")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pj.requests.index', compact('bookings'));
    }

    public function show(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        $booking->load(['user', 'room', 'verifiedBy']);

        return view('pj.requests.show', compact('booking'));
    }

    public function approve(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        if ($booking->status !== BookingStatus::Pending) {
            return back()->with('error', 'Pengajuan ini tidak dapat disetujui karena statusnya sudah berubah.');
        }

        $request->validate([
    'verification_note' => ['required', 'string', 'max:1000'],
    ]);

        app(\App\Services\BookingService::class)->approve(
            $booking,
            $request->user(),
            $request->input('verification_note')
        );

        return redirect()
            ->route('pj.requests.index')
            ->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        if ($booking->status !== BookingStatus::Pending) {
            return back()->with('error', 'Pengajuan ini tidak dapat ditolak karena statusnya sudah berubah.');
        }

        $data = $request->validate([
            'verification_note' => ['required', 'string', 'max:1000'],
        ]);

        app(\App\Services\BookingService::class)->reject(
            $booking,
            $request->user(),
            $data['verification_note']
        );

        return redirect()
            ->route('pj.requests.index')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }

    public function schedule(Request $request)
    {
        $user = $request->user();

        $rooms = Room::query()
            ->where('pj_group', $user->pj_group)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $roomIds = $rooms->pluck('id');

        $bookings = Booking::with(['user', 'room'])
            ->whereIn('room_id', $roomIds)
            ->where('status', BookingStatus::Approved->value)
            ->when($request->filled('date'), function ($query) use ($request) {
                $query->whereDate('date', $request->date);
            }, function ($query) {
                $query->whereDate('date', '>=', today());
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('pj.schedule', compact('rooms', 'bookings'));
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with(['user', 'room', 'verifiedBy'])
            ->whereHas('room', function ($query) use ($user) {
                $query->where('pj_group', $user->pj_group);
            })
            ->whereIn('status', [
                BookingStatus::Approved->value,
                BookingStatus::Rejected->value,
                BookingStatus::Cancelled->value,
            ])
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%' . $request->q . '%';

                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('name', 'like', $keyword)
                            ->orWhere('identifier', 'like', $keyword);
                    })
                    ->orWhereHas('room', function ($roomQuery) use ($keyword) {
                        $roomQuery->where('name', 'like', $keyword);
                    });
                });
            })
            ->latest('verified_at')
            ->paginate(10)
            ->withQueryString();

        return view('pj.history', compact('bookings'));
    }

    private function authorizeBooking(Request $request, Booking $booking): void
    {
        $user = $request->user();

        $allowed = $booking->room()
            ->where('pj_group', $user->pj_group)
            ->exists();

        abort_unless($allowed, 403);
    }
}