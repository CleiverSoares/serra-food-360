<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-tema="serra">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Serra Food 360') — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:300,400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[var(--cor-fundo)] text-[var(--cor-texto)] font-sans antialiased flex flex-col">
    <main class="flex-1 pb-20 md:pb-24">
        @yield('conteudo')
    </main>

    {{-- Bottom Navigation (estilo app) --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-[var(--cor-superficie)] border-t border-[var(--cor-borda)] safe-area-pb z-50 shadow-[0_-4px_20px_var(--cor-borda)]"
         style="box-shadow: var(--sombra-lg);">
        <div class="flex items-center justify-around h-16 md:h-20 max-w-lg mx-auto px-2">
            @yield('bottom-nav')
        </div>
    </nav>
</body>
</html>
