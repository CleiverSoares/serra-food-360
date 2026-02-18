@extends('layouts.dashboard')

@section('titulo', 'Nova Compra Coletiva')
@section('page-title', 'Nova Compra Coletiva')
@section('page-subtitle', 'Criar nova compra coletiva')

@section('header-actions')
<a href="{{ route('admin.compras-coletivas.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-4xl mx-auto">

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-sm text-red-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.compras-coletivas.store') }}" class="space-y-6">
            @csrf

            <!-- 📸 AVISO SOBRE IMAGEM -->
            <div class="bg-blue-50 border-2 border-blue-300 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <i data-lucide="info" class="w-6 h-6 text-blue-600 flex-shrink-0"></i>
                    <div>
                        <h3 class="text-lg font-bold text-blue-900 mb-2">ℹ️ Como adicionar imagem?</h3>
                        <p class="text-sm text-blue-800 mb-3">
                            A imagem da compra coletiva vem do <strong>Produto do Catálogo</strong> que você selecionar abaixo.
                        </p>
                        <p class="text-xs text-blue-700">
                            💡 <strong>Para adicionar/editar a foto do produto:</strong> Vá em <a href="{{ route('admin.compras-coletivas.produtos.index') }}" class="underline font-bold">Catálogo de Produtos</a> → Editar Produto → Adicione a foto lá.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Informações Básicas -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5"></i>
                    Informações Básicas
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Produto do Catálogo *</label>
                        <select name="produto_catalogo_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Selecione um produto</option>
                            @foreach($produtos as $produto)
                                <option value="{{ $produto->id }}">{{ $produto->nome }} ({{ $produto->unidade_medida }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-600 mt-1">💡 A imagem da compra vem do produto selecionado</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Título da Compra *</label>
                        <input type="text" name="titulo" required value="{{ old('titulo') }}"
                               placeholder="Ex: Compra Coletiva de Óleo de Soja - Março 2026"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Descrição</label>
                        <textarea name="descricao" rows="3"
                                  placeholder="Informações adicionais sobre esta compra coletiva..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('descricao') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Quantidade Mínima *</label>
                        <input type="number" name="quantidade_minima" step="0.01" min="0.01" required value="{{ old('quantidade_minima') }}"
                               placeholder="Ex: 100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        <p class="text-xs text-gray-600 mt-1">Quantidade mínima necessária para efetivação da compra</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Data de Início *</label>
                            <input type="date" name="data_inicio" required value="{{ old('data_inicio', now()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Data de Encerramento *</label>
                            <input type="date" name="data_fim" required value="{{ old('data_fim', now()->addDays(15)->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Observações Internas</label>
                        <textarea name="observacoes" rows="2"
                                  placeholder="Observações visíveis apenas para administradores..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('observacoes') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Status *</label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                            <option value="ativa" {{ old('status') === 'ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="encerrada" {{ old('status') === 'encerrada' ? 'selected' : '' }}>Encerrada</option>
                            <option value="cancelada" {{ old('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base flex-1">
                    <i data-lucide="save" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Criar Compra Coletiva</span>
                </button>
                <a href="{{ route('admin.compras-coletivas.index') }}" 
                   class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base flex-1">
                    <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cancelar</span>
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
