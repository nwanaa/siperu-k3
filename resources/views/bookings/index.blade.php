<x-app-layout>
    <x-slot name="header">Status Pengajuan</x-slot>

    @php $field = 'rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600'; @endphp

    {{-- Tab status --}}
    <div class="flex gap-2 overflow-x-auto pb-2 mb-4">
        @foreach ($tabs as $tab)
            @php
                $active = $tab['value'] === $activeStatus;
                $params = array_merge(request()->except('page', 'status'), $tab['value'] !== '' ? ['status' => $tab['value']] : []);
            @endphp
            <a href="{{ route('bookings.index', $params) }}"
               class="shrink-0 rounded-lg border px-4 py-2 text-sm font-semibold transition
                      {{ $active ? 'bg-blue-900 border-blue-900 text-white' : 'bg-white border-slate-300 text-slate-700 hover:bg-slate-50' }}">
                {{ $tab['label'] }} ({{ $tab['count'] }})
            </a>
        @endforeach
    </div>

    {{-- Pencarian --}}
    <form method="GET" action="{{ route('bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] gap-3 mb-5">
        @if ($activeStatus !== '')
            <input type="hidden" name="status" value="{{ $activeStatus }}">
        @endif
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama ruangan" class="{{ $field }}">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="{{ $field }}">
        <div class="flex gap-2">
            <button type="submit" class="rounded-lg bg-blue-900 hover:bg-blue-800 px-5 py-2 text-sm font-semibold text-white transition">Cari</button>
            @if (request()->hasAny(['q', 'tanggal']))
                <a href="{{ route('bookings.index', $activeStatus !== '' ? ['status' => $activeStatus] : []) }}"
                   class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </div>
    </form>

    @error('booking')
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    @include('bookings.partials.list')
</x-app-layout>
