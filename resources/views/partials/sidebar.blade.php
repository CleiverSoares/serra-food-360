{{-- Sidebar Desktop --}}
<aside class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col bg-white border-r border-[var(--cor-borda)] z-40">
    <div class="flex flex-col flex-1 min-h-0">
        {{-- Logo --}}
        <div class="flex items-center justify-center h-20 px-6 border-b border-[var(--cor-borda)]">
            <img src="/images/logo-serra.png" alt="Serra Food 360" class="h-16 w-auto">
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @include('partials.menu-items', ['context' => 'sidebar'])
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
