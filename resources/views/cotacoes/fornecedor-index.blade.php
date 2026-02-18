@extends('layouts.dashboard')

@section('titulo', 'Cotações Disponíveis')
@section('page-title', 'Cotações')
@section('page-subtitle', 'Adicione suas ofertas e aumente suas vendas')

@section('header-actions')
<a href="{{ route('cotacoes.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('mobile-header-actions')
<a href="{{ route('cotacoes.index') }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Voltar">
    <i data-lucide="arrow-left" class="w-5 h-5 text-[var(--cor-texto)]"></i>
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <p>{{ session('sucesso') }}</p>
            </div>
        @endif

        @if($cotacoes->isEmpty())
            <!-- Estado vazio -->
            <div class="flex flex-col items-center justify-center py-16 px-4">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i data-lucide="file-text" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-[var(--cor-texto)] mb-2">Nenhuma cotação disponível</h3>
                <p class="text-[var(--cor-texto-secundario)] text-center max-w-md">
                    Quando houver cotações no seu segmento, você poderá adicionar suas ofertas aqui.
                </p>
            </div>
        @else
            <!-- Lista de Cotações para Fornecedor -->
            <div class="space-y-6" x-data="{ modalAberto: null }" 
                 x-init="$watch('modalAberto', value => { document.body.style.overflow = value ? 'hidden' : 'auto'; })">
                @foreach($cotacoes as $cotacao)
                    @php
                        $minhaOferta = $cotacao->ofertas->firstWhere('fornecedor_id', auth()->id());
                        $melhorOferta = $cotacao->melhorOferta();
                        $totalOfertas = $cotacao->ofertas->count();
                    @endphp

                    <!-- Card da Cotação -->
                    <div class="bg-white rounded-xl shadow-sm border-2 border-[var(--cor-borda)] overflow-hidden hover:shadow-lg transition-all">
                        
                        <!-- Header -->
                        <div class="bg-[var(--cor-verde-serra)] text-white p-4 lg:p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h2 class="text-xl lg:text-2xl font-bold mb-2">{{ $cotacao->produto }}</h2>
                                    <p class="text-sm lg:text-base text-green-50 mb-3">{{ $cotacao->titulo }}</p>
                                    
                                    <div class="flex flex-wrap gap-3 text-sm">
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="package" class="w-4 h-4"></i>
                                            {{ $cotacao->unidade }}
                                        </span>
                                        @if($cotacao->quantidade_minima)
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="box" class="w-4 h-4"></i>
                                                Mín: {{ number_format($cotacao->quantidade_minima, 0, ',', '.') }} {{ $cotacao->unidade }}
                                            </span>
                                        @endif
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-4 h-4"></i>
                                            Até {{ $cotacao->data_fim->format('d/m') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="users" class="w-4 h-4"></i>
                                            {{ $totalOfertas }} oferta(s)
                                        </span>
                                    </div>
                                </div>
                                
                                <span class="bg-green-800 px-3 py-1 rounded-lg text-xs lg:text-sm font-semibold whitespace-nowrap text-white">
                                    {{ $cotacao->segmento->nome }}
                                </span>
                            </div>
                            
                            @if($cotacao->descricao)
                                <p class="mt-4 text-sm text-green-50 border-t border-green-400 pt-4">
                                    {{ $cotacao->descricao }}
                                </p>
                            @endif
                        </div>

                        <!-- Status da Minha Oferta -->
                        <div class="p-6 bg-gray-50">
                            @if($minhaOferta)
                                <!-- Já Tenho Oferta -->
                                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-4">
                                    <div class="flex items-start justify-between gap-4 mb-4">
                                        <div>
                                            <p class="text-sm font-bold text-blue-900 mb-2">✓ Você já enviou uma oferta</p>
                                            <div class="text-3xl font-black text-blue-700">
                                                R$ {{ number_format($minhaOferta->preco_unitario, 2, ',', '.') }}
                                                <span class="text-base font-normal text-gray-600">/{{ $cotacao->unidade }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Posição no Ranking -->
                                        @php
                                            $ranking = $cotacao->ofertas->sortBy('preco_unitario')->values();
                                            $minhaPosicao = $ranking->search(fn($o) => $o->id === $minhaOferta->id) + 1;
                                        @endphp
                                        
                                        <div class="text-center">
                                            @if($minhaPosicao === 1)
                                                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-2">
                                                    <i data-lucide="trophy" class="w-8 h-8 text-yellow-600"></i>
                                                </div>
                                                <p class="text-xs font-bold text-yellow-700">MELHOR<br>OFERTA</p>
                                            @elseif($minhaPosicao === 2)
                                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                                    <span class="text-2xl font-black text-gray-600">2º</span>
                                                </div>
                                                <p class="text-xs font-semibold text-gray-600">2º lugar</p>
                                            @elseif($minhaPosicao === 3)
                                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-2">
                                                    <span class="text-2xl font-black text-orange-600">3º</span>
                                                </div>
                                                <p class="text-xs font-semibold text-orange-600">3º lugar</p>
                                            @else
                                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                                    <span class="text-2xl font-black text-gray-500">{{ $minhaPosicao }}º</span>
                                                </div>
                                                <p class="text-xs text-gray-500">{{ $minhaPosicao }}º lugar</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="grid lg:grid-cols-3 gap-3 text-sm mb-4">
                                        @if($minhaOferta->prazo_entrega)
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="truck" class="w-4 h-4 text-blue-600"></i>
                                                <span>{{ $minhaOferta->prazo_entrega }}</span>
                                            </div>
                                        @endif
                                        @if($minhaOferta->quantidade_disponivel)
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="box" class="w-4 h-4 text-purple-600"></i>
                                                <span>{{ number_format($minhaOferta->quantidade_disponivel, 0, ',', '.') }} {{ $cotacao->unidade }}</span>
                                            </div>
                                        @endif
                                        @if($minhaOferta->observacoes)
                                            <div class="lg:col-span-3 text-xs text-blue-700">
                                                {{ $minhaOferta->observacoes }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Comparação com Melhor Oferta -->
                                    @if($melhorOferta && $melhorOferta->id !== $minhaOferta->id)
                                        @php
                                            $diferenca = $minhaOferta->preco_unitario - $melhorOferta->preco_unitario;
                                            $percentual = ($diferenca / $melhorOferta->preco_unitario) * 100;
                                        @endphp
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                            <p class="text-xs text-yellow-800">
                                                💡 <strong>A melhor oferta está em R$ {{ number_format($melhorOferta->preco_unitario, 2, ',', '.') }}</strong>
                                                <br>Você está R$ {{ number_format($diferenca, 2, ',', '.') }} ({{ number_format($percentual, 1) }}%) mais caro.
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Botões -->
                                    <div class="flex gap-3 mt-4">
                                        <button @click="modalAberto = {{ $cotacao->id }}"
                                                class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold flex-1">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                            <span>Editar Oferta</span>
                                        </button>
                                        <form action="{{ route('cotacoes.deletar-oferta', $cotacao->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Tem certeza que deseja remover sua oferta?')"
                                                    class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-semibold">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                <span>Remover</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <!-- Ainda Não Tenho Oferta -->
                                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-4 mb-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="alert-circle" class="w-6 h-6 text-yellow-700"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-bold text-yellow-900 mb-1">Você ainda não enviou uma oferta</p>
                                            <p class="text-sm text-yellow-700 mb-3">
                                                Adicione seu preço e aumente suas chances de venda!
                                                @if($melhorOferta)
                                                    <br>Melhor oferta atual: <strong>R$ {{ number_format($melhorOferta->preco_unitario, 2, ',', '.') }}</strong>
                                                @endif
                                            </p>
                                            <button @click="modalAberto = {{ $cotacao->id }}"
                                                    class="flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-[var(--cor-verde-escuro)] transition-all font-semibold">
                                                <i data-lucide="plus" class="w-4 h-4"></i>
                                                <span>Adicionar Minha Oferta</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Ver Todas as Ofertas (opcional) -->
                            @if($totalOfertas > 0)
                                <details class="group">
                                    <summary class="cursor-pointer text-sm font-semibold text-[var(--cor-texto)] hover:text-[var(--cor-verde-serra)] transition-colors flex items-center gap-2">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        Ver todas as {{ $totalOfertas }} ofertas
                                        <i data-lucide="chevron-down" class="w-4 h-4 group-open:rotate-180 transition-transform"></i>
                                    </summary>
                                    <div class="mt-4 space-y-2">
                                        @foreach($cotacao->ofertasOrdenadas()->get() as $index => $oferta)
                                            <div class="bg-white border rounded-lg p-3 text-sm">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span class="font-bold">{{ $index + 1 }}º - R$ {{ number_format($oferta->preco_unitario, 2, ',', '.') }}</span>
                                                        @if($oferta->prazo_entrega)
                                                            <span class="text-gray-600 ml-2">• {{ $oferta->prazo_entrega }}</span>
                                                        @endif
                                                    </div>
                                                    @if($index === 0)
                                                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-bold">MELHOR</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            @endif
                        </div>

                        <!-- Modal de Adicionar/Editar Oferta -->
                        <div x-show="modalAberto === {{ $cotacao->id }}"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click.self="modalAberto = null"
                             class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
                            <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 @click.stop>
                                <form action="{{ route('cotacoes.salvar-oferta', $cotacao->id) }}" method="POST">
                                    @csrf
                                    
                                    <div class="p-6 border-b border-gray-200">
                                        <h3 class="text-2xl font-bold text-[var(--cor-texto)]">
                                            {{ $minhaOferta ? 'Editar' : 'Adicionar' }} Oferta
                                        </h3>
                                        <p class="text-sm text-[var(--cor-texto-secundario)] mt-1">{{ $cotacao->produto }}</p>
                                    </div>

                                    <div class="p-6 space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                                                Preço Unitário (R$) *
                                            </label>
                                            <input type="number" 
                                                   name="preco_unitario" 
                                                   step="0.01" 
                                                   min="0.01"
                                                   value="{{ old('preco_unitario', $minhaOferta->preco_unitario ?? '') }}"
                                                   required
                                                   class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg text-2xl font-bold"
                                                   placeholder="0,00">
                                            <p class="text-xs text-gray-500 mt-1">Preço por {{ $cotacao->unidade }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                                                Prazo de Entrega
                                            </label>
                                            <input type="text" 
                                                   name="prazo_entrega" 
                                                   value="{{ old('prazo_entrega', $minhaOferta->prazo_entrega ?? '') }}"
                                                   class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg"
                                                   placeholder="Ex: 24h, 2 dias úteis">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                                                Quantidade Disponível
                                            </label>
                                            <input type="number" 
                                                   name="quantidade_disponivel" 
                                                   step="0.01"
                                                   min="0"
                                                   value="{{ old('quantidade_disponivel', $minhaOferta->quantidade_disponivel ?? '') }}"
                                                   class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg"
                                                   placeholder="0">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                                                Observações
                                            </label>
                                            <textarea name="observacoes" 
                                                      rows="3"
                                                      maxlength="500"
                                                      class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg"
                                                      placeholder="Informações adicionais sobre sua oferta">{{ old('observacoes', $minhaOferta->observacoes ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="p-6 bg-gray-50 border-t border-gray-200 flex gap-3">
                                        <button type="submit"
                                                class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-[var(--cor-verde-escuro)] transition-all font-semibold">
                                            <i data-lucide="check" class="w-5 h-5"></i>
                                            <span>Enviar Oferta</span>
                                        </button>
                                        <button type="button"
                                                @click="modalAberto = null"
                                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
