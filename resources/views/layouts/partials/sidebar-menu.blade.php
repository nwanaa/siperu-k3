@php
    $u = auth()->user();

    $menus = match (true) {
        $u->isAdmin() => [
            ['Dashboard',          'dashboard',        'home'],
            ['Kelola Data',        'admin.data',       'database'],
            ['Laporan Peminjaman', 'admin.reports',    'report'],
        ],
        $u->isPj() => [
            ['Dashboard',          'dashboard',        'home'],
            ['Pengajuan Masuk',    'pj.requests.index','inbox'],
            ['Jadwal Ruangan',     'pj.schedule',      'calendar'],
            ['Riwayat Verifikasi', 'pj.history',       'clock'],
        ],
        default => [
            ['Dashboard',          'dashboard',        'home'],
            ['Kalender Ruangan',   'kalender',         'calendar'],
            ['Ajukan Peminjaman',  'bookings.create',  'plus'],
            ['Status Pengajuan',   'bookings.index',   'check'],
            ['Riwayat Peminjaman', 'bookings.history', 'clock'],
        ],
    };
@endphp

@foreach ($menus as $menu)
    @php
        [$label, $routeName, $icon] = $menu;
        $url = Route::has($routeName) ? route($routeName) : '#';
        $active = $url !== '#' && request()->routeIs($routeName);
    @endphp
    <a href="{{ $url }}"
       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm transition
              {{ $active ? 'bg-blue-500/40 font-semibold' : 'font-medium hover:bg-blue-800/60' }}">
        <x-icon :name="$icon" :class="$active ? 'w-5 h-5' : 'w-5 h-5 opacity-70'" />
        {{ $label }}
    </a>
@endforeach