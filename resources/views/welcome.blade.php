<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="SIPERU - Sistem Informasi Peminjaman Ruangan Fakultas Teknik Universitas Sulawesi Barat. Pinjam ruangan kelas & laboratorium secara online, real-time, dan tanpa bentrok jadwal.">
    <title>SIPERU - Sistem Informasi Peminjaman Ruangan | Universitas Sulawesi Barat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef4ff',
                            100: '#dae6ff',
                            200: '#bdd4ff',
                            300: '#90b8ff',
                            400: '#5b91ff',
                            500: '#3b6cf7',
                            600: '#2550e0',
                            700: '#1d3fca',
                            800: '#1e36a4',
                            900: '#1e3282',
                            950: '#16204f',
                        },
                    },
                },
            },
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 40%, #1d4ed8 70%, #2563eb 100%);
            background-size: 300% 300%;
            animation: gradientShift 12s ease infinite;
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: float 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: #60a5fa;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 400px;
            height: 400px;
            background: #818cf8;
            bottom: -80px;
            left: -60px;
            animation-delay: 3s;
        }

        .orb-3 {
            width: 300px;
            height: 300px;
            background: #34d399;
            top: 50%;
            left: 40%;
            animation-delay: 5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -30px) scale(1.05);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.95);
            }
        }

        .grid-pattern {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 {
            transition-delay: 0.1s;
        }

        .reveal-delay-2 {
            transition-delay: 0.2s;
        }

        .reveal-delay-3 {
            transition-delay: 0.3s;
        }

        .reveal-delay-4 {
            transition-delay: 0.4s;
        }

        .feature-card {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px -12px rgba(37, 99, 235, 0.15);
        }

        .stat-glow {
            text-shadow: 0 0 40px rgba(96, 165, 250, 0.4);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        .nav-scrolled {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .mobile-menu.open {
            max-height: 300px;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- ============ NAVBAR ============ -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 md:h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-3 group" id="nav-logo">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 transition-shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold text-white tracking-tight">SIPERU</span>
                        <span class="hidden lg:inline text-xs text-blue-200 ml-2 font-medium">Fakultas Teknik</span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="#fitur"
                        class="px-4 py-2 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition">Fitur</a>
                    <a href="#cara-kerja"
                        class="px-4 py-2 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition">Cara
                        Kerja</a>
                    <a href="#ruangan"
                        class="px-4 py-2 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition">Ruangan</a>
                    <a href="#faq"
                        class="px-4 py-2 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition">FAQ</a>
                </div>

                <!-- CTA -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="bg-white text-brand-700 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-white/10 hover:shadow-white/20 transition-all">
                            Buka Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-white text-brand-700 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-white/10 hover:shadow-white/20 transition-all">
                            Masuk
                        </a>
                    @endauth

                    <!-- Mobile menu toggle -->
                    <button onclick="toggleMobileMenu()"
                        class="md:hidden text-white p-2 rounded-lg hover:bg-white/10 transition"
                        aria-label="Toggle menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="mobile-menu md:hidden pb-4">
                <div class="flex flex-col gap-1 pt-2 border-t border-white/10">
                    <a href="#fitur"
                        class="px-4 py-3 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition">Fitur</a>
                    <a href="#cara-kerja"
                        class="px-4 py-3 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition">Cara
                        Kerja</a>
                    <a href="#ruangan"
                        class="px-4 py-3 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition">Ruangan</a>
                    <a href="#faq"
                        class="px-4 py-3 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition">FAQ</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============ HERO SECTION ============ -->
    <section class="hero-gradient relative min-h-screen flex items-center overflow-hidden">
        <!-- Banner: foto gedung -->
        <img src="{{ asset('images/image.png') }}" alt="Gedung Universitas Sulawesi Barat"
            class="absolute inset-0 w-full h-full object-cover object-center">

        <!-- Overlay gradient agar teks tetap terbaca -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/90 via-blue-900/80 to-blue-700/60"></div>

        <!-- Decorative elements -->
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="absolute inset-0 grid-pattern"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Column: Copy & CTAs -->
                <div class="lg:col-span-7">
                    <div
                        class="reveal inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-blue-200 font-semibold px-4 py-2 rounded-full text-xs sm:text-sm mb-6">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        Sistem Manajemen Fasilitas Akademik
                    </div>

                    <h1
                        class="reveal reveal-delay-1 text-4xl sm:text-5xl md:text-6xl font-extrabold text-white leading-[1.15] mb-6 tracking-tight">
                        Pinjam Ruangan<br>
                        <span
                            class="bg-gradient-to-r from-blue-300 via-cyan-300 to-emerald-300 bg-clip-text text-transparent">Lebih
                            Cerdas & Mudah</span>
                    </h1>

                    <p
                        class="reveal reveal-delay-2 text-base sm:text-lg text-blue-100/90 max-w-xl mb-8 leading-relaxed">
                        Sistem antrean otomatis dan notifikasi real-time untuk mengelola peminjaman ruangan kelas &
                        laboratorium Fakultas Teknik Universitas Sulawesi Barat. Tanpa bentrok jadwal dan transparan.
                    </p>

                    <div class="reveal reveal-delay-3 flex flex-wrap gap-4 items-center">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center justify-center gap-2 bg-white text-blue-900 hover:bg-blue-50 px-7 py-3.5 rounded-xl font-bold text-base shadow-xl shadow-blue-950/20 hover:scale-[1.02] transition-all">
                                Buka Dashboard
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center gap-2 bg-white text-blue-900 hover:bg-blue-50 px-7 py-3.5 rounded-xl font-bold text-base shadow-xl shadow-blue-950/20 hover:scale-[1.02] transition-all">
                                Mulai Pinjam Ruangan
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                            <a href="#fitur"
                                class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 px-6 py-3.5 rounded-xl font-semibold text-base transition-all">
                                Pelajari Lebih Lanjut
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Column: kartu ruangan (data dari database) -->
                <div class="lg:col-span-5 w-full max-w-md mx-auto lg:max-w-none">
                    <div class="space-y-4">
                        @foreach ($previewRooms as $room)
                            @php
                                $isAvailable = in_array(strtolower((string) $room->status), [
                                    'tersedia',
                                    'available',
                                    'aktif',
                                    'is_active',
                                ]);
                                $badge = $isAvailable
                                    ? 'bg-emerald-500/20 border-emerald-400/30 text-emerald-300'
                                    : 'bg-amber-500/20 border-amber-400/30 text-amber-300';
                            @endphp
                            <div
                                class="bg-slate-900/40 backdrop-blur-xl border border-white/20 rounded-2xl p-5 shadow-2xl hover:scale-[1.02] transition-all duration-300">
                                <div class="flex items-center justify-between mb-3 gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div
                                            class="w-10 h-10 shrink-0 rounded-xl bg-blue-500/30 border border-blue-400/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-white font-bold text-sm truncate">{{ $room->name }}</p>
                                            <p class="text-blue-200 text-xs truncate">
                                                {{ $room->building ?: 'Fakultas Teknik' }} • {{ $room->capacity }} kursi
                                            </p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 border text-xs font-semibold rounded-lg shrink-0 {{ $badge }}">
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </div>

                                @if (!empty($room->facilities))
                                    <div
                                        class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] text-blue-200/80 bg-white/5 rounded-lg px-3 py-1.5 border border-white/10">
                                        @foreach (array_slice($room->facilities, 0, 3) as $facility)
                                            <span>{{ $facility }}</span>
                                            @if (!$loop->last)
                                                <span>•</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- Kartu notifikasi (ilustrasi fitur) -->
                        <div
                            class="bg-emerald-950/40 backdrop-blur-xl border border-emerald-400/30 rounded-2xl p-4 shadow-2xl hover:scale-[1.02] transition-all duration-300">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-xl bg-emerald-500/30 border border-emerald-400/40 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-white text-sm font-semibold truncate">Pengajuan Disetujui!</p>
                                    <p class="text-emerald-200/80 text-xs truncate">Notifikasi langsung saat pengajuan
                                        diproses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                class="w-full h-16 md:h-24">
                <path
                    d="M0 120L60 105C120 90 240 60 360 52.5C480 45 600 60 720 67.5C840 75 960 75 1080 67.5C1200 60 1320 45 1380 37.5L1440 30V120H0Z"
                    fill="#f8fafc" />
            </svg>
        </div>
    </section>

    <!-- ============ STATS BAR (data dari database) ============ -->
    <section class="bg-slate-50 py-6 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="reveal bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-10 grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 -mt-20 md:-mt-24 relative z-20">
                <div class="text-center" id="stat-rooms">
                    <p class="text-3xl md:text-4xl font-extrabold text-brand-600 stat-glow"
                        data-count="{{ $stats['rooms'] }}">{{ $stats['rooms'] }}</p>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Ruangan Tersedia</p>
                </div>
                <div class="text-center" id="stat-users">
                    <p class="text-3xl md:text-4xl font-extrabold text-brand-600 stat-glow"
                        data-count="{{ $stats['users'] }}">{{ $stats['users'] }}</p>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Pengguna Terdaftar</p>
                </div>
                <div class="text-center" id="stat-bookings">
                    <p class="text-3xl md:text-4xl font-extrabold text-brand-600 stat-glow"
                        data-count="{{ $stats['bookings'] }}">{{ $stats['bookings'] }}</p>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Peminjaman Disetujui</p>
                </div>
                <div class="text-center" id="stat-departments">
                    <p class="text-3xl md:text-4xl font-extrabold text-brand-600 stat-glow"
                        data-count="{{ $stats['groups'] }}">{{ $stats['groups'] }}</p>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Jurusan & Lab</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FITUR SECTION ============ -->
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Fitur Utama SIPERU</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Kemudahan dalam mengelola dan meminjam fasilitas kampus
                    dengan sistem yang terintegrasi penuh.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="feature-card p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Penjadwalan Real-time</h3>
                    <p class="text-slate-600">Cek ketersediaan ruangan secara langsung. Jadwal diperbarui setiap saat
                        untuk mencegah bentrok.</p>
                </div>
                <!-- Fitur 2 -->
                <div class="feature-card p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div
                        class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Notifikasi Status</h3>
                    <p class="text-slate-600">Dapatkan pemberitahuan seketika saat status pengajuan Anda disetujui atau
                        ditolak oleh Penanggung Jawab.</p>
                </div>
                <!-- Fitur 3 -->
                <div class="feature-card p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <div
                        class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Surat Izin Otomatis</h3>
                    <p class="text-slate-600">Sistem akan secara otomatis membuat draf surat peminjaman yang bisa
                        dicetak langsung setelah disetujui.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CARA KERJA SECTION ============ -->
    <section id="cara-kerja" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Cara Peminjaman</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Hanya butuh 3 langkah sederhana untuk memastikan ruangan
                    Anda siap digunakan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center relative">
                <!-- Garis Penghubung (Hanya Desktop) -->
                <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-0.5 bg-slate-200 z-0"></div>

                <!-- Langkah 1 -->
                <div class="relative z-10">
                    <div
                        class="w-24 h-24 bg-white rounded-full shadow-lg border-4 border-blue-500 mx-auto flex items-center justify-center mb-6 text-2xl font-bold text-blue-600">
                        1</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Pilih Ruangan</h3>
                    <p class="text-slate-600">Login dan cari ruangan atau laboratorium yang tersedia sesuai dengan
                        jadwal yang Anda inginkan.</p>
                </div>
                <!-- Langkah 2 -->
                <div class="relative z-10">
                    <div
                        class="w-24 h-24 bg-white rounded-full shadow-lg border-4 border-blue-500 mx-auto flex items-center justify-center mb-6 text-2xl font-bold text-blue-600">
                        2</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Isi Formulir</h3>
                    <p class="text-slate-600">Lengkapi formulir pengajuan, lampirkan dokumen jika diperlukan, dan kirim
                        permohonan.</p>
                </div>
                <!-- Langkah 3 -->
                <div class="relative z-10">
                    <div
                        class="w-24 h-24 bg-white rounded-full shadow-lg border-4 border-emerald-500 mx-auto flex items-center justify-center mb-6 text-2xl font-bold text-emerald-600">
                        3</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Tunggu Persetujuan</h3>
                    <p class="text-slate-600">Admin atau PJ Ruangan akan memverifikasi permohonan Anda. Anda akan
                        dinotifikasi jika disetujui!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-slate-900 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center gap-3 mb-4 md:mb-0">
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
                <span class="text-lg font-bold text-white">SIPERU</span>
            </div>
            <p class="text-slate-400 text-sm text-center">
                &copy; {{ date('Y') }} Fakultas Teknik, Universitas Sulawesi Barat. Hak cipta dilindungi.
            </p>
        </div>
    </footer>

    <!-- Script untuk menjalankan animasi Reveal dan Mobile Menu -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menjalankan animasi teks agar muncul (menghilangkan opacity 0)
            const revealElements = document.querySelectorAll('.reveal');

            // Memberikan sedikit jeda (100ms) agar efek transisinya terlihat mulus saat halaman dimuat
            setTimeout(() => {
                revealElements.forEach((el) => {
                    el.classList.add('visible');
                });
            }, 100);

            // Mengaktifkan tombol menu mobile
            window.toggleMobileMenu = function() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('open');
            };

            // Mengubah latar Navbar saat scroll
            window.addEventListener('scroll', function() {
                const nav = document.getElementById('navbar');
                if (window.scrollY > 50) {
                    nav.classList.add('nav-scrolled');
                } else {
                    nav.classList.remove('nav-scrolled');
                }
            });
        });
    </script>
</body>

</html>
