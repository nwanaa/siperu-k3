<x-app-layout>
    <x-slot name="header">Kelola Ruangan & Laboratorium</x-slot>

    <div class="max-w-7xl mx-auto">
        @if (session('success'))
            <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 mb-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari nama/gedung..."
                    class="rounded-lg border-slate-200 text-sm"
                >

                <select name="type" class="rounded-lg border-slate-200 text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="kelas" @selected(request('type') === 'kelas')>Kelas</option>
                    <option value="laboratorium" @selected(request('type') === 'laboratorium')>Laboratorium</option>
                </select>

                <select name="pj_group" class="rounded-lg border-slate-200 text-sm">
                    <option value="">Semua Kelompok PJ</option>
                    @foreach ($pjGroups as $key => $label)
                        <option value="{{ $key }}" @selected(request('pj_group') === $key)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Filter
                </button>
            </form>
        </div>

        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Data Ruangan</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola ruangan kelas, laboratorium, kapasitas, fasilitas, dan kelompok PJ.
                </p>
            </div>

            <a
                href="{{ route('admin.rooms.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
                + Tambah Ruangan
            </a>
        </div>

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Ruangan</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Jenis</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Kapasitas</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Fasilitas</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">PJ Group</th>
                            <th class="text-left px-5 py-3 font-semibold text-slate-600">Status</th>
                            <th class="text-right px-5 py-3 font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rooms as $room)
                            <tr class="{{ $room->trashed() ? 'bg-slate-50 opacity-70' : '' }}">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-800">{{ $room->name }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ $room->building ?: 'Gedung belum diatur' }}
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $room->type === 'laboratorium' ? 'Laboratorium' : 'Kelas' }}
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $room->capacity }} orang
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse (($room->facilities ?? []) as $facility)
                                            <span class="rounded-full bg-slate-100 px-2 py-1 text-xs text-slate-600">
                                                {{ $facility }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400">Belum ada</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-slate-600">
                                    {{ $pjGroups[$room->pj_group] ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($room->trashed())
                                        <span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                            Dihapus
                                        </span>
                                    @elseif (!$room->is_active || $room->status === 'disabled')
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                            Nonaktif
                                        </span>
                                    @elseif ($room->status === 'in_use')
                                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                            Sedang Digunakan
                                        </span>
                                    @else
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            Tersedia
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        @if ($room->trashed())
                                            <form method="POST" action="{{ route('admin.rooms.restore', $room->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">
                                                    Pulihkan
                                                </button>
                                            </form>
                                        @else
                                            <a
                                                href="{{ route('admin.rooms.edit', $room) }}"
                                                class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200"
                                            >
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('admin.rooms.toggle', $room) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg {{ $room->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} px-3 py-2 text-xs font-semibold">
                                                    {{ $room->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Hapus ruangan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                                    Belum ada data ruangan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($rooms->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $rooms->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>