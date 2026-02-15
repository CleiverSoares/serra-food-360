<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-tema="serra">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titulo', 'Admin') — Serra Food 360</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:300,400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[var(--cor-fundo)] text-[var(--cor-texto)] font-sans antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar (será expandido na Fase 8) --}}
        <aside class="hidden md:flex w-64 flex-col bg-[var(--cor-superficie)] border-r border-[var(--cor-borda)]">
            <div class="p-6">
                <span class="text-xl font-semibold text-[var(--cor-primaria)]">Serra Food 360</span>
                <p class="text-sm text-[var(--cor-texto-muted)] mt-1">Painel Admin</p>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                {{-- Itens serão adicionados na Fase 8 --}}
            </nav>
        </aside>

        <main class="flex-1 p-6 md:p-8 overflow-auto">
            @yield('conteudo')
        </main>
    </div>
</body>
</html>
