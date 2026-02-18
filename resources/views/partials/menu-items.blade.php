{{-- 
    Itens do Menu Unificado
    Usado em: sidebar, bottom-nav, drawer
    
    Parâmetros:
    - $context: 'sidebar', 'bottom-nav', 'drawer'
--}}

@php
    $isSidebar = $context === 'sidebar';
    $isBottomNav = $context === 'bottom-nav';
    $isDrawer = $context === 'drawer';
    
    $role = auth()->user()->role;
    $isAdmin = $role === 'admin';
    $isFornecedor = $role === 'fornecedor';
    
    // Classes condicionais
    $linkClass = $isBottomNav 
        ? 'flex flex-col items-center gap-1 p-2 transition-colors'
        : 'flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition-all hover:translate-x-1';
    
    $activeClass = $isBottomNav
        ? 'text-[var(--cor-verde-serra)]'
        : 'bg-[var(--cor-verde-serra)] text-white shadow-sm';
    
    $inactiveClass = $isBottomNav
        ? 'text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)]'
        : 'text-[var(--cor-texto-secundario)] hover:bg-gray-100 hover:text-[var(--cor-verde-serra)]';
@endphp

{{-- Início --}}
<a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" 
   class="{{ $linkClass }} {{ request()->routeIs(['admin.dashboard', 'dashboard']) ? $activeClass : $inactiveClass }}"
   @if($isDrawer) @click="menuAberto = false" @endif>
    <i data-lucide="home" class="w-5 h-5"></i>
    @if(!$isBottomNav || $isBottomNav)
        <span class="{{ $isBottomNav ? 'text-[10px]' : '' }} {{ request()->routeIs(['admin.dashboard', 'dashboard']) && $isBottomNav ? 'font-semibold' : ($isBottomNav ? 'font-medium' : '') }}">Início</span>
    @endif
</a>

{{-- Aprovações (apenas admin, não aparece no bottom nav) --}}
@if($isAdmin && !$isBottomNav)
    @php
        $pendentesCount = \App\Models\UserModel::where('status', 'pendente')->count();
    @endphp
    <a href="{{ route('admin.usuarios.index') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('admin.usuarios.*') ? $activeClass : $inactiveClass }} relative"
       @if($isDrawer) @click="menuAberto = false" @endif>
        <i data-lucide="user-check" class="w-5 h-5"></i>
        <span>Aprovações</span>
        @if($pendentesCount > 0)
            <span class="ml-auto inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-600 rounded-full">
                {{ $pendentesCount }}
            </span>
        @endif
    </a>
@endif

{{-- Compradores (todos veem) --}}
<a href="{{ $isAdmin ? route('admin.compradores.index') : route('compradores.index') }}" 
   class="{{ $linkClass }} {{ request()->routeIs(['admin.compradores.*', 'compradores.*']) ? $activeClass : $inactiveClass }}"
   @if($isDrawer) @click="menuAberto = false" @endif>
    <i data-lucide="store" class="w-5 h-5"></i>
    <span class="{{ $isBottomNav ? 'text-[10px]' : '' }} {{ request()->routeIs(['admin.compradores.*', 'compradores.*']) && $isBottomNav ? 'font-semibold' : ($isBottomNav ? 'font-medium' : '') }}">Compradores</span>
</a>

{{-- Fornecedores (todos veem) --}}
<a href="{{ $isAdmin ? route('admin.fornecedores.index') : route('fornecedores.index') }}" 
   class="{{ $linkClass }} {{ request()->routeIs(['admin.fornecedores.*', 'fornecedores.*']) ? $activeClass : $inactiveClass }}"
   @if($isDrawer) @click="menuAberto = false" @endif>
    <i data-lucide="package" class="w-5 h-5"></i>
    <span class="{{ $isBottomNav ? 'text-[10px]' : '' }} {{ request()->routeIs(['admin.fornecedores.*', 'fornecedores.*']) && $isBottomNav ? 'font-semibold' : ($isBottomNav ? 'font-medium' : '') }}">Fornecedores</span>
