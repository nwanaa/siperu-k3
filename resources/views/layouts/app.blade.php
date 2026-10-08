@php
    $authUser = auth()->user();
    $unread = $authUser->unreadNotifications()->count();
    $pageTitle = isset($header) ? trim(strip_tags((string) $header)) : 'SIPERU';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} - SIPERU</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ sidebar: false, collapsed: false }">

    {{-- Overlay (mobile) --}}
    <div x-show="sidebar" x-transition.opacity @click="sidebar = false"
         class="fixed inset-0 z-30 bg-slate-900/50 md:hidden" style="display:none"></div>

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-blue-900 text-white flex flex-col transition-transform duration-200"
           :class="[sidebar ? 'translate-x-0' : '-translate-x-full', collapsed ? 'md:-translate-x-full' : 'md:translate-x-0']">
        <div class="h-16 flex items-center px-6 mb-2">
            <span class="text-2xl font-extrabold tracking-wider">SIPERU</span>
        </div>

        <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
            @include('layouts.partials.sidebar-menu')
        </nav>

        <div class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-3 px-4 py-3 w-full rounded-lg text-sm font-medium hover:bg-red-600 transition">
                    <x-icon name="logout" class="w-5 h-5 opacity-70" />
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Konten --}}
    <div class="min-h-screen flex flex-col transition-[padding] duration-200"
         :class="collapsed ? 'md:pl-0' : 'md:pl-64'">

        <header class="sticky top-0 z-20 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8">
            <div class="flex items-center gap-3 min-w-0">
                <button type="button" aria-label="Menu"
                        @click="window.innerWidth >= 768 ? collapsed = !collapsed : sidebar = !sidebar"
                        class="text-slate-500 hover:text-slate-800">
                    <x-icon name="menu" class="w-6 h-6" />
                </button>
                @isset($header)
                    <h1 class="font-bold text-blue-900 truncate">{{ $header }}</h1>
                @endisset
            </div>

            <div class="flex items-center gap-4 md:gap-5">
                <a href="{{ Route::has('notifications.index') ? route('notifications.index') : '#' }}"
                   class="relative text-slate-500 hover:text-slate-800" aria-label="Notifikasi">
                    <x-icon name="bell" class="w-6 h-6" />
                    @if ($unread > 0)
                        <span class="absolute -top-1 -right-1 min-w-4 h-4 px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
                            {{ $unread > 9 ? '9+' : $unread }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 border-l border-slate-200 pl-4 md:pl-5">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center uppercase">
                        {{ mb_substr($authUser->name, 0, 2) }}
                    </div>
                    <div class="hidden md:block leading-tight">
                        <p class="text-sm font-semibold text-slate-800">{{ $authUser->name }}</p>
                        <p class="text-xs text-slate-500">{{ $authUser->roleLabel() }}</p>
                    </div>
                </a>
            </div>
        </header>

        <main class="flex-1 p-4 md:p-8">
            @foreach (['success' => 'emerald', 'status' => 'emerald', 'error' => 'red'] as $key => $color)
                @if (session($key) && is_string(session($key)))
                    <div class="mb-6 rounded-lg border px-4 py-3 text-sm
                                {{ $color === 'red' ? 'border-red-200 bg-red-50 text-red-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}">
                        {{ session($key) }}
                    </div>
                @endif
            @endforeach

            {{ $slot }}
        </main>
    </div>
</body>
</html>