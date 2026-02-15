@extends('layouts.dashboard')

@section('titulo', 'Talentos')
@section('page-title', 'Banco de Talentos')
@section('page-subtitle', auth()->user()->role === 'admin' ? 'Gerenciar profissionais cadastrados' : 'Encontre profissionais')

@if(auth()->user()->role === 'admin')
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
@endif

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
                <div class="bg-gray-50 rounded-lg p-4 md:p-6 border border-gray-200"
                     x-data="{
                         min: {{ $valorMin ?: 0 }},
                         max: {{ $valorMax ?: 500 }},
                         absoluteMin: 0,
                         absoluteMax: 500,
                         formatCurrency(value) {
                             return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',');
                         }
                     }">
                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-4">
                        <i data-lucide="dollar-sign" class="w-4 h-4 inline mr-1"></i>
                        Faixa de Valor
                    </label>
                    
                    <!-- Valores selecionados -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="text-center flex-1">
                            <span class="text-xs text-gray-600 block mb-1">Mínimo</span>
                            <span class="text-lg font-bold text-emerald-600" x-text="formatCurrency(min)"></span>
                        </div>
                        <div class="px-3 text-gray-400">—</div>
                        <div class="text-center flex-1">
                            <span class="text-xs text-gray-600 block mb-1">Máximo</span>
                            <span class="text-lg font-bold text-emerald-600" x-text="formatCurrency(max)"></span>
                        </div>
                    </div>

                    <!-- Range Slider -->
                    <div class="relative h-2 mb-8">
                        <!-- Track de fundo -->
                        <div class="absolute w-full h-2 bg-gray-300 rounded-full"></div>
                        
                        <!-- Track ativo (entre min e max) -->
                        <div class="absolute h-2 bg-emerald-500 rounded-full"
                             :style="`left: ${(min / absoluteMax) * 100}%; right: ${100 - (max / absoluteMax) * 100}%`"></div>
                        
                        <!-- Input Range Mínimo -->
                        <input type="range" 
                               :min="absoluteMin" 
                               :max="absoluteMax" 
                               x-model.number="min"
                               @input="if (min > max - 10) min = max - 10"
                               step="5"
                               class="absolute w-full h-2 appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-emerald-500 [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:hover:scale-110 [&::-webkit-slider-thumb]:transition-transform [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-emerald-500 [&::-moz-range-thumb]:cursor-pointer [&::-moz-range-thumb]:shadow-md [&::-moz-range-thumb]:hover:scale-110 [&::-moz-range-thumb]:transition-transform"
                               style="z-index: 1">
                        
                        <!-- Input Range Máximo -->
                        <input type="range" 
                               :min="absoluteMin" 
                               :max="absoluteMax" 
                               x-model.number="max"
                               @input="if (max < min + 10) max = min + 10"
                               step="5"
                               class="absolute w-full h-2 appearance-none bg-transparent pointer-events-none [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-emerald-500 [&::-webkit-slider-thumb]:cursor-pointer [&::-webkit-slider-thumb]:shadow-md [&::-webkit-slider-thumb]:hover:scale-110 [&::-webkit-slider-thumb]:transition-transform [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:w-5 [&::-moz-range-thumb]:h-5 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:border-2 [&::-moz-range-thumb]:border-emerald-500 [&::-moz-range-thumb]:cursor-pointer [&::-moz-range-thumb]:shadow-md [&::-moz-range-thumb]:hover:scale-110 [&::-moz-range-thumb]:transition-transform"
                               style="z-index: 2">
                    </div>

                    <!-- Marcações de valores -->
                    <div class="flex justify-between text-xs text-gray-500 mb-4">
                        <span>R$ 0</span>
                        <span>R$ 125</span>
                        <span>R$ 250</span>
                        <span>R$ 375</span>
                        <span>R$ 500</span>
                    </div>

                    <!-- Hidden inputs para enviar no formulário -->
                    <input type="hidden" name="valor_min" :value="min">
                    <input type="hidden" name="valor_max" :value="max">
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
                                <a href="{{ auth()->user()->role === 'admin' ? route('admin.talentos.show', $talento->id) : route('talentos.show', $talento->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium">
                                    <i data-lucide="eye" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ver</span>
                                </a>
                                
                                <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $talento->whatsapp) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium">
                                    <i data-lucide="message-circle" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">WhatsApp</span>
                                </a>
                                
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.talentos.edit', $talento->id) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium">
                                        <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                                        <span class="whitespace-nowrap">Editar</span>
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
