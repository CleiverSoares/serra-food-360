@extends('layouts.dashboard')

@section('titulo', 'Aprovações Pendentes')
@section('page-title', 'Aprovações')
@section('page-subtitle', 'Aprovar ou rejeitar novos cadastros')

@section('sidebar-nav')
<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span>Dashboard</span>
</a>
<a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-medium">
    <i data-lucide="user-check" class="w-5 h-5"></i>
    <span>Aprovações</span>
</a>
<a href="{{ route('admin.compradores.index') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
    <span>Compradores</span>
</a>
<a href="{{ route('admin.fornecedores.index') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span>Fornecedores</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span>Talentos</span>
</a>
@endsection

@section('mobile-header-actions')
<a href="{{ route('admin.usuarios.create') }}" class="flex items-center justify-center w-10 h-10 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all">
    <i data-lucide="plus" class="w-5 h-5"></i>
</a>
@endsection

@section('bottom-nav')
<a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Início</span>
</a>
<a href="{{ route('admin.compradores.index') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Compradores</span>
</a>
<a href="{{ route('admin.fornecedores.index') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Fornecedores</span>
</a>
@endsection

@section('header-actions')
<a href="{{ route('admin.usuarios.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all">
    <i data-lucide="user-plus" class="w-4 h-4"></i>
    Novo Usuário
