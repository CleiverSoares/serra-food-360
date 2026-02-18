@extends('layouts.dashboard')

@section('titulo', 'Propostas')
@section('page-title', 'Propostas de Compras Coletivas')
@section('page-subtitle', 'Analise e gerencie propostas enviadas pelos compradores')

@section('header-actions')
<a href="{{ route('admin.compras-coletivas.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('mobile-header-actions')
<a href="{{ route('admin.compras-coletivas.index') }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Voltar">
    <i data-lucide="arrow-left" class="w-5 h-5 text-[var(--cor-texto)]"></i>
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

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 mb-6">
            <form method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    
                    <!-- Status -->
                    <div class="md:col-span-2">
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] text-sm">
                            <option value="">Todos os Status</option>
                            <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="em_votacao" {{ request('status') === 'em_votacao' ? 'selected' : '' }}>Em Votação</option>
                            <option value="aprovada" {{ request('status') === 'aprovada' ? 'selected' : '' }}>Aprovada</option>
                            <option value="rejeitada" {{ request('status') === 'rejeitada' ? 'selected' : '' }}>Rejeitada</option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Filtrar</span>
                        </button>
                        <a href="{{ route('admin.compras-coletivas.propostas.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
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
                <strong>{{ $propostas->total() }}</strong> proposta(s) encontrada(s)
            </p>
        </div>

        <!-- Lista de Propostas -->
        <div class="grid grid-cols-1 gap-4">
            @forelse($propostas as $proposta)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-3 md:gap-4">
                        
                        <!-- Thumbnail do Produto -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden bg-gray-100">
                                @if($proposta->produto->imagem_url)
                                    <img src="{{ asset('storage/' . $proposta->produto->imagem_url) }}" alt="{{ $proposta->produto->nome }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informações -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base md:text-lg font-bold text-[var(--cor-texto)] truncate">
                                        {{ $proposta->produto->nome }}
                                    </h3>
                                    @if($proposta->produto->descricao)
                                        <p class="text-sm text-[var(--cor-texto-muted)] line-clamp-2">{{ $proposta->produto->descricao }}</p>
                                    @endif
                                </div>

                                <!-- Status Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap flex-shrink-0
                                    {{ $proposta->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $proposta->status === 'em_votacao' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $proposta->status === 'aprovada' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $proposta->status === 'rejeitada' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $proposta->status)) }}
                                </span>
                            </div>

                            <!-- Estatísticas -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm mb-3">
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="ruler" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $proposta->produto->unidade_medida }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="user" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $proposta->propositor->nome }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="heart" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $proposta->votos_count }} votos</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="calendar" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $proposta->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <!-- Justificativa -->
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3">
                                <p class="text-xs text-gray-600 font-medium mb-1">Justificativa:</p>
                                <p class="text-sm text-[var(--cor-texto)]">{{ $proposta->justificativa }}</p>
                            </div>

                            @if($proposta->status === 'em_votacao')
                                <p class="text-xs text-blue-600 mb-3 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    Votação encerra em {{ $proposta->data_votacao_fim->format('d/m/Y') }}
                                </p>
                            @endif

                            <!-- Ações -->
                            <div class="flex flex-wrap gap-2">
                                @if($proposta->status === 'pendente')
                                    <form action="{{ route('admin.compras-coletivas.propostas.aprovar', $proposta->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                            <i data-lucide="thumbs-up" class="w-4 h-4 flex-shrink-0"></i>
                                            <span class="whitespace-nowrap">Colocar em Votação</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.compras-coletivas.propostas.rejeitar', $proposta->id) }}" method="POST" onsubmit="return confirm('Confirma rejeição?')" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                            <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                                            <span class="whitespace-nowrap">Rejeitar</span>
                                        </button>
                                    </form>
                                @endif

                                @if($proposta->status === 'aprovada')
                                    <a href="{{ route('admin.compras-coletivas.create', ['proposta_id' => $proposta->id]) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                        <i data-lucide="plus" class="w-4 h-4 flex-shrink-0"></i>
                                        <span class="whitespace-nowrap">Criar Compra Coletiva</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                    <p class="text-yellow-800 font-medium">Nenhuma proposta encontrada</p>
                    <p class="text-yellow-700 text-sm mt-1">Aguarde compradores enviarem propostas de produtos</p>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($propostas->hasPages())
            <div class="mt-6">
                {{ $propostas->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
