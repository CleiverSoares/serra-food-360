@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Propostas de Compras Coletivas</h1>
        <p class="text-sm text-gray-600 mt-1">Analise e gerencie propostas enviadas pelos compradores</p>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.compras-coletivas.propostas.index') }}" class="flex gap-4">
            <select name="status" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="">Todos os Status</option>
                <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="em_votacao" {{ request('status') === 'em_votacao' ? 'selected' : '' }}>Em Votação</option>
                <option value="aprovada" {{ request('status') === 'aprovada' ? 'selected' : '' }}>Aprovada</option>
                <option value="rejeitada" {{ request('status') === 'rejeitada' ? 'selected' : '' }}>Rejeitada</option>
            </select>
            <button type="submit" class="btn-primary">
                <i data-lucide="search" class="w-4 h-4 inline"></i>
                Filtrar
            </button>
            <a href="{{ route('admin.compras-coletivas.propostas.index') }}" class="btn-secondary px-3">
                <i data-lucide="x" class="w-4 h-4"></i>
            </a>
        </form>
    </div>

    <!-- Lista -->
    @if($propostas->count() > 0)
        <div class="space-y-4">
            @foreach($propostas as $proposta)
                <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between gap-4">
                        <!-- Conteúdo -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $proposta->produto->nome }}</h3>
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    {{ $proposta->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $proposta->status === 'em_votacao' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $proposta->status === 'aprovada' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $proposta->status === 'rejeitada' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $proposta->status)) }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3">{{ $proposta->produto->descricao }}</p>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-3">
                                <div>
                                    <span class="text-gray-600">Unidade:</span>
                                    <span class="font-medium ml-1">{{ $proposta->produto->unidade_medida }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Proposto por:</span>
                                    <span class="font-medium ml-1">{{ $proposta->propositor->nome }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Votos:</span>
                                    <span class="font-medium ml-1">{{ $proposta->votos_count }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Data:</span>
                                    <span class="font-medium ml-1">{{ $proposta->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                <p class="text-sm text-gray-700"><strong>Justificativa:</strong> {{ $proposta->justificativa }}</p>
                            </div>

                            @if($proposta->status === 'em_votacao')
                                <p class="text-xs text-blue-600">
                                    <i data-lucide="clock" class="w-3 h-3 inline"></i>
                                    Votação encerra em {{ $proposta->data_votacao_fim->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>

                        <!-- Ações -->
                        <div class="flex flex-col gap-2">
                            @if($proposta->status === 'pendente')
                                <form action="{{ route('admin.compras-coletivas.propostas.iniciar-votacao', $proposta->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-primary text-sm whitespace-nowrap">
                                        <i data-lucide="vote" class="w-3 h-3 inline"></i>
                                        Iniciar Votação
                                    </button>
                                </form>

                                <form action="{{ route('admin.compras-coletivas.propostas.aprovar', $proposta->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-success text-sm whitespace-nowrap">
                                        <i data-lucide="check" class="w-3 h-3 inline"></i>
                                        Aprovar Direto
                                    </button>
                                </form>

                                <form action="{{ route('admin.compras-coletivas.propostas.rejeitar', $proposta->id) }}" method="POST" onsubmit="return confirm('Confirma rejeição?')">
                                    @csrf
                                    <button type="submit" class="btn-danger text-sm whitespace-nowrap">
                                        <i data-lucide="x" class="w-3 h-3 inline"></i>
                                        Rejeitar
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('admin.compras-coletivas.propostas.show', $proposta->id) }}" class="btn-secondary text-sm text-center">
                                <i data-lucide="eye" class="w-3 h-3 inline"></i>
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $propostas->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
            <p class="text-gray-600">Nenhuma proposta encontrada</p>
        </div>
    @endif
</div>
@endsection
