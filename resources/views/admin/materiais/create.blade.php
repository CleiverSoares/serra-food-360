@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-8 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.materiais.index') }}" class="text-primary hover:underline flex items-center gap-2 text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Voltar para Materiais
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Novo Material</h1>
    </div>

    <form action="{{ route('admin.materiais.store') }}" method="POST" enctype="multipart/form-data" 
          x-data="{ tipo: 'arquivo', previewThumbnail: null }" class="bg-white rounded-lg shadow-sm p-6">
        @csrf

        <!-- Título -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
            <input type="text" name="titulo" value="{{ old('titulo') }}" required 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            @error('titulo')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Descrição -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
            <textarea name="descricao" rows="4" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('descricao') }}</textarea>
            @error('descricao')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Categoria e Tipo -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoria *</label>
                <select name="categoria" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Selecione...</option>
                    @foreach($categorias as $key => $label)
                        <option value="{{ $key }}" {{ old('categoria') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('categoria')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Material *</label>
                <select name="tipo" x-model="tipo" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="arquivo">Arquivo (PDF, DOC, XLS, etc)</option>
                    <option value="video">Vídeo (YouTube)</option>
                    <option value="link">Link Externo</option>
                </select>
                @error('tipo')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Upload de Arquivo -->
        <div x-show="tipo === 'arquivo'" class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Arquivo *</label>
            <input type="file" name="arquivo" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (máx. 10MB)</p>
            @error('arquivo')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- URL do Vídeo -->
        <div x-show="tipo === 'video'" class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">URL do YouTube *</label>
            <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">Cole o link completo do vídeo no YouTube</p>
            @error('video_url')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Link Externo -->
        <div x-show="tipo === 'link'" class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Link Externo *</label>
            <input type="url" name="link_externo" value="{{ old('link_externo') }}" placeholder="https://..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            @error('link_externo')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Thumbnail -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail (Miniatura)</label>
            <input type="file" name="thumbnail" accept="image/jpeg,image/jpg,image/png,image/webp" 
                   @change="previewThumbnail = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">JPG, PNG, WEBP (máx. 2MB, recomendado 1280x720)</p>
            
            <!-- Preview -->
            <div x-show="previewThumbnail" class="mt-3">
                <img :src="previewThumbnail" class="w-full max-w-md h-48 object-cover rounded-lg">
            </div>
            @error('thumbnail')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- Checkboxes -->
        <div class="flex flex-col gap-3 mb-6 p-4 bg-gray-50 rounded-lg">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="apenas_vip" value="1" {{ old('apenas_vip') ? 'checked' : '' }} 
                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                <span class="text-sm font-medium text-gray-700">Apenas para assinantes VIP</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }} 
                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                <span class="text-sm font-medium text-gray-700">Ativo (visível para usuários)</span>
            </label>
        </div>

        <!-- Botões -->
        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1">
                <i data-lucide="save" class="w-4 h-4 inline"></i>
                Criar Material
            </button>
            <a href="{{ route('admin.materiais.index') }}" class="btn-secondary flex-1 text-center">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
