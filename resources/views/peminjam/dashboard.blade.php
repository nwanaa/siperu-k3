<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    @php
        $cards = [
            ['Pengajuan Aktif', $stats['aktif'], 'bg-blue-50 text-blue-600', 'document'],
            ['Pending', $stats['pending'], 'bg-amber-50 text-amber-600', 'clock'],
            ['Disetujui', $stats['disetujui'], 'bg-emerald-50 text-emerald-600', 'check'],
            ['Riwayat', $stats['riwayat'], 'bg-purple-50 text-purple-600', 'archive'],
        ];
    @endphp

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-blue-900">Halo, {{ auth()->user()->name }}</h2>
        <p class="text-slate-500 text-sm">Berikut informasi peminjaman ruangan Anda</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2 bg-white rounded-xl border border-slate-100 p-5 md:p-6 shadow-sm">
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-bold text-slate-800">Pengajuan Terbaru</h3>

                @if (Route::has('bookings.index'))
                    <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-blue-600 hover:underline">
                        Lihat Semua
                    </a>
                @endif
            </div>

            @forelse ($latest as $b)
                <div class="flex items-start justify-between gap-3 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-slate-800 truncate">
                            {{ $b->room->name }}
                        </h4>

                        <p class="text-xs text-slate-500 mt-1">
                            {{ $b->date->locale('id')->translatedFormat('d M Y') }}
                            |
                            {{ $b->time_range }}
                        </p>

                        @if ($b->status === \App\Enums\BookingStatus::Queued)
                            <div class="mt-2">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">
                                    Antrean ke-{{ $b->queue_position }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <span class="shrink-0 px-3 py-1 text-xs font-semibold rounded-full {{ $b->status->badge() }}">
                        {{ $b->status->label() }}
                    </span>
                </div>
            @empty
                <div class="py-10 text-center text-sm text-slate-500">
                    Belum ada pengajuan.

                    @if (Route::has('bookings.create'))
                        <a href="{{ route('bookings.create') }}" class="block mt-2 font-semibold text-blue-600 hover:underline">
                            Ajukan peminjaman pertama Anda
                        </a>
                    @endif
                </div>
            @endforelse
        </section>

        <section class="bg-white rounded-xl border border-slate-100 p-5 md:p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4">Kalender</h3>

            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('dashboard', ['bulan' => $prevMonth]) }}" class="p-1 text-slate-400 hover:text-slate-700" aria-label="Bulan sebelumnya">
                    &lsaquo;
                </a>

                <p class="font-semibold text-slate-700">
                    {{ $monthLabel }}
                </p>

                <a href="{{ route('dashboard', ['bulan' => $nextMonth]) }}" class="p-1 text-slate-400 hover:text-slate-700" aria-label="Bulan berikutnya">
                    &rsaquo;
                </a>
            </div>

            <div class="grid grid-cols-7 gap-1 text-center text-xs text-slate-500 mb-2">
                @foreach (['Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb', 'Mg'] as $dn)
                    <div>{{ $dn }}</div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-1 text-center text-sm">
                @foreach ($days as $d)
                    <a
                        href="{{ Route::has('kalender') ? route('kalender', ['tanggal' => $d['date']->toDateString()]) : '#' }}"
                        class="relative py-2 rounded-full {{ $d['isToday'] ? 'bg-blue-600 text-white font-bold' : 'text-slate-700 hover:bg-slate-100' }} {{ !$d['inMonth'] ? 'opacity-30' : '' }}"
                    >
                        {{ $d['date']->day }}

                        @if ($d['hasBooking'])
                            <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full {{ $d['isToday'] ? 'bg-white' : 'bg-blue-600' }}"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>