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

/** Sisi peminjam (mahasiswa & dosen). Verifikasi PJ ada di controller terpisah. */
class BookingController extends Controller
{
    public function __construct(private BookingService $bookings)
    {
    }

    public function create()
    {
        // Ruangan yang sedang dipakai hari ini tetap bisa dipinjam untuk waktu lain.
        $rooms = Room::where('is_active', true)->orderBy('type')->orderBy('name')->get();

        return view('bookings.create', compact('rooms'));
    }

    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookings->submit(
            $request->user(),
            $request->safe()->except('letter'),
            $request->file('letter'),
        );

        $message = $booking->status === BookingStatus::Queued
            ? "Pengajuan {$booking->code} masuk antrean ke-{$booking->queue_position}."
            : "Pengajuan {$booking->code} berhasil dikirim dan menunggu verifikasi PJ.";

        return redirect()->route('bookings.show', $booking)->with('success', $message);
    }

    /** Status Pengajuan: semua pengajuan milik user, dengan filter status/ruangan/tanggal. */
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:50'],
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $user = $request->user();
        $status = BookingStatus::tryFrom((string) $request->query('status'));

        $bookings = $user->bookings()
            ->with(['room' => fn ($q) => $q->withTrashed()])
            ->when($status, fn ($q) => $q->status($status))
            ->when($request->filled('q'), fn ($q) => $q->whereHas('room', fn ($r) => $r->withTrashed()
                ->where('name', 'like', '%' . $request->query('q') . '%')))
            ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('date', $request->query('tanggal')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = DB::table('bookings')->where('user_id', $user->id)
            ->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $tabs = collect([['value' => '', 'label' => 'Semua', 'count' => (int) $counts->sum()]])
            ->concat(collect(BookingStatus::cases())->map(fn ($s) => [
                'value' => $s->value,
                'label' => $s->label(),
                'count' => (int) ($counts[$s->value] ?? 0),
            ]));

        return view('bookings.index', [
            'bookings' => $bookings,
            'tabs' => $tabs,
            'activeStatus' => $status?->value ?? '',
        ]);
    }

    /** Riwayat: pengajuan yang sudah final (ditolak, dibatalkan, atau disetujui dan sudah lewat). */
    public function history(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->history()
            ->with(['room' => fn ($q) => $q->withTrashed()])
            ->orderByDesc('date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    public function show(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['room' => fn ($q) => $q->withTrashed()->with('pj')], 'verifier');

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        $this->bookings->cancel($booking, $request->user());

        return redirect()->route('bookings.index')->with('success', "Pengajuan {$booking->code} dibatalkan.");
    }

    /** Surat izin hanya bisa dibuka pemilik, PJ ruangan terkait, dan admin. */
    public function letter(Request $request, Booking $booking)
    {
        $user = $request->user();

        abort_unless(
            $user->isAdmin()
            || $booking->user_id === $user->id
            || ($user->isPj() && $booking->room->pj_user_id === $user->id),
            403
        );
        abort_unless($booking->letter_path && Storage::exists($booking->letter_path), 404);

        return Storage::response($booking->letter_path);
    }
}
