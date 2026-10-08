<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ketersediaan Ruangan - SIPERU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header & Navigasi -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Dashboard SIPERU</h1>
                <p class="text-slate-500 mt-1">Selamat datang, <span class="font-semibold text-blue-600">{{ auth()->user()->name }}</span> ({{ ucfirst(auth()->user()->role) }})</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('bookings.index') }}" class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-100 font-medium transition">
                    Kelola Pengajuan
                </a>
                
                <a href="{{ route('notifications.index') }}" class="relative bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-50 font-medium transition">
                    Notifikasi
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 font-medium shadow-sm transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Bagian Ketersediaan Ruangan -->
        <div class="mb-6 border-b border-slate-200 pb-4">
            <h2 class="text-2xl font-bold text-slate-800">Ketersediaan Ruangan Hari Ini</h2>
            <p class="text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-slate-900">{{ $room->name }}</h3>
                            <p class="text-sm text-slate-500">{{ ucfirst($room->type) }} | Kapasitas: {{ $room->capacity }}</p>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">
                            {{ strtoupper($room->status) }}
                        </span>
                    </div>
                    
                    <div class="text-sm border-t border-slate-100 pt-4 mt-2">
                        <strong class="block mb-3 text-slate-700">Jadwal Terpakai (Approved):</strong>
                        
                        @if($room->bookings->isEmpty())
                            <div class="flex items-center justify-center p-4 bg-green-50 border border-green-100 rounded-lg text-green-700 font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Kosong Sepanjang Hari
                            </div>
                        @else
                            <ul class="space-y-3">
                                @foreach($room->bookings as $booking)
                                    <li class="bg-slate-50 p-3 rounded-lg border border-slate-200 border-l-4 border-l-blue-500">
                                        <div class="font-bold text-blue-700 mb-1">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                        </div>
                                        <div class="text-slate-700 font-medium">{{ $booking->user->name }}</div>
                                        <div class="text-xs text-slate-500 mt-1 truncate" title="{{ $booking->purpose }}">{{ $booking->purpose }}</div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center p-8 bg-white rounded-xl shadow-sm border border-slate-200 text-slate-500">
                    Belum ada data ruangan yang tersedia di sistem.
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>