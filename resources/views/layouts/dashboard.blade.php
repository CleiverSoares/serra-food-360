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
<body class="min-h-screen bg-[var(--cor-fundo)] text-[var(--cor-texto)] font-sans antialiased overflow-x-hidden">
    
    {{-- DESKTOP: Sidebar --}}
    <aside class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col bg-white border-r border-[var(--cor-borda)] z-40">
        <div class="flex flex-col flex-1 min-h-0">
            {{-- Logo --}}
            <div class="flex items-center h-20 px-6 border-b border-[var(--cor-borda)]">
                <img src="/images/logo-serra.png" alt="Serra Food 360" class="h-10 w-auto">
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.dashboard') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                {{-- Aprovações --}}
                <a href="{{ route('admin.usuarios.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.usuarios.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                    <span>Aprovações</span>
                </a>

                {{-- Compradores --}}
                <a href="{{ route('admin.compradores.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.compradores.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span>Compradores</span>
                </a>

                {{-- Fornecedores --}}
                <a href="{{ route('admin.fornecedores.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.fornecedores.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Fornecedores</span>
                </a>

                {{-- Talentos --}}
                <a href="{{ route('admin.talentos.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.talentos.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                    <span>Talentos</span>
                </a>

                {{-- Segmentos --}}
                <a href="{{ route('admin.segmentos.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.segmentos.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    <span>Segmentos</span>
                </a>

                {{-- Configurações --}}
                <a href="#" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span>Configurações</span>
                </a>
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
    <div class="lg:pl-64 flex flex-col min-h-screen w-full overflow-x-hidden">
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
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-[var(--cor-borda)] z-50 safe-area-pb shadow-[0_-4px_20px_rgba(0,0,0,0.08)]" 
         x-data="{ 
             menuAberto: false,
             startX: 0,
             currentX: 0,
             isDragging: false,
             handleTouchStart(e) {
                 this.startX = e.touches[0].clientX;
                 this.isDragging = true;
             },
             handleTouchMove(e) {
                 if (!this.isDragging) return;
                 this.currentX = e.touches[0].clientX;
                 const diff = this.currentX - this.startX;
                 if (diff > 0) {
                     e.target.style.transform = `translateX(${diff}px)`;
                 }
             },
             handleTouchEnd(e) {
                 if (!this.isDragging) return;
                 const diff = this.currentX - this.startX;
                 if (diff > 100) {
                     this.menuAberto = false;
                 }
                 e.target.style.transform = '';
                 this.isDragging = false;
             }
         }">
        <div class="flex items-center justify-around h-16 max-w-lg mx-auto px-2">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="flex flex-col items-center gap-1 p-2 transition-colors
               {{ request()->routeIs('admin.dashboard') 
                   ? 'text-[var(--cor-verde-serra)]' 
                   : 'text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)]' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="text-[10px] {{ request()->routeIs('admin.dashboard') ? 'font-semibold' : 'font-medium' }}">Início</span>
            </a>

            {{-- Compradores --}}
            <a href="{{ route('admin.compradores.index') }}" 
               class="flex flex-col items-center gap-1 p-2 transition-colors
               {{ request()->routeIs('admin.compradores.*') 
                   ? 'text-[var(--cor-verde-serra)]' 
                   : 'text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)]' }}">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                <span class="text-[10px] {{ request()->routeIs('admin.compradores.*') ? 'font-semibold' : 'font-medium' }}">Compradores</span>
            </a>

            {{-- Fornecedores --}}
            <a href="{{ route('admin.fornecedores.index') }}" 
               class="flex flex-col items-center gap-1 p-2 transition-colors
               {{ request()->routeIs('admin.fornecedores.*') 
                   ? 'text-[var(--cor-verde-serra)]' 
                   : 'text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)]' }}">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span class="text-[10px] {{ request()->routeIs('admin.fornecedores.*') ? 'font-semibold' : 'font-medium' }}">Fornecedores</span>
            </a>
            
            {{-- Menu Hamburguer (sempre visível no mobile) --}}
            <button @click="menuAberto = true" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
                <i data-lucide="menu" class="w-5 h-5"></i>
                <span class="text-[10px] font-medium">Menu</span>
            </button>
        </div>

        {{-- Overlay escuro --}}
        <div x-show="menuAberto" 
             x-transition:enter="transition-opacity duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="menuAberto = false"
             class="fixed inset-0 bg-black/50 z-[60] backdrop-blur-sm"
             style="display: none;">
        </div>

        {{-- Menu Drawer Lateral --}}
        <div x-show="menuAberto"
             x-transition:enter="transition-transform duration-300 ease-out"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition-transform duration-300 ease-in"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             @touchstart="handleTouchStart($event)"
             @touchmove="handleTouchMove($event)"
             @touchend="handleTouchEnd($event)"
             class="fixed top-0 right-0 bottom-0 w-80 max-w-[85vw] bg-white shadow-2xl z-[70] overflow-y-auto"
             style="display: none;">
            
            {{-- Header do Menu --}}
            <div class="sticky top-0 bg-[var(--cor-verde-serra)] text-white p-6 flex items-center justify-between shadow-md z-10">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-lg flex-shrink-0 ring-2 ring-white/30">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold truncate text-base">{{ auth()->user()->name }}</p>
                        <p class="text-xs opacity-90 capitalize truncate">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <button @click="menuAberto = false" class="p-2 hover:bg-white/20 rounded-lg transition-all flex-shrink-0 ml-2">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- Indicador de Swipe --}}
            <div class="bg-gray-50 px-6 py-2 text-center">
                <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto"></div>
                <p class="text-[10px] text-gray-500 mt-1">Arraste para fechar →</p>
            </div>

            {{-- Lista de Menu --}}
            <div class="p-4 space-y-2 pb-6">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" 
                   @click="menuAberto = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.dashboard') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                {{-- Aprovações --}}
                <a href="{{ route('admin.usuarios.index') }}" 
                   @click="menuAberto = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.usuarios.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                    <span>Aprovações</span>
                </a>

                {{-- Compradores --}}
                <a href="{{ route('admin.compradores.index') }}" 
                   @click="menuAberto = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.compradores.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span>Compradores</span>
                </a>

                {{-- Fornecedores --}}
                <a href="{{ route('admin.fornecedores.index') }}" 
                   @click="menuAberto = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.fornecedores.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span>Fornecedores</span>
                </a>

                {{-- Talentos --}}
                <a href="{{ route('admin.talentos.index') }}" 
                   @click="menuAberto = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.talentos.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="briefcase" class="w-5 h-5"></i>
                    <span>Talentos</span>
                </a>

                {{-- Segmentos --}}
                <a href="{{ route('admin.segmentos.index') }}" 
                   @click="menuAberto = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   {{ request()->routeIs('admin.segmentos.*') 
                       ? 'bg-[var(--cor-verde-serra)] text-white shadow-sm' 
                       : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]' }}">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                    <span>Segmentos</span>
                </a>

                {{-- Configurações --}}
                <a href="#" 
                   @click="menuAberto = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1
                   text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span>Configurações</span>
                </a>
            </div>

            {{-- Footer do Menu --}}
            <div class="sticky bottom-0 bg-gray-50 border-t border-[var(--cor-borda)] p-4 shadow-[0_-4px_12px_rgba(0,0,0,0.05)]">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-medium shadow-lg">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Sair da Conta
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <script>document.addEventListener('DOMContentLoaded',()=>{typeof lucide!=='undefined'&&lucide.createIcons()})</script>
</body>
</html>
