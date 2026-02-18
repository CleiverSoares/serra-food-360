@extends('layouts.dashboard')

@section('titulo', $compra->titulo)
@section('page-title', 'Compras Coletivas')
@section('page-subtitle', $compra->titulo)

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

        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('compras-coletivas.index') }}" 
               class="inline-flex items-center gap-2 text-[var(--cor-verde-serra)] hover:underline text-sm font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Voltar para Compras Coletivas
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden">
            
            <!-- Header com Imagem e Info -->
            <div class="relative">
                <!-- Imagem do Produto -->
                <div class="h-48 md:h-64 lg:h-80 bg-[var(--cor-verde-serra)] relative overflow-hidden">
                    @if($compra->produto->imagem_url)
                        <img src="{{ asset('storage/' . $compra->produto->imagem_url) }}" 
                             alt="{{ $compra->titulo }}" 
                             class="w-full h-full object-cover opacity-90">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-600 to-green-700">
                            <i data-lucide="shopping-cart" class="w-20 h-20 md:w-24 md:h-24 text-white opacity-50"></i>
                        </div>
                    @endif
                    
                    <!-- Overlay escuro -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    
                    <!-- Título sobre a imagem -->
                    <div class="absolute bottom-0 left-0 right-0 p-4 lg:p-6 text-white">
                        <h1 class="text-2xl lg:text-3xl font-bold mb-1">{{ $compra->titulo }}</h1>
                        <p class="text-green-100 text-sm lg:text-base">{{ $compra->produto->nome }}</p>
                    </div>
                    
                    <!-- Badge de Progresso -->
                    @php
                        $progresso = $compra->quantidade_minima > 0 
                            ? min(100, ($compra->quantidade_atual / $compra->quantidade_minima) * 100) 
                            : 0;
                    @endphp
                    <div class="absolute top-4 right-4 px-4 py-2 bg-white rounded-full shadow-lg">
                        <span class="text-lg lg:text-xl font-bold text-[var(--cor-verde-serra)]">{{ number_format($progresso, 0) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Informações -->
            <div class="p-4 lg:p-6">
                @if($compra->descricao)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-[var(--cor-texto)]">{{ $compra->descricao }}</p>
                    </div>
                @endif

                <!-- Estatísticas em Grid Mobile-First -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4 mb-6">
                    <div class="bg-blue-50 rounded-lg p-3 lg:p-4 text-center">
                        <p class="text-xl lg:text-2xl font-bold text-blue-600">{{ number_format($compra->quantidade_minima, 0) }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Meta ({{ $compra->produto->unidade_medida }})</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 lg:p-4 text-center">
                        <p class="text-xl lg:text-2xl font-bold text-green-600">{{ number_format($compra->quantidade_atual, 0) }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Atual</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-3 lg:p-4 text-center">
                        <p class="text-xl lg:text-2xl font-bold text-purple-600">{{ $compra->participantes_count }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Participantes</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-3 lg:p-4 text-center">
                        <p class="text-sm font-bold text-orange-600">{{ $compra->data_fim->format('d/m/Y') }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Encerra em</p>
                    </div>
                </div>

                <!-- Barra de Progresso -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-[var(--cor-texto)]">Progresso da Meta</span>
                        <span class="text-sm font-bold text-[var(--cor-verde-serra)]">{{ number_format($progresso, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 lg:h-4">
                        <div class="bg-[var(--cor-verde-serra)] rounded-full h-3 lg:h-4 transition-all" style="width: {{ $progresso }}%"></div>
                    </div>
                </div>

                <!-- Formulário de Participação (apenas compradores) -->
                @if($usuario->role === 'comprador')
                    @if($minhaAdesao)
                        <!-- Já está participando -->
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 lg:p-6 mb-6">
                            <h3 class="font-bold text-green-900 mb-4 flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                Você está participando!
                            </h3>
                            
                            <form action="{{ route('compras-coletivas.adesao.atualizar', $minhaAdesao->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Quantidade ({{ $compra->produto->unidade_medida }}) *</label>
                                    <input type="number" name="quantidade" step="0.01" min="0.01" value="{{ $minhaAdesao->quantidade }}" required 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Observações</label>
                                    <textarea name="observacoes" rows="2" 
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ $minhaAdesao->observacoes }}</textarea>
                                </div>
                                
                                <div class="flex flex-col md:flex-row gap-3">
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium flex-1">
                                        <i data-lucide="save" class="w-4 h-4"></i>
                                        Atualizar Participação
                                    </button>
                                </div>
                            </form>
                            
                            <form action="{{ route('compras-coletivas.adesao.cancelar', $minhaAdesao->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Confirma cancelamento da participação?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-all font-medium text-sm">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                    Cancelar Minha Participação
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Formulário para Participar -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 lg:p-6 mb-6">
                            <h3 class="font-bold text-blue-900 mb-4">Participar desta Compra</h3>
                            
                            <form action="{{ route('compras-coletivas.participar', $compra->id) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Quantidade que deseja ({{ $compra->produto->unidade_medida }}) *</label>
                                    <input type="number" name="quantidade" step="0.01" min="0.01" required 
                                           placeholder="Ex: 10"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Observações</label>
                                    <textarea name="observacoes" rows="2" 
                                              placeholder="Prazo de entrega, preferências, etc."
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"></textarea>
                                </div>
                                
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium">
                                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                    Participar Agora
                                </button>
                            </form>
                        </div>
                    @endif
                @endif

                <!-- Ofertas dos Fornecedores -->
                @if($compra->ofertas->count() > 0)
                    <div class="mb-6">
                        <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                            <i data-lucide="tag" class="w-5 h-5 text-purple-600"></i>
                            Ofertas Recebidas ({{ $compra->ofertas->count() }})
                        </h3>
                        <div class="space-y-3">
                            @foreach($compra->ofertas as $oferta)
                                <div class="bg-gray-50 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="flex-1">
                                        <p class="font-bold text-[var(--cor-texto)]">{{ $oferta->fornecedor->nome }}</p>
                                        <p class="text-sm text-[var(--cor-texto-muted)]">{{ $oferta->condicoes }}</p>
                                    </div>
                                    <div class="text-left md:text-right">
                                        <p class="text-2xl font-bold text-[var(--cor-verde-serra)]">R$ {{ number_format($oferta->preco_unitario, 2, ',', '.') }}</p>
                                        <p class="text-xs text-[var(--cor-texto-muted)]">por {{ $compra->produto->unidade_medida }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Participantes -->
                @if($compra->adesoes->count() > 0)
                    <div>
                        <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                            <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                            Participantes ({{ $compra->participantes_count }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($compra->adesoes as $adesao)
                                <div class="bg-gray-50 rounded-lg p-3 flex items-center justify-between">
                                    <span class="text-sm font-medium text-[var(--cor-texto)]">{{ $adesao->comprador->nome }}</span>
                                    <span class="text-sm font-bold text-[var(--cor-verde-serra)]">{{ number_format($adesao->quantidade, 2) }} {{ $compra->produto->unidade_medida }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
