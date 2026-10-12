<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPERU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white text-slate-800 antialiased overflow-hidden">

    <div class="flex min-h-screen w-full">
        <!-- Kiri: Form Login -->
        <div class="w-full md:w-[55%] lg:w-1/2 flex flex-col justify-center px-10 md:px-16 lg:px-24">

            <!-- Logo & Title -->
            <div class="flex items-center gap-4 mb-10">
                <div
                    class="w-16 h-16 shrink-0 relative bg-blue-100 rounded shadow-sm border border-blue-200 flex items-center justify-center">
                    <span class="text-blue-700 font-bold text-xs">LOGO</span>
                    <div
                        class="absolute -bottom-1 -right-1 w-6 h-6 bg-teal-400 rounded-full border-2 border-white flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold text-blue-900 tracking-tight leading-none mb-1">SIPERU</h1>
                    <p class="text-slate-600 text-sm font-medium leading-tight">Sistem Informasi Peminjaman
                        Ruangan<br>Universitas Sulawesi Barat</p>
                </div>
            </div>

            <!-- Selamat Datang -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-blue-900 mb-1">Selamat Datang!</h2>
                <p class="text-slate-600">Silahkan login untuk masuk</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <input type="text" name="identifier" value="{{ old('identifier') }}"
                        placeholder="Email / NIM / NIDN / NIP.." required autofocus autocomplete="username"
                        class="w-full px-4 py-2.5 rounded border text-sm placeholder-slate-400 focus:outline-none focus:border-blue-600
                           {{ $errors->has('identifier') ? 'border-red-500' : 'border-slate-300' }}">
                    @error('identifier')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input type="password" name="password" placeholder="Password.." required
                        autocomplete="current-password"
                        class="w-full px-4 py-2.5 rounded border border-slate-300 focus:outline-none focus:border-blue-600 text-sm placeholder-slate-400">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center pt-2">
                    <label class="flex items-center text-sm text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="mr-2 w-4 h-4 rounded border-slate-300 text-blue-700">
                        Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-medium text-slate-600 hover:text-blue-700">Lupa password?</a>
                </div>

                <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2.5 rounded mt-2 transition text-lg">
                    Login
                </button>
            </form>

            <div class="mt-8 text-center text-xs text-slate-600 font-medium">
                Universitas Sulawesi Barat
            </div>
        </div>

        <!-- Kanan: Gambar -->
        <div class="hidden md:block md:w-[45%] lg:w-1/2 relative bg-slate-200">
            <img src="{{ asset('images/image.png') }}" alt="Gedung Universitas Sulawesi Barat"
                class="absolute inset-0 w-full h-full object-cover object-left">
            <div
                class="absolute bottom-0 left-0 right-0 w-full h-48 overflow-hidden pointer-events-none flex items-end">
                <svg viewBox="0 0 1440 320" preserveAspectRatio="none" class="w-full h-full">
                    <path fill="#1e3a8a"
                        d="M0,224L80,229.3C160,235,320,245,480,229.3C640,213,800,171,960,165.3C1120,160,1280,192,1360,208L1440,224L1440,320L0,320Z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

</body>

</html>
