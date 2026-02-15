@extends('layouts.dashboard')

@section('titulo', 'Compradores')
@section('page-title', 'Compradores')
@section('page-subtitle', 'Gerenciar todos os compradores')

@section('sidebar-nav')
<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span>Dashboard</span>
</a>
<a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="user-check" class="w-5 h-5"></i>
    <span>Aprovações</span>
</a>
<a href="{{ route('admin.compradores.index') }}" class="flex items-center gap-3 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-medium">
    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
    <span>Compradores</span>
</a>
<a href="{{ route('admin.fornecedores.index') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span>Fornecedores</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span>Talentos</span>
</a>
@endsection

@section('bottom-nav')
<a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Início</span>
</a>
<a href="{{ route('admin.compradores.index') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-verde-serra)]">
    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
    <span class="text-[10px] font-semibold">Compradores</span>
</a>
<a href="{{ route('admin.fornecedores.index') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Fornecedores</span>
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        @if(session('erro'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800">{{ session('erro') }}</p>
            </div>
        @endif

        <!-- Botão Criar Novo -->
        <div class="mb-6">
            <a href="{{ route('admin.usuarios.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium shadow-sm hover:shadow-md">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Novo Comprador</span>
            </a>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6 mb-6">
            <form method="GET" action="{{ route('admin.compradores.index') }}" class="space-y-4">
                
                <!-- Busca por nome -->
                <div>
                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">
                        <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                        Buscar por nome ou email
                    </label>
                    <input 
                        type="text" 
                        name="busca" 
                        value="{{ $busca }}"
                        placeholder="Digite o nome ou email..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                    >
                </div>

                <!-- Filtros em linha -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            @foreach($filtrosStatus as $key => $label)
                                <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Plano -->
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Plano</label>
                        <select name="plano" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            @foreach($filtrosPlano as $key => $label)
                                <option value="{{ $key }}" {{ $plano == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cidade -->
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Cidade</label>
                        <select name="cidade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            @foreach($filtrosCidade as $c)
                                <option value="{{ $c }}" {{ $cidade == $c ? 'selected' : '' }}>
                                    {{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Segmento -->
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Segmento</label>
                        <select name="segmento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Todos</option>
                            @foreach($segmentos as $seg)
                                <option value="{{ $seg->id }}" {{ $segmentoId == $seg->id ? 'selected' : '' }}>
                                    {{ $seg->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm md:text-base">
                        <i data-lucide="filter" class="w-4 h-4 flex-shrink-0"></i>
                        <span class="whitespace-nowrap">Filtrar</span>
                    </button>
                    <a href="{{ route('admin.compradores.index') }}" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                        <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                        <span class="whitespace-nowrap">Limpar</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Contador -->
        <div class="mb-4">
            <p class="text-sm text-[var(--cor-texto-muted)]">
                <strong>{{ $compradores->count() }}</strong> comprador(es) encontrado(s)
            </p>
        </div>

        <!-- Lista de Compradores -->
        <div class="grid grid-cols-1 gap-4">
            @forelse($compradores as $comprador)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 hover:shadow-md transition-shadow overflow-hidden">
                    <div class="flex items-start gap-3 md:gap-4 min-w-0">
                        
                        <!-- Logo/Avatar -->
                        <div class="flex-shrink-0">
                            @if($comprador->comprador && $comprador->comprador->logo_path)
                                <img src="{{ asset('storage/' . $comprador->comprador->logo_path) }}" 
                                     alt="{{ $comprador->name }}"
                                     class="w-12 h-12 md:w-16 md:h-16 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-lg bg-[var(--cor-verde-serra)] text-white flex items-center justify-center text-xl md:text-2xl font-bold">
                                    {{ strtoupper(substr($comprador->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-start justify-between gap-2 md:gap-4 mb-2">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base md:text-lg font-bold text-[var(--cor-texto)] truncate">
                                        {{ $comprador->name }}
                                    </h3>
                                    @if($comprador->comprador)
                                        <p class="text-sm text-[var(--cor-texto-muted)] truncate">
                                            {{ $comprador->comprador->nome_negocio }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Status Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                    {{ $comprador->status === 'aprovado' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $comprador->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $comprador->status === 'inativo' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $comprador->status === 'rejeitado' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($comprador->status) }}
                                </span>
                            </div>

                            <!-- Detalhes -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm mb-3">
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="mail" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $comprador->email }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="phone" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $comprador->whatsapp }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $comprador->cidade }}</span>
                                </div>
                            </div>

                            <!-- Segmentos -->
                            @if($comprador->segmentos->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($comprador->segmentos as $segmento)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium"
                                              style="background-color: {{ $segmento->cor }}20; color: {{ $segmento->cor }};">
                                            <i data-lucide="{{ $segmento->icone }}" class="w-3 h-3"></i>
                                            {{ $segmento->nome }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Ações -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('admin.compradores.show', $comprador->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i data-lucide="eye" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ver</span>
                                </a>
                                <a href="{{ route('admin.compradores.edit', $comprador->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                    <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Editar</span>
                                </a>
                                @if($comprador->status === 'inativo')
                                    <form action="{{ route('admin.compradores.ativar', $comprador->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                            <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                                            <span class="whitespace-nowrap">Ativar</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.compradores.inativar', $comprador->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors text-sm font-medium">
                                            <i data-lucide="pause-circle" class="w-4 h-4 flex-shrink-0"></i>
                                            <span class="whitespace-nowrap">Inativar</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                    <p class="text-yellow-800 font-medium">Nenhum comprador encontrado</p>
                    <p class="text-yellow-700 text-sm mt-1">Ajuste os filtros ou limpe para ver todos</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
