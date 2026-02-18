@extends('layouts.dashboard')

@section('titulo', 'Editar Produto')
@section('page-title', 'Editar Produto')
@section('page-subtitle', $produto->nome)

@section('header-actions')
<a href="{{ route('admin.compras-coletivas.produtos.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-4xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-sm text-red-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.compras-coletivas.produtos.update', $produto->id) }}" enctype="multipart/form-data" 
              x-data="{ imagemPreview: '{{ $produto->imagem_url ? asset('storage/' . $produto->imagem_url) : '' }}' }" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informações do Produto -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="package" class="w-5 h-5 text-blue-600"></i>
                    Informações do Produto
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Nome do Produto *</label>
                        <input type="text" name="nome" required value="{{ old('nome', $produto->nome) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Descrição</label>
                        <textarea name="descricao" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('descricao', $produto->descricao) }}</textarea>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Unidade de Medida *</label>
                            <input type="text" name="unidade_medida" required value="{{ old('unidade_medida', $produto->unidade_medida) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Categoria</label>
                            <select name="categoria_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                                <option value="">Sem categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ $produto->categoria_id == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Imagem do Produto</label>
                        
                        <!-- Preview Atual -->
                        @if($produto->imagem_url)
                            <div class="mb-3">
                                <p class="text-xs text-gray-600 mb-2">Imagem atual:</p>
                                <img src="{{ asset('storage/' . $produto->imagem_url) }}" alt="{{ $produto->nome }}" 
                                     class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            </div>
                        @endif

                        <input type="file" name="imagem" accept="image/jpeg,image/jpg,image/png,image/webp"
                               @change="imagemPreview = $event.target.files.length > 0 ? URL.createObjectURL($event.target.files[0]) : '{{ $produto->imagem_url ? asset('storage/' . $produto->imagem_url) : '' }}'"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[var(--cor-verde-serra)] file:text-white hover:file:bg-green-700">
                        <p class="text-xs text-gray-600 mt-1">Deixe em branco para manter a imagem atual. Formatos: JPG, PNG, WEBP (máx. 2MB)</p>
                        
                        <!-- Preview Nova -->
                        <div x-show="imagemPreview" class="mt-3">
                            <p class="text-xs text-gray-600 mb-2">Nova prévia:</p>
                            <img :src="imagemPreview" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="ativo" id="ativo" value="1" {{ $produto->ativo ? 'checked' : '' }}
                               class="w-4 h-4 text-[var(--cor-verde-serra)] border-gray-300 rounded focus:ring-[var(--cor-verde-serra)]">
                        <label for="ativo" class="text-sm font-medium text-[var(--cor-texto)]">Produto ativo</label>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base flex-1">
                    <i data-lucide="save" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Salvar Alterações</span>
                </button>
                <a href="{{ route('admin.compras-coletivas.produtos.index') }}" 
                   class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base flex-1">
                    <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cancelar</span>
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
