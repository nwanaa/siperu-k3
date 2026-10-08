<x-app-layout>
    <x-slot name="header">Kalender Ketersediaan Ruangan</x-slot>

    @php
        $styles = [
            'available'   => ['bg-emerald-400 hover:bg-emerald-500', 'Tersedia'],
            'pending'     => ['bg-amber-400 hover:bg-amber-500',     'Pending (masih bisa masuk antrean)'],
            'booked'      => ['bg-red-400',                          'Terbooking'],
            'unavailable' => ['bg-slate-500',                        'Tidak tersedia'],
            'past'        => ['bg-slate-200',                        'Sudah lewat'],
        ];
        $field = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600';
    @endphp

    {{-- Filter --}}
    <form method="GET" action="{{ route('kalender') }}" class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
        <div>
            <label for="tanggal" class="block text-xs font-semibold text-slate-500 mb-1">Tanggal</label>
            <input id="tanggal" type="date" name="tanggal" value="{{ $date->toDateString() }}" class="w-full {{ $field }}">
        </div>
        <div>
            <label for="gedung" class="block text-xs font-semibold text-slate-500 mb-1">Gedung</label>
            <select id="gedung" name="gedung" class="w-full {{ $field }}">
                <option value="">Semua Gedung</option>
                @foreach ($buildings as $b)
                    <option value="{{ $b }}" @selected(($filters['gedung'] ?? '') === $b)>{{ $b }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="jenis" class="block text-xs font-semibold text-slate-500 mb-1">Jenis</label>
            <select id="jenis" name="jenis" class="w-full {{ $field }}">
                <option value="">Semua Jenis</option>
                <option value="kelas" @selected(($filters['jenis'] ?? '') === 'kelas')>Ruang Kelas</option>
                <option value="laboratorium" @selected(($filters['jenis'] ?? '') === 'laboratorium')>Laboratorium</option>
            </select>
        </div>
        <button type="submit" class="rounded-lg bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold py-2.5 transition">Cari</button>
        <div class="flex gap-2 text-sm">
            <a href="{{ route('kalender', array_merge($filters, ['tanggal' => $date->copy()->subDay()->toDateString()])) }}"
               class="flex-1 text-center rounded-lg border border-slate-300 py-2.5 hover:bg-slate-50">&lsaquo; Kemarin</a>
            <a href="{{ route('kalender', array_merge($filters, ['tanggal' => $date->copy()->addDay()->toDateString()])) }}"
               class="flex-1 text-center rounded-lg border border-slate-300 py-2.5 hover:bg-slate-50">Besok &rsaquo;</a>
        </div>
    </form>

    {{-- Legenda --}}
    <div class="flex flex-wrap gap-x-5 gap-y-2 mb-4 text-xs text-slate-600">
        @foreach ($styles as [$color, $label])
            <span class="inline-flex items-center gap-2">
                <span class="w-4 h-4 rounded {{ explode(' ', $color)[0] }}"></span>{{ $label }}
            </span>
        @endforeach
    </div>

    <p class="text-sm font-semibold text-blue-900 mb-3">{{ $date->locale('id')->translatedFormat('l, d F Y') }}</p>

    @if ($rooms->isEmpty())
        <div class="bg-white rounded-xl border border-slate-100 p-10 text-center text-sm text-slate-500">
            Ruangan tidak ditemukan atau belum didaftarkan.
        </div>
    @else
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs text-slate-500">
                        <th class="sticky left-0 z-10 bg-white text-left font-semibold px-4 py-3 min-w-44">Nama Ruangan</th>
                        @foreach ($hours as $h)
                            <th class="font-semibold px-1 py-3 min-w-14 text-center">{{ sprintf('%02d.00', $h) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr class="border-b border-slate-50 last:border-0">
                            <th scope="row" class="sticky left-0 z-10 bg-white text-left px-4 py-3 font-medium text-slate-700">
                                {{ $room->name }}
                                <span class="block text-xs font-normal text-slate-400">{{ $room->capacity }} orang</span>
                            </th>
                            @foreach ($hours as $h)
                                @php
                                    $cell = $grid[$room->id][$h];
                                    [$color, $label] = $styles[$cell['state']];
                                    $clickable = in_array($cell['state'], ['available', 'pending'], true);
                                @endphp
                                <td class="px-1 py-2">
                                    @if ($clickable)
                                        <a href="{{ route('bookings.create', ['room_id' => $room->id, 'date' => $date->toDateString(), 'start_time' => $cell['from'], 'end_time' => $cell['to']]) }}"
                                           class="block h-8 rounded {{ $color }} transition"
                                           title="{{ $label }} - klik untuk mengajukan"
                                           aria-label="{{ $room->name }} {{ $cell['from'] }}: {{ $label }}"></a>
                                    @else
                                        <div class="h-8 rounded {{ $color }}" title="{{ $label }}"></div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-3 text-xs text-slate-500">Klik kotak hijau atau kuning untuk langsung mengajukan peminjaman pada jam tersebut.</p>
    @endif
</x-app-layout>
