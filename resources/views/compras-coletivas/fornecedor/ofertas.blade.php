@extends('layouts.dashboard')

@section('titulo', 'Minhas Ofertas')
@section('page-title', 'Minhas Ofertas')
@section('page-subtitle', 'Gerenciar ofertas enviadas para compras coletivas')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Botão Voltar -->
        <div class="mb-6">
            <a href="{{ route('compras-coletivas.index') }}" 
               class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                <i data-lucide="arrow-left" class="w-4 h-4 flex-shrink-0"></i>
                <span class="whitespace-nowrap">Compras Coletivas</span>
            </a>
        </div>

        <!-- Contador -->
        <div class="mb-4">
            <p class="text-sm text-[var(--cor-texto-muted)]">
                <strong>{{ $ofertas->count() }}</strong> oferta(s) enviada(s)
            </p>
        </div>

        <!-- Lista de Ofertas -->
        <div class="grid grid-cols-1 gap-4">
            @forelse($ofertas as $oferta)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-3 md:gap-4">
                        
                        <!-- Thumbnail -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden bg-gray-100">
                                @if($oferta->compra->produto->imagem_url)
                                    <img src="{{ asset('storage/' . $oferta->compra->produto->imagem_url) }}" alt="{{ $oferta->compra->titulo }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="shopping-cart" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informações -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base md:text-lg font-bold text-[var(--cor-texto)] truncate">
                                        {{ $oferta->compra->titulo }}
                                    </h3>
                                    <p class="text-sm text-[var(--cor-texto-muted)] truncate">
                                        {{ $oferta->compra->produto->nome }}
                                    </p>
                                </div>

                                <!-- Status Badge -->
                                <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap flex-shrink-0
                                    {{ $oferta->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $oferta->status === 'aceita' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $oferta->status === 'recusada' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($oferta->status) }}
                                </span>
                            </div>

                            <!-- Sua Oferta -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                    <div>
                                        <p class="text-blue-700 font-medium mb-1">Preço Unitário</p>
                                        <p class="text-base font-bold text-blue-900">R$ {{ number_format($oferta->preco_unitario, 2, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-blue-700 font-medium mb-1">Quantidade Ofertada</p>
                                        <p class="text-base font-bold text-blue-900">{{ number_format($oferta->quantidade_disponivel, 0) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-blue-700 font-medium mb-1">Prazo Entrega</p>
                                        <p class="text-base font-bold text-blue-900">{{ $oferta->prazo_entrega }} dias</p>
                                    </div>
                                    <div>
                                        <p class="text-blue-700 font-medium mb-1">Valor Total</p>
                                        <p class="text-base font-bold text-blue-900">R$ {{ number_format($oferta->preco_unitario * $oferta->quantidade_disponivel, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                
                                @if($oferta->observacoes)
                                    <div class="mt-3 pt-3 border-t border-blue-300">
                                        <p class="text-blue-700 font-medium text-xs mb-1">Observações:</p>
                                        <p class="text-sm text-blue-900">{{ $oferta->observacoes }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Dados da Compra Coletiva -->
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm mb-3">
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="target" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">Meta: {{ number_format($oferta->compra->quantidade_minima, 0) }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="users" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $oferta->compra->participantes_count }} participantes</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="calendar" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">Até {{ $oferta->compra->data_fim->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <p class="text-xs text-[var(--cor-texto-muted)] flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                Enviada em {{ $oferta->created_at->format('d/m/Y \à\s H:i') }}
                            </p>

                            <!-- Ações -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('compras-coletivas.show', $oferta->compra_coletiva_id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i data-lucide="eye" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ver Detalhes</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                    <p class="text-yellow-800 font-medium">Nenhuma oferta enviada ainda</p>
                    <p class="text-yellow-700 text-sm mt-1">Acesse as compras coletivas ativas e envie suas ofertas</p>
                    <a href="{{ route('compras-coletivas.index') }}" 
                       class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                        <i data-lucide="shopping-basket" class="w-4 h-4"></i>
                        Ver Compras Coletivas
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($ofertas->hasPages())
            <div class="mt-6">
                {{ $ofertas->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
