<x-app-layout>
    <x-slot name="header">Dashboard PJ</x-slot>

    @php
        $cards = [
            ['Menunggu Verifikasi', $stats['pending'], 'bg-amber-50 text-amber-600', 'inbox'],
            ['Dalam Antrean', $stats['queued'], 'bg-orange-50 text-orange-600', 'clock'],
            ['Disetujui Hari Ini', $stats['approved_today'], 'bg-emerald-50 text-emerald-600', 'check'],
            ['Ruangan Dikelola', $stats['rooms'], 'bg-blue-50 text-blue-600', 'database'],
        ];
    @endphp

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-blue-900">Halo, {{ auth()->user()->name }}</h2>
        <p class="text-slate-500 text-sm">Kelola pengajuan dan jadwal ruangan/laboratorium yang menjadi tanggung jawab Anda.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        @foreach ($cards as [$label, $value, $tone, $icon])
            <div class="bg-white rounded-xl border border-slate-100 p-4 md:p-5 shadow-sm">
                <div class="w-10 h-10 rounded-lg {{ $tone }} flex items-center justify-center mb-4">
                    <x-icon :name="$icon" class="w-5 h-5" />
                </div>
                <p class="text-slate-500 text-sm font-medium mb-1">{{ $label }}</p>
                <p class="text-2xl font-bold text-slate-800">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 md:px-6 py-5 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-800">Pengajuan Menunggu Verifikasi</h3>
                    <p class="text-xs text-slate-500 mt-1">Urutan berdasarkan pengajuan yang masuk lebih dahulu.</p>
                </div>
                <a href="{{ route('pj.requests.index', ['status' => 'pending']) }}"
                   class="text-sm font-semibold text-blue-600 hover:underline">Lihat Semua</a>
            </div>

            @forelse ($latest as $b)
                <a href="{{ route('pj.requests.show', $b) }}"
                   class="flex items-start justify-between gap-4 px-5 md:px-6 py-4 hover:bg-slate-50 transition border-b border-slate-100 last:border-b-0">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-bold text-sm text-slate-800">{{ $b->room->name }}</h4>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700">Pending</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $b->user->name }} · {{ $b->date->locale('id')->translatedFormat('d M Y') }} · {{ $b->time_range }}
                        </p>
                        <p class="text-xs text-slate-600 mt-1 truncate">{{ $b->purpose }}</p>
                    </div>
                    <span class="shrink-0 text-xs font-semibold text-blue-600">Detail &rarr;</span>
                </a>
            @empty
                <div class="py-12 text-center text-sm text-slate-500">
                    Tidak ada pengajuan yang menunggu verifikasi.
                </div>
            @endforelse
        </section>

        <div class="space-y-6">
            <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-800">Jadwal Hari Ini</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                    <a href="{{ route('pj.schedule') }}" class="text-sm font-semibold text-blue-600 hover:underline">Jadwal</a>
                </div>

                @forelse ($todaySchedules as $b)
                    <div class="py-3 {{ ! $loop->last ? 'border-b border-slate-100' : '' }}">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ $b->room->name }}</p>
                            <span class="shrink-0 text-xs font-semibold text-emerald-700">{{ $b->time_range }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ $b->user->name }} · {{ $b->participants }} peserta</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-5 text-center">Belum ada jadwal yang disetujui hari ini.</p>
                @endforelse
            </section>

            <section class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800">Ruangan Saya</h3>
                    <span class="text-xs font-semibold text-slate-400">{{ $rooms->count() }} ruangan</span>
                </div>

                <div class="space-y-3">
                    @forelse ($rooms as $room)
                        <div class="rounded-lg bg-slate-50 p-3">
                            <p class="text-sm font-semibold text-slate-800">{{ $room->name }}</p>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ ucfirst($room->type) }} · Kapasitas {{ $room->capacity }} orang
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 py-3 text-center">Belum ada ruangan yang ditugaskan.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>