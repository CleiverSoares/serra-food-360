@extends('layouts.dashboard')

@section('titulo', 'Fornecedores')
@section('page-title', 'Fornecedores')
@section('page-subtitle', auth()->user()->role === 'admin' ? 'Gerenciar todos os fornecedores' : 'Diretório de fornecedores')

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

        <!-- Botão Criar Novo (apenas admin) -->
        @if(auth()->user()->role === 'admin')
            <div class="mb-6">
                <a href="{{ route('admin.fornecedores.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium shadow-sm hover:shadow-md">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Novo Fornecedor</span>
                </a>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6 mb-6">
            <form method="GET" action="{{ route('admin.fornecedores.index') }}" class="space-y-4">
                
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
                    <a href="{{ route('admin.fornecedores.index') }}" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                        <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                        <span class="whitespace-nowrap">Limpar</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Contador -->
        <div class="mb-4">
            <p class="text-sm text-[var(--cor-texto-muted)]">
                <strong>{{ $fornecedores->count() }}</strong> fornecedor(es) encontrado(s)
            </p>
        </div>

        <!-- Lista de Fornecedores -->
        <div class="grid grid-cols-1 gap-4">
            @forelse($fornecedores as $fornecedor)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 hover:shadow-md transition-shadow overflow-hidden">
                    <div class="flex items-start gap-3 md:gap-4 min-w-0">
                        
                        <!-- Logo/Avatar -->
                        <div class="flex-shrink-0">
                            @if($fornecedor->fornecedor && $fornecedor->fornecedor->logo_path)
                                <img src="{{ asset('storage/' . $fornecedor->fornecedor->logo_path) }}" 
                                     alt="{{ $fornecedor->name }}"
                                     class="w-12 h-12 md:w-16 md:h-16 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-lg bg-purple-600 text-white flex items-center justify-center text-xl md:text-2xl font-bold">
                                    {{ strtoupper(substr($fornecedor->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-start justify-between gap-2 md:gap-4 mb-2">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base md:text-lg font-bold text-[var(--cor-texto)] truncate">
                                        {{ $fornecedor->name }}
                                    </h3>
                                    @if($fornecedor->fornecedor)
                                        <p class="text-sm text-[var(--cor-texto-muted)] truncate">
                                            {{ $fornecedor->fornecedor->nome_empresa }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Status Badges -->
                                <div class="flex flex-col gap-1">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                        {{ $fornecedor->status === 'aprovado' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $fornecedor->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $fornecedor->status === 'inativo' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $fornecedor->status === 'rejeitado' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($fornecedor->status) }}
                                    </span>
                                    @php
                                        $assinatura = $fornecedor->assinaturaAtiva;
                                    @endphp
                                    @if($assinatura)
                                        @if($assinatura->estaAtiva())
                                            <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap bg-blue-100 text-blue-800">
                                                @if($assinatura->diasRestantes() <= 7)
                                                    <i data-lucide="alert-circle" class="w-3 h-3 inline"></i>
                                                @endif
                                                {{ $assinatura->diasRestantes() }}d restantes
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap bg-red-100 text-red-800">
                                                Vencida
                                            </span>
                                        @endif
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap bg-gray-100 text-gray-600">
                                            Sem assinatura
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Detalhes -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm mb-3">
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="mail" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $fornecedor->email }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="phone" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $fornecedor->whatsappPrincipal?->formatado() ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="map-pin" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $fornecedor->enderecoPrincipal?->cidade ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <!-- Segmentos -->
                            @if($fornecedor->segmentos->count() > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($fornecedor->segmentos as $segmento)
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
                                <a href="{{ auth()->user()->role === 'admin' ? route('admin.fornecedores.show', $fornecedor->id) : route('fornecedores.show', $fornecedor->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i data-lucide="eye" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ver</span>
                                </a>
                                
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.fornecedores.edit', $fornecedor->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                        <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                                        <span class="whitespace-nowrap">Editar</span>
                                    </a>
                                    @if($fornecedor->status === 'inativo')
                                        <form action="{{ route('admin.fornecedores.ativar', $fornecedor->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                                <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                                                <span class="whitespace-nowrap">Ativar</span>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.fornecedores.inativar', $fornecedor->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors text-sm font-medium">
                                                <i data-lucide="pause-circle" class="w-4 h-4 flex-shrink-0"></i>
                                                <span class="whitespace-nowrap">Inativar</span>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                    <p class="text-yellow-800 font-medium">Nenhum fornecedor encontrado</p>
                    <p class="text-yellow-700 text-sm mt-1">Ajuste os filtros ou limpe para ver todos</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
