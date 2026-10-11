<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookings)
    {
    }

    public function create()
    {
        $rooms = Room::query()
            ->where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('bookings.create', compact('rooms'));
    }

    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookings->submit(
            $request->user(),
            $request->safe()->except('letter'),
            $request->file('letter')
        );

        $message = $booking->status === BookingStatus::Queued
            ? "Pengajuan {$booking->code} masuk antrean ke-{$booking->queue_position}."
            : "Pengajuan {$booking->code} berhasil dikirim dan menunggu verifikasi PJ.";

        return redirect()->route('bookings.show', $booking)->with('success', $message);
    }

    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:50'],
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'status' => ['nullable', 'string', 'in:pending,queued,approved,rejected,cancelled'],
        ]);

        $user = $request->user();
        $status = BookingStatus::tryFrom((string) $request->query('status'));

        $bookings = $user->bookings()
            ->with(['room' => fn ($query) => $query->withTrashed()])
            ->when($status, fn ($query) => $query->status($status))
            ->when($request->filled('q'), fn ($query) => $query->whereHas(
                'room',
                fn ($roomQuery) => $roomQuery->withTrashed()->where('name', 'like', '%' . $request->query('q') . '%')
            ))
            ->when($request->filled('tanggal'), fn ($query) => $query->whereDate('date', $request->query('tanggal')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = DB::table('bookings')
            ->where('user_id', $user->id)
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

        return view('bookings.index', [
            'bookings' => $bookings,
            'tabs' => $tabs,
            'activeStatus' => $status?->value ?? '',
        ]);
    }

    public function history(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->history()
            ->with(['room' => fn ($query) => $query->withTrashed()])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    public function show(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load([
            'room' => fn ($query) => $query->withTrashed(),
            'verifiedBy',
        ]);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        $this->bookings->cancel($booking, $request->user());

        return redirect()->route('bookings.index')
            ->with('success', "Pengajuan {$booking->code} dibatalkan.");
    }

    public function letter(Request $request, Booking $booking)
    {
        $user = $request->user();
        $room = $booking->room()->withTrashed()->first();

        $allowed = $user->isAdmin()
            || $booking->user_id === $user->id
            || ($user->isPj() && $room && $user->pj_group === $room->pj_group);

        abort_unless($allowed, 403);
        abort_unless($booking->letter_path && Storage::exists($booking->letter_path), 404);

        return Storage::response($booking->letter_path);
    }
}