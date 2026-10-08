<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPERU - Sistem Informasi Peminjaman Ruangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 antialiased font-sans text-slate-800">
    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 py-4 px-8 flex justify-between items-center shadow-sm">
        <div class="font-bold text-2xl text-blue-700 tracking-tight">
            SIPERU<span class="text-slate-800 text-sm ml-2 font-normal hidden md:inline">Fakultas Teknik</span>
        </div>
        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="text-blue-600 font-medium hover:underline">Masuk ke Dashboard &rarr;</a>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium shadow-sm transition">Login</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="max-w-6xl mx-auto px-4 py-20 mt-10 md:mt-16 flex flex-col items-center text-center">
        <div class="inline-block bg-blue-50 text-blue-700 font-semibold px-4 py-1.5 rounded-full text-sm mb-6 border border-blue-200">
            Sistem Informasi Manajemen Fasilitas Akademik
        </div>
        
        <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 leading-tight mb-6 max-w-4xl">
            Peminjaman Ruangan Kelas & Lab Kini <span class="text-blue-600">Lebih Mudah dan Real-Time</span>
        </h1>
        
        <p class="text-lg text-slate-600 mb-10 max-w-2xl">
            Sistem antrean otomatis dan notifikasi terintegrasi untuk mencegah bentrok jadwal. Kelola fasilitas pendidikan Fakultas Teknik dengan transparan dan terstruktur.
        </p>
        
        <div class="flex gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold shadow-md transition text-lg">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold shadow-md transition text-lg flex items-center gap-2">
                    Mulai Pinjam Ruangan
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            @endauth
        </div>
    </div>

    <!-- Fitur Unggulan -->
    <div class="bg-white border-t border-slate-200 py-16 mt-12">
        <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 border border-slate-100 rounded-xl bg-slate-50">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-slate-900">Jadwal Real-Time</h3>
                <p class="text-slate-600 text-sm">Cek ketersediaan ruangan kapan saja tanpa perlu datang ke lokasi.</p>
            </div>
            
            <div class="p-6 border border-slate-100 rounded-xl bg-slate-50">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-slate-900">Sistem Antrean</h3>
                <p class="text-slate-600 text-sm">Pengajuan di slot yang sama otomatis masuk antrean dengan adil.</p>
            </div>

            <div class="p-6 border border-slate-100 rounded-xl bg-slate-50">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-slate-900">Notifikasi Cepat</h3>
                <p class="text-slate-600 text-sm">Dapatkan info persetujuan atau pembatalan secara instan.</p>
            </div>
        </div>
    </div>
</body>
</html>