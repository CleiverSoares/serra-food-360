@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Compras Coletivas</h1>
        <p class="text-sm text-gray-600 mt-1">Compre em grupo e economize! Quanto mais gente, melhor o preço.</p>
    </div>

    <!-- Ações Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('compras-coletivas.propostas.index') }}" class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i data-lucide="lightbulb" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Propor Produto</h3>
                    <p class="text-xs text-gray-600">Sugira um produto para compra coletiva</p>
                </div>
            </div>
        </a>

        <a href="{{ route('compras-coletivas.propostas.index') }}" class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i data-lucide="vote" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Votar em Propostas</h3>
                    <p class="text-xs text-gray-600">Escolha os produtos que você quer</p>
                </div>
            </div>
        </a>

        @if($usuario->role === 'fornecedor')
            <a href="{{ route('compras-coletivas.fornecedor.ofertas') }}" class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <i data-lucide="tag" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Minhas Ofertas</h3>
                        <p class="text-xs text-gray-600">Gerencie suas ofertas enviadas</p>
                    </div>
                </div>
            </a>
        @endif
    </div>

    <!-- Lista de Compras Ativas -->
    <h2 class="text-lg font-bold text-gray-900 mb-4">Compras Ativas</h2>

    @if($compras->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($compras as $compra)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Imagem do Produto -->
                    <div class="aspect-video bg-gradient-to-br from-primary to-secondary relative">
                        @if($compra->produto->imagem_url)
                            <img src="{{ asset('storage/' . $compra->produto->imagem_url) }}" alt="{{ $compra->titulo }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="shopping-cart" class="w-12 h-12 text-white"></i>
                            </div>
                        @endif
                        
                        <!-- Badge de Progresso -->
                        @php
                            $progresso = $compra->quantidade_minima > 0 
                                ? min(100, ($compra->quantidade_atual / $compra->quantidade_minima) * 100) 
                                : 0;
                        @endphp
                        <div class="absolute top-2 right-2 px-3 py-1 bg-white rounded-full text-xs font-bold">
                            {{ number_format($progresso, 0) }}%
                        </div>
                    </div>

                    <!-- Conteúdo -->
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $compra->titulo }}</h3>
                        
                        <div class="space-y-2 text-sm mb-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Meta:</span>
                                <span class="font-bold text-gray-900">{{ number_format($compra->quantidade_minima, 2) }} {{ $compra->produto->unidade_medida }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Atual:</span>
                                <span class="font-bold text-primary">{{ number_format($compra->quantidade_atual, 2) }} {{ $compra->produto->unidade_medida }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Participantes:</span>
                                <span class="font-bold">{{ $compra->participantes_count }}</span>
                            </div>
                        </div>

                        <!-- Barra de Progresso -->
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                            <div class="bg-primary rounded-full h-2 transition-all" style="width: {{ $progresso }}%"></div>
                        </div>

                        <!-- Prazo -->
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-4">
                            <span>Encerra em:</span>
                            <span class="font-medium">{{ $compra->data_fim->format('d/m/Y') }}</span>
                        </div>

                        <a href="{{ route('compras-coletivas.show', $compra->id) }}" class="btn-primary w-full text-center">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
            <p class="text-gray-600 mb-2">Nenhuma compra coletiva ativa no momento</p>
            <p class="text-sm text-gray-500 mb-4">Seja o primeiro a propor um produto!</p>
            <a href="{{ route('compras-coletivas.propostas.create') }}" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Propor Produto
            </a>
        </div>
    @endif
</div>
@endsection
