<x-app-layout>
    <x-slot name="header">Pengajuan Masuk</x-slot>

    @php $field = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp

    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-5">
        <div>
            <h2 class="text-xl font-bold text-blue-900">Pengajuan Masuk</h2>
            <p class="text-sm text-slate-500 mt-1">Hanya pengajuan untuk ruangan yang menjadi tanggung jawab Anda.</p>
        </div>
    </div>

    <div class="flex gap-2 overflow-x-auto pb-2 mb-4">
        @foreach ($tabs as $tab)
            @php
                $active = $tab['value'] === $activeStatus;
                $params = array_merge(request()->except('page', 'status'), ['status' => $tab['value']]);
            @endphp
            <a href="{{ route('pj.requests.index', $params) }}"
               class="shrink-0 rounded-lg border px-4 py-2 text-sm font-semibold transition
                      {{ $active ? 'bg-blue-900 border-blue-900 text-white' : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50' }}">
                {{ $tab['label'] }} ({{ $tab['count'] }})
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('pj.requests.index') }}" class="grid grid-cols-1 md:grid-cols-[1fr_180px_180px_auto] gap-3 mb-5">
        <input type="hidden" name="status" value="{{ $activeStatus }}">
        <input type="search" name="q" value="{{ request('q') }}"
               placeholder="Cari peminjam atau ruangan" class="{{ $field }}">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="{{ $field }}">
        <select name="room_id" class="{{ $field }}">
            <option value="">Semua Ruangan</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->id }}" @selected((string) request('room_id') === (string) $room->id)>
                    {{ $room->name }}
                </option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-blue-900 hover:bg-blue-800 px-5 py-2 text-sm font-semibold text-white transition">Cari</button>
            @if (request()->hasAny(['q', 'tanggal', 'room_id']))
                <a href="{{ route('pj.requests.index', ['status' => $activeStatus]) }}"
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </div>
    </form>

    @error('booking')
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    @if ($bookings->isEmpty())
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-12 text-center">
            <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                <x-icon name="inbox" class="w-6 h-6" />
            </div>
            <p class="font-semibold text-slate-700 mt-4">Tidak ada pengajuan.</p>
            <p class="text-sm text-slate-500 mt-1">Belum ada data yang sesuai dengan filter yang dipilih.</p>
        </div>
    @else
        <div class="hidden md:block bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Peminjam</th>
                        <th class="px-4 py-3 text-left font-semibold">Ruangan</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold">Jam</th>
                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                        <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($bookings as $b)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-4 text-slate-500">{{ $bookings->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-800">{{ $b->user->name }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $b->user->identifier }}</p>
                            </td>
                            <td class="px-4 py-4 font-medium text-slate-800">{{ $b->room->name }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">{{ $b->date->locale('id')->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">{{ $b->time_range }}</td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $b->status->badge() }}">{{ $b->status->label() }}</span>
                                @if ($b->queue_position)
                                    <span class="block mt-1 text-xs font-semibold text-orange-600">Antrean ke-{{ $b->queue_position }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('pj.requests.show', $b) }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-3">
            @foreach ($bookings as $b)
                <a href="{{ route('pj.requests.show', $b) }}" class="block bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-bold text-slate-800 truncate">{{ $b->room->name }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $b->user->name }} · {{ $b->user->identifier }}</p>
                            <p class="text-xs text-slate-600 mt-2">{{ $b->date->locale('id')->translatedFormat('d M Y') }} · {{ $b->time_range }}</p>
                        </div>
                        <span class="shrink-0 px-3 py-1 text-xs font-semibold rounded-full {{ $b->status->badge() }}">{{ $b->status->label() }}</span>
                    </div>
                    @if ($b->queue_position)
                        <p class="text-xs font-semibold text-orange-600 mt-3">Antrean ke-{{ $b->queue_position }}</p>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $bookings->links() }}</div>
    @endif
</x-app-layout>