</a>
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
            <a href="{{ route('admin.usuarios.index', ['filtro' => 'compradores']) }}" 
               class="px-4 py-2 rounded-lg font-medium transition-all {{ $filtro === 'compradores' ? 'bg-[var(--cor-verde-serra)] text-white' : 'bg-white text-[var(--cor-texto-secundario)] hover:bg-gray-50' }}">
                Compradores
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
            <div class="space-y-4" x-data="{ expandido: null }">
                @foreach($usuarios as $usuario)
                    <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden">
                        {{-- Header do Card --}}
                        <div class="p-4 flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition-colors" 
                             @click="expandido = expandido === {{ $usuario->id }} ? null : {{ $usuario->id }}">
                            {{-- Foto/Logo --}}
                            <div class="flex-shrink-0">
                                @if($usuario->restaurante && $usuario->restaurante->logo_path)
                                    <img src="{{ asset('storage/' . $usuario->restaurante->logo_path) }}" 
                                         alt="Logo" 
                                         class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200">
                                @elseif($usuario->fornecedor && $usuario->fornecedor->logo_path)
                                    <img src="{{ asset('storage/' . $usuario->fornecedor->logo_path) }}" 
                                         alt="Logo" 
                                         class="w-16 h-16 rounded-lg object-cover border-2 border-gray-200">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-[var(--cor-verde-serra)] flex items-center justify-center text-white text-2xl font-bold">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            {{-- Info Principal --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <h3 class="text-lg font-bold text-[var(--cor-texto)] truncate">{{ $usuario->name }}</h3>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold capitalize
                                        {{ $usuario->role === 'restaurante' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $usuario->role === 'fornecedor' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $usuario->role === 'admin' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ $usuario->role }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                        {{ $usuario->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $usuario->status === 'aprovado' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $usuario->status === 'rejeitado' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $usuario->status === 'inativo' ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ $usuario->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-[var(--cor-texto-secundario)] truncate">{{ $usuario->email }}</p>
                                @if($usuario->restaurante)
                                    <p class="text-sm text-[var(--cor-texto-muted)] truncate">{{ $usuario->restaurante->nome_estabelecimento }}</p>
                                @elseif($usuario->fornecedor)
                                    <p class="text-sm text-[var(--cor-texto-muted)] truncate">{{ $usuario->fornecedor->nome_empresa }}</p>
                                @endif
                            </div>

                            {{-- Ícone Expandir --}}
                            <div class="flex-shrink-0">
                                <i data-lucide="chevron-down" 
                                   class="w-5 h-5 text-gray-400 transition-transform"
                                   :class="{ 'rotate-180': expandido === {{ $usuario->id }} }"></i>
                            </div>
                        </div>

                        {{-- Detalhes Expandidos --}}
                        <div x-show="expandido === {{ $usuario->id }}" 
                             x-collapse
                             class="border-t border-[var(--cor-borda)]">
                            <div class="p-6 space-y-6">
                                {{-- Grid de Informações --}}
                                <div class="grid md:grid-cols-2 gap-6">
                                    {{-- Coluna 1: Informações Pessoais --}}
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center gap-2 mb-4">
                                            <i data-lucide="user" class="w-5 h-5 text-[var(--cor-verde-serra)]"></i>
                                            <h4 class="font-bold text-[var(--cor-texto)]">Informações Pessoais</h4>
                                        </div>
                                        <div class="space-y-3 text-sm">
                                            <div class="flex items-start">
                                                <span class="text-[var(--cor-texto-muted)] w-24 flex-shrink-0">Nome:</span>
                                                <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->name }}</span>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="text-[var(--cor-texto-muted)] w-24 flex-shrink-0">Email:</span>
                                                <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->email }}</span>
                                            </div>
                                            @if($usuario->telefone)
                                                <div class="flex items-start">
                                                    <span class="text-[var(--cor-texto-muted)] w-24 flex-shrink-0">Telefone:</span>
                                                    <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->telefone }}</span>
                                                </div>
                                            @endif
                                            @if($usuario->whatsapp)
                                                <div class="flex items-start">
                                                    <span class="text-[var(--cor-texto-muted)] w-24 flex-shrink-0">WhatsApp:</span>
                                                    <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->whatsapp }}</span>
                                                </div>
                                            @endif
                                            @if($usuario->cidade)
                                                <div class="flex items-start">
                                                    <span class="text-[var(--cor-texto-muted)] w-24 flex-shrink-0">Cidade:</span>
                                                    <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->cidade }}</span>
                                                </div>
                                            @endif
                                            @if($usuario->plano)
                                                <div class="flex items-start">
                                                    <span class="text-[var(--cor-texto-muted)] w-24 flex-shrink-0">Plano:</span>
                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold uppercase">{{ $usuario->plano }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Coluna 2: Informações do Negócio --}}
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center gap-2 mb-4">
                                            <i data-lucide="building-2" class="w-5 h-5 text-[var(--cor-verde-serra)]"></i>
                                            <h4 class="font-bold text-[var(--cor-texto)]">Informações do Negócio</h4>
                                        </div>
                                        <div class="space-y-3 text-sm">
                                            @if($usuario->restaurante)
                                                <div class="flex items-start">
                                                    <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">Estabelecimento:</span>
                                                    <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->restaurante->nome_estabelecimento }}</span>
                                                </div>
                                                @if($usuario->restaurante->cnpj)
                                                    <div class="flex items-start">
                                                        <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">CNPJ:</span>
                                                        <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->restaurante->cnpj }}</span>
                                                    </div>
                                                @endif
                                                @if($usuario->restaurante->tipo_cozinha)
                                                    <div class="flex items-start">
                                                        <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">Tipo Cozinha:</span>
                                                        <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->restaurante->tipo_cozinha }}</span>
                                                    </div>
                                                @endif
                                                @if($usuario->restaurante->capacidade)
                                                    <div class="flex items-start">
                                                        <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">Capacidade:</span>
                                                        <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->restaurante->capacidade }} lugares</span>
                                                    </div>
                                                @endif
                                                @if($usuario->restaurante->colaboradores)
                                                    <div class="flex items-start">
                                                        <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">Colaboradores:</span>
                                                        <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->restaurante->colaboradores }}</span>
                                                    </div>
                                                @endif
                                                @if($usuario->restaurante->site_url)
                                                    <div class="flex items-start">
                                                        <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">Site:</span>
                                                        <a href="{{ $usuario->restaurante->site_url }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $usuario->restaurante->site_url }}</a>
                                                    </div>
                                                @endif
                                            @endif

                                            @if($usuario->fornecedor)
                                                <div class="flex items-start">
                                                    <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">Empresa:</span>
                                                    <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->fornecedor->nome_empresa }}</span>
                                                </div>
                                                @if($usuario->fornecedor->cnpj)
                                                    <div class="flex items-start">
                                                        <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">CNPJ:</span>
                                                        <span class="font-medium text-[var(--cor-texto)]">{{ $usuario->fornecedor->cnpj }}</span>
                                                    </div>
                                                @endif
                                                @if($usuario->fornecedor->site_url)
                                                    <div class="flex items-start">
                                                        <span class="text-[var(--cor-texto-muted)] w-32 flex-shrink-0">Site:</span>
                                                        <a href="{{ $usuario->fornecedor->site_url }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $usuario->fornecedor->site_url }}</a>
                                                    </div>
                                                @endif
                                                @if($usuario->fornecedor->categorias)
                                                    <div>
                                                        <span class="text-[var(--cor-texto-muted)] block mb-2">Categorias:</span>
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($usuario->fornecedor->categorias as $categoria)
                                                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">{{ $categoria }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Descrição (se houver) --}}
                                @if(($usuario->restaurante && $usuario->restaurante->descricao) || ($usuario->fornecedor && $usuario->fornecedor->descricao))
                                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                        <div class="flex items-start gap-2">
                                            <i data-lucide="file-text" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                                            <div>
                                                <h4 class="font-semibold text-blue-900 mb-1">Descrição</h4>
                                                <p class="text-sm text-blue-800">
                                                    {{ $usuario->restaurante?->descricao ?? $usuario->fornecedor?->descricao }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Info de Aprovação --}}
                                @if($usuario->aprovado_em)
                                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                                        <div class="flex items-start gap-2">
                                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
                                            <div class="text-sm">
                                                <p class="text-green-900"><strong>Aprovado em:</strong> {{ $usuario->aprovado_em->format('d/m/Y') }} às {{ $usuario->aprovado_em->format('H:i') }}</p>
                                                @if($usuario->aprovador)
                                                    <p class="text-green-800">Por: {{ $usuario->aprovador->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($usuario->motivo_rejeicao)
                                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                                        <div class="flex items-start gap-2">
                                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
                                            <div class="text-sm">
                                                <h4 class="font-semibold text-red-900 mb-1">Motivo da Rejeição</h4>
                                                <p class="text-red-800">{{ $usuario->motivo_rejeicao }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Ações --}}
                                <div class="flex gap-2 flex-wrap pt-4 border-t border-gray-200">
                                    @if($usuario->status === 'pendente')
                                        <form action="{{ route('admin.usuarios.aprovar', $usuario->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all shadow-sm hover:shadow-md font-medium">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                                Aprovar
                                            </button>
                                        </form>
                                        <button onclick="if(confirm('Tem certeza que deseja rejeitar?')) document.getElementById('rejeitar-{{ $usuario->id }}').submit()" 
                                                class="flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all shadow-sm hover:shadow-md font-medium">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                            Rejeitar
                                        </button>
                                        <form id="rejeitar-{{ $usuario->id }}" action="{{ route('admin.usuarios.rejeitar', $usuario->id) }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                    @endif

                                    @if($usuario->whatsapp)
                                        <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $usuario->whatsapp) }}" 
                                           target="_blank"
                                           class="flex items-center gap-2 px-5 py-2.5 bg-[#25D366] text-white rounded-lg hover:bg-[#20BA5A] transition-all shadow-sm hover:shadow-md font-medium">
                                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                                            WhatsApp
                                        </a>
                                    @endif

                                    <a href="#" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-sm hover:shadow-md font-medium">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                        Editar
                                    </a>

                                    <button onclick="if(confirm('Tem certeza que deseja deletar este usuário?')) document.getElementById('deletar-{{ $usuario->id }}').submit()"
                                            class="flex items-center gap-2 px-5 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all shadow-sm hover:shadow-md font-medium">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        Deletar
                                    </button>
                                    <form id="deletar-{{ $usuario->id }}" action="{{ route('admin.usuarios.deletar', $usuario->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
