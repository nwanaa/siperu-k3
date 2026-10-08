{{-- Daftar pengajuan: tabel di desktop, kartu di mobile. Butuh $bookings (paginator). --}}
@if ($bookings->isEmpty())
    <div class="bg-white rounded-xl border border-slate-100 p-10 text-center text-sm text-slate-500">
        Tidak ada data pengajuan.
    </div>
@else
    {{-- Desktop --}}
    <div class="hidden md:block bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">No</th>
                    <th class="px-4 py-3 text-left font-semibold">Ruangan</th>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Jam</th>
                    <th class="px-4 py-3 text-left font-semibold">Keperluan</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($bookings as $b)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-500">{{ $bookings->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $b->room->name }}</td>
                        <td class="px-4 py-3">{{ $b->date->locale('id')->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $b->time_range }}</td>
                        <td class="px-4 py-3 max-w-56 truncate" title="{{ $b->purpose }}">{{ $b->purpose }}</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $b->status->badge() }}">{{ $b->status->label() }}</span>
                            @if ($b->queue_position)
                                <span class="block mt-1 text-xs font-semibold text-orange-600">Antrean ke-{{ $b->queue_position }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('bookings.show', $b) }}" class="text-xs font-semibold text-blue-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile --}}
    <div class="md:hidden space-y-3">
        @foreach ($bookings as $b)
            <a href="{{ route('bookings.show', $b) }}" class="block bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 truncate">{{ $b->room->name }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $b->date->locale('id')->translatedFormat('d M Y') }} | {{ $b->time_range }}</p>
                        <p class="text-xs text-slate-600 mt-2 truncate">{{ $b->purpose }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $b->status->badge() }}">{{ $b->status->label() }}</span>
                        @if ($b->queue_position)
                            <span class="block mt-1 text-xs font-semibold text-orange-600">Antrean ke-{{ $b->queue_position }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-4">{{ $bookings->links() }}</div>
@endif
