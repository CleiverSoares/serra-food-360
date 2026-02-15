@extends('layouts.dashboard')

@section('titulo', 'Talentos')
@section('page-title', 'Banco de Talentos')
@section('page-subtitle', 'Gerenciar profissionais cadastrados')

@section('sidebar-nav')
<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span>Dashboard</span>
</a>
<a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
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
<a href="{{ route('admin.talentos.index') }}" class="flex items-center gap-3 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-medium">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span>Talentos</span>
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
<a href="{{ route('admin.talentos.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all">
    <i data-lucide="plus" class="w-4 h-4"></i>
    Adicionar Talento
</a>
@endsection

@section('mobile-header-actions')
<a href="{{ route('admin.talentos.create') }}" class="flex items-center justify-center w-10 h-10 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all">
    <i data-lucide="plus" class="w-5 h-5"></i>
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

        @if(session('erro'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800">{{ session('erro') }}</p>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 mb-6">
            <form method="GET" action="{{ route('admin.talentos.index') }}" class="space-y-4">
                
                <!-- Busca por nome -->
                <div>
                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">
                        <i data-lucide="search" class="w-4 h-4 inline mr-1"></i>
                        Buscar por nome, cargo ou telefone
                    </label>
                    <input 
                        type="text" 
                        name="busca" 
                        value="{{ $busca }}"
                        placeholder="Digite para buscar..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                    >
                </div>

                <!-- Filtros em linha -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Cargo</label>
                        <select name="cargo" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Todos os cargos</option>
                            @foreach($cargos as $c)
                                <option value="{{ $c }}" {{ $cargo == $c ? 'selected' : '' }}>
                                    {{ $c }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Disponibilidade</label>
                        <select name="disponibilidade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Todas</option>
                            @foreach($disponibilidades as $d)
                                <option value="{{ $d }}" {{ $disponibilidade == $d ? 'selected' : '' }}>
                                    {{ $d }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Tipo de Cobrança</label>
                        <select name="tipo_cobranca" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Todos</option>
                            <option value="hora" {{ $tipoCobranca === 'hora' ? 'selected' : '' }}>Por Hora</option>
                            <option value="dia" {{ $tipoCobranca === 'dia' ? 'selected' : '' }}>Por Dia</option>
                        </select>
                    </div>
                </div>

                <!-- Filtro por Range de Valor -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-3">
                        <i data-lucide="dollar-sign" class="w-4 h-4 inline mr-1"></i>
                        Faixa de Valor (R$)
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Valor Mínimo</label>
                            <input type="number" name="valor_min" value="{{ $valorMin }}" 
                                   placeholder="Ex: 50" step="0.01" min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Valor Máximo</label>
                            <input type="number" name="valor_max" value="{{ $valorMax }}" 
                                   placeholder="Ex: 200" step="0.01" min="0"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm md:text-base">
                        <i data-lucide="filter" class="w-4 h-4 flex-shrink-0"></i>
                        <span class="whitespace-nowrap">Filtrar</span>
                    </button>
                    <a href="{{ route('admin.talentos.index') }}" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                        <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                        <span class="whitespace-nowrap">Limpar</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Contador -->
        <div class="mb-4">
            <p class="text-sm text-[var(--cor-texto-muted)]">
                <strong>{{ $talentos->count() }}</strong> talento(s) encontrado(s)
            </p>
        </div>

        <!-- Lista de Talentos -->
        <div class="grid grid-cols-1 gap-4">
            @forelse($talentos as $talento)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6 hover:shadow-md transition-shadow overflow-hidden">
                    <div class="flex items-start gap-3 md:gap-4 min-w-0">
                        
                        <!-- Foto/Avatar -->
                        <div class="flex-shrink-0">
                            @if($talento->foto_path)
                                <img src="{{ asset('storage/' . $talento->foto_path) }}" 
                                     alt="{{ $talento->nome }}"
                                     class="w-12 h-12 md:w-16 md:h-16 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-lg bg-amber-600 text-white flex items-center justify-center text-xl md:text-2xl font-bold">
                                    {{ strtoupper(substr($talento->nome, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <div class="flex items-start justify-between gap-2 md:gap-4 mb-2">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base md:text-lg font-bold text-[var(--cor-texto)] truncate">
                                        {{ $talento->nome }}
                                    </h3>
                                    <p class="text-sm text-amber-700 font-medium truncate">
                                        {{ $talento->cargo }}
                                    </p>
                                </div>

                                <!-- Badges -->
                                <div class="flex flex-wrap gap-1 flex-shrink-0">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                        {{ $talento->ativo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $talento->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                    <span class="px-2 py-1 rounded-full text-xs font-bold whitespace-nowrap
                                        {{ $talento->tipo_cobranca === 'hora' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $talento->tipo_cobranca === 'hora' ? '⏰ Hora' : '📅 Dia' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Detalhes -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm mb-3">
                                <div class="flex items-center gap-1 min-w-0">
                                    <i data-lucide="phone" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                    <span class="text-[var(--cor-texto-muted)] truncate">{{ $talento->whatsapp }}</span>
                                </div>
                                @if($talento->pretensao)
                                    <div class="flex items-center gap-1 min-w-0">
                                        <i data-lucide="dollar-sign" class="w-4 h-4 flex-shrink-0 text-gray-400"></i>
                                        <span class="text-[var(--cor-texto-muted)]">
                                            R$ {{ number_format($talento->pretensao, 2, ',', '.') }}/{{ $talento->tipo_cobranca === 'hora' ? 'hora' : 'dia' }}
                                        </span>
                                    </div>
                                @endif
                                @if($talento->disponibilidade)
                                    <div class="flex items-center gap-1 min-w-0 md:col-span-2">
                                        <i data-lucide="clock" class="w-4 h-4 flex-shrink-0 text-amber-600"></i>
                                        <span class="text-amber-700 font-medium truncate">{{ $talento->disponibilidade }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Mini Currículo (apenas 2 linhas) -->
                            <p class="text-sm text-[var(--cor-texto-muted)] line-clamp-2 mb-3">
                                {{ $talento->mini_curriculo }}
                            </p>

                            <!-- Indicadores de Arquivos -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                @if($talento->curriculo_path)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                        Currículo
                                    </span>
                                @endif
                                @if($talento->carta_recomendacao_path)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-purple-50 text-purple-700 rounded text-xs font-medium">
                                        <i data-lucide="award" class="w-3 h-3"></i>
                                        Recomendação
                                    </span>
                                @endif
                            </div>

                            <!-- Ações -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('admin.talentos.show', $talento->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i data-lucide="eye" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ver</span>
                                </a>
                                <a href="{{ route('admin.talentos.edit', $talento->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                    <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Editar</span>
                                </a>
                                <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $talento->whatsapp) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                    <i data-lucide="message-circle" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">WhatsApp</span>
                                </a>
                                @if($talento->ativo)
                                    <form action="{{ route('admin.talentos.inativar', $talento->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors text-sm font-medium">
                                            <i data-lucide="pause-circle" class="w-4 h-4 flex-shrink-0"></i>
                                            <span class="whitespace-nowrap">Inativar</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.talentos.ativar', $talento->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                            <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                                            <span class="whitespace-nowrap">Ativar</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                    <p class="text-yellow-800 font-medium">Nenhum talento encontrado</p>
                    <p class="text-yellow-700 text-sm mt-1">Ajuste os filtros ou adicione um novo talento</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
