@extends('layouts.dashboard')

@section('titulo', 'Material de Gestão')
@section('page-title', 'Material de Gestão')
@section('page-subtitle', 'Vídeos, planilhas e guias para melhorar seu negócio')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Header: Botão Favoritos -->
        <div class="flex items-center justify-end mb-6">
            <a href="{{ route('materiais.favoritos') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-[var(--cor-verde-escuro)] transition-all font-semibold shadow-sm hover:shadow-md">
                <i data-lucide="heart" class="w-5 h-5"></i>
                <span>Meus Favoritos</span>
            </a>
        </div>

        @if($isVip)
            <div class="mb-6">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm font-bold rounded-lg">
                    <i data-lucide="crown" class="w-4 h-4"></i>
                    Acesso VIP Ativo
                </span>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6 mb-6">
            <form method="GET" action="{{ route('materiais.index') }}" class="space-y-4">
                
                <!-- Busca por título -->
                <div>
                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">
                        <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                        Buscar por título ou descrição
                    </label>
                    <input type="text" 
                           name="busca" 
                           value="{{ request('busca') }}"
                           placeholder="Digite para buscar..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                </div>

                <!-- Filtros em linha -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Categoria -->
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Categoria</label>
                        <select name="categoria" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Todas</option>
                            @foreach($categorias as $key => $label)
                                <option value="{{ $key }}" {{ request('categoria') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Tipo</label>
                        <select name="tipo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Todos</option>
                            <option value="arquivo" {{ request('tipo') === 'arquivo' ? 'selected' : '' }}>Arquivo</option>
                            <option value="video" {{ request('tipo') === 'video' ? 'selected' : '' }}>Vídeo</option>
                            <option value="link" {{ request('tipo') === 'link' ? 'selected' : '' }}>Link</option>
                        </select>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>Filtrar</span>
                    </button>
                    <a href="{{ route('materiais.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        <span>Limpar</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Contador -->
        <div class="mb-4">
            <p class="text-sm text-[var(--cor-texto-muted)]">
                <strong>{{ $materiais->total() }}</strong> material(is) encontrado(s)
            </p>
        </div>

        <!-- Lista de Materiais -->
        @if($materiais->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
                @foreach($materiais as $material)
                    <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full">
                        <!-- Thumbnail -->
                        <a href="{{ route('materiais.show', $material->id) }}" class="block bg-gray-100 relative overflow-hidden group flex-shrink-0" style="height: 200px; min-height: 200px;">
                            @if($material->thumbnail_url)
                                <!-- Thumbnail customizado -->
                                <img src="{{ asset('storage/' . $material->thumbnail_url) }}" 
                                     alt="{{ $material->titulo }}" 
                                     class="w-full h-full object-contain bg-white group-hover:scale-105 transition-transform duration-300">
                            @elseif($material->tipo === 'video' && $material->video_url)
                                <!-- Preview do YouTube -->
                                @php
                                    preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $material->video_url, $matches);
                                    $youtubeId = $matches[1] ?? null;
                                @endphp
                                @if($youtubeId)
                                    <img src="https://img.youtube.com/vi/{{ $youtubeId }}/maxresdefault.jpg" 
                                         alt="{{ $material->titulo }}" 
                                         class="w-full h-full object-contain bg-black group-hover:scale-105 transition-transform duration-300"
                                         onerror="this.src='https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg'">
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
                                <h3 class="font-bold text-[var(--cor-texto)] text-base line-clamp-2 hover:text-[var(--cor-verde-serra)] transition-colors" style="height: 3rem; min-height: 3rem;">
                                    {{ $material->titulo }}
                                </h3>
                            </a>
                            
                            <!-- Tags -->
                            <div class="flex items-center gap-2 text-xs mb-3 flex-wrap" style="height: 2rem; min-height: 2rem;">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ $categorias[$material->categoria] ?? $material->categoria }}</span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded capitalize">{{ $material->tipo }}</span>
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center gap-3 text-xs text-[var(--cor-texto-muted)] mb-3" style="height: 1.25rem; min-height: 1.25rem;">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3 h-3"></i>
                                    {{ $material->views_count }}
                                </span>
                                @if($material->tipo === 'arquivo')
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="download" class="w-3 h-3"></i>
                                        {{ $material->downloads_count }}
                                    </span>
                                @endif
                            </div>

                            <!-- Ações (sempre no final) -->
                            <div class="flex items-center gap-2 mt-auto">
                                <a href="{{ route('materiais.show', $material->id) }}" 
                                   class="inline-flex items-center justify-center gap-1 px-3 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all text-sm font-medium flex-1">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    Ver Detalhes
                                </a>
                                <button onclick="toggleFavorito({{ $material->id }}, this)" 
                                        class="p-2.5 rounded-lg hover:bg-pink-50 transition-transform duration-200 border border-transparent hover:border-pink-200"
                                        data-favorito="{{ $material->is_favorito > 0 ? 'true' : 'false' }}"
                                        title="{{ $material->is_favorito > 0 ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}">
                                    <i data-lucide="heart" class="w-5 h-5 {{ $material->is_favorito > 0 ? 'fill-pink-500 stroke-pink-500' : 'stroke-gray-500' }}"></i>
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
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                <p class="text-yellow-800 font-medium">Nenhum material encontrado</p>
                @if(!$isVip)
                    <p class="text-yellow-700 text-sm mt-2">Faça upgrade para VIP e tenha acesso a conteúdos exclusivos!</p>
                @endif
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
function pintarCoracaoFavorito(button, favorito) {
    const svg = button.querySelector('svg');
    
    if (!svg) {
        console.error('SVG não encontrado no botão');
        return;
    }
    
    console.log('Pintando coração:', favorito ? '❤️ ROSA' : '🤍 CINZA');
    
    // Aplicar estilos DIRETAMENTE no SVG (classes CSS não funcionam bem)
    if (favorito) {
        svg.style.fill = '#ec4899';      // rosa-500
        svg.style.stroke = '#ec4899';    // rosa-500
    } else {
        svg.style.fill = 'none';         // sem preenchimento
        svg.style.stroke = '#6b7280';    // cinza-500
    }
    
    console.log('✅ Coração pintado!');
}

