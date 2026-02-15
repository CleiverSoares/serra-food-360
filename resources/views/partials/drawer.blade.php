{{-- Menu Drawer Mobile --}}

{{-- Overlay escuro --}}
<div x-show="menuAberto" 
     x-transition:enter="transition-opacity duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="menuAberto = false"
     class="fixed inset-0 bg-black/50 z-[60] backdrop-blur-sm lg:hidden"
     style="display: none;">
</div>

{{-- Drawer Lateral --}}
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
     class="fixed top-0 right-0 bottom-0 w-80 max-w-[85vw] bg-white shadow-2xl z-[70] overflow-y-auto lg:hidden"
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
        @include('partials.menu-items', ['context' => 'drawer'])
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
