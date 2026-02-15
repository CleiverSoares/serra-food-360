<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-tema="serra">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Serra Food 360') — {{ config('app.name') }}</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="/images/fiveicon-360.svg">
    <link rel="shortcut icon" href="/images/fiveicon-360.svg">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700|fraunces:500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body class="min-h-screen bg-[var(--cor-fundo)] text-[var(--cor-texto)] font-sans antialiased flex flex-col">
    {{-- Navbar Desktop (esconde no mobile) --}}
    <nav class="hidden md:block sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-[var(--cor-borda)] shadow-sm">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="/" class="block group">
                    <img src="/images/logo-serra.png" 
                         alt="Serra Food 360" 
                         class="h-12 w-auto group-hover:scale-105 transition-transform"
                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden w-10 h-10 rounded-xl bg-[var(--cor-verde-serra)] items-center justify-center group-hover:scale-110 transition-transform">
                        <i data-lucide="utensils" class="w-6 h-6 text-white"></i>
                    </div>
                </a>
                
                {{-- Links de navegação --}}
                <div class="flex items-center gap-8">
                    <a href="/#como-funciona" class="text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] font-medium transition-colors">
                        Como Funciona
                    </a>
                    <a href="/#modulos" class="text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] font-medium transition-colors">
                        Módulos
                    </a>
                    <a href="/#planos" class="text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] font-medium transition-colors">
                        Planos
                    </a>
                    <a href="https://wa.me/5551999999999" target="_blank" rel="noopener" class="text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] font-medium transition-colors">
                        Contato
                    </a>
                    
                    {{-- Botão Login/Admin --}}
                    <a href="/admin/login" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-xl font-bold hover:bg-[var(--cor-verde-serra-hover)] transition-all shadow-md hover:shadow-lg">
                        <i data-lucide="log-in" class="w-4 h-4"></i>
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-1 pb-20 md:pb-0">
        @yield('conteudo')
    </main>

    {{-- Bottom Navigation (apenas mobile - estilo app) --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-[var(--cor-superficie)] border-t border-[var(--cor-borda)] safe-area-pb z-50 shadow-[0_-4px_20px_var(--cor-borda)]"
         style="box-shadow: var(--sombra-lg);">
        <div class="flex items-center justify-around h-16 max-w-lg mx-auto px-2">
            @yield('bottom-nav')
        </div>
    </nav>
    <script>document.addEventListener('DOMContentLoaded',()=>{typeof lucide!=='undefined'&&lucide.createIcons()})</script>
</body>
</html>
