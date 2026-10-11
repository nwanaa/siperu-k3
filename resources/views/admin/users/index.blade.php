<x-app-layout>
    <x-slot name="header">Kelola Pengguna</x-slot>

    <div class="max-w-7xl mx-auto">
        @if (session('success'))
            <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 mb-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, ID, email..." class="rounded-lg border-slate-200 text-sm">
                <select name="role" class="rounded-lg border-slate-200 text-sm">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin Utama</option>
                    <option value="pj_ruangan" @selected(request('role') === 'pj_ruangan')>PJ Ruangan</option>
                    <option value="peminjam" @selected(request('role') === 'peminjam')>Peminjam</option>
                </select>
                <select name="status" class="rounded-lg border-slate-200 text-sm">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                </select>
                <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Filter</button>
            </form>
        </div>

        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Data Pengguna</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola akun, role, status, dan kelompok PJ.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">+ Tambah Pengguna</a>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Pengguna</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Identifier</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Role</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">PJ Group</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Status</th>
                            <th class="text-right px-5 py-3 font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $user->identifier }}</td>
                                <td class="px-5 py-4">
                                    @if ($user->isAdmin())
                                        <span class="rounded-full bg-purple-50 px-2.5 py-1 text-xs font-semibold text-purple-700">Admin Utama</span>
                                    @elseif ($user->isPj())
                                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">PJ Ruangan</span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($user->category ?? 'Peminjam') }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $user->isPj() ? ($pjGroups[$user->pj_group] ?? '-') : '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    @if ($user->is_active)
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200">Edit</a>
                                        @if (!$user->is(auth()->user()))
                                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg px-3 py-2 text-xs font-semibold {{ $user->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>