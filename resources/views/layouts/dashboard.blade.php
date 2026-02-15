<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-tema="serra">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Dashboard') — {{ config('app.name') }}</title>

    <link rel="icon" type="image/svg+xml" href="/images/fiveicon-360.svg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|fraunces:500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body class="min-h-screen bg-[var(--cor-fundo)] text-[var(--cor-texto)] font-sans antialiased">
    
    {{-- DESKTOP: Sidebar --}}
    <aside class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col bg-white border-r border-[var(--cor-borda)] z-40">
        <div class="flex flex-col flex-1 min-h-0">
            {{-- Logo --}}
            <div class="flex items-center h-20 px-6 border-b border-[var(--cor-borda)]">
                <img src="/images/logo-serra.png" alt="Serra Food 360" class="h-10 w-auto">
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                @yield('sidebar-nav')
            </nav>

            {{-- User Info + Logout --}}
            <div class="flex-shrink-0 border-t border-[var(--cor-borda)] p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-[var(--cor-verde-serra)] flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-[var(--cor-texto)] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)] capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-all text-sm font-medium">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="lg:pl-64 flex flex-col min-h-screen">
        {{-- DESKTOP: Top Header --}}
        <header class="hidden lg:flex items-center justify-between h-20 px-8 bg-white border-b border-[var(--cor-borda)] sticky top-0 z-30">
            <div>
                <h1 class="text-2xl font-bold text-[var(--cor-texto)]">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-[var(--cor-texto-secundario)]">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-4">
                @yield('header-actions')
            </div>
        </header>

        {{-- MOBILE: Top Header --}}
        <header class="lg:hidden flex items-center justify-between h-16 px-4 bg-white border-b border-[var(--cor-borda)] sticky top-0 z-30">
            <img src="/images/logo-serra.png" alt="Serra Food 360" class="h-8 w-auto">
            <div class="flex items-center gap-2">
                @yield('mobile-header-actions')
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 pb-20 lg:pb-0">
            @yield('conteudo')
        </main>
    </div>

    {{-- MOBILE: Bottom Navigation --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-[var(--cor-borda)] z-50 safe-area-pb shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
        <div class="flex items-center justify-around h-16 max-w-lg mx-auto px-2">
            @yield('bottom-nav')
        </div>
    </nav>

    <script>document.addEventListener('DOMContentLoaded',()=>{typeof lucide!=='undefined'&&lucide.createIcons()})</script>
</body>
</html>
