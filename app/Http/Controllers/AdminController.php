<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    private array $pjGroups = [
        'informatika' => 'Jurusan Informatika',
        'sipil' => 'Jurusan Sipil',
        'pwk' => 'Jurusan PWK',
        'lab_ict' => 'Laboratorium ICT',
        'lab_terpadu' => 'Laboratorium Terpadu',
    ];

    public function dashboard(Request $request)
    {
        $stats = [
            'users' => User::count(),
            'peminjam' => User::where('role', User::PEMINJAM)->count(),
            'pj' => User::where('role', User::PJ)->count(),
            'rooms' => Room::count(),
            'active_rooms' => Room::where('is_active', true)->count(),
            'pending' => Booking::where('status', BookingStatus::Pending->value)->count(),
            'queued' => Booking::where('status', BookingStatus::Queued->value)->count(),
            'approved' => Booking::where('status', BookingStatus::Approved->value)->count(),
            'rejected' => Booking::where('status', BookingStatus::Rejected->value)->count(),
        ];

        $latestBookings = Booking::with(['user', 'room'])
            ->latest()
            ->take(8)
            ->get();

        $rooms = Room::query()
            ->withCount('bookings')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $pjGroups = $this->pjGroups;

        $pjUsers = User::query()
            ->where('role', User::PJ)
            ->where('is_active', true)
            ->orderBy('pj_group')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'latestBookings',
            'rooms',
            'pjGroups',
            'pjUsers'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | PENGGUNA
    |--------------------------------------------------------------------------
    */

    public function users(Request $request)
    {
        $users = User::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%' . $request->q . '%';

                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', $keyword)
                        ->orWhere('identifier', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword);
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->role))
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->when($request->filled('pj_group'), fn ($query) => $query->where('pj_group', $request->pj_group))
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'pjGroups' => $this->pjGroups,
        ]);
    }

    public function createUser()
    {
        return view('admin.users.create', [
            'pjGroups' => $this->pjGroups,
        ]);
    }

    public function storeUser(Request $request)
    {
        $data = $this->validateUser($request);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($data['role'] !== User::PJ) {
            $data['pj_group'] = null;
        }

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'pjGroups' => $this->pjGroups,
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $this->validateUser($request, $user);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');

        if ($data['role'] !== User::PJ) {
            $data['pj_group'] = null;
        }

        if ($user->is($request->user()) && !$data['is_active']) {
            return back()
                ->withInput()
                ->withErrors([
                    'is_active' => 'Anda tidak dapat menonaktifkan akun sendiri.',
                ]);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function toggleUser(Request $request, User $user)
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return back()->with(
            'success',
            $user->is_active
                ? 'Pengguna berhasil diaktifkan.'
                : 'Pengguna berhasil dinonaktifkan.'
        );
    }

    private function validateUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'identifier' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'identifier')->ignore($user?->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => [
                $user ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'role' => [
                'required',
                Rule::in([
                    User::ADMIN,
                    User::PJ,
                    User::PEMINJAM,
                ]),
            ],
'category' => [
    'nullable',
    'string',
    Rule::in(['mahasiswa', 'dosen']),
    'required_if:role,' . User::PEMINJAM,
],
            'pj_group' => [
                'nullable',
                Rule::in(array_keys($this->pjGroups)),
                'required_if:role,' . User::PJ,
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RUANGAN & LABORATORIUM
    |--------------------------------------------------------------------------
    */

    public function rooms(Request $request)
    {
        $rooms = Room::withTrashed()
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = '%' . $request->q . '%';

                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', $keyword)
                        ->orWhere('building', 'like', $keyword);
                });
            })
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->type))
            ->when($request->filled('pj_group'), fn ($query) => $query->where('pj_group', $request->pj_group))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.rooms.index', [
            'rooms' => $rooms,
            'pjGroups' => $this->pjGroups,
        ]);
    }

    public function createRoom()
    {
        return view('admin.rooms.create', [
            'pjGroups' => $this->pjGroups,
        ]);
    }

    public function storeRoom(Request $request)
    {
        $data = $this->validateRoom($request);

        $data['facilities'] = $this->parseFacilities($request->input('facilities'));
        $data['is_active'] = $request->boolean('is_active', true);
        $data['status'] = $data['is_active'] ? 'available' : 'disabled';

        Room::create($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function editRoom(Room $room)
    {
        return view('admin.rooms.edit', [
            'room' => $room,
            'pjGroups' => $this->pjGroups,
        ]);
    }

    public function updateRoom(Request $request, Room $room)
    {
        $data = $this->validateRoom($request);

        $data['facilities'] = $this->parseFacilities($request->input('facilities'));
        $data['is_active'] = $request->boolean('is_active');

        if (!$data['is_active']) {
            $data['status'] = 'disabled';
        } elseif ($room->status === 'disabled') {
            $data['status'] = 'available';
        } else {
            $data['status'] = $room->status;
        }

        $room->update($data);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function toggleRoom(Room $room)
    {
        $active = !$room->is_active;

        $room->update([
            'is_active' => $active,
            'status' => $active ? 'available' : 'disabled',
        ]);

        return back()->with(
            'success',
            $active
                ? 'Ruangan berhasil diaktifkan.'
                : 'Ruangan berhasil dinonaktifkan.'
        );
    }

    public function destroyRoom(Room $room)
    {
        if ($room->bookings()->exists()) {
            return back()->with(
                'error',
                'Ruangan tidak dapat dihapus karena sudah memiliki riwayat peminjaman. Nonaktifkan ruangan jika sudah tidak digunakan.'
            );
        }

        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }

    public function restoreRoom(int $room)
    {
        $room = Room::withTrashed()->findOrFail($room);

        $room->restore();

        $room->update([
            'is_active' => true,
            'status' => 'available',
        ]);

        return back()->with('success', 'Ruangan berhasil dipulihkan.');
    }

    private function validateRoom(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in(['kelas', 'laboratorium'])],
            'pj_group' => [
                'required',
                Rule::in(array_keys($this->pjGroups)),
            ],
            'capacity' => ['required', 'integer', 'min:1', 'max:10000'],
            'facilities' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function parseFacilities(?string $facilities): array
    {
        if (!$facilities) {
            return [];
        }

        return collect(preg_split('/[\r\n,]+/', $facilities))
            ->map(fn ($facility) => trim($facility))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}