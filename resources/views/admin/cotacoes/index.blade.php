@extends('layouts.dashboard')

@section('titulo', 'Gerenciar Cotações')
@section('page-title', 'Cotações')
@section('page-subtitle', 'A tela mais importante do sistema')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header com estatísticas e botão criar -->
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-[var(--cor-texto)]">Gestão de Cotações</h1>
                    <p class="text-[var(--cor-texto-secundario)] mt-1">Gerencie ofertas e economize dinheiro para compradores</p>
                </div>
                <a href="{{ route('admin.cotacoes.create') }}" 
                   class="flex items-center justify-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-semibold hover:bg-[var(--cor-verde-escuro)] transition-all shadow-sm hover:shadow-md">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Nova Cotação</span>
                </a>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-[var(--cor-borda)] rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="file-text" class="w-6 h-6 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-[var(--cor-texto)]">{{ $cotacoes->count() }}</p>
                            <p class="text-xs text-[var(--cor-texto-secundario)]">Total Cotações</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[var(--cor-borda)] rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="trending-up" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-[var(--cor-texto)]">{{ $cotacoes->where('status', 'ativo')->count() }}</p>
                            <p class="text-xs text-[var(--cor-texto-secundario)]">Ativas</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[var(--cor-borda)] rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-[var(--cor-texto)]">{{ $cotacoes->sum(fn($c) => $c->ofertas->count()) }}</p>
                            <p class="text-xs text-[var(--cor-texto-secundario)]">Total Ofertas</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-[var(--cor-borda)] rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="tag" class="w-6 h-6 text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-[var(--cor-texto)]">{{ $cotacoes->pluck('segmento_id')->unique()->count() }}</p>
                            <p class="text-xs text-[var(--cor-texto-secundario)]">Segmentos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <p>{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Lista de Cotações com Cards Expansíveis -->
        @if($cotacoes->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-12 text-center">
                <i data-lucide="file-text" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                <h3 class="text-xl font-bold text-[var(--cor-texto)] mb-2">Nenhuma cotação cadastrada</h3>
                <p class="text-[var(--cor-texto-secundario)] mb-6">Comece criando a primeira cotação da semana</p>
                <a href="{{ route('admin.cotacoes.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-semibold hover:bg-[var(--cor-verde-escuro)] transition-all">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Criar Primeira Cotação</span>
                </a>
            </div>
        @else
            <div class="space-y-4" x-data="{ expandido: null }">
                @foreach($cotacoes as $cotacao)
                    @php
                        $melhorOferta = $cotacao->melhorOferta();
                        $totalOfertas = $cotacao->ofertas->count();
                        $mediaPreco = $totalOfertas > 0 ? $cotacao->ofertas->avg('preco_unitario') : 0;
                        $menorPreco = $melhorOferta ? $melhorOferta->preco_unitario : 0;
                        $maiorPreco = $totalOfertas > 0 ? $cotacao->ofertas->max('preco_unitario') : 0;
                        $economia = $totalOfertas > 1 && $melhorOferta ? ($maiorPreco - $menorPreco) : 0;
                        $economiaPercent = $maiorPreco > 0 ? (($economia / $maiorPreco) * 100) : 0;
                    @endphp

                    <!-- Card da Cotação -->
                    <div class="bg-white rounded-xl shadow-sm border-2 border-[var(--cor-borda)] overflow-hidden hover:shadow-lg transition-all"
                         :class="expandido === {{ $cotacao->id }} ? 'ring-2 ring-[var(--cor-verde-serra)]' : ''">
                        
                        <!-- Header Clicável -->
                        <div class="cursor-pointer" @click="expandido = expandido === {{ $cotacao->id }} ? null : {{ $cotacao->id }}">
                            <div class="p-6">
                                <div class="flex gap-4">
                                    <!-- Foto do Produto -->
                                    <div class="flex-shrink-0">
                                        @if($cotacao->imagem_produto_url)
                                            <img src="{{ asset('storage/' . $cotacao->imagem_produto_url) }}" 
                                                 alt="{{ $cotacao->produto }}"
                                                 class="w-24 h-24 lg:w-32 lg:h-32 object-cover rounded-xl border-2 border-gray-200">
                                        @else
                                            <div class="w-24 h-24 lg:w-32 lg:h-32 bg-gray-100 rounded-xl border-2 border-gray-200 flex items-center justify-center">
                                                <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Informações Principais -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4 mb-3">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-xl lg:text-2xl font-bold text-[var(--cor-texto)]">{{ $cotacao->produto }}</h3>
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $cotacao->status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                                        {{ $cotacao->status === 'ativo' ? '✓ Ativa' : 'Encerrada' }}
                                                    </span>
                                                </div>
                                                <p class="text-[var(--cor-texto-secundario)] mb-3">{{ $cotacao->titulo }}</p>
                                                
                                                <!-- Badges de Informações -->
                                                <div class="flex flex-wrap gap-2 text-sm">
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-50 text-purple-700 rounded-lg font-semibold">
                                                        <i data-lucide="tag" class="w-4 h-4"></i>
                                                        {{ $cotacao->segmento->nome }}
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 rounded-lg font-semibold">
                                                        <i data-lucide="package" class="w-4 h-4"></i>
                                                        {{ $cotacao->unidade }}
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-50 text-orange-700 rounded-lg font-semibold">
                                                        <i data-lucide="calendar" class="w-4 h-4"></i>
                                                        {{ $cotacao->data_inicio->format('d/m') }} - {{ $cotacao->data_fim->format('d/m/Y') }}
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 rounded-lg font-semibold">
                                                        <i data-lucide="users" class="w-4 h-4"></i>
                                                        {{ $totalOfertas }} oferta(s)
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Indicador de Expansão -->
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center transition-transform"
                                                     :class="expandido === {{ $cotacao->id }} ? 'rotate-180' : ''">
                                                    <i data-lucide="chevron-down" class="w-6 h-6 text-gray-600"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Resumo de Preços (Sempre Visível) -->
                                        @if($melhorOferta)
                                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-4">
                                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                                    <p class="text-xs text-green-700 font-semibold mb-1">🏆 Melhor Oferta</p>
                                                    <p class="text-lg font-black text-green-800">
                                                        R$ {{ number_format($menorPreco, 2, ',', '.') }}
                                                    </p>
                                                </div>
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                                    <p class="text-xs text-gray-600 font-semibold mb-1">📊 Preço Médio</p>
                                                    <p class="text-lg font-black text-gray-700">
                                                        R$ {{ number_format($mediaPreco, 2, ',', '.') }}
                                                    </p>
                                                </div>
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                                    <p class="text-xs text-red-700 font-semibold mb-1">📈 Maior Preço</p>
                                                    <p class="text-lg font-black text-red-800">
                                                        R$ {{ number_format($maiorPreco, 2, ',', '.') }}
                                                    </p>
                                                </div>
                                                @if($economia > 0)
                                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                                        <p class="text-xs text-yellow-700 font-semibold mb-1">💰 Economia Máx</p>
                                                        <p class="text-lg font-black text-yellow-800">
                                                            {{ number_format($economiaPercent, 1) }}%
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conteúdo Expansível -->
                        <div x-show="expandido === {{ $cotacao->id }}" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-y-95"
                             x-transition:enter-end="opacity-100 transform scale-y-100"
                             class="border-t-2 border-gray-100 bg-gray-50">
                            
                            <div class="p-6 space-y-6">
                                <!-- Descrição -->
                                @if($cotacao->descricao)
                                    <div class="bg-white rounded-lg p-4 border-l-4 border-blue-500">
                                        <p class="text-sm font-semibold text-blue-900 mb-1">📝 Descrição</p>
                                        <p class="text-[var(--cor-texto-secundario)]">{{ $cotacao->descricao }}</p>
                                    </div>
                                @endif

                                <!-- Lista de Ofertas -->
                                @if($cotacao->ofertas->isNotEmpty())
                                    <div>
                                        <h4 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                                            <i data-lucide="package-check" class="w-5 h-5"></i>
                                            Ofertas Cadastradas ({{ $totalOfertas }})
                                        </h4>

                                        <div class="grid lg:grid-cols-3 gap-4">
                                            @foreach($cotacao->ofertasOrdenadas()->get() as $index => $oferta)
                                                <div class="bg-white border-2 rounded-xl p-4 {{ $index === 0 ? 'border-yellow-400' : 'border-gray-200' }}">
                                                    @if($index === 0)
                                                        <div class="flex items-center gap-2 text-yellow-700 font-bold text-sm mb-3">
                                                            <i data-lucide="trophy" class="w-4 h-4"></i>
                                                            MELHOR OFERTA
                                                        </div>
                                                    @else
                                                        <div class="text-gray-600 font-semibold text-sm mb-3">{{ $index + 1 }}º lugar</div>
                                                    @endif

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
                                                            <h5 class="font-bold text-[var(--cor-texto)] truncate text-sm">
                                                                {{ $oferta->fornecedor->fornecedor->nome_empresa }}
                                                            </h5>
                                                            @if($oferta->fornecedor->enderecoPrincipal)
                                                                <p class="text-xs text-[var(--cor-texto-secundario)]">
                                                                    {{ $oferta->fornecedor->enderecoPrincipal->cidade }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                                        <p class="text-2xl font-black text-green-600">
                                                            R$ {{ number_format($oferta->preco_unitario, 2, ',', '.') }}
                                                            <span class="text-sm font-normal text-gray-600">/{{ $cotacao->unidade }}</span>
                                                        </p>
                                                    </div>

                                                    <div class="space-y-2 text-sm">
                                                        @if($oferta->prazo_entrega)
                                                            <div class="flex items-center gap-2">
                                                                <i data-lucide="truck" class="w-4 h-4 text-blue-600"></i>
                                                                <span>{{ $oferta->prazo_entrega }}</span>
                                                            </div>
                                                        @endif
                                                        @if($oferta->quantidade_disponivel)
                                                            <div class="flex items-center gap-2">
                                                                <i data-lucide="box" class="w-4 h-4 text-purple-600"></i>
                                                                <span>{{ number_format($oferta->quantidade_disponivel, 0, ',', '.') }} {{ $cotacao->unidade }}</span>
                                                            </div>
                                                        @endif
                                                        @if($oferta->observacoes)
                                                            <div class="pt-2 border-t border-gray-200">
                                                                <p class="text-xs text-[var(--cor-texto-secundario)]">{{ $oferta->observacoes }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                        <p class="text-[var(--cor-texto-secundario)]">Nenhuma oferta cadastrada ainda</p>
                                    </div>
                                @endif

                                <!-- Ações -->
                                <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                                    <a href="{{ route('admin.cotacoes.edit', $cotacao->id) }}" 
                                       class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                        <span>Editar Cotação</span>
                                    </a>

                                    @if($cotacao->status === 'ativo')
                                        <form action="{{ route('admin.cotacoes.encerrar', $cotacao->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Tem certeza que deseja encerrar esta cotação?')"
                                                    class="flex items-center gap-2 px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-all font-semibold">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                                <span>Encerrar</span>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.cotacoes.destroy', $cotacao->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Tem certeza que deseja DELETAR esta cotação? Esta ação não pode ser desfeita!')"
                                                class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-semibold">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            <span>Deletar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
