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

        {{-- User Info + Dropdown --}}
        <div class="flex-shrink-0 border-t border-[var(--cor-borda)] p-4" x-data="{ dropdownAberto: false }">
            <div class="relative">
                @php
                    $userLogoPath = auth()->user()->logo_path ?? auth()->user()->comprador?->logo_path ?? auth()->user()->fornecedor?->logo_path ?? null;
                @endphp
                <button @click="dropdownAberto = !dropdownAberto" 
                        class="w-full flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                    @if($userLogoPath)
                        <img src="{{ asset('storage/' . $userLogoPath) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-[var(--cor-verde-serra)] flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0 text-left">
                        <p class="text-sm font-semibold text-[var(--cor-texto)] truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)] capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <i data-lucide="chevron-up" class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': !dropdownAberto }"></i>
                </button>

                {{-- Dropdown --}}
                <div x-show="dropdownAberto" 
                     @click.away="dropdownAberto = false"
                     x-transition
                     class="absolute bottom-full left-0 right-0 mb-2 bg-white border border-[var(--cor-borda)] rounded-lg shadow-lg overflow-hidden">
                    <a href="{{ route('perfil.editar') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors cursor-pointer">
                        <i data-lucide="user-circle" class="w-4 h-4 text-gray-600"></i>
                        <span class="text-sm font-medium text-[var(--cor-texto)]">Meu Perfil</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-colors text-left cursor-pointer">
                            <i data-lucide="log-out" class="w-4 h-4 text-red-600"></i>
                            <span class="text-sm font-medium text-red-700">Sair</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
