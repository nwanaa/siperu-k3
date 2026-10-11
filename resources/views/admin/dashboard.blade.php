<x-app-layout>
    <x-slot name="header">Dashboard Admin</x-slot>

    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-blue-900">Dashboard Admin</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola pengguna, ruangan, PJ, dan aktivitas SIPERU.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                <p class="text-sm text-slate-500">Total Pengguna</p>
                <p class="text-2xl font-bold text-blue-900 mt-2">{{ $stats['users'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                <p class="text-sm text-slate-500">Peminjam</p>
                <p class="text-2xl font-bold text-blue-900 mt-2">{{ $stats['peminjam'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                <p class="text-sm text-slate-500">PJ</p>
                <p class="text-2xl font-bold text-blue-900 mt-2">{{ $stats['pj'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                <p class="text-sm text-slate-500">Ruangan Aktif</p>
                <p class="text-2xl font-bold text-blue-900 mt-2">{{ $stats['active_rooms'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-amber-50 rounded-xl border border-amber-100 p-5">
                <p class="text-sm text-amber-700">Pending</p>
                <p class="text-2xl font-bold text-amber-800 mt-2">{{ $stats['pending'] }}</p>
            </div>

            <div class="bg-orange-50 rounded-xl border border-orange-100 p-5">
                <p class="text-sm text-orange-700">Antrean</p>
                <p class="text-2xl font-bold text-orange-800 mt-2">{{ $stats['queued'] }}</p>
            </div>

            <div class="bg-emerald-50 rounded-xl border border-emerald-100 p-5">
                <p class="text-sm text-emerald-700">Disetujui</p>
                <p class="text-2xl font-bold text-emerald-800 mt-2">{{ $stats['approved'] }}</p>
            </div>

            <div class="bg-red-50 rounded-xl border border-red-100 p-5">
                <p class="text-sm text-red-700">Ditolak</p>
                <p class="text-2xl font-bold text-red-800 mt-2">{{ $stats['rejected'] }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-slate-800">Kelompok PJ</h3>
                        <p class="text-xs text-slate-500 mt-1">Pembagian tanggung jawab ruangan/laboratorium.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach ($pjGroups as $group => $label)
                        @php
                            $pj = $pjUsers->firstWhere('pj_group', $group);
                            $roomCount = $rooms->where('pj_group', $group)->count();
                        @endphp

                        <div class="flex items-center justify-between rounded-lg bg-slate-50 p-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $label }}</p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $pj?->name ?? 'Belum ada PJ' }}
                                </p>
                            </div>

                            <span class="text-xs font-semibold text-blue-600">
                                {{ $roomCount }} ruangan
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-slate-800">Pengajuan Terbaru</h3>
                        <p class="text-xs text-slate-500 mt-1">Aktivitas peminjaman terbaru.</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($latestBookings as $booking)
                        <div class="py-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $booking->room->name }}
                                </p>

                                <span class="text-xs font-semibold">
                                    {{ $booking->status->label() ?? ucfirst($booking->status) }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $booking->user->name }} ·
                                {{ $booking->date?->format('d/m/Y') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 py-5 text-center">
                            Belum ada pengajuan.
                        </p>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-800">Daftar Ruangan</h3>
                    <p class="text-xs text-slate-500 mt-1">Ruangan dan kelompok PJ yang bertanggung jawab.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-left">
                            <th class="py-3 pr-4 text-slate-500 font-semibold">Ruangan</th>
                            <th class="py-3 pr-4 text-slate-500 font-semibold">Tipe</th>
                            <th class="py-3 pr-4 text-slate-500 font-semibold">Kelompok PJ</th>
                            <th class="py-3 pr-4 text-slate-500 font-semibold">Kapasitas</th>
                            <th class="py-3 text-slate-500 font-semibold">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($rooms as $room)
                            <tr class="border-b border-slate-50">
                                <td class="py-3 pr-4 font-semibold text-slate-800">
                                    {{ $room->name }}
                                </td>

                                <td class="py-3 pr-4 text-slate-600">
                                    {{ ucfirst($room->type) }}
                                </td>

                                <td class="py-3 pr-4 text-slate-600">
                                    {{ $pjGroups[$room->pj_group] ?? '-' }}
                                </td>

                                <td class="py-3 pr-4 text-slate-600">
                                    {{ $room->capacity }} orang
                                </td>

                                <td class="py-3">
                                    @if ($room->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500">
                                    Belum ada ruangan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>