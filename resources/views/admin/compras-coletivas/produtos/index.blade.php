@extends('layouts.dashboard')

@section('titulo', 'Catálogo de Produtos')
@section('page-title', 'Catálogo de Produtos')
@section('page-subtitle', 'Gerenciar produtos disponíveis para compras coletivas')

@section('header-actions')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.compras-coletivas.produtos.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors font-medium" style="background-color: #2D5F3F; color: #ffffff;">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Novo Produto
    </a>
    <a href="{{ route('admin.compras-coletivas.index') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-[#2D5F3F] transition-colors font-medium">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Voltar
    </a>
</div>
@endsection

@section('mobile-header-actions')
<a href="{{ route('admin.compras-coletivas.index') }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Voltar">
    <i data-lucide="arrow-left" class="w-5 h-5 text-[var(--cor-texto)]"></i>
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 mb-6">
            <form method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                    
                    <!-- Busca -->
                    <div class="md:col-span-2">
                        <input type="text" name="busca" value="{{ request('busca') }}" 
                               placeholder="Buscar produto..."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent text-sm">
                    </div>

                    <!-- Categoria -->
                    <div>
                        <select name="categoria_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] text-sm">
                            <option value="">Categoria</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <select name="ativo" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] text-sm">
                            <option value="">Status</option>
                            <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Filtrar</span>
                        </button>
                        <a href="{{ route('admin.compras-coletivas.produtos.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm">
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
                <strong>{{ $produtos->total() }}</strong> produto(s) encontrado(s)
            </p>
        </div>

        <!-- Grid de Produtos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @forelse($produtos as $produto)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] hover:shadow-md transition-shadow overflow-hidden">
                    <!-- Imagem -->
                    <div class="relative w-full h-40 bg-gray-100">
                        @if($produto->imagem_url)
                            <img src="{{ asset('storage/' . $produto->imagem_url) }}" alt="{{ $produto->nome }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="package" class="w-12 h-12 text-gray-400"></i>
                            </div>
                        @endif
                        
                        <!-- Badge Status -->
                        <span class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-bold
                            {{ $produto->ativo ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                            {{ $produto->ativo ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>

                    <!-- Conteúdo -->
                    <div class="p-4">
                        <h3 class="text-base font-bold text-[var(--cor-texto)] mb-2 line-clamp-2">
                            {{ $produto->nome }}
                        </h3>
                        
                        @if($produto->descricao)
                            <p class="text-sm text-[var(--cor-texto-muted)] mb-3 line-clamp-2">{{ $produto->descricao }}</p>
                        @endif

                        <div class="flex items-center gap-2 mb-3 text-sm">
                            <i data-lucide="ruler" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                            <span class="text-[var(--cor-texto-muted)]">{{ $produto->unidade_medida }}</span>
                        </div>

                        @if($produto->categoria)
                            <div class="flex items-center gap-2 mb-4 text-sm">
                                <i data-lucide="tag" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                                <span class="text-[var(--cor-texto-muted)]">{{ $produto->categoria->nome }}</span>
                            </div>
                        @endif

                        <!-- Ações -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.compras-coletivas.produtos.edit', $produto->id) }}" 
                               class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                                <span class="whitespace-nowrap">Editar</span>
                            </a>
                            <form action="{{ route('admin.compras-coletivas.produtos.destroy', $produto->id) }}" method="POST" onsubmit="return confirm('Confirma exclusão?')" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-1 px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                    <i data-lucide="trash-2" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Excluir</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                    <p class="text-yellow-800 font-medium">Nenhum produto encontrado</p>
                    <p class="text-yellow-700 text-sm mt-1">Adicione produtos ao catálogo para criar compras coletivas</p>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($produtos->hasPages())
            <div class="mt-6">
                {{ $produtos->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
