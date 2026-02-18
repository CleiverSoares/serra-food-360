@extends('layouts.dashboard')

@section('titulo', 'Editar Cotação')
@section('page-title', 'Editar Cotação')
@section('page-subtitle', $cotacao->titulo)

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-6xl mx-auto space-y-6">

        @if(session('sucesso'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <p>{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Formulário de Edição da Cotação -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6 lg:p-8">
            <h2 class="text-xl font-bold text-[var(--cor-texto)] mb-6">Informações da Cotação</h2>
            
            <form action="{{ route('admin.cotacoes.update', $cotacao->id) }}" method="POST" enctype="multipart/form-data" x-data="{ imagemPreview: '{{ $cotacao->imagem_produto_url ? asset('storage/' . $cotacao->imagem_produto_url) : '' }}' }">
                @csrf
                @method('PUT')

                <!-- 📸 IMAGEM DO PRODUTO - DESTAQUE -->
                <div class="mb-6 p-6 bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="image" class="w-6 h-6 text-blue-600"></i>
                        <h3 class="text-lg font-bold text-blue-900">Foto do Produto</h3>
                        @if($cotacao->imagem_produto_url)
                            <span class="px-2 py-0.5 bg-green-500 text-white text-xs font-bold rounded">✓ CADASTRADA</span>
                        @else
                            <span class="px-2 py-0.5 bg-orange-500 text-white text-xs font-bold rounded">SEM FOTO</span>
                        @endif
                    </div>
                    
                    @if($cotacao->imagem_produto_url)
                        <div class="mb-4 p-4 bg-white rounded-lg border-2 border-blue-200">
                            <p class="text-sm font-bold text-blue-900 mb-3 flex items-center gap-2">
                                <i data-lucide="image" class="w-4 h-4"></i>
                                Imagem Atual
                            </p>
                            <img :src="imagemPreview" alt="Imagem atual" class="w-full max-w-md h-48 object-cover rounded-lg shadow-md">
                        </div>
                        <p class="text-sm text-blue-700 mb-4">
                            📌 Selecione um novo arquivo para substituir ou deixe em branco para manter
                        </p>
                    @else
                        <p class="text-sm text-blue-700 mb-4">
                            📌 Adicione uma foto do produto para facilitar a identificação pelos fornecedores
                        </p>
                    @endif
                    
                    <input type="file" 
                           id="imagem_produto" 
                           name="imagem_produto" 
                           accept="image/jpeg,image/jpg,image/png,image/webp"
                           @change="if ($event.target.files[0]) { imagemPreview = URL.createObjectURL($event.target.files[0]); }"
                           class="w-full px-4 py-3 border-2 border-blue-300 bg-white rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-blue-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    <p class="text-xs text-blue-600 mt-2 font-medium">✓ JPG, PNG ou WEBP • Máximo 2MB</p>
                    @error('imagem_produto')
                        <p class="text-red-600 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                    
                    <!-- Preview da Nova Imagem -->
                    <div x-show="imagemPreview && imagemPreview !== '{{ $cotacao->imagem_produto_url ? asset('storage/' . $cotacao->imagem_produto_url) : '' }}'" class="mt-4 p-4 bg-white rounded-lg border-2 border-green-200">
                        <p class="text-sm font-bold text-green-900 mb-3 flex items-center gap-2">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                            Nova Imagem (Preview)
                        </p>
                        <img :src="imagemPreview" alt="Nova imagem" class="w-full max-w-md h-48 object-cover rounded-lg shadow-md mx-auto">
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="produto" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Produto *
                        </label>
                        <input type="text" 
                               id="produto" 
                               name="produto" 
                               value="{{ old('produto', $cotacao->produto) }}"
                               required
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>

                    <div>
                        <label for="titulo" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Título *
                        </label>
                        <input type="text" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo', $cotacao->titulo) }}"
                               required
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="descricao" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                        Descrição
                    </label>
                    <textarea id="descricao" 
                              name="descricao" 
                              rows="3"
                              class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('descricao', $cotacao->descricao) }}</textarea>
                </div>

                <div class="grid lg:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="unidade" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Unidade *
                        </label>
                        <select id="unidade" name="unidade" required class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="kg" {{ old('unidade', $cotacao->unidade) === 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                            <option value="litro" {{ old('unidade', $cotacao->unidade) === 'litro' ? 'selected' : '' }}>Litro</option>
                            <option value="unidade" {{ old('unidade', $cotacao->unidade) === 'unidade' ? 'selected' : '' }}>Unidade</option>
                            <option value="caixa" {{ old('unidade', $cotacao->unidade) === 'caixa' ? 'selected' : '' }}>Caixa</option>
                            <option value="pacote" {{ old('unidade', $cotacao->unidade) === 'pacote' ? 'selected' : '' }}>Pacote</option>
                        </select>
                    </div>

                    <div>
                        <label for="quantidade_minima" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Quantidade Mínima
                        </label>
                        <input type="number" 
                               id="quantidade_minima" 
                               name="quantidade_minima" 
                               value="{{ old('quantidade_minima', $cotacao->quantidade_minima) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                    </div>

                    <div>
                        <label for="segmento_id" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Segmento *
                        </label>
                        <select id="segmento_id" name="segmento_id" required class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            @foreach($segmentos as $segmento)
                                <option value="{{ $segmento->id }}" {{ old('segmento_id', $cotacao->segmento_id) == $segmento->id ? 'selected' : '' }}>
                                    {{ $segmento->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="data_inicio" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Data de Início *
                        </label>
                        <input type="date" 
                               id="data_inicio" 
                               name="data_inicio" 
                               value="{{ old('data_inicio', $cotacao->data_inicio->format('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                    </div>

                    <div>
                        <label for="data_fim" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Data de Término *
                        </label>
                        <input type="date" 
                               id="data_fim" 
                               name="data_fim" 
                               value="{{ old('data_fim', $cotacao->data_fim->format('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-semibold hover:bg-[var(--cor-verde-escuro)] transition-all">
                        <i data-lucide="check" class="w-5 h-5"></i>
                        <span>Salvar Alterações</span>
                    </button>
                    <a href="{{ route('admin.cotacoes.index') }}" 
                       class="flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                        <span>Voltar</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Ofertas dos Fornecedores -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6 lg:p-8" 
             x-data="{ 
                 mostrarForm: false, 
                 fornecedorSelecionado: '' 
             }">
            
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-[var(--cor-texto)]">Ofertas dos Fornecedores</h2>
                    <p class="text-sm text-[var(--cor-texto-secundario)] mt-1">{{ $cotacao->ofertas->count() }} oferta(s) cadastrada(s)</p>
                </div>
                <button @click="mostrarForm = !mostrarForm" 
                        class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-semibold hover:bg-blue-100 transition-all">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    <span>Adicionar Oferta</span>
                </button>
            </div>

            <!-- Formulário de Nova Oferta -->
            <div x-show="mostrarForm" 
                 x-cloak 
                 x-transition
                 class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-bold text-[var(--cor-texto)] mb-4">Nova Oferta</h3>
                
                <form action="{{ route('admin.cotacoes.ofertas.adicionar', $cotacao->id) }}" method="POST">
                    @csrf

                    <div class="grid lg:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="fornecedor_id" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                                Fornecedor *
                            </label>
                            <select id="fornecedor_id" 
                                    name="fornecedor_id" 
                                    required 
                                    x-model="fornecedorSelecionado"
                                    class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione um fornecedor</option>
                                @foreach($fornecedores as $fornecedor)
                                    <option value="{{ $fornecedor->id }}">
                                        {{ $fornecedor->fornecedor->nome_empresa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="preco_unitario" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                                Preço Unitário (R$) *
                            </label>
                            <input type="number" 
                                   id="preco_unitario" 
                                   name="preco_unitario" 
                                   required
                                   step="0.01"
                                   min="0"
                                   class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="0,00">
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="prazo_entrega" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                                Prazo de Entrega
                            </label>
                            <input type="text" 
                                   id="prazo_entrega" 
                                   name="prazo_entrega"
                                   class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="Ex: 24h, 2 dias úteis">
                        </div>

                        <div>
                            <label for="quantidade_disponivel" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                                Quantidade Disponível
                            </label>
                            <input type="number" 
                                   id="quantidade_disponivel" 
                                   name="quantidade_disponivel"
                                   step="0.01"
                                   min="0"
                                   class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="0">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="observacoes" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Observações
                        </label>
                        <textarea id="observacoes" 
                                  name="observacoes" 
                                  rows="2"
                                  class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Informações adicionais sobre a oferta"></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" 
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-all">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>Adicionar Oferta</span>
                        </button>
                        <button type="button" 
                                @click="mostrarForm = false"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lista de Ofertas -->
            @if($cotacao->ofertas->isEmpty())
                <div class="text-center py-12">
                    <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-[var(--cor-texto-secundario)]">Nenhuma oferta cadastrada ainda</p>
                    <p class="text-sm text-[var(--cor-texto-secundario)] mt-1">Clique em "Adicionar Oferta" para começar</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($cotacao->ofertasOrdenadas()->get() as $index => $oferta)
                        <div class="border-2 rounded-lg p-4 transition-all hover:shadow-md
                                    {{ $oferta->destaque ? 'border-yellow-400 bg-yellow-50' : 'border-gray-200 bg-gray-50' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        @if($index === 0)
                                            <span class="flex items-center gap-1 text-yellow-700 font-bold text-sm">
                                                <i data-lucide="trophy" class="w-4 h-4"></i>
                                                Melhor Oferta
                                            </span>
                                        @else
                                            <span class="text-gray-600 text-sm">{{ $index + 1 }}º lugar</span>
                                        @endif
                                    </div>
                                    
                                    <h4 class="font-bold text-[var(--cor-texto)] mb-1">
                                        {{ $oferta->fornecedor->fornecedor->nome_empresa }}
                                    </h4>
                                    
                                    <div class="flex flex-wrap gap-4 text-sm text-[var(--cor-texto-secundario)]">
                                        <span class="font-bold text-lg text-green-600">
                                            R$ {{ number_format($oferta->preco_unitario, 2, ',', '.') }}/{{ $cotacao->unidade }}
                                        </span>
                                        @if($oferta->prazo_entrega)
                                            <span>Entrega: {{ $oferta->prazo_entrega }}</span>
                                        @endif
                                        @if($oferta->quantidade_disponivel)
                                            <span>Qtd: {{ number_format($oferta->quantidade_disponivel, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    
                                    @if($oferta->observacoes)
                                        <p class="text-sm text-[var(--cor-texto-secundario)] mt-2">
                                            {{ $oferta->observacoes }}
                                        </p>
                                    @endif
                                </div>

                                <form action="{{ route('admin.cotacoes.ofertas.deletar', $oferta->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Tem certeza que deseja remover esta oferta?')"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
