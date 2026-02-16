@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Material de Gestão</h1>
            <p class="text-sm text-gray-600 mt-1">Gerencie conteúdos educacionais para os usuários</p>
        </div>
        <a href="{{ route('admin.materiais.create') }}" class="btn-primary inline-flex items-center justify-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Novo Material
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.materiais.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Título ou descrição" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select name="categoria" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Todas</option>
                    @foreach($categorias as $key => $label)
                        <option value="{{ $key }}" {{ request('categoria') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select name="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Todos</option>
                    <option value="arquivo" {{ request('tipo') === 'arquivo' ? 'selected' : '' }}>Arquivo</option>
                    <option value="video" {{ request('tipo') === 'video' ? 'selected' : '' }}>Vídeo</option>
                    <option value="link" {{ request('tipo') === 'link' ? 'selected' : '' }}>Link</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary flex-1">
                    <i data-lucide="search" class="w-4 h-4 inline"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.materiais.index') }}" class="btn-secondary px-3 py-2">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Materiais -->
    @if($materiais->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($materiais as $material)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Thumbnail -->
                    <div class="aspect-video bg-gray-100 relative">
                        @if($material->thumbnail_url)
                            <img src="{{ asset('storage/' . $material->thumbnail_url) }}" alt="{{ $material->titulo }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                @if($material->tipo === 'video')
                                    <i data-lucide="video" class="w-12 h-12 text-gray-400"></i>
                                @elseif($material->tipo === 'arquivo')
                                    <i data-lucide="file-text" class="w-12 h-12 text-gray-400"></i>
                                @else
                                    <i data-lucide="link" class="w-12 h-12 text-gray-400"></i>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-2 right-2 flex flex-col gap-1">
                            @if($material->apenas_vip)
                                <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded">VIP</span>
                            @endif
                            @if(!$material->ativo)
                                <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">Inativo</span>
                            @endif
                        </div>
                    </div>

                    <!-- Conteúdo -->
                    <div class="p-4">
                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2">{{ $material->titulo }}</h3>
                        
                        <div class="flex items-center gap-2 text-xs text-gray-600 mb-3">
                            <span class="px-2 py-1 bg-gray-100 rounded">{{ $categorias[$material->categoria] ?? $material->categoria }}</span>
                            <span class="px-2 py-1 bg-gray-100 rounded capitalize">{{ $material->tipo }}</span>
                        </div>

                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span><i data-lucide="eye" class="w-3 h-3 inline"></i> {{ $material->views_count }}</span>
                            <span><i data-lucide="download" class="w-3 h-3 inline"></i> {{ $material->downloads_count }}</span>
                            <span>{{ $material->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.materiais.edit', $material->id) }}" class="btn-secondary flex-1 text-center text-sm">
                                <i data-lucide="edit" class="w-3 h-3 inline"></i>
                                Editar
                            </a>
                            <form action="{{ route('admin.materiais.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Confirma exclusão?')" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger w-full text-sm">
                                    <i data-lucide="trash-2" class="w-3 h-3 inline"></i>
                                    Deletar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $materiais->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <i data-lucide="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
            <p class="text-gray-600">Nenhum material encontrado</p>
            <a href="{{ route('admin.materiais.create') }}" class="btn-primary inline-flex items-center gap-2 mt-4">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Criar Primeiro Material
            </a>
        </div>
    @endif
</div>
@endsection
