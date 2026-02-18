@extends('layouts.dashboard')

@section('titulo', 'Compras Coletivas')
@section('page-title', 'Compras Coletivas')
@section('page-subtitle', 'Compre em grupo e economize! Quanto mais gente, melhor o preço.')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Ações Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <a href="{{ route('compras-coletivas.propostas.index') }}" 
               class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 rounded-lg flex-shrink-0">
                        <i data-lucide="lightbulb" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-[var(--cor-texto)]">Propor Produto</h3>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Sugira um produto para compra coletiva</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('compras-coletivas.propostas.index') }}" 
               class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-green-100 rounded-lg flex-shrink-0">
                        <i data-lucide="vote" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-[var(--cor-texto)]">Votar em Propostas</h3>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Escolha os produtos que você quer</p>
                    </div>
                </div>
            </a>

            @if($usuario->role === 'fornecedor')
                <a href="{{ route('compras-coletivas.fornecedor.ofertas') }}" 
                   class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-purple-100 rounded-lg flex-shrink-0">
                            <i data-lucide="tag" class="w-6 h-6 text-purple-600"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-[var(--cor-texto)]">Minhas Ofertas</h3>
                            <p class="text-xs text-[var(--cor-texto-muted)]">Gerencie suas ofertas enviadas</p>
                        </div>
                    </div>
                </a>
            @endif
        </div>

        <!-- Contador -->
        <div class="mb-4">
            <p class="text-sm text-[var(--cor-texto-muted)]">
                <strong>{{ $compras->count() }}</strong> compra(s) coletiva(s) ativa(s)
            </p>
        </div>

        <!-- Lista de Compras Coletivas Ativas -->
        @if($compras->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                @foreach($compras as $compra)
                    @php
                        $progresso = $compra->quantidade_minima > 0 
                            ? min(100, ($compra->quantidade_atual / $compra->quantidade_minima) * 100) 
                            : 0;
                    @endphp
                    <a href="{{ route('compras-coletivas.show', $compra->id) }}" 
                       class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden hover:shadow-md transition-shadow group flex flex-col">
                        
                        <!-- Imagem -->
                        <div class="relative h-48 bg-gray-100 overflow-hidden">
                            @if($compra->produto->imagem_url)
                                <img src="{{ asset('storage/' . $compra->produto->imagem_url) }}" 
                                     alt="{{ $compra->titulo }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <i data-lucide="shopping-cart" class="w-16 h-16 text-gray-400"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Conteúdo -->
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-bold text-[var(--cor-texto)] text-lg mb-1 line-clamp-2">{{ $compra->titulo }}</h3>
                            <p class="text-sm text-[var(--cor-texto-muted)] mb-4">{{ $compra->produto->nome }}</p>
                            
                            <!-- Progresso -->
                            <div class="mt-auto">
                                <div class="flex items-center justify-between mb-2 text-sm">
                                    <span class="text-[var(--cor-texto-muted)]">{{ number_format($compra->quantidade_atual, 0) }} de {{ number_format($compra->quantidade_minima, 0) }}</span>
                                    <span class="font-bold text-[var(--cor-verde-serra)]">{{ number_format($progresso, 0) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden mb-3">
                                    <div class="bg-[var(--cor-verde-serra)] rounded-full h-2 transition-all duration-500" style="width: {{ $progresso }}%"></div>
                                </div>
                                
                                <div class="flex items-center justify-between text-xs text-[var(--cor-texto-muted)]">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="users" class="w-3 h-3"></i>
                                        {{ $compra->participantes_count }} {{ $compra->participantes_count == 1 ? 'pessoa' : 'pessoas' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        até {{ $compra->data_fim->format('d/m') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <i data-lucide="shopping-cart" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                <p class="text-yellow-800 font-medium">Nenhuma compra coletiva ativa no momento</p>
                <p class="text-yellow-700 text-sm mt-2">Que tal propor um produto? Quanto mais votos, maiores as chances de aprovação!</p>
                <a href="{{ route('compras-coletivas.propostas.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium mt-4">
                    <i data-lucide="lightbulb" class="w-5 h-5"></i>
                    Propor Produto
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
