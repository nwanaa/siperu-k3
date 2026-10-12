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

        /* Animated gradient background */
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

        /* Floating orbs */
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

        /* Grid pattern overlay */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Scroll animations */
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

        /* Glassmorphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-card-light {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        /* Feature card hover */
        .feature-card {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px -12px rgba(37, 99, 235, 0.15);
        }

        /* Timeline connector */
        .timeline-line {
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #dbeafe, #2563eb, #dbeafe);
            transform: translateX(-50%);
        }

        /* Stat counter glow */
        .stat-glow {
            text-shadow: 0 0 40px rgba(96, 165, 250, 0.4);
        }

        /* Smooth scrollbar */
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

        /* Navbar solid background on scroll */
        .nav-scrolled {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        /* Pulse ring for CTA */
        .pulse-ring {
            animation: pulseRing 2.5s infinite;
        }

        @keyframes pulseRing {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }

            70% {
                box-shadow: 0 0 0 16px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        /* Typing cursor */
        .typing-cursor {
            display: inline-block;
            width: 3px;
            height: 1.1em;
            background: #60a5fa;
            margin-left: 2px;
            vertical-align: text-bottom;
            animation: blink 1s step-end infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        /* Mobile menu */
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
                        class="px-4 py-2 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition"
                        id="nav-fitur">Fitur</a>
                    <a href="#cara-kerja"
                        class="px-4 py-2 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition"
                        id="nav-cara-kerja">Cara Kerja</a>
                    <a href="#ruangan"
                        class="px-4 py-2 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition"
                        id="nav-ruangan">Ruangan</a>
                    <a href="#faq"
                        class="px-4 py-2 text-sm font-medium text-blue-100 hover:text-white rounded-lg hover:bg-white/10 transition"
                        id="nav-faq">FAQ</a>
                </div>

                <!-- CTA -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" id="nav-cta-dashboard"
                            class="bg-white text-brand-700 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-white/10 hover:shadow-white/20 transition-all">
                            Buka Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" id="nav-cta-login"
                            class="bg-white text-brand-700 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-white/10 hover:shadow-white/20 transition-all">
                            Masuk
                        </a>
                    @endauth

                    <!-- Mobile menu toggle -->
                    <button onclick="toggleMobileMenu()"
                        class="md:hidden text-white p-2 rounded-lg hover:bg-white/10 transition" id="mobile-menu-btn"
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

        <!-- Decorative elements (tetap, tidak diubah) -->
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="absolute inset-0 grid-pattern"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Column: Copy & CTAs -->
                <div class="lg:col-span-7">
                    <!-- Badge -->
                    <div
                        class="reveal inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-blue-200 font-semibold px-4 py-2 rounded-full text-xs sm:text-sm mb-6">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        Sistem Manajemen Fasilitas Akademik
                    </div>

                    <!-- Heading -->
                    <h1
                        class="reveal reveal-delay-1 text-4xl sm:text-5xl md:text-6xl font-extrabold text-white leading-[1.15] mb-6 tracking-tight">
                        Pinjam Ruangan<br>
                        <span
                            class="bg-gradient-to-r from-blue-300 via-cyan-300 to-emerald-300 bg-clip-text text-transparent">Lebih
                            Cerdas & Mudah</span>
                    </h1>

                    <!-- Subheading -->
                    <p
                        class="reveal reveal-delay-2 text-base sm:text-lg text-blue-100/90 max-w-xl mb-8 leading-relaxed">
                        Sistem antrean otomatis dan notifikasi real-time untuk mengelola peminjaman ruangan kelas &
                        laboratorium Fakultas Teknik Universitas Sulawesi Barat. Tanpa bentrok jadwal dan transparan.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="reveal reveal-delay-3 flex flex-wrap gap-4 items-center">
                        @auth
                            <a href="{{ route('dashboard') }}" id="hero-cta-dashboard"
                                class="inline-flex items-center justify-center gap-2 bg-white text-blue-900 hover:bg-blue-50 px-7 py-3.5 rounded-xl font-bold text-base shadow-xl shadow-blue-950/20 hover:scale-[1.02] transition-all">
                                Buka Dashboard
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" id="hero-cta-login"
                                class="inline-flex items-center justify-center gap-2 bg-white text-blue-900 hover:bg-blue-50 px-7 py-3.5 rounded-xl font-bold text-base shadow-xl shadow-blue-950/20 hover:scale-[1.02] transition-all">
                                Mulai Pinjam Ruangan
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                            <a href="#fitur" id="hero-cta-learn"
                                class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 px-6 py-3.5 rounded-xl font-semibold text-base transition-all">
                                Pelajari Lebih Lanjut
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Column: Interactive / Floating room cards preview -->
                <div class="lg:col-span-5 w-full max-w-md mx-auto lg:max-w-none">
                    <div class="space-y-4">
                        <!-- Card 1 -->
                        <div
                            class="bg-slate-900/40 backdrop-blur-xl border border-white/20 rounded-2xl p-5 shadow-2xl hover:scale-[1.02] transition-all duration-300">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-blue-500/30 border border-blue-400/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-sm">Lab Komputer ICT</p>
                                        <p class="text-blue-200 text-xs">Gedung ICT • 40 kursi</p>
                                    </div>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-semibold rounded-lg">Tersedia</span>
                            </div>
                            <div
                                class="flex items-center gap-2 text-[11px] text-blue-200/80 bg-white/5 rounded-lg px-3 py-1.5 border border-white/10">
                                <span>⚡ Proyektor</span>
                                <span>•</span>
                                <span>❄️ AC</span>
                                <span>•</span>
                                <span>📶 WiFi Terpadu</span>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div
                            class="bg-slate-900/40 backdrop-blur-xl border border-white/20 rounded-2xl p-5 shadow-2xl hover:scale-[1.02] transition-all duration-300">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-indigo-500/30 border border-indigo-400/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-sm">Ruang Kelas A2.01</p>
                                        <p class="text-blue-200 text-xs">Gedung Informatika • 60 kursi</p>
                                    </div>
                                </div>
                                <span
                                    class="px-2.5 py-1 bg-amber-500/20 border border-amber-400/30 text-amber-300 text-xs font-semibold rounded-lg">Digunakan</span>
                            </div>
                            <div
                                class="flex items-center justify-between text-xs text-blue-200/90 bg-white/5 rounded-lg px-3 py-1.5 border border-white/10">
                                <span>Jadwal Aktif</span>
                                <span class="font-semibold text-amber-300">08:00 – 10:00 WITA</span>
                            </div>
                        </div>

                        <!-- Notification popup card -->
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
                                    <p class="text-emerald-200/80 text-xs truncate">Lab Komputer ICT — Senin, 14 Okt
                                        2026</p>
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

    <!-- ============ STATS BAR ============ -->
    <section class="bg-slate-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="reveal bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-10 grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 -mt-20 md:-mt-24 relative z-20">
                <div class="text-center" id="stat-rooms">
                    <p class="text-3xl md:text-4xl font-extrabold text-brand-600 stat-glow" data-count="20">0</p>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Ruangan Tersedia</p>
                </div>
                <div class="text-center" id="stat-users">
                    <p class="text-3xl md:text-4xl font-extrabold text-brand-600 stat-glow" data-count="500">0</p>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Pengguna Aktif</p>
                </div>
                <div class="text-center" id="stat-bookings">
                    <p class="text-3xl md:text-4xl font-extrabold text-brand-600 stat-glow" data-count="1200">0</p>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Peminjaman</p>
                </div>
                <div class="text-center" id="stat-departments">
                    <p class="text-3xl md:text-4xl font-extrabold text-brand-600 stat-glow" data-count="5">0</p>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Jurusan & Lab</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FEATURES ============ -->
    <section id="fitur" class="bg-slate-50 py-20 md:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section header -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <p class="reveal text-sm font-bold text-brand-600 uppercase tracking-wider mb-3">Fitur Unggulan</p>
                <h2
                    class="reveal reveal-delay-1 text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-4">
                    Semua yang Anda Butuhkan untuk
                    <span class="bg-gradient-to-r from-brand-600 to-cyan-500 bg-clip-text text-transparent">Kelola
                        Ruangan</span>
                </h2>
                <p class="reveal reveal-delay-2 text-slate-600 text-lg">
                    Dirancang untuk mempermudah proses peminjaman ruangan di lingkungan akademik.
                </p>
            </div>

            <!-- Feature cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Feature 1 -->
                <div class="reveal feature-card bg-white rounded-2xl p-7 border border-slate-100 shadow-sm"
                    id="feature-calendar">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-5 shadow-lg shadow-blue-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Kalender Real-Time</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Lihat ketersediaan ruangan secara langsung
                        melalui kalender interaktif. Tidak perlu datang ke lokasi untuk mengecek jadwal.</p>
                </div>

                <!-- Feature 2 -->
                <div class="reveal reveal-delay-1 feature-card bg-white rounded-2xl p-7 border border-slate-100 shadow-sm"
                    id="feature-queue">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mb-5 shadow-lg shadow-amber-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Sistem Antrean Otomatis</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Pengajuan di slot waktu yang sama otomatis masuk
                        antrean. Adil dan transparan — tidak ada lagi perebutan ruangan.</p>
                </div>

                <!-- Feature 3 -->
                <div class="reveal reveal-delay-2 feature-card bg-white rounded-2xl p-7 border border-slate-100 shadow-sm"
                    id="feature-notif">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center mb-5 shadow-lg shadow-emerald-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Notifikasi Instan</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Dapatkan pemberitahuan langsung saat pengajuan
                        disetujui, ditolak, atau terjadi perubahan jadwal.</p>
                </div>

                <!-- Feature 4 -->
                <div class="reveal feature-card bg-white rounded-2xl p-7 border border-slate-100 shadow-sm"
                    id="feature-roles">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center mb-5 shadow-lg shadow-violet-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Multi-Role Akses</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Sistem mendukung tiga peran: Peminjam (mahasiswa
                        & dosen), Penanggung Jawab Ruangan, dan Admin untuk pengelolaan menyeluruh.</p>
                </div>

                <!-- Feature 5 -->
                <div class="reveal reveal-delay-1 feature-card bg-white rounded-2xl p-7 border border-slate-100 shadow-sm"
                    id="feature-letter">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center mb-5 shadow-lg shadow-rose-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Surat Izin Digital</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Cetak surat izin penggunaan ruangan secara
                        otomatis setelah pengajuan disetujui. Paperless dan efisien.</p>
                </div>

                <!-- Feature 6 -->
                <div class="reveal reveal-delay-2 feature-card bg-white rounded-2xl p-7 border border-slate-100 shadow-sm"
                    id="feature-dashboard">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-sky-500 flex items-center justify-center mb-5 shadow-lg shadow-cyan-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Dashboard Informatif</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Dashboard yang disesuaikan per peran menampilkan
                        ringkasan aktivitas, statistik, dan status peminjaman secara visual.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ HOW IT WORKS ============ -->
    <section id="cara-kerja" class="bg-white py-20 md:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section header -->
            <div class="text-center max-w-2xl mx-auto mb-20">
                <p class="reveal text-sm font-bold text-brand-600 uppercase tracking-wider mb-3">Cara Kerja</p>
                <h2
                    class="reveal reveal-delay-1 text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-4">
                    Empat Langkah
                    <span
                        class="bg-gradient-to-r from-brand-600 to-cyan-500 bg-clip-text text-transparent">Mudah</span>
                </h2>
                <p class="reveal reveal-delay-2 text-slate-600 text-lg">
                    Proses peminjaman yang simpel dan terstruktur dari awal hingga akhir.
                </p>
            </div>

            <!-- Steps -->
            <div class="relative max-w-4xl mx-auto">
                <!-- Vertical timeline line (desktop) -->
                <div class="hidden md:block timeline-line"></div>

                <!-- Step 1 -->
                <div class="reveal flex flex-col md:flex-row items-center gap-6 md:gap-12 mb-12 md:mb-16">
                    <div class="md:w-1/2 md:text-right md:pr-12">
                        <div
                            class="inline-flex items-center gap-2 bg-blue-50 text-brand-600 font-bold px-3 py-1 rounded-full text-xs mb-3">
                            Langkah 01
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Login ke Sistem</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Masuk menggunakan akun yang telah didaftarkan
                            oleh admin. Gunakan NIM, NIDN, NIP, atau email sebagai identifier.</p>
                    </div>
                    <div
                        class="relative z-10 w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-xl shadow-blue-500/30 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </div>
                    <div class="md:w-1/2 md:pl-12 hidden md:block"></div>
                </div>

                <!-- Step 2 -->
                <div class="reveal flex flex-col md:flex-row items-center gap-6 md:gap-12 mb-12 md:mb-16">
                    <div class="md:w-1/2 md:text-right md:pr-12 hidden md:block"></div>
                    <div
                        class="relative z-10 w-14 h-14 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-xl shadow-amber-500/30 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="md:w-1/2 md:pl-12">
                        <div
                            class="inline-flex items-center gap-2 bg-amber-50 text-amber-600 font-bold px-3 py-1 rounded-full text-xs mb-3">
                            Langkah 02
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Cek & Pilih Ruangan</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Lihat kalender ketersediaan ruangan, pilih
                            ruangan dan slot waktu yang diinginkan, lalu isi formulir pengajuan.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="reveal flex flex-col md:flex-row items-center gap-6 md:gap-12 mb-12 md:mb-16">
                    <div class="md:w-1/2 md:text-right md:pr-12">
                        <div
                            class="inline-flex items-center gap-2 bg-violet-50 text-violet-600 font-bold px-3 py-1 rounded-full text-xs mb-3">
                            Langkah 03
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Tunggu Verifikasi PJ</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Penanggung jawab ruangan akan memverifikasi
                            pengajuan Anda. Anda akan mendapat notifikasi secara real-time.</p>
                    </div>
                    <div
                        class="relative z-10 w-14 h-14 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-xl shadow-violet-500/30 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div class="md:w-1/2 md:pl-12 hidden md:block"></div>
                </div>

                <!-- Step 4 -->
                <div class="reveal flex flex-col md:flex-row items-center gap-6 md:gap-12">
                    <div class="md:w-1/2 md:text-right md:pr-12 hidden md:block"></div>
                    <div
                        class="relative z-10 w-14 h-14 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-xl shadow-emerald-500/30 shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <div class="md:w-1/2 md:pl-12">
                        <div
                            class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-600 font-bold px-3 py-1 rounded-full text-xs mb-3">
                            Langkah 04
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Gunakan Ruangan</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">Setelah disetujui, cetak surat izin digital
                            dan gunakan ruangan sesuai jadwal. Status ruangan otomatis terbarui.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ ROOM TYPES ============ -->
    <section id="ruangan" class="bg-slate-50 py-20 md:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section header -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <p class="reveal text-sm font-bold text-brand-600 uppercase tracking-wider mb-3">Jurusan & Laboratorium
                </p>
                <h2
                    class="reveal reveal-delay-1 text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-4">
                    Kelola Fasilitas di
                    <span class="bg-gradient-to-r from-brand-600 to-cyan-500 bg-clip-text text-transparent">Seluruh
                        Fakultas</span>
                </h2>
                <p class="reveal reveal-delay-2 text-slate-600 text-lg">
                    SIPERU mencakup ruangan kelas dan laboratorium dari berbagai jurusan di Fakultas Teknik.
                </p>
            </div>

            <!-- Department cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Informatika -->
                <div class="reveal feature-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm"
                    id="dept-informatika">
                    <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                    <div class="p-7">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">Jurusan Informatika</h3>
                                <p class="text-sm text-slate-500">Ruang Kelas & Lab</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">Ruangan untuk kegiatan perkuliahan dan
                            praktikum informatika dengan fasilitas komputer terkini.</p>
                    </div>
                </div>

                <!-- Sipil -->
                <div class="reveal reveal-delay-1 feature-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm"
                    id="dept-sipil">
                    <div class="h-2 bg-gradient-to-r from-amber-500 to-orange-500"></div>
                    <div class="p-7">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">Jurusan Sipil</h3>
                                <p class="text-sm text-slate-500">Ruang Kelas & Lab</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">Ruangan untuk kegiatan perkuliahan dan
                            praktikum teknik sipil dilengkapi peralatan teknis standar.</p>
                    </div>
                </div>

                <!-- PWK -->
                <div class="reveal reveal-delay-2 feature-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm"
                    id="dept-pwk">
                    <div class="h-2 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                    <div class="p-7">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">Jurusan PWK</h3>
                                <p class="text-sm text-slate-500">Perencanaan Wilayah & Kota</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">Ruangan untuk kegiatan perkuliahan dan studio
                            perencanaan wilayah dan kota.</p>
                    </div>
                </div>

                <!-- Lab ICT -->
                <div class="reveal feature-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm"
                    id="dept-lab-ict">
                    <div class="h-2 bg-gradient-to-r from-violet-500 to-purple-500"></div>
                    <div class="p-7">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">Laboratorium ICT</h3>
                                <p class="text-sm text-slate-500">Pusat Teknologi Informasi</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">Laboratorium komputer dengan koneksi jaringan
                            berkecepatan tinggi untuk praktikum dan penelitian.</p>
                    </div>
                </div>

                <!-- Lab Terpadu -->
                <div class="reveal reveal-delay-1 feature-card bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm sm:col-span-2 lg:col-span-2"
                    id="dept-lab-terpadu">
                    <div class="h-2 bg-gradient-to-r from-rose-500 via-pink-500 to-fuchsia-500"></div>
                    <div class="p-7 flex flex-col sm:flex-row gap-6">
                        <div class="flex items-center gap-4 sm:min-w-[240px]">
                            <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">Laboratorium Terpadu</h3>
                                <p class="text-sm text-slate-500">Multi-Disiplin</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">Laboratorium bersama yang mendukung berbagai
                            kegiatan praktikum lintas jurusan dengan peralatan lengkap dan terintegrasi. Tersedia untuk
                            semua program studi di Fakultas Teknik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section id="faq" class="bg-white py-20 md:py-28">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <p class="reveal text-sm font-bold text-brand-600 uppercase tracking-wider mb-3">FAQ</p>
                <h2 class="reveal reveal-delay-1 text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
                    Pertanyaan yang Sering Ditanyakan
                </h2>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="reveal faq-item bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <button onclick="toggleFaq(this)"
                        class="w-full flex items-center justify-between px-6 py-5 text-left" id="faq-1-btn">
                        <span class="font-bold text-slate-900 pr-4">Siapa saja yang bisa menggunakan SIPERU?</span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 faq-icon transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 pb-0 max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-slate-600 text-sm leading-relaxed pb-5">SIPERU dapat digunakan oleh seluruh
                            civitas akademika Fakultas Teknik Universitas Sulawesi Barat, termasuk mahasiswa, dosen, dan
                            staf yang telah didaftarkan oleh admin.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div
                    class="reveal reveal-delay-1 faq-item bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <button onclick="toggleFaq(this)"
                        class="w-full flex items-center justify-between px-6 py-5 text-left" id="faq-2-btn">
                        <span class="font-bold text-slate-900 pr-4">Bagaimana jika slot waktu yang saya inginkan sudah
                            terisi?</span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 faq-icon transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 pb-0 max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-slate-600 text-sm leading-relaxed pb-5">Pengajuan Anda akan otomatis masuk ke
                            dalam sistem antrean. Jika peminjam sebelumnya membatalkan, Anda akan otomatis naik ke
                            posisi berikutnya dan mendapat notifikasi.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div
                    class="reveal reveal-delay-2 faq-item bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <button onclick="toggleFaq(this)"
                        class="w-full flex items-center justify-between px-6 py-5 text-left" id="faq-3-btn">
                        <span class="font-bold text-slate-900 pr-4">Berapa lama proses verifikasi pengajuan?</span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 faq-icon transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 pb-0 max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-slate-600 text-sm leading-relaxed pb-5">Proses verifikasi dilakukan oleh
                            Penanggung Jawab (PJ) ruangan masing-masing jurusan. Biasanya proses ini berlangsung dalam
                            waktu 1×24 jam pada hari kerja.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div
                    class="reveal reveal-delay-3 faq-item bg-slate-50 rounded-2xl border border-slate-100 overflow-hidden">
                    <button onclick="toggleFaq(this)"
                        class="w-full flex items-center justify-between px-6 py-5 text-left" id="faq-4-btn">
                        <span class="font-bold text-slate-900 pr-4">Apakah saya bisa membatalkan peminjaman?</span>
                        <svg class="w-5 h-5 text-slate-400 shrink-0 faq-icon transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 pb-0 max-h-0 overflow-hidden transition-all duration-300">
                        <p class="text-slate-600 text-sm leading-relaxed pb-5">Ya, Anda dapat membatalkan peminjaman
                            melalui halaman "Status Pengajuan" selama status masih pending atau sebelum waktu penggunaan
                            dimulai.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ CTA SECTION ============ -->
    <section class="hero-gradient relative py-20 md:py-28 overflow-hidden">
        <div class="orb orb-1" style="opacity:0.1"></div>
        <div class="orb orb-2" style="opacity:0.08"></div>
        <div class="absolute inset-0 grid-pattern"></div>

        <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="reveal text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-6">
                Siap Mengelola Ruangan dengan Lebih Efisien?
            </h2>
            <p class="reveal reveal-delay-1 text-blue-100/80 text-lg mb-10 max-w-xl mx-auto">
                Bergabung sekarang dan nikmati kemudahan peminjaman ruangan secara digital.
            </p>
            <div class="reveal reveal-delay-2">
                @auth
                    <a href="{{ route('dashboard') }}" id="cta-bottom-dashboard"
                        class="inline-flex items-center gap-2 bg-white text-brand-700 hover:bg-blue-50 px-10 py-4 rounded-2xl font-bold text-lg shadow-2xl shadow-blue-900/30 transition-all">
                        Buka Dashboard
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" id="cta-bottom-login"
                        class="inline-flex items-center gap-2 bg-white text-brand-700 hover:bg-blue-50 px-10 py-4 rounded-2xl font-bold text-lg shadow-2xl shadow-blue-900/30 transition-all">
                        Mulai Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-slate-900 text-slate-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-8 mb-12">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold text-white tracking-tight">SIPERU</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-sm mb-4">
                        Sistem Informasi Peminjaman Ruangan Fakultas Teknik Universitas Sulawesi Barat. Mempermudah
                        pengelolaan fasilitas akademik secara digital.
                    </p>
                </div>

                <!-- Quick links -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#fitur" class="hover:text-white transition">Fitur</a></li>
                        <li><a href="#cara-kerja" class="hover:text-white transition">Cara Kerja</a></li>
                        <li><a href="#ruangan" class="hover:text-white transition">Ruangan</a></li>
                        <li><a href="#faq" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Kontak</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Majene, Sulawesi Barat
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            ft@unsulbar.ac.id
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom bar -->
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} SIPERU — Fakultas Teknik, Universitas Sulawesi Barat. All rights
                    reserved.
                </p>
                <p class="text-xs text-slate-600">
                    Dibangun dengan <span class="text-red-400">♥</span> untuk kemajuan pendidikan.
                </p>
            </div>
        </div>
    </footer>

    <!-- ============ SCRIPTS ============ -->
    <script>
        // ---- Scroll reveal animations ----
        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -40px 0px'
        });
        revealElements.forEach(el => revealObserver.observe(el));

        // ---- Navbar background on scroll ----
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('nav-scrolled', window.scrollY > 40);
        });

        // ---- Stats counter animation ----
        const statElements = document.querySelectorAll('[data-count]');
        let statAnimated = false;
        const statObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !statAnimated) {
                    statAnimated = true;
                    statElements.forEach(el => {
                        const target = parseInt(el.dataset.count);
                        const duration = 2000;
                        const start = performance.now();
                        const animate = (now) => {
                            const elapsed = now - start;
                            const progress = Math.min(elapsed / duration, 1);
                            const eased = 1 - Math.pow(1 - progress, 4);
                            el.textContent = Math.floor(eased * target) + (target >= 100 ? '+' :
                                '');
                            if (progress < 1) requestAnimationFrame(animate);
                        };
                        requestAnimationFrame(animate);
                    });
                }
            });
        }, {
            threshold: 0.3
        });
        statElements.forEach(el => statObserver.observe(el));

        // ---- FAQ accordion ----
        function toggleFaq(btn) {
            const item = btn.closest('.faq-item');
            const answer = item.querySelector('.faq-answer');
            const icon = item.querySelector('.faq-icon');
            const isOpen = answer.classList.contains('faq-open');

            // Close all
            document.querySelectorAll('.faq-answer').forEach(a => {
                a.style.maxHeight = null;
                a.classList.remove('faq-open');
            });
            document.querySelectorAll('.faq-icon').forEach(i => {
                i.style.transform = 'rotate(0deg)';
            });

            // Open clicked if was closed
            if (!isOpen) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                answer.classList.add('faq-open');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // ---- Mobile menu ----
        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('open');
        }

        // Close mobile menu on nav link click
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.remove('open');
            });
        });
    </script>

</body>

</html>
