@extends('layouts.dashboard')

@section('titulo', $compra->titulo)
@section('page-title', 'Compras Coletivas')
@section('page-subtitle', $compra->titulo)

@section('header-actions')
<a href="{{ route('compras-coletivas.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('mobile-header-actions')
<a href="{{ route('compras-coletivas.index') }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Voltar">
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

        @if(session('erro'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800">{{ session('erro') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden">
            
            <!-- Imagem do Produto -->
            @php
                $progresso = $compra->quantidade_minima > 0 
                    ? min(100, ($compra->quantidade_atual / $compra->quantidade_minima) * 100) 
                    : 0;
            @endphp
            <div class="flex items-center justify-center bg-gray-50 p-6" style="min-height: 280px; max-height: 400px;">
                @if($compra->produto->imagem_url)
                    <img src="{{ asset('storage/' . $compra->produto->imagem_url) }}" 
                         alt="{{ $compra->titulo }}" 
                         class="max-h-80 max-w-full w-auto h-auto object-contain mx-auto">
                @else
                    <div class="flex items-center justify-center">
                        <i data-lucide="shopping-cart" class="w-24 h-24 text-gray-300"></i>
                    </div>
                @endif
            </div>
            
            <!-- Header Info -->
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-[var(--cor-texto)] mb-2">{{ $compra->titulo }}</h1>
                <p class="text-[var(--cor-texto-muted)]">{{ $compra->produto->nome }}</p>
            </div>

            <!-- Informações -->
            <div class="p-6">
                @if($compra->descricao)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-[var(--cor-texto)]">{{ $compra->descricao }}</p>
                    </div>
                @endif

                <!-- Progresso -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-[var(--cor-texto-muted)]">{{ number_format($compra->quantidade_atual, 0) }} de {{ number_format($compra->quantidade_minima, 0) }} {{ $compra->produto->unidade_medida }}</span>
                            <span class="text-[var(--cor-texto-muted)]">•</span>
                            <span class="text-[var(--cor-texto-muted)]">{{ $compra->participantes_count }} participantes</span>
                        </div>
                        <span class="text-sm font-bold text-[var(--cor-verde-serra)]">{{ number_format($progresso, 0) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                        <div class="bg-[var(--cor-verde-serra)] rounded-full h-3 transition-all" style="width: {{ $progresso }}%"></div>
                    </div>
                    <p class="text-xs text-[var(--cor-texto-muted)] flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3 h-3"></i>
                        Encerra em {{ $compra->data_fim->format('d/m/Y') }}
                    </p>
                </div>

                <!-- Formulário de Participação (apenas compradores) -->
                @if($usuario->role === 'comprador')
                    @if($minhaAdesao)
                        <!-- Já está participando -->
                        <div class="border border-green-300 bg-green-50 rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                                Você está participando
                            </h3>
                            
                            <form action="{{ route('compras-coletivas.adesao.atualizar', $minhaAdesao->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                
                                <div>
                                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Quantidade ({{ $compra->produto->unidade_medida }}) *</label>
                                    <input type="number" name="quantidade" step="0.01" min="0.01" value="{{ $minhaAdesao->quantidade }}" required 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Observações</label>
                                    <textarea name="observacoes" rows="2" 
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">{{ $minhaAdesao->observacoes }}</textarea>
                                </div>
                                
                                <div class="flex gap-3">
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex-1">
                                        <i data-lucide="save" class="w-4 h-4"></i>
                                        Atualizar
                                    </button>
                                </div>
                            </form>
                            
                            <form action="{{ route('compras-coletivas.adesao.cancelar', $minhaAdesao->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Confirma cancelamento?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-white text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors font-medium text-sm">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                    Cancelar Participação
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Formulário para Participar -->
                        <div class="border border-[var(--cor-borda)] rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-[var(--cor-texto)] mb-4">Participar desta Compra</h3>
                            
                            <form action="{{ route('compras-coletivas.participar', $compra->id) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Quantidade ({{ $compra->produto->unidade_medida }}) *</label>
                                    <input type="number" name="quantidade" step="0.01" min="0.01" required 
                                           placeholder="Ex: 10"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Observações</label>
                                    <textarea name="observacoes" rows="2" 
                                              placeholder="Observações opcionais"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]"></textarea>
                                </div>
                                
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                    Participar Agora
                                </button>
                            </form>
                        </div>
                    @endif
                @endif

                <!-- Participantes -->
                @if($compra->adesoes->count() > 0)
                    <div>
                        <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                            <i data-lucide="users" class="w-5 h-5"></i>
                            Quem está participando ({{ $compra->participantes_count }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($compra->adesoes as $adesao)
                                <div class="border border-[var(--cor-borda)] rounded-lg p-3 flex items-center justify-between">
                                    <span class="text-sm text-[var(--cor-texto)]">{{ $adesao->comprador->nome }}</span>
                                    <span class="text-sm font-bold text-[var(--cor-verde-serra)]">{{ number_format($adesao->quantidade, 0) }} {{ $compra->produto->unidade_medida }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Ofertas dos Fornecedores -->
                @if($compra->ofertas->count() > 0)
                    <div class="mb-6">
                        <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                            <i data-lucide="tag" class="w-5 h-5"></i>
                            Ofertas ({{ $compra->ofertas->count() }})
                        </h3>
                        <div class="space-y-3">
                            @foreach($compra->ofertas as $oferta)
                                <div class="border border-[var(--cor-borda)] rounded-lg p-4 flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-[var(--cor-texto)]">{{ $oferta->fornecedor->nome }}</p>
                                        @if($oferta->condicoes)
                                            <p class="text-sm text-[var(--cor-texto-muted)] mt-1">{{ $oferta->condicoes }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xl font-bold text-[var(--cor-verde-serra)]">R$ {{ number_format($oferta->preco_unitario, 2, ',', '.') }}</p>
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
                            <i data-lucide="users" class="w-5 h-5"></i>
                            Participantes ({{ $compra->participantes_count }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($compra->adesoes as $adesao)
                                <div class="border border-[var(--cor-borda)] rounded-lg p-3 flex items-center justify-between">
                                    <span class="text-sm font-medium text-[var(--cor-texto)]">{{ $adesao->comprador->nome }}</span>
                                    <span class="text-sm font-bold text-[var(--cor-verde-serra)]">{{ number_format($adesao->quantidade, 0) }} {{ $compra->produto->unidade_medida }}</span>
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