</a>

{{-- Talentos (fornecedor NÃO vê) --}}
@if(!$isFornecedor)
    <a href="{{ $isAdmin ? route('admin.talentos.index') : route('talentos.index') }}" 
       class="{{ $linkClass }} {{ request()->routeIs(['admin.talentos.*', 'talentos.*']) ? $activeClass : $inactiveClass }}"
       @if($isDrawer) @click="menuAberto = false" @endif>
        <i data-lucide="briefcase" class="w-5 h-5"></i>
        <span class="{{ $isBottomNav ? 'text-[10px]' : '' }} {{ request()->routeIs(['admin.talentos.*', 'talentos.*']) && $isBottomNav ? 'font-semibold' : ($isBottomNav ? 'font-medium' : '') }}">Talentos</span>
    </a>
@endif

{{-- Segmentos (apenas admin, não aparece no bottom nav) --}}
@if($isAdmin && !$isBottomNav)
    <a href="{{ route('admin.segmentos.index') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('admin.segmentos.*') ? $activeClass : $inactiveClass }}"
       @if($isDrawer) @click="menuAberto = false" @endif>
        <i data-lucide="tag" class="w-5 h-5"></i>
        <span>Segmentos</span>
    </a>
@endif

{{-- Cotações (todos veem, exceto bottom nav) --}}
@if(!$isBottomNav)
    <a href="{{ $isAdmin ? route('admin.cotacoes.index') : route('cotacoes.index') }}" 
       class="{{ $linkClass }} {{ request()->routeIs(['admin.cotacoes.*', 'cotacoes.*']) ? $activeClass : $inactiveClass }}"
       @if($isDrawer) @click="menuAberto = false" @endif>
        <i data-lucide="file-text" class="w-5 h-5"></i>
        <span>Cotações</span>
    </a>

    <a href="{{ $isAdmin ? route('admin.compras-coletivas.index') : route('compras-coletivas.index') }}" 
       class="{{ $linkClass }} {{ request()->routeIs(['admin.compras-coletivas.*', 'compras-coletivas.*']) ? $activeClass : $inactiveClass }}"
       @if($isDrawer) @click="menuAberto = false" @endif>
        <i data-lucide="shopping-basket" class="w-5 h-5 flex-shrink-0"></i>
        <span class="whitespace-nowrap">Compras Coletivas</span>
    </a>

    <a href="{{ $isAdmin ? route('admin.materiais.index') : route('materiais.index') }}" 
       class="{{ $linkClass }} {{ request()->routeIs(['admin.materiais.*', 'materiais.*']) ? $activeClass : $inactiveClass }}"
       @if($isDrawer) @click="menuAberto = false" @endif>
        <i data-lucide="book-open" class="w-5 h-5 flex-shrink-0"></i>
        <span class="whitespace-nowrap">Material de Gestão</span>
    </a>
@endif

{{-- Meu Perfil (todos, APENAS no drawer/mobile) --}}
@if($isDrawer)
    <a href="{{ route('perfil.editar') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('perfil.*') ? $activeClass : $inactiveClass }}"
       @click="menuAberto = false">
        <i data-lucide="user-circle" class="w-5 h-5"></i>
        <span>Meu Perfil</span>
    </a>
@endif

{{-- Configurações (apenas admin, no final de tudo) --}}
@if($isAdmin && !$isBottomNav)
    <a href="{{ route('admin.configuracoes.index') }}" 
       class="{{ $linkClass }} {{ request()->routeIs('admin.configuracoes.*') ? $activeClass : $inactiveClass }}"
       @if($isDrawer) @click="menuAberto = false" @endif>
        <i data-lucide="settings" class="w-5 h-5"></i>
        <span>Configurações</span>
    </a>
@endif
