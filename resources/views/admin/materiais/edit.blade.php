@extends('layouts.dashboard')

@section('titulo', 'Editar Material')
@section('page-title', 'Editar Material')
@section('page-subtitle', $material->titulo)

@section('header-actions')
<a href="{{ route('admin.materiais.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
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

        <form method="POST" action="{{ route('admin.materiais.update', $material->id) }}" enctype="multipart/form-data" 
              x-data="{ 
                  tipo: '{{ old('tipo', $material->tipo) }}', 
                  thumbnailPreview: '{{ $material->thumbnail_url ? asset('storage/' . $material->thumbnail_url) : '' }}'
              }" 
              class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informações Básicas -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
                    Informações Básicas
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Título *</label>
                        <input type="text" name="titulo" value="{{ old('titulo', $material->titulo) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Descrição</label>
                        <textarea name="descricao" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('descricao', $material->descricao) }}</textarea>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Categoria *</label>
                            <select name="categoria" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                                <option value="">Selecione...</option>
                                <option value="financeiro" {{ old('categoria', $material->categoria) === 'financeiro' ? 'selected' : '' }}>Financeiro</option>
                                <option value="cozinha" {{ old('categoria', $material->categoria) === 'cozinha' ? 'selected' : '' }}>Cozinha</option>
                                <option value="legislacao" {{ old('categoria', $material->categoria) === 'legislacao' ? 'selected' : '' }}>Legislação</option>
                                <option value="gestao_equipe" {{ old('categoria', $material->categoria) === 'gestao_equipe' ? 'selected' : '' }}>Gestão de Equipe</option>
                                <option value="marketing" {{ old('categoria', $material->categoria) === 'marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="atendimento" {{ old('categoria', $material->categoria) === 'atendimento' ? 'selected' : '' }}>Atendimento</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Tipo *</label>
                            <select name="tipo" x-model="tipo" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                                <option value="arquivo">Arquivo (PDF, DOC, XLS)</option>
                                <option value="video">Vídeo (YouTube)</option>
                                <option value="link">Link Externo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo do Material -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="upload" class="w-5 h-5 text-green-600"></i>
                    Conteúdo
                </h3>

                <!-- Arquivo -->
                <div x-show="tipo === 'arquivo'" class="space-y-4">
                    @if($material->tipo === 'arquivo' && $material->arquivo_path)
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <i data-lucide="file" class="w-4 h-4 inline"></i>
                                Arquivo atual: <strong>{{ basename($material->arquivo_path) }}</strong>
                            </p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">
                            {{ $material->tipo === 'arquivo' && $material->arquivo_path ? 'Substituir Arquivo' : 'Arquivo' }}
                        </label>
                        <input type="file" name="arquivo" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        <p class="text-xs text-gray-600 mt-1">Formatos: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX. Máximo 10MB</p>
                    </div>
                </div>

                <!-- Vídeo -->
                <div x-show="tipo === 'video'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">URL do YouTube *</label>
                        <input type="url" name="video_url" value="{{ old('video_url', $material->video_url) }}"
                               placeholder="https://www.youtube.com/watch?v=..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        <p class="text-xs text-gray-600 mt-1">Cole o link completo do vídeo do YouTube</p>
                    </div>
                </div>

                <!-- Link -->
                <div x-show="tipo === 'link'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Link Externo *</label>
                        <input type="url" name="link_externo" value="{{ old('link_externo', $material->link_externo) }}"
                               placeholder="https://..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="image" class="w-5 h-5 text-purple-600"></i>
                    Imagem de Capa (Thumbnail)
                </h3>

                @if($material->thumbnail_url)
                    <div class="mb-4">
                        <p class="text-sm text-[var(--cor-texto)] mb-2">Thumbnail Atual:</p>
                        <img :src="thumbnailPreview" alt="Thumbnail atual" class="w-48 h-32 object-cover rounded-lg border border-gray-300">
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">
                        {{ $material->thumbnail_url ? 'Substituir Thumbnail' : 'Thumbnail' }}
                    </label>
                    <input type="file" name="thumbnail" accept="image/*"
                           @change="if ($event.target.files[0]) { thumbnailPreview = URL.createObjectURL($event.target.files[0]); }"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    <p class="text-xs text-gray-600 mt-1">Formatos: JPG, PNG, WebP. Máximo 2MB. Deixe em branco para manter atual.</p>
                </div>

                <div x-show="thumbnailPreview && thumbnailPreview !== '{{ $material->thumbnail_url ? asset('storage/' . $material->thumbnail_url) : '' }}'" class="mt-4">
                    <p class="text-sm text-[var(--cor-texto)] mb-2">Preview:</p>
                    <img :src="thumbnailPreview" alt="Preview" class="w-48 h-32 object-cover rounded-lg border border-gray-300">
                </div>
            </div>

            <!-- Configurações -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="settings" class="w-5 h-5 text-gray-600"></i>
                    Configurações
                </h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="apenas_vip" value="1" {{ old('apenas_vip', $material->apenas_vip) ? 'checked' : '' }}
                               class="w-5 h-5 rounded accent-[var(--cor-verde-serra)]">
                        <span class="text-sm font-medium text-[var(--cor-texto)]">Exclusivo para assinantes VIP</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="ativo" value="1" {{ old('ativo', $material->ativo) ? 'checked' : '' }}
                               class="w-5 h-5 rounded accent-[var(--cor-verde-serra)]">
                        <span class="text-sm font-medium text-[var(--cor-texto)]">Material ativo (visível para usuários)</span>
                    </label>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart" class="w-5 h-5 text-blue-600"></i>
                    Estatísticas
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-[var(--cor-verde-serra)]">{{ $material->views_count }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Visualizações</p>
                    </div>
                    @if($material->tipo === 'arquivo')
                        <div class="text-center">
                            <p class="text-2xl font-bold text-[var(--cor-verde-serra)]">{{ $material->downloads_count }}</p>
                            <p class="text-xs text-[var(--cor-texto-muted)]">Downloads</p>
                        </div>
                    @endif
                    <div class="text-center">
                        <p class="text-sm font-bold text-[var(--cor-texto)]">{{ $material->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Criado em</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-bold text-[var(--cor-texto)]">{{ $material->updated_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-[var(--cor-texto-muted)]">Atualizado em</p>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base">
                    <i data-lucide="save" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Salvar Alterações</span>
                </button>
                <a href="{{ route('admin.materiais.index') }}" 
                   class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                    <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cancelar</span>
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
