<x-app-layout>
    <x-slot name="header">Riwayat Verifikasi</x-slot>

    @php $field = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp

    <div class="mb-5">
        <h2 class="text-xl font-bold text-blue-900">Riwayat Verifikasi</h2>
        <p class="text-sm text-slate-500 mt-1">Riwayat keputusan yang telah Anda lakukan sebagai PJ.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-sm text-slate-500">Disetujui</p>
            <p class="text-2xl font-bold text-emerald-700 mt-1">{{ (int) ($counts['approved'] ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-sm text-slate-500">Ditolak</p>
            <p class="text-2xl font-bold text-red-700 mt-1">{{ (int) ($counts['rejected'] ?? 0) }}</p>
        </div>
    </div>

    <form method="GET" action="{{ route('pj.history') }}" class="grid grid-cols-1 md:grid-cols-[1fr_180px_auto] gap-3 mb-5">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari peminjam atau ruangan" class="{{ $field }}">
        <select name="status" class="{{ $field }}">
            <option value="">Semua hasil</option>
            <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
            <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
        </select>
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-blue-900 hover:bg-blue-800 px-5 py-2 text-sm font-semibold text-white transition">Cari</button>
            @if (request()->hasAny(['q', 'status']))
                <a href="{{ route('pj.history') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </div>
    </form>

    @if ($bookings->isEmpty())
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-12 text-center text-sm text-slate-500">
            Belum ada riwayat verifikasi.
        </div>
    @else
        <div class="hidden md:block bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-xs text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Peminjam</th>
                        <th class="px-4 py-3 text-left font-semibold">Ruangan</th>
                        <th class="px-4 py-3 text-left font-semibold">Jadwal</th>
                        <th class="px-4 py-3 text-left font-semibold">Hasil</th>
                        <th class="px-4 py-3 text-left font-semibold">Diverifikasi</th>
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
                            <td class="px-4 py-4 whitespace-nowrap">
                                <p>{{ $b->date->locale('id')->translatedFormat('d M Y') }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $b->time_range }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $b->status->badge() }}">{{ $b->status->label() }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ $b->verified_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('pj.requests.show', $b) }}" class="text-xs font-semibold text-blue-600 hover:underline">Detail</a>
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
                        <div>
                            <p class="font-bold text-slate-800">{{ $b->room->name }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $b->user->name }} · {{ $b->date->locale('id')->translatedFormat('d M Y') }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ $b->time_range }}</p>
                        </div>
                        <span class="shrink-0 px-3 py-1 text-xs font-semibold rounded-full {{ $b->status->badge() }}">{{ $b->status->label() }}</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-3">{{ $b->verified_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $bookings->links() }}</div>
    @endif
</x-app-layout>