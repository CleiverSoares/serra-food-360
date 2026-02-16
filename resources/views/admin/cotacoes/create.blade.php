@extends('layouts.dashboard')

@section('titulo', 'Nova Cotação')
@section('page-title', 'Nova Cotação')
@section('page-subtitle', 'Crie uma nova cotação para comparação de preços')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-4xl mx-auto">

        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6 lg:p-8">
            
            <form action="{{ route('admin.cotacoes.store') }}" method="POST">
                @csrf

                <!-- Título e Produto -->
                <div class="grid lg:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="produto" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Produto *
                        </label>
                        <input type="text" 
                               id="produto" 
                               name="produto" 
                               value="{{ old('produto') }}"
                               required
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                               placeholder="Ex: Filé Mignon, Óleo de Soja">
                        @error('produto')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="titulo" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Título da Cotação *
                        </label>
                        <input type="text" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo') }}"
                               required
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                               placeholder="Ex: Cotação Semana 15/02">
                        @error('titulo')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descrição -->
                <div class="mb-6">
                    <label for="descricao" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                        Descrição (opcional)
                    </label>
                    <textarea id="descricao" 
                              name="descricao" 
                              rows="3"
                              class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                              placeholder="Informações adicionais sobre a cotação">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unidade, Quantidade, Segmento -->
                <div class="grid lg:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="unidade" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Unidade *
                        </label>
                        <select id="unidade" 
                                name="unidade" 
                                required
                                class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                            <option value="">Selecione</option>
                            <option value="kg" {{ old('unidade') === 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                            <option value="litro" {{ old('unidade') === 'litro' ? 'selected' : '' }}>Litro</option>
                            <option value="unidade" {{ old('unidade') === 'unidade' ? 'selected' : '' }}>Unidade</option>
                            <option value="caixa" {{ old('unidade') === 'caixa' ? 'selected' : '' }}>Caixa</option>
                            <option value="pacote" {{ old('unidade') === 'pacote' ? 'selected' : '' }}>Pacote</option>
                        </select>
                        @error('unidade')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quantidade_minima" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Quantidade Mínima
                        </label>
                        <input type="number" 
                               id="quantidade_minima" 
                               name="quantidade_minima" 
                               value="{{ old('quantidade_minima') }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                               placeholder="Ex: 10">
                        @error('quantidade_minima')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="segmento_id" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Segmento *
                        </label>
                        <select id="segmento_id" 
                                name="segmento_id" 
                                required
                                class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                            <option value="">Selecione</option>
                            @foreach($segmentos as $segmento)
                                <option value="{{ $segmento->id }}" {{ old('segmento_id') == $segmento->id ? 'selected' : '' }}>
                                    {{ $segmento->nome }}
                                </option>
                            @endforeach
                        </select>
                        @error('segmento_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Datas -->
                <div class="grid lg:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="data_inicio" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Data de Início *
                        </label>
                        <input type="date" 
                               id="data_inicio" 
                               name="data_inicio" 
                               value="{{ old('data_inicio', now()->format('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        @error('data_inicio')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="data_fim" class="block text-sm font-semibold text-[var(--cor-texto)] mb-2">
                            Data de Término *
                        </label>
                        <input type="date" 
                               id="data_fim" 
                               name="data_fim" 
                               value="{{ old('data_fim', now()->addDays(7)->format('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-3 border border-[var(--cor-borda)] rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        @error('data_fim')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex flex-col lg:flex-row gap-3">
                    <button type="submit" 
                            class="flex items-center justify-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-semibold hover:bg-[var(--cor-verde-escuro)] transition-all shadow-sm hover:shadow-md">
                        <i data-lucide="check" class="w-5 h-5"></i>
                        <span>Criar Cotação</span>
                    </button>
                    <a href="{{ route('admin.cotacoes.index') }}" 
                       class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                        <i data-lucide="x" class="w-5 h-5"></i>
                        <span>Cancelar</span>
                    </a>
                </div>
            </form>

        </div>

    </div>
</div>
@endsection
