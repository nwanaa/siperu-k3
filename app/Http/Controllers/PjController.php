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
        $status = BookingStatus::tryFrom((string) $request->query('status'));

        $rooms = Room::query()
            ->where('pj_group', $user->pj_group)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $bookings = Booking::with(['user', 'room'])
            ->whereHas('room', function ($query) use ($user) {
                $query->where('pj_group', $user->pj_group);
            })
            ->when($status, fn ($query) => $query->status($status))
            ->when($request->filled('room_id'), fn ($query) => $query->where('room_id', $request->query('room_id')))
            ->when($request->filled('tanggal'), fn ($query) => $query->whereDate('date', $request->query('tanggal')))
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

        $counts = Booking::whereHas('room', function ($query) use ($user) {
                $query->where('pj_group', $user->pj_group);
            })
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $tabs = collect([[
            'value' => '',
            'label' => 'Semua',
            'count' => (int) $counts->sum(),
        ]])->concat(collect(BookingStatus::cases())->map(fn ($item) => [
            'value' => $item->value,
            'label' => $item->label(),
            'count' => (int) ($counts[$item->value] ?? 0),
        ]));

        return view('pj.requests.index', [
            'bookings' => $bookings,
            'rooms' => $rooms,
            'tabs' => $tabs,
            'activeStatus' => $status?->value ?? '',
        ]);
    }

    public function show(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        $booking->load(['user', 'room', 'verifiedBy']);

        $conflicts = Booking::overlapping(
            $booking->room_id,
            $booking->date->toDateString(),
            substr((string) $booking->start_time, 0, 5),
            substr((string) $booking->end_time, 0, 5)
        )
            ->where('id', '!=', $booking->id)
            ->status(...BookingStatus::open())
            ->with('user')
            ->orderBy('created_at')
            ->get();

        return view('pj.requests.show', compact('booking', 'conflicts'));
    }

    public function approve(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);

        if ($booking->status !== BookingStatus::Pending) {
            return back()->with('error', 'Pengajuan ini tidak dapat disetujui karena statusnya sudah berubah.');
        }

        $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
            'verification_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $note = $request->input('note') ?? $request->input('verification_note');

        app(\App\Services\BookingService::class)->approve(
            $booking,
            $request->user(),
            $note
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

        $request->validate([
            'note' => ['nullable', 'string', 'max:1000'],
            'verification_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $note = trim((string) ($request->input('note') ?? $request->input('verification_note')));

        if ($note === '') {
            return back()
                ->withInput()
                ->withErrors(['note' => 'Alasan penolakan wajib diisi.']);
        }

        app(\App\Services\BookingService::class)->reject(
            $booking,
            $request->user(),
            $note
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

        $selectedRoom = $request->filled('room_id') ? (int) $request->query('room_id') : null;

        $from = rescue(
            fn () => \Carbon\Carbon::createFromFormat('Y-m-d', (string) $request->query('from'))->startOfDay(),
            today(),
            report: false
        );

        $to = rescue(
            fn () => \Carbon\Carbon::createFromFormat('Y-m-d', (string) $request->query('to'))->endOfDay(),
            today()->addDays(14)->endOfDay(),
            report: false
        );

        if ($to->lt($from)) {
            $to = $from->copy()->addDays(14)->endOfDay();
        }

        if ($to->diffInDays($from) > 63) {
            $to = $from->copy()->addDays(63)->endOfDay();
        }

        $roomIds = $rooms->pluck('id');

        $bookings = Booking::with(['user', 'room'])
            ->whereIn('room_id', $roomIds)
            ->where('status', BookingStatus::Approved->value)
            ->when($selectedRoom, fn ($query) => $query->where('room_id', $selectedRoom))
            ->whereBetween('date', [$from->toDateString(), $to->toDateString()])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $schedules = $bookings->groupBy(fn ($item) => $item->date->toDateString());

        return view('pj.schedule', [
            'rooms' => $rooms,
            'selectedRoom' => $selectedRoom,
            'from' => $from,
            'to' => $to,
            'schedules' => $schedules,
        ]);
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
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
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

        $counts = Booking::whereHas('room', function ($query) use ($user) {
                $query->where('pj_group', $user->pj_group);
            })
            ->whereIn('status', [BookingStatus::Approved->value, BookingStatus::Rejected->value])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('pj.history', compact('bookings', 'counts'));
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