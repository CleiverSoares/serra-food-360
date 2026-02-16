@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Material de Gestão</h1>
        <p class="text-sm text-gray-600 mt-1">Conteúdos educacionais para melhorar sua gestão</p>
        @if($isVip)
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full mt-2">
                <i data-lucide="crown" class="w-3 h-3"></i>
                Acesso VIP Ativo
            </span>
        @endif
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('materiais.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <a href="{{ route('materiais.index') }}" class="btn-secondary px-3 py-2">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Link para Favoritos -->
    <div class="mb-4">
        <a href="{{ route('materiais.favoritos') }}" class="inline-flex items-center gap-2 text-primary hover:underline text-sm">
            <i data-lucide="heart" class="w-4 h-4"></i>
            Meus Favoritos
        </a>
    </div>

    <!-- Lista de Materiais -->
    @if($materiais->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($materiais as $material)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Thumbnail -->
                    <a href="{{ route('materiais.show', $material->id) }}" class="block aspect-video bg-gray-100 relative">
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
                        
                        <!-- Badge VIP -->
                        @if($material->apenas_vip)
                            <span class="absolute top-2 right-2 px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded flex items-center gap-1">
                                <i data-lucide="crown" class="w-3 h-3"></i>
                                VIP
                            </span>
                        @endif
                    </a>

                    <!-- Conteúdo -->
                    <div class="p-4">
                        <a href="{{ route('materiais.show', $material->id) }}" class="block">
                            <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 hover:text-primary transition-colors">{{ $material->titulo }}</h3>
                        </a>
                        
                        <div class="flex items-center gap-2 text-xs text-gray-600 mb-3">
                            <span class="px-2 py-1 bg-gray-100 rounded">{{ $categorias[$material->categoria] ?? $material->categoria }}</span>
                            <span class="px-2 py-1 bg-gray-100 rounded capitalize">{{ $material->tipo }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('materiais.show', $material->id) }}" class="btn-primary flex-1 text-center text-sm">
                                Ver Detalhes
                            </a>
                            <button onclick="toggleFavorito({{ $material->id }}, this)" 
                                    class="ml-2 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-favorito="{{ $material->is_favorito > 0 ? 'true' : 'false' }}">
                                <i data-lucide="heart" class="w-5 h-5 {{ $material->is_favorito > 0 ? 'fill-red-500 stroke-red-500' : 'stroke-gray-400' }}"></i>
                            </button>
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
            @if(!$isVip)
                <p class="text-sm text-gray-500 mt-2">Faça upgrade para VIP e tenha acesso a conteúdos exclusivos!</p>
            @endif
        </div>
    @endif
</div>

<script>
function toggleFavorito(materialId, button) {
    fetch(`/materiais/${materialId}/favoritar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const icon = button.querySelector('i');
            if (data.favorito) {
                icon.classList.add('fill-red-500', 'stroke-red-500');
                icon.classList.remove('stroke-gray-400');
                button.dataset.favorito = 'true';
            } else {
                icon.classList.remove('fill-red-500', 'stroke-red-500');
                icon.classList.add('stroke-gray-400');
                button.dataset.favorito = 'false';
            }
        }
    });
}
</script>
@endsection
