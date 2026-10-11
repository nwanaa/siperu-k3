<x-app-layout>
    <x-slot name="header">Jadwal Ruangan</x-slot>

    @php $field = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp

    <div class="mb-6">
        <h2 class="text-xl font-bold text-blue-900">Jadwal Ruangan</h2>
        <p class="text-sm text-slate-500 mt-1">Jadwal hanya menampilkan peminjaman yang sudah disetujui untuk ruangan yang Anda kelola.</p>
    </div>

    <form method="GET" action="{{ route('pj.schedule') }}" class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 md:p-5 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label for="room_id" class="block text-xs font-semibold text-slate-600 mb-2">Ruangan</label>
                <select id="room_id" name="room_id" class="w-full {{ $field }}">
                    <option value="">Semua Ruangan</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" @selected($selectedRoom === $room->id)>{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="from" class="block text-xs font-semibold text-slate-600 mb-2">Dari</label>
                <input id="from" type="date" name="from" value="{{ $from->toDateString() }}" class="w-full {{ $field }}">
            </div>
            <div>
                <label for="to" class="block text-xs font-semibold text-slate-600 mb-2">Sampai</label>
                <input id="to" type="date" name="to" value="{{ $to->toDateString() }}" class="w-full {{ $field }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-lg bg-blue-900 hover:bg-blue-800 px-5 py-2.5 text-sm font-semibold text-white transition">Tampilkan</button>
                <a href="{{ route('pj.schedule') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">Reset</a>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-3">Rentang maksimal otomatis dibatasi 63 hari agar halaman tetap ringan.</p>
    </form>

    @if ($schedules->isEmpty())
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-12 text-center">
            <div class="mx-auto w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <x-icon name="calendar" class="w-6 h-6" />
            </div>
            <p class="font-semibold text-slate-700 mt-4">Tidak ada jadwal disetujui.</p>
            <p class="text-sm text-slate-500 mt-1">Belum ada peminjaman yang disetujui dalam rentang tanggal tersebut.</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach ($schedules as $date => $items)
                <section class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-5 md:px-6 py-4 bg-slate-50 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800">{{ $items->first()->date->locale('id')->translatedFormat('l, d F Y') }}</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach ($items as $b)
                            <div class="px-5 md:px-6 py-4">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                    <div class="flex items-start gap-4 min-w-0">
                                        <div class="shrink-0 w-20 text-center rounded-lg bg-blue-50 text-blue-700 px-2 py-2">
                                            <p class="text-xs font-semibold">Jam</p>
                                            <p class="text-sm font-bold mt-0.5">{{ substr($b->start_time, 0, 5) }}</p>
                                            <p class="text-xs">s.d. {{ substr($b->end_time, 0, 5) }}</p>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800">{{ $b->room->name }}</p>
                                            <p class="text-sm text-slate-600 mt-1">{{ $b->user->name }} · {{ $b->participants }} peserta</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ $b->purpose }}</p>
                                        </div>
                                    </div>
                                    <span class="shrink-0 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</x-app-layout>