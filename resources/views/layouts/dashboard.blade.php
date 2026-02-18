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
<body class="min-h-screen bg-[var(--cor-fundo)] text-[var(--cor-texto)] font-sans antialiased overflow-x-hidden" 
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
                  e.target.style.transform = 'translateX(' + diff + 'px)';
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
      }"
      x-init="$watch('menuAberto', value => { document.body.style.overflow = value ? 'hidden' : 'auto'; })">
    
    {{-- Sidebar Desktop --}}
    @include('partials.sidebar')

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
            <div class="flex items-center gap-3">
                @yield('mobile-header-actions')
                
                {{-- Botão Hambúrguer --}}
                <button @click="menuAberto = true" 
                        class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                        aria-label="Abrir menu">
                    <i data-lucide="menu" class="w-6 h-6 text-[var(--cor-texto)]"></i>
                </button>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 pb-20 lg:pb-0">
            @yield('conteudo')
        </main>
    </div>

    {{-- Bottom Navigation Mobile --}}
    @include('partials.bottom-nav')

    {{-- Drawer Menu Mobile --}}
    @include('partials.drawer')

    <script>document.addEventListener('DOMContentLoaded',()=>{typeof lucide!=='undefined'&&lucide.createIcons()})</script>
    
    @stack('scripts')
</body>
</html>
