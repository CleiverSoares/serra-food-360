@extends('layouts.dashboard')

@section('titulo', 'Gestão de Usuários')
@section('page-title', 'Gestão de Usuários')
@section('page-subtitle', 'Aprovar e gerenciar cadastros')

@section('sidebar-nav')
<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span>Dashboard</span>
</a>
<a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-medium">
    <i data-lucide="users" class="w-5 h-5"></i>
    <span>Usuários</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span>Talentos</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="settings" class="w-5 h-5"></i>
    <span>Configurações</span>
</a>
@endsection

@section('bottom-nav')
<a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Início</span>
</a>
<a href="{{ route('admin.usuarios.index') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-verde-serra)]">
    <i data-lucide="users" class="w-5 h-5"></i>
    <span class="text-[10px] font-semibold">Usuários</span>
</a>
<a href="#" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Talentos</span>
</a>
<a href="#" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="settings" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Config</span>
</a>
@endsection

@section('header-actions')
<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        @if(session('erro'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800">{{ session('erro') }}</p>
            </div>
        @endif

        <!-- Filtros -->
        <div class="mb-6 flex gap-2 flex-wrap">
            <a href="{{ route('admin.usuarios.index', ['filtro' => 'pendentes']) }}" 
               class="px-4 py-2 rounded-lg font-medium transition-all {{ $filtro === 'pendentes' ? 'bg-[var(--cor-verde-serra)] text-white' : 'bg-white text-[var(--cor-texto-secundario)] hover:bg-gray-50' }}">
                Pendentes
            </a>
            <a href="{{ route('admin.usuarios.index', ['filtro' => 'aprovados']) }}" 
               class="px-4 py-2 rounded-lg font-medium transition-all {{ $filtro === 'aprovados' ? 'bg-[var(--cor-verde-serra)] text-white' : 'bg-white text-[var(--cor-texto-secundario)] hover:bg-gray-50' }}">
                Aprovados
            </a>
            <a href="{{ route('admin.usuarios.index', ['filtro' => 'restaurantes']) }}" 
               class="px-4 py-2 rounded-lg font-medium transition-all {{ $filtro === 'restaurantes' ? 'bg-[var(--cor-verde-serra)] text-white' : 'bg-white text-[var(--cor-texto-secundario)] hover:bg-gray-50' }}">
                Restaurantes
            </a>
            <a href="{{ route('admin.usuarios.index', ['filtro' => 'fornecedores']) }}" 
               class="px-4 py-2 rounded-lg font-medium transition-all {{ $filtro === 'fornecedores' ? 'bg-[var(--cor-verde-serra)] text-white' : 'bg-white text-[var(--cor-texto-secundario)] hover:bg-gray-50' }}">
                Fornecedores
            </a>
        </div>

        <!-- Lista de usuários -->
        @if($usuarios->isEmpty())
            <div class="bg-white rounded-xl p-8 text-center shadow-sm border border-[var(--cor-borda)]">
                <i data-lucide="inbox" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
                <p class="text-[var(--cor-texto-secundario)]">Nenhum usuário encontrado neste filtro.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($usuarios as $usuario)
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-bold text-[var(--cor-texto)]">{{ $usuario->name }}</h3>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold capitalize
                                        {{ $usuario->role === 'restaurante' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $usuario->role === 'fornecedor' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ $usuario->role }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $usuario->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $usuario->status === 'aprovado' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $usuario->status === 'rejeitado' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $usuario->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-[var(--cor-texto-secundario)] mb-1">
                                    <strong>Email:</strong> {{ $usuario->email }}
                                </p>
                                @if($usuario->nome_estabelecimento)
                                    <p class="text-sm text-[var(--cor-texto-secundario)] mb-1">
                                        <strong>Estabelecimento:</strong> {{ $usuario->nome_estabelecimento }}
                                    </p>
                                @endif
                                @if($usuario->cidade)
                                    <p class="text-sm text-[var(--cor-texto-secundario)] mb-1">
                                        <strong>Cidade:</strong> {{ $usuario->cidade }}
                                    </p>
                                @endif
                                @if($usuario->whatsapp)
                                    <p class="text-sm text-[var(--cor-texto-secundario)]">
                                        <strong>WhatsApp:</strong> {{ $usuario->whatsapp }}
                                    </p>
                                @endif
                            </div>

                            @if($usuario->status === 'pendente')
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.usuarios.aprovar', $usuario->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                            Aprovar
                                        </button>
                                    </form>
                                    <button onclick="if(confirm('Tem certeza que deseja rejeitar?')) document.getElementById('rejeitar-{{ $usuario->id }}').submit()" 
                                            class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                        Rejeitar
                                    </button>
                                    <form id="rejeitar-{{ $usuario->id }}" action="{{ route('admin.usuarios.rejeitar', $usuario->id) }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
