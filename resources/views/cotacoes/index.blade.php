@extends('layouts.dashboard')

@section('titulo', 'Cotações da Semana')
@section('page-title', 'Cotações')
@section('page-subtitle', 'Compare preços e encontre as melhores ofertas')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 lg:p-6 mb-6">
            <form method="GET" action="{{ route('cotacoes.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                    
                    <!-- Busca -->
                    <div class="md:col-span-2">
                        <input 
                            type="text" 
                            name="busca" 
                            value="{{ $filtros['busca'] ?? '' }}"
                            placeholder="Buscar por nome ou produto..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent text-sm">
                    </div>

                    <!-- Segmento -->
                    <div>
                        <select 
                            name="segmento_id" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent text-sm">
                            <option value="">Segmento</option>
                            @foreach($segmentos as $segmento)
                                <option value="{{ $segmento->id }}" {{ ($filtros['segmento_id'] ?? '') == $segmento->id ? 'selected' : '' }}>
                                    {{ $segmento->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <select 
                            name="status" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent text-sm">
                            <option value="">Status</option>
                            <option value="ativo" {{ ($filtros['status'] ?? '') === 'ativo' ? 'selected' : '' }}>Ativas</option>
                            <option value="encerrado" {{ ($filtros['status'] ?? '') === 'encerrado' ? 'selected' : '' }}>Encerradas</option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all flex items-center justify-center gap-2 text-sm font-medium">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            <span class="hidden md:inline">Buscar</span>
                        </button>
                        <a href="{{ route('cotacoes.index') }}" class="px-3 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        @if($cotacoes->isEmpty())
            <!-- Estado vazio -->
            <div class="flex flex-col items-center justify-center py-16 px-4">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i data-lucide="file-text" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-[var(--cor-texto)] mb-2">Nenhuma cotação disponível</h3>
                <p class="text-[var(--cor-texto-secundario)] text-center max-w-md">
                    As cotações da semana aparecerão aqui quando o administrador cadastrar novos produtos.
                </p>
            </div>
        @else
            <!-- Lista de Cotações -->
            @foreach($cotacoes as $cotacao)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] mb-6 overflow-hidden">
                    
                    <!-- Header da Cotação -->
                    <div class="bg-[var(--cor-verde-serra)] text-white p-4 lg:p-6">
                        <div class="flex items-start gap-4">
                            <!-- Imagem do Produto -->
                            @if($cotacao->imagem_produto_url)
                                <div class="flex-shrink-0 w-16 h-16 lg:w-20 lg:h-20 rounded-lg overflow-hidden border-2 border-white/20">
                                    <img src="{{ asset('storage/' . $cotacao->imagem_produto_url) }}" alt="{{ $cotacao->produto }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            
                            <div class="flex-1 flex items-start justify-between gap-4">
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
                                </div>
                                </div>
                                
                                <span class="bg-green-800 px-3 py-1 rounded-lg text-xs lg:text-sm font-semibold whitespace-nowrap text-white flex-shrink-0">
                                    {{ $cotacao->segmento->nome }}
                                </span>
                            </div>
                        </div>
                        
                        @if($cotacao->descricao)
                            <p class="mt-4 text-sm text-green-50 border-t border-green-400 pt-4">
                                {{ $cotacao->descricao }}
                            </p>
                        @endif
                    </div>

                    @if($cotacao->ofertas->isEmpty())
                        <!-- Sem ofertas ainda -->
                        <div class="p-8 text-center">
                            <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-[var(--cor-texto-secundario)]">Ainda não há ofertas para este produto</p>
                        </div>
                    @else
                        <!-- Kanban de Ofertas (Mobile: Swipe Horizontal | Desktop: Grid) -->
                        <div class="p-4 lg:p-6" 
                             x-data="{ 
                                 scrollContainer: null,
                                 init() {
                                     this.scrollContainer = this.$refs.kanban;
                                 }
                             }">
                            
                            <!-- Indicador de Swipe (Mobile) -->
                            <div class="flex items-center justify-between mb-4 lg:hidden">
                                <p class="text-sm text-[var(--cor-texto-secundario)] flex items-center gap-2">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    Deslize para ver todas as ofertas
                                </p>
                                <span class="text-xs font-semibold text-[var(--cor-verde-serra)]">
                                    {{ $cotacao->ofertas->count() }} ofertas
                                </span>
                            </div>

                            <!-- Container Kanban -->
                            <div 
                                x-ref="kanban"
                                class="flex gap-4 overflow-x-auto pb-4 snap-x snap-mandatory lg:grid lg:grid-cols-3 lg:overflow-visible lg:snap-none"
                                style="scrollbar-width: thin; -webkit-overflow-scrolling: touch;">
                                
                                @foreach($cotacao->ofertasOrdenadas as $index => $oferta)
                                    <!-- Card de Oferta -->
                                    <div class="flex-shrink-0 w-[85vw] lg:w-auto snap-center lg:snap-align-none">
                                        <div class="bg-white border-2 rounded-xl overflow-hidden transition-all hover:shadow-lg
                                                    {{ $oferta->destaque ? 'border-yellow-400 shadow-md' : 'border-[var(--cor-borda)]' }}">
                                            
                                            <!-- Badge de Posição -->
                                            <div class="px-4 pt-4 pb-2 flex items-center justify-between
                                                        {{ $index === 0 ? 'bg-yellow-50' : ($index === 1 ? 'bg-gray-50' : 'bg-white') }}">
                                                @if($index === 0)
                                                    <span class="flex items-center gap-1 text-yellow-700 font-bold text-sm">
                                                        <i data-lucide="trophy" class="w-4 h-4"></i>
                                                        Melhor Oferta
                                                    </span>
                                                @elseif($index === 1)
                                                    <span class="text-gray-600 font-semibold text-sm">2º Lugar</span>
                                                @elseif($index === 2)
                                                    <span class="text-gray-500 font-medium text-sm">3º Lugar</span>
                                                @else
                                                    <span class="text-gray-400 text-sm">{{ $index + 1 }}º</span>
                                                @endif
                                                
                                                @if($oferta->destaque)
                                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full font-bold">
                                                        DESTAQUE
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Informações do Fornecedor -->
                                            <div class="p-4 border-b border-gray-100">
                                                <div class="flex items-start gap-3 mb-3">
                                                    @if($oferta->fornecedor->fornecedor->logo_path)
                                                        <img src="{{ $oferta->fornecedor->fornecedor->logo_path }}" 
                                                             alt="Logo" 
                                                             class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                            <i data-lucide="building-2" class="w-6 h-6 text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="flex-1 min-w-0">
                                                        <h3 class="font-bold text-[var(--cor-texto)] truncate text-sm lg:text-base">
                                                            {{ $oferta->fornecedor->fornecedor->nome_empresa }}
                                                        </h3>
                                                        @if($oferta->fornecedor->enderecoPrincipal)
                                                            <p class="text-xs text-[var(--cor-texto-secundario)] flex items-center gap-1">
                                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                                {{ $oferta->fornecedor->enderecoPrincipal->cidade }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Preço Destaque -->
                                            <div class="p-6 bg-gray-50 text-center">
                                                <p class="text-xs text-[var(--cor-texto-secundario)] mb-1 uppercase tracking-wide">Preço por {{ $cotacao->unidade }}</p>
                                                <p class="text-3xl lg:text-4xl font-black 
                                                          {{ $index === 0 ? 'text-green-600' : 'text-[var(--cor-texto)]' }}">
                                                    R$ {{ number_format($oferta->preco_unitario, 2, ',', '.') }}
                                                </p>
                                                
                                                @if($index === 0 && $cotacao->ofertas->count() > 1)
                                                    @php
                                                        $segundoMelhor = $cotacao->ofertas->skip(1)->first();
                                                        if ($segundoMelhor) {
                                                            $economia = $segundoMelhor->preco_unitario - $oferta->preco_unitario;
                                                            $percentual = ($economia / $segundoMelhor->preco_unitario) * 100;
                                                        }
                                                    @endphp
                                                    @if(isset($economia) && $economia > 0)
                                                        <p class="text-xs text-green-600 font-semibold mt-2">
                                                            Economia de R$ {{ number_format($economia, 2, ',', '.') }} ({{ number_format($percentual, 1) }}%)
                                                        </p>
                                                    @endif
                                                @endif
                                            </div>

                                            <!-- Detalhes da Oferta -->
                                            <div class="p-4 space-y-3">
                                                @if($oferta->prazo_entrega)
                                                    <div class="flex items-center gap-2 text-sm">
                                                        <i data-lucide="truck" class="w-4 h-4 text-blue-600"></i>
                                                        <span class="text-[var(--cor-texto-secundario)]">Entrega:</span>
                                                        <span class="font-semibold text-[var(--cor-texto)]">{{ $oferta->prazo_entrega }}</span>
                                                    </div>
                                                @endif

                                                @if($oferta->quantidade_disponivel)
                                                    <div class="flex items-center gap-2 text-sm">
                                                        <i data-lucide="package-check" class="w-4 h-4 text-purple-600"></i>
                                                        <span class="text-[var(--cor-texto-secundario)]">Disponível:</span>
                                                        <span class="font-semibold text-[var(--cor-texto)]">
                                                            {{ number_format($oferta->quantidade_disponivel, 0, ',', '.') }} {{ $cotacao->unidade }}
                                                        </span>
                                                    </div>
                                                @endif

                                                @if($oferta->observacoes)
                                                    <div class="pt-3 border-t border-gray-100">
                                                        <p class="text-xs text-[var(--cor-texto-secundario)] mb-1 font-semibold">Observações:</p>
                                                        <p class="text-sm text-[var(--cor-texto)]">{{ $oferta->observacoes }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Botão de Contato -->
                                            <div class="p-4 bg-gray-50 border-t border-gray-100">
                                                @php
                                                    $whatsapp = $oferta->fornecedor->contatos->where('tipo', 'whatsapp')->where('is_principal', true)->first();
                                                    $mensagem = "Olá! Vi sua oferta de *{$cotacao->produto}* por R$ " . number_format($oferta->preco_unitario, 2, ',', '.') . " no Serra Food 360. Gostaria de mais informações!";
                                                @endphp
                                                
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp->valor ?? '') }}?text={{ urlencode($mensagem) }}"
                                                   target="_blank"
                                                   class="flex items-center justify-center gap-2 w-full px-4 py-3 bg-[#25D366] text-white rounded-lg font-semibold hover:bg-[#1DA851] transition-all shadow-sm hover:shadow-md">
                                                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                                                    <span>Contatar via WhatsApp</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Dica de economia (Desktop) -->
                            @if($cotacao->ofertas->count() > 1)
                                <div class="hidden lg:block mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-900 mb-1">Dica de Economia</p>
                                            <p class="text-sm text-blue-700">
                                                Compare os preços acima e escolha a melhor oferta para o seu negócio. 
                                                A diferença pode representar economia significativa no mês!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
// Melhorar experiência de scroll horizontal no mobile
document.querySelectorAll('[x-ref="kanban"]').forEach(container => {
    let isDown = false;
    let startX;
    let scrollLeft;

    container.addEventListener('mousedown', (e) => {
        isDown = true;
        container.style.cursor = 'grabbing';
        startX = e.pageX - container.offsetLeft;
        scrollLeft = container.scrollLeft;
    });

    container.addEventListener('mouseleave', () => {
        isDown = false;
        container.style.cursor = 'grab';
    });

    container.addEventListener('mouseup', () => {
        isDown = false;
        container.style.cursor = 'grab';
    });

    container.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - container.offsetLeft;
        const walk = (x - startX) * 2;
        container.scrollLeft = scrollLeft - walk;
    });
});
</script>
@endpush
