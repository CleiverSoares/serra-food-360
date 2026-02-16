@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-8 max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('compras-coletivas.propostas.index') }}" class="text-primary hover:underline flex items-center gap-2 text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Voltar
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Propor Produto para Compra Coletiva</h1>
        <p class="text-sm text-gray-600 mt-1">Sugira um produto que você gostaria de comprar em grupo para obter melhores preços</p>
    </div>

    <form action="{{ route('compras-coletivas.propostas.store') }}" method="POST" class="bg-white rounded-lg shadow-sm p-6">
        @csrf

        <!-- Nome do Produto -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Produto *</label>
            <input type="text" name="produto_nome" value="{{ old('produto_nome') }}" required 
                   placeholder="Ex: Arroz Branco Tipo 1"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            @error('produto_nome')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Descrição -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição do Produto</label>
            <textarea name="produto_descricao" rows="3" 
                      placeholder="Especificações, marca preferida, qualidade, etc."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('produto_descricao') }}</textarea>
            @error('produto_descricao')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Unidade de Medida -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Unidade de Medida *</label>
            <select name="unidade_medida" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="">Selecione...</option>
                <option value="kg" {{ old('unidade_medida') === 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                <option value="g" {{ old('unidade_medida') === 'g' ? 'selected' : '' }}>Grama (g)</option>
                <option value="litro" {{ old('unidade_medida') === 'litro' ? 'selected' : '' }}>Litro</option>
                <option value="ml" {{ old('unidade_medida') === 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                <option value="un" {{ old('unidade_medida') === 'un' ? 'selected' : '' }}>Unidade</option>
                <option value="cx" {{ old('unidade_medida') === 'cx' ? 'selected' : '' }}>Caixa</option>
                <option value="sc" {{ old('unidade_medida') === 'sc' ? 'selected' : '' }}>Saco</option>
                <option value="pt" {{ old('unidade_medida') === 'pt' ? 'selected' : '' }}>Pacote</option>
            </select>
            @error('unidade_medida')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Categoria -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoria (opcional)</label>
            <select name="categoria_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="">Nenhuma</option>
                @foreach($categorias ?? [] as $categoria)
                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nome }}</option>
                @endforeach
            </select>
            @error('categoria_id')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Justificativa -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Por que você quer este produto? *</label>
            <textarea name="justificativa" rows="4" required 
                      placeholder="Explique por que este produto seria útil para você e para outros compradores. Quanto mais clara sua justificativa, mais chances de ser aprovada!"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('justificativa') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Mínimo 50 caracteres</p>
            @error('justificativa')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4"></i>
                Como funciona?
            </h3>
            <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                <li>Você propõe o produto</li>
                <li>O admin analisa e aprova</li>
                <li>A proposta vai para votação (7 dias)</li>
                <li>Se aprovada, vira uma Compra Coletiva oficial</li>
                <li>Você e outros podem participar!</li>
            </ol>
        </div>

        <!-- Botões -->
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1">
                <i data-lucide="send" class="w-4 h-4 inline"></i>
                Enviar Proposta
            </button>
            <a href="{{ route('compras-coletivas.propostas.index') }}" class="btn-secondary flex-1 text-center">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
