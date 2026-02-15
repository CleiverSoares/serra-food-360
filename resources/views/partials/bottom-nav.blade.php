{{-- Bottom Navigation Mobile --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-[var(--cor-borda)] z-50 safe-area-pb shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
    <div class="flex items-center justify-around h-16 max-w-lg mx-auto px-2">
        @include('partials.menu-items', ['context' => 'bottom-nav'])
    </div>
</nav>
