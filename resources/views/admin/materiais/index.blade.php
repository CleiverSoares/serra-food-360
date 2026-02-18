@extends('layouts.dashboard')

@section('titulo', 'Material de Gestão')
@section('page-title', 'Material de Gestão')
@section('page-subtitle', 'Gerenciar vídeos, planilhas e guias')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        @if(session('erro'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800">{{ session('erro') }}</p>
            </div>
        @endif

        <!-- Botão Criar Novo -->
        <div class="mb-6">
            <a href="{{ route('admin.materiais.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium shadow-sm hover:shadow-md">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Novo Material</span>
            </a>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 mb-6">
            <form method="GET" action="{{ route('admin.materiais.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3">
                    
                    <!-- Busca -->
                    <div class="md:col-span-2">
                        <input 
                            type="text" 
                            name="busca" 
                            value="{{ request('busca') }}"
                            placeholder="Buscar por título ou descrição..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent text-sm"
                        >
                    </div>

                    <!-- Categoria -->
                    <div>
                        <select name="categoria" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] text-sm">
                            <option value="">Categoria</option>
                            <option value="financeiro" {{ request('categoria') === 'financeiro' ? 'selected' : '' }}>Financeiro</option>
                            <option value="cozinha" {{ request('categoria') === 'cozinha' ? 'selected' : '' }}>Cozinha</option>
                            <option value="legislacao" {{ request('categoria') === 'legislacao' ? 'selected' : '' }}>Legislação</option>
                            <option value="gestao_equipe" {{ request('categoria') === 'gestao_equipe' ? 'selected' : '' }}>Gestão de Equipe</option>
                            <option value="marketing" {{ request('categoria') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="atendimento" {{ request('categoria') === 'atendimento' ? 'selected' : '' }}>Atendimento</option>
                        </select>
                    </div>

                    <!-- Tipo -->
                    <div>
                        <select name="tipo" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] text-sm">
                            <option value="">Tipo</option>
                            <option value="video" {{ request('tipo') === 'video' ? 'selected' : '' }}>Vídeo</option>
                            <option value="arquivo" {{ request('tipo') === 'arquivo' ? 'selected' : '' }}>Arquivo</option>
                            <option value="link" {{ request('tipo') === 'link' ? 'selected' : '' }}>Link</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <select name="ativo" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] text-sm">
                            <option value="">Status</option>
                            <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativos</option>
                            <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativos</option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Filtrar</span>
                        </button>
                        <a href="{{ route('admin.materiais.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span>Limpar</span>
                        </a>
                    </div>
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
        <div class="grid grid-cols-1 gap-4">
            @forelse($materiais as $material)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 hover:shadow-md transition-shadow overflow-hidden">
                    <div class="flex items-start gap-3 md:gap-4 min-w-0">
                        
                        <!-- Thumbnail -->
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden bg-gray-100 relative">
                                @if($material->thumbnail_url)
                                    <!-- Thumbnail customizado tem prioridade -->
                                    <img src="{{ asset('storage/' . $material->thumbnail_url) }}" 
                                         alt="{{ $material->titulo }}" 
                                         class="w-full h-full object-cover">
                                    @if($material->tipo === 'video')
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-20">
                                            <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                                                <i data-lucide="play" class="w-4 h-4 text-white fill-white ml-0.5"></i>
                                            </div>
                                        </div>
                                    @endif
                                @elseif($material->tipo === 'video' && $material->video_url)
                                    <!-- Preview do YouTube se não tiver thumbnail -->
                                    @php
                                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $material->video_url, $matches);
                                        $youtubeId = $matches[1] ?? null;
                                    @endphp
                                    @if($youtubeId)
                                        <img src="https://img.youtube.com/vi/{{ $youtubeId }}/maxresdefault.jpg" 
                                             alt="{{ $material->titulo }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.src='https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg'">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-20">
                                            <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                                                <i data-lucide="play" class="w-4 h-4 text-white fill-white ml-0.5"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-lucide="video" class="w-8 h-8 text-gray-400"></i>
                                        </div>
                                    @endif
                                @else
                                    <!-- Fallback para outros tipos -->
                                    <div class="w-full h-full flex items-center justify-center">
                                        @if($material->tipo === 'arquivo')
                                            <i data-lucide="file-text" class="w-8 h-8 text-gray-400"></i>
                                        @elseif($material->tipo === 'link')
                                            <i data-lucide="link" class="w-8 h-8 text-gray-400"></i>
                                        @else
                                            <i data-lucide="file" class="w-8 h-8 text-gray-400"></i>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-start justify-between gap-2 md:gap-4 mb-2">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base md:text-lg font-bold text-[var(--cor-texto)] truncate">
                                        {{ $material->titulo }}
                                    </h3>
                                    @if($material->descricao)
                                        <p class="text-sm text-[var(--cor-texto-muted)] line-clamp-2">
                                            {{ $material->descricao }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Status Badges -->
                                <div class="flex flex-col gap-1">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                        {{ $material->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $material->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                    @if($material->apenas_vip)
                                        <span class="px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap bg-yellow-100 text-yellow-800">
                                            <i data-lucide="crown" class="w-3 h-3 inline"></i>
                                            VIP
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Detalhes -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm mb-3">
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="folder" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate capitalize">{{ str_replace('_', ' ', $material->categoria) }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="tag" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate capitalize">{{ $material->tipo }}</span>
                                </div>
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="eye" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $material->views_count }} views</span>
                                </div>
                                @if($material->tipo === 'arquivo')
                                    <div class="flex items-center gap-1 min-w-0">
                                        <i data-lucide="download" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                        <span class="text-[var(--cor-texto-muted)] truncate">{{ $material->downloads_count }} downloads</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('admin.materiais.edit', $material->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                    <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Editar</span>
                                </a>
                                
                                <a href="{{ route('materiais.show', $material->id) }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i data-lucide="eye" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Visualizar</span>
                                </a>
                                
                                <form action="{{ route('admin.materiais.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Confirma exclusão?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                        <i data-lucide="trash-2" class="w-4 h-4 flex-shrink-0"></i>
                                        <span class="whitespace-nowrap">Excluir</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                    <p class="text-yellow-800 font-medium">Nenhum material encontrado</p>
                    <p class="text-yellow-700 text-sm mt-1">Ajuste os filtros ou crie um novo material</p>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($materiais->hasPages())
            <div class="mt-6">
                {{ $materiais->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
