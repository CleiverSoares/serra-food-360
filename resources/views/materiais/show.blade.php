@extends('layouts.dashboard')

@section('titulo', $material->titulo)
@section('page-title', 'Material de Gestão')
@section('page-subtitle', $material->titulo)

@section('header-actions')
<a href="{{ route('materiais.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('mobile-header-actions')
<a href="{{ route('materiais.index') }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Voltar">
    <i data-lucide="arrow-left" class="w-5 h-5 text-[var(--cor-texto)]"></i>
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Coluna Principal - 2/3 -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden">
                    
                    @if($material->tipo === 'video')
                        <!-- Vídeo do YouTube -->
                        @php
                            preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $material->video_url, $matches);
                            $youtubeId = $matches[1] ?? null;
                        @endphp
                        
                        @if($youtubeId)
                            <!-- Player do YouTube Responsivo -->
                            <div class="relative bg-black" style="padding-bottom: 56.25%;">
                                <iframe 
                                    id="youtube-player"
                                    class="absolute inset-0 w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @else
                            <!-- Fallback para URL inválida -->
                            <div class="aspect-video bg-gray-900 flex items-center justify-center p-6">
                                <div class="text-center text-white">
                                    <i data-lucide="video-off" class="w-16 h-16 mx-auto mb-4 opacity-50"></i>
                                    <p class="text-lg mb-4">URL do vídeo inválida</p>
                                    <a href="{{ $material->video_url }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium">
                                        <i data-lucide="external-link" class="w-5 h-5"></i>
                                        Abrir Link Original
                                    </a>
                                </div>
                            </div>
                        @endif
                    @elseif($material->tipo === 'arquivo')
                        <!-- Preview de Arquivo -->
                        <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center p-8">
                            <div class="text-center">
                                @if($material->thumbnail_url)
                                    <img src="{{ asset('storage/' . $material->thumbnail_url) }}" 
                                         alt="{{ $material->titulo }}" 
                                         class="max-w-full max-h-64 mx-auto rounded-lg shadow-lg mb-6">
                                @else
                                    <div class="w-32 h-32 mx-auto mb-6 bg-[var(--cor-verde-serra)] rounded-full flex items-center justify-center">
                                        <i data-lucide="file-text" class="w-16 h-16 text-white"></i>
                                    </div>
                                @endif
                                <p class="text-xl font-bold text-[var(--cor-texto)] mb-2">Documento Disponível</p>
                                <p class="text-[var(--cor-texto-muted)] mb-6">Clique no botão abaixo para fazer o download</p>
                                @if($material->arquivo_path)
                                    <a href="{{ route('materiais.download', $material->id) }}" 
                                       class="inline-flex items-center gap-2 px-8 py-4 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-bold text-lg shadow-lg hover:shadow-xl">
                                        <i data-lucide="download" class="w-6 h-6"></i>
                                        Baixar Documento
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Link Externo -->
                        <div class="aspect-video bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center p-8">
                            <div class="text-center">
                                @if($material->thumbnail_url)
                                    <img src="{{ asset('storage/' . $material->thumbnail_url) }}" 
                                         alt="{{ $material->titulo }}" 
                                         class="max-w-full max-h-64 mx-auto rounded-lg shadow-lg mb-6">
                                @else
                                    <div class="w-32 h-32 mx-auto mb-6 bg-blue-600 rounded-full flex items-center justify-center">
                                        <i data-lucide="link" class="w-16 h-16 text-white"></i>
                                    </div>
                                @endif
                                <p class="text-xl font-bold text-[var(--cor-texto)] mb-2">Link Externo</p>
                                <p class="text-[var(--cor-texto-muted)] mb-6">Clique no botão para acessar o conteúdo</p>
                                @if($material->link_externo)
                                    <a href="{{ $material->link_externo }}" target="_blank"
                                       class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-bold text-lg shadow-lg hover:shadow-xl">
                                        <i data-lucide="external-link" class="w-6 h-6"></i>
                                        Acessar Conteúdo
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Descrição Abaixo do Player -->
                    @if($material->descricao)
                        <div class="p-6 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-3 flex items-center gap-2">
                                <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                                Sobre este material
                            </h3>
                            <p class="text-[var(--cor-texto-muted)] whitespace-pre-line leading-relaxed">{{ $material->descricao }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar - 1/3 -->
            <div class="lg:col-span-1">
                <!-- Card de Informações -->
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6 sticky top-6">
                    
                    <!-- Título -->
                    <h1 class="text-xl font-bold text-[var(--cor-texto)] mb-4">{{ $material->titulo }}</h1>
                    
                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                            {{ ucfirst(str_replace('_', ' ', $material->categoria)) }}
                        </span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium capitalize flex items-center gap-1">
                            @if($material->tipo === 'video')
                                <i data-lucide="video" class="w-3 h-3"></i>
                            @elseif($material->tipo === 'arquivo')
                                <i data-lucide="file-text" class="w-3 h-3"></i>
                            @else
                                <i data-lucide="link" class="w-3 h-3"></i>
                            @endif
                            {{ $material->tipo }}
                        </span>
                        @if($material->apenas_vip)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold flex items-center gap-1">
                                <i data-lucide="crown" class="w-3 h-3"></i>
                                VIP
                            </span>
                        @endif
                    </div>

                    <!-- Stats -->
                    <div class="space-y-3 mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-[var(--cor-texto-muted)]">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                                Visualizações
                            </span>
                            <span class="font-bold text-[var(--cor-texto)]">{{ $material->views_count }}</span>
                        </div>
                        @if($material->tipo === 'arquivo')
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-[var(--cor-texto-muted)]">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                    Downloads
                                </span>
                                <span class="font-bold text-[var(--cor-texto)]">{{ $material->downloads_count }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-[var(--cor-texto-muted)]">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                Publicado em
                            </span>
                            <span class="font-bold text-[var(--cor-texto)]">{{ $material->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <!-- Botão de Ação Principal -->
                    <div class="space-y-3">
                        @if($material->tipo === 'video' && $material->video_url)
                            <a href="{{ $material->video_url }}" target="_blank" 
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all font-bold shadow-sm hover:shadow-md">
                                <i data-lucide="youtube" class="w-5 h-5"></i>
                                Abrir no YouTube
                            </a>
                        @elseif($material->tipo === 'arquivo' && $material->arquivo_path)
                            <a href="{{ route('materiais.download', $material->id) }}" 
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-bold shadow-sm hover:shadow-md">
                                <i data-lucide="download" class="w-5 h-5"></i>
                                Baixar Documento
                            </a>
                        @elseif($material->tipo === 'link' && $material->link_externo)
                            <a href="{{ $material->link_externo }}" target="_blank" 
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-bold shadow-sm hover:shadow-md">
                                <i data-lucide="external-link" class="w-5 h-5"></i>
                                Acessar Link
                            </a>
                        @endif

                        <!-- Botão Favoritar -->
                        <button onclick="toggleFavorito({{ $material->id }}, this)" 
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-lg border-2 transition-all font-medium {{ $isFavorito ? 'bg-pink-50 border-pink-400 hover:bg-pink-100 text-pink-700' : 'bg-white border-gray-300 hover:bg-pink-50 hover:border-pink-300 text-gray-700' }}"
                                data-favorito="{{ $isFavorito ? 'true' : 'false' }}"
                                title="{{ $isFavorito ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}">
                            <i data-lucide="heart" class="w-5 h-5 {{ $isFavorito ? 'fill-pink-500 stroke-pink-500' : 'stroke-current' }}"></i>
                            <span>{{ $isFavorito ? 'Favoritado' : 'Adicionar aos Favoritos' }}</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@push('scripts')
<script>
function toggleFavorito(materialId, button) {
    const icon = button.querySelector('i');
    const texto = button.querySelector('span');
    const eraFavorito = button.dataset.favorito === 'true';
    
    // Feedback visual IMEDIATO
    icon.classList.add('animate-pulse');
    button.style.transform = 'scale(0.95)';
    setTimeout(() => { button.style.transform = ''; }, 150);
    
    if (eraFavorito) {
        icon.classList.remove('fill-pink-500', 'stroke-pink-500');
        icon.classList.add('stroke-current');
        button.classList.remove('bg-pink-50', 'border-pink-400', 'text-pink-700');
        button.classList.add('bg-white', 'border-gray-300', 'text-gray-700');
        texto.textContent = 'Adicionar aos Favoritos';
    } else {
        icon.classList.remove('stroke-current');
        icon.classList.add('fill-pink-500', 'stroke-pink-500');
        button.classList.remove('bg-white', 'border-gray-300', 'text-gray-700');
        button.classList.add('bg-pink-50', 'border-pink-400', 'text-pink-700');
        texto.textContent = 'Favoritado';
    }
    
    fetch(`/materiais/${materialId}/favoritar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        icon.classList.remove('animate-pulse');
        button.dataset.favorito = data.favorito ? 'true' : 'false';
        
        if (!data.success) {
            // Reverter em caso de erro
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        icon.classList.remove('animate-pulse');
        location.reload();
    });
}
</script>
@endpush
@endsection