function toggleFavorito(materialId, button) {
    const eraFavorito = button.dataset.favorito === 'true';
    console.log('Toggle favorito material:', materialId, 'Era favorito:', eraFavorito);
    
    // Animação de pulso no botão
    button.style.transform = 'scale(1.2)';
    setTimeout(() => { button.style.transform = 'scale(1)'; }, 150);
    
    // Enviar requisição
    fetch(`/materiais/${materialId}/favoritar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Resposta do servidor:', data);
        if (data.success) {
            // Atualizar o estado do botão
            button.dataset.favorito = data.favorito ? 'true' : 'false';
            button.title = data.favorito ? 'Remover dos favoritos' : 'Adicionar aos favoritos';
            
            // Pintar o coração
            pintarCoracaoFavorito(button, data.favorito);
        }
    })
    .catch(error => {
        console.error('Erro ao favoritar:', error);
    });
}

// Inicializar corações favoritados ao carregar a página
function inicializarCoracoesFavoritos() {
    console.log('Inicializando corações favoritos...');
    const botoesFavoritos = document.querySelectorAll('button[data-favorito="true"]');
    console.log('Encontrados', botoesFavoritos.length, 'favoritos');
    
    botoesFavoritos.forEach(button => {
        pintarCoracaoFavorito(button, true);
    });
}

// Executar após o DOM carregar
document.addEventListener('DOMContentLoaded', function() {
    // Aguardar Lucide processar os ícones (tempos maiores)
    setTimeout(inicializarCoracoesFavoritos, 300);
    setTimeout(inicializarCoracoesFavoritos, 600);
    setTimeout(inicializarCoracoesFavoritos, 1000);
});
</script>
@endpush
@endsection
