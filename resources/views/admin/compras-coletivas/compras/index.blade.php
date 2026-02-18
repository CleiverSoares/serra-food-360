@extends('layouts.dashboard')

@section('titulo', 'Compras Coletivas')
@section('page-title', 'Compras Coletivas')
@section('page-subtitle', 'Gerenciar compras coletivas ativas')

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

        <!-- Botões de Ação -->
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('admin.compras-coletivas.produtos.index') }}" 
               class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                <i data-lucide="package" class="w-4 h-4 flex-shrink-0"></i>
                <span class="whitespace-nowrap">Catálogo de Produtos</span>
            </a>
            <a href="{{ route('admin.compras-coletivas.propostas.index') }}" 
               class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                <i data-lucide="lightbulb" class="w-4 h-4 flex-shrink-0"></i>
                <span class="whitespace-nowrap">Propostas</span>
            </a>
            <a href="{{ route('admin.compras-coletivas.create') }}" 
               class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium text-sm md:text-base shadow-sm hover:shadow-md">
                <i data-lucide="plus" class="w-4 h-4 flex-shrink-0"></i>
                <span class="whitespace-nowrap">Nova Compra Coletiva</span>
            </a>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 mb-6">
            <form method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                    
                    <!-- Busca -->
                    <div class="md:col-span-2">
                        <input type="text" name="busca" value="{{ request('busca') }}" 
                               placeholder="Buscar por título ou produto..."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent text-sm">
                    </div>

                    <!-- Status -->
                    <div>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] text-sm">
                            <option value="">Status</option>
                            <option value="ativa" {{ request('status') === 'ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="encerrada" {{ request('status') === 'encerrada' ? 'selected' : '' }}>Encerrada</option>
                            <option value="cancelada" {{ request('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <!-- Ordenar -->
                    <div>
                        <select name="ordem" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] text-sm">
                            <option value="recente" {{ request('ordem') === 'recente' ? 'selected' : '' }}>Mais Recente</option>
                            <option value="antiga" {{ request('ordem') === 'antiga' ? 'selected' : '' }}>Mais Antiga</option>
                            <option value="participantes" {{ request('ordem') === 'participantes' ? 'selected' : '' }}>Mais Participantes</option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Filtrar</span>
                        </button>
                        <a href="{{ route('admin.compras-coletivas.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span>Limpar</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Contador -->
        <div class="mb-4">
            <p class="text-sm text-[var(--cor-texto-muted)]">
                <strong>{{ $compras->count() }}</strong> compra(s) encontrada(s)
            </p>
        </div>

        <!-- Lista de Compras -->
        <div class="grid grid-cols-1 gap-4">
            @forelse($compras as $compra)
                @php
                    $progresso = $compra->quantidade_minima > 0 
                        ? min(100, ($compra->quantidade_atual / $compra->quantidade_minima) * 100) 
                        : 0;
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 hover:shadow-md transition-shadow overflow-hidden">
                    <div class="flex items-start gap-3 md:gap-4 min-w-0">
                        
                        <!-- Thumbnail -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden bg-gray-100">
                                @if($compra->produto->imagem_url)
                                    <img src="{{ asset('storage/' . $compra->produto->imagem_url) }}" alt="{{ $compra->titulo }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="shopping-cart" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informações -->
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-start justify-between gap-2 md:gap-4 mb-2">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base md:text-lg font-bold text-[var(--cor-texto)] truncate">
                                        {{ $compra->titulo }}
                                    </h3>
                                    <p class="text-sm text-[var(--cor-texto-muted)] truncate">
                                        {{ $compra->produto->nome }}
                                    </p>
                                </div>

                                <!-- Status Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap flex-shrink-0
                                    {{ $compra->status === 'ativa' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $compra->status === 'encerrada' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $compra->status === 'cancelada' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($compra->status) }}
                                </span>
                            </div>

                            <!-- Stats em Grid Mobile-First -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm mb-3">
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="target" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">Meta: {{ number_format($compra->quantidade_minima, 0) }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="trending-up" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">Atual: {{ number_format($compra->quantidade_atual, 0) }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="users" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $compra->participantes_count }} pessoas</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="tag" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $compra->ofertas_count }} ofertas</span>
                                </div>
                            </div>

                            <!-- Progresso -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs text-[var(--cor-texto-muted)]">Progresso</span>
                                    <span class="text-xs font-bold text-[var(--cor-verde-serra)]">{{ number_format($progresso, 0) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-[var(--cor-verde-serra)] rounded-full h-2 transition-all" style="width: {{ $progresso }}%"></div>
                                </div>
                            </div>

                            <p class="text-xs text-[var(--cor-texto-muted)] mb-3 flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                Encerra em {{ $compra->data_fim->format('d/m/Y') }}
                            </p>

                            <!-- Ações -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('admin.compras-coletivas.edit', $compra->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                    <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Editar</span>
                                </a>
                                <a href="{{ route('compras-coletivas.show', $compra->id) }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i data-lucide="external-link" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ver Pública</span>
                                </a>
                                <form action="{{ route('admin.compras-coletivas.destroy', $compra->id) }}" method="POST" onsubmit="return confirm('Confirma exclusão?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                        <i data-lucide="trash-2" class="w-4 h-4 flex-shrink-0"></i>
                                        <span class="whitespace-nowrap">Excluir</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                    <p class="text-yellow-800 font-medium">Nenhuma compra coletiva encontrada</p>
                    <p class="text-yellow-700 text-sm mt-1">Crie uma nova compra coletiva para começar</p>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($compras->hasPages())
            <div class="mt-6">
                {{ $compras->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
