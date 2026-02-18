@extends('layouts.dashboard')

@section('titulo', 'Meus Favoritos')
@section('page-title', 'Meus Favoritos')
@section('page-subtitle', 'Materiais salvos para acesso rápido')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <!-- Botão Voltar -->
        <div class="mb-6">
            <a href="{{ route('materiais.index') }}" 
               class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                <i data-lucide="arrow-left" class="w-4 h-4 flex-shrink-0"></i>
                <span class="whitespace-nowrap">Material de Gestão</span>
            </a>
        </div>

        <!-- Contador -->
        @if($materiais->count() > 0)
            <div class="mb-4">
                <p class="text-sm text-[var(--cor-texto-muted)]">
                    <strong>{{ $materiais->count() }}</strong> material(is) favoritado(s)
                </p>
            </div>
        @endif

        <!-- Grid de Materiais -->
        @if($materiais->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 items-stretch" id="materiaisFavoritos">
                @foreach($materiais as $material)
                    <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] hover:shadow-md transition-shadow overflow-hidden flex flex-col h-full">
                        <!-- Thumbnail -->
                        <a href="{{ route('materiais.show', $material->id) }}" class="block relative aspect-video bg-gray-100 overflow-hidden group flex-shrink-0">
                            @if($material->thumbnail_url)
                                <!-- Thumbnail customizado -->
                                <img src="{{ asset('storage/' . $material->thumbnail_url) }}" 
                                     alt="{{ $material->titulo }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @elseif($material->tipo === 'video' && $material->video_url)
                                <!-- Preview do YouTube -->
                                @php
                                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $material->video_url, $matches);
                                    $videoId = $matches[1] ?? null;
                                @endphp
                                @if($videoId)
                                    <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg" 
                                         alt="{{ $material->titulo }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         onerror="this.src='https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg'">
                                    <!-- Play button overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 group-hover:bg-opacity-40 transition-all">
                                        <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                            <i data-lucide="play" class="w-8 h-8 text-white fill-white ml-1"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <i data-lucide="video" class="w-12 h-12 text-gray-400"></i>
                                    </div>
                                @endif
                            @else
                                <!-- Fallback para outros tipos -->
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    @if($material->tipo === 'arquivo')
                                        <i data-lucide="file-text" class="w-12 h-12 text-gray-400"></i>
                                    @else
                                        <i data-lucide="link" class="w-12 h-12 text-gray-400"></i>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Badge VIP -->
                            @if($material->apenas_vip)
                                <span class="absolute top-2 right-2 px-2 py-1 bg-yellow-500 text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-sm z-10">
                                    <i data-lucide="crown" class="w-3 h-3"></i>
                                    VIP
                                </span>
                            @endif
                        </a>

                        <!-- Conteúdo -->
                        <div class="p-4 flex-1 flex flex-col">
                            <a href="{{ route('materiais.show', $material->id) }}" class="block mb-2">
                                <h3 class="text-base font-bold text-[var(--cor-texto)] line-clamp-2 hover:text-[var(--cor-verde-serra)] transition-colors" style="height: 3rem; min-height: 3rem;">
                                    {{ $material->titulo }}
                                </h3>
                            </a>
                            
                            <div class="flex flex-wrap items-center gap-2 text-xs mb-3" style="height: 2rem; min-height: 2rem;">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded capitalize">{{ $material->categoria }}</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded capitalize flex items-center gap-1">
                                    @if($material->tipo === 'video')
                                        <i data-lucide="video" class="w-3 h-3"></i>
                                    @elseif($material->tipo === 'arquivo')
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                    @else
                                        <i data-lucide="link" class="w-3 h-3"></i>
                                    @endif
                                    {{ $material->tipo }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 mt-auto">
                                <a href="{{ route('materiais.show', $material->id) }}" 
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    <span class="whitespace-nowrap">Ver Detalhes</span>
                                </a>
                                <button onclick="toggleFavorito({{ $material->id }}, this)" 
                                        class="p-2 rounded-lg hover:bg-pink-50 transition-transform duration-200 border border-transparent hover:border-pink-200 flex-shrink-0"
                                        data-favorito="true">
                                    <i data-lucide="heart" class="w-5 h-5 fill-pink-500 stroke-pink-500"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8 text-center">
                <i data-lucide="heart" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                <p class="text-yellow-800 font-medium">Você ainda não tem favoritos</p>
                <p class="text-yellow-700 text-sm mt-1 mb-4">Adicione materiais aos favoritos para acessá-los rapidamente</p>
                <a href="{{ route('materiais.index') }}" 
                   class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium text-sm md:text-base">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    <span class="whitespace-nowrap">Explorar Materiais</span>
                </a>
            </div>
        @endif

    </div>
</div>

<script>
function pintarCoracaoFavorito(button, favorito) {
    const svg = button.querySelector('svg');
    
    if (!svg) {
        console.error('SVG não encontrado no botão');
        return;
    }
    
    console.log('Pintando coração:', favorito ? '❤️ ROSA' : '🤍 CINZA');
    
    // Aplicar estilos DIRETAMENTE no SVG
    if (favorito) {
        svg.style.fill = '#ec4899';      // rosa-500
        svg.style.stroke = '#ec4899';    // rosa-500
    } else {
        svg.style.fill = 'none';
        svg.style.stroke = '#6b7280';    // cinza-500
    }
    
    console.log('✅ Coração pintado!');
}

function toggleFavorito(materialId, button) {
    console.log('Removendo favorito:', materialId);
    
    // Animação de pulso
    button.style.transform = 'scale(1.2)';
    setTimeout(() => { button.style.transform = 'scale(1)'; }, 150);
    
    fetch(`/materiais/${materialId}/favoritar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Resposta:', data);
        if (data.success && !data.favorito) {
            // Remover o card da tela com animação
            const card = button.closest('.grid > div');
            if (card) {
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.remove();
                    
                    // Se não sobrou nenhum favorito, recarregar página
                    const remainingCards = document.querySelectorAll('#materiaisFavoritos > div');
                    if (remainingCards.length === 0) {
                        location.reload();
                    }
                }, 300);
            }
        }
    })
    .catch(error => {
        console.error('Erro ao remover favorito:', error);
    });
}

// Inicializar corações na página de favoritos
document.addEventListener('DOMContentLoaded', function() {
    function inicializarFavoritos() {
        console.log('Inicializando corações favoritos...');
        const botoesFavoritos = document.querySelectorAll('button[data-favorito="true"]');
        console.log('Encontrados', botoesFavoritos.length, 'favoritos');
        botoesFavoritos.forEach(button => pintarCoracaoFavorito(button, true));
    }
    
    // Tentar em vários momentos
    setTimeout(inicializarFavoritos, 300);
    setTimeout(inicializarFavoritos, 600);
    setTimeout(inicializarFavoritos, 1000);
});
</script>
@endsection
