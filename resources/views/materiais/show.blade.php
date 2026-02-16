@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-8 max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('materiais.index') }}" class="text-primary hover:underline flex items-center gap-2 text-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Voltar para Materiais
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Conteúdo Principal -->
        @if($material->tipo === 'video')
            <!-- Vídeo do YouTube -->
            <div class="aspect-video bg-gray-900">
                @php
                    // Extrair ID do YouTube
                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $material->video_url, $matches);
                    $youtubeId = $matches[1] ?? null;
                @endphp
                
                @if($youtubeId)
                    <iframe 
                        width="100%" 
                        height="100%" 
                        src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                        class="w-full h-full">
                    </iframe>
                @else
                    <div class="w-full h-full flex items-center justify-center text-white">
                        <div class="text-center">
                            <i data-lucide="alert-circle" class="w-12 h-12 mx-auto mb-2"></i>
                            <p>URL do vídeo inválida</p>
                            <a href="{{ $material->video_url }}" target="_blank" class="text-primary hover:underline text-sm">Abrir link direto</a>
                        </div>
                    </div>
                @endif
            </div>
        @elseif($material->tipo === 'arquivo')
            <!-- Preview de Arquivo (thumbnail ou ícone) -->
            <div class="aspect-video bg-gray-100 relative flex items-center justify-center">
                @if($material->thumbnail_url)
                    <img src="{{ asset('storage/' . $material->thumbnail_url) }}" alt="{{ $material->titulo }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="file-text" class="w-16 h-16 text-gray-400"></i>
                @endif
            </div>
        @elseif($material->tipo === 'link')
            <!-- Link Externo -->
            <div class="aspect-video bg-gradient-to-br from-primary to-secondary relative flex items-center justify-center">
                @if($material->thumbnail_url)
                    <img src="{{ asset('storage/' . $material->thumbnail_url) }}" alt="{{ $material->titulo }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="external-link" class="w-16 h-16 text-white"></i>
                @endif
            </div>
        @endif

        <!-- Informações -->
        <div class="p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $material->titulo }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-3 text-sm">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full">{{ $material->categoria }}</span>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full capitalize">{{ $material->tipo }}</span>
                        @if($material->apenas_vip)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full flex items-center gap-1">
                                <i data-lucide="crown" class="w-3 h-3"></i>
                                Conteúdo VIP
                            </span>
                        @endif
                    </div>
                </div>

                <button onclick="toggleFavorito({{ $material->id }}, this)" 
                        class="p-3 rounded-lg hover:bg-gray-100 transition-colors"
                        data-favorito="false">
                    <i data-lucide="heart" class="w-6 h-6 stroke-gray-400"></i>
                </button>
            </div>

            <!-- Descrição -->
            @if($material->descricao)
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-2">Sobre este material</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $material->descricao }}</p>
                </div>
            @endif

            <!-- Estatísticas -->
            <div class="grid grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="text-center">
                    <p class="text-2xl font-bold text-primary">{{ $material->views_count }}</p>
                    <p class="text-xs text-gray-600">Visualizações</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-primary">{{ $material->downloads_count }}</p>
                    <p class="text-xs text-gray-600">Downloads</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-bold text-primary">{{ $material->created_at->diffForHumans() }}</p>
                    <p class="text-xs text-gray-600">Publicado</p>
                </div>
            </div>

            <!-- Ações -->
            <div class="flex flex-col sm:flex-row gap-3">
                @if($material->tipo === 'arquivo')
                    <a href="{{ route('materiais.download', $material->id) }}" class="btn-primary flex-1 text-center">
                        <i data-lucide="download" class="w-4 h-4 inline"></i>
                        Baixar Arquivo
                    </a>
                @elseif($material->tipo === 'video')
                    <a href="{{ $material->video_url }}" target="_blank" class="btn-primary flex-1 text-center">
                        <i data-lucide="external-link" class="w-4 h-4 inline"></i>
                        Abrir no YouTube
                    </a>
                @elseif($material->tipo === 'link')
                    <a href="{{ $material->link_externo }}" target="_blank" class="btn-primary flex-1 text-center">
                        <i data-lucide="external-link" class="w-4 h-4 inline"></i>
                        Acessar Link
                    </a>
                @endif

                <a href="{{ route('materiais.index') }}" class="btn-secondary flex-1 text-center">
                    Ver Mais Materiais
                </a>
            </div>

            <!-- Informações Adicionais -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    Publicado por <span class="font-medium">{{ $material->criador->nome }}</span> em {{ $material->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
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
