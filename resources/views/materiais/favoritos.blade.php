@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-8">
    <div class="mb-6">
        <a href="{{ route('materiais.index') }}" class="text-primary hover:underline flex items-center gap-2 text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Voltar para Materiais
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2 flex items-center gap-2">
            <i data-lucide="heart" class="w-6 h-6 text-red-500"></i>
            Meus Favoritos
        </h1>
        <p class="text-sm text-gray-600 mt-1">Materiais salvos para acesso rápido</p>
    </div>

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
                            <span class="px-2 py-1 bg-gray-100 rounded">{{ $material->categoria }}</span>
                            <span class="px-2 py-1 bg-gray-100 rounded capitalize">{{ $material->tipo }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('materiais.show', $material->id) }}" class="btn-primary flex-1 text-center text-sm">
                                Ver Detalhes
                            </a>
                            <button onclick="toggleFavorito({{ $material->id }}, this)" 
                                    class="ml-2 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                                    data-favorito="true">
                                <i data-lucide="heart" class="w-5 h-5 fill-red-500 stroke-red-500"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <i data-lucide="heart" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
            <p class="text-gray-600 mb-2">Você ainda não tem favoritos</p>
            <p class="text-sm text-gray-500 mb-4">Adicione materiais aos favoritos para acessá-los rapidamente</p>
            <a href="{{ route('materiais.index') }}" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                Explorar Materiais
            </a>
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
            // Remover o card da tela (está nos favoritos, logo remover = deixou de ser favorito)
            button.closest('.grid > div').remove();
            
            // Se não sobrou nenhum favorito, recarregar página
            if (document.querySelectorAll('.grid > div').length === 0) {
                location.reload();
            }
        }
    });
}
</script>
@endsection
