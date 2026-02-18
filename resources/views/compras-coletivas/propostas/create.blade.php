@extends('layouts.dashboard')

@section('titulo', 'Propor Produto')
@section('page-title', 'Compras Coletivas')
@section('page-subtitle', 'Propor novo produto para compra coletiva')

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

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 lg:p-6 mb-6">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-blue-900 mb-1">Como Funciona?</p>
                    <p class="text-sm text-blue-700">
                        1. Você propõe um produto<br>
                        2. Outros compradores votam na sua proposta<br>
                        3. Propostas com mais votos viram compras coletivas oficiais<br>
                        4. Fornecedores enviam ofertas e todos economizam!
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('compras-coletivas.propostas.store') }}" class="space-y-6">
            @csrf

            <!-- Informações do Produto -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 lg:p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="package" class="w-5 h-5 text-green-600"></i>
                    Informações do Produto
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Nome do Produto *</label>
                        <input type="text" name="nome_produto" value="{{ old('nome_produto') }}" required
                               placeholder="Ex: Óleo de Soja 900ml"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Descrição do Produto</label>
                        <textarea name="descricao_produto" rows="3"
                                  placeholder="Descreva o produto, marca preferida, especificações..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('descricao_produto') }}</textarea>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Unidade de Medida *</label>
                            <input type="text" name="unidade_medida" value="{{ old('unidade_medida') }}" required
                                   placeholder="Ex: Litro, Kg, Caixa, Unidade"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Categoria *</label>
                            <select name="categoria_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                                <option value="">Selecione...</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Justificativa -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 lg:p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-5 h-5 text-blue-600"></i>
                    Justificativa
                </h3>
                
                <div>
                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Por que este produto? *</label>
                    <textarea name="justificativa" rows="4" required
                              placeholder="Explique por que você acha que este produto deveria estar em compra coletiva. Quanto mais convincente, mais votos você terá!"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('justificativa') }}</textarea>
                    <p class="text-xs text-gray-600 mt-1">Dica: Mencione frequência de uso, volume de compra, potencial de economia.</p>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-col md:flex-row gap-3">
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium text-sm md:text-base flex-1">
                    <i data-lucide="send" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Enviar Proposta</span>
                </button>
                <a href="{{ route('compras-coletivas.propostas.index') }}" 
                   class="inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base flex-1">
                    <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cancelar</span>
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
