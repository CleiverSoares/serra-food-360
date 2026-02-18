@extends('layouts.dashboard')

@section('titulo', 'Editar Compra Coletiva')
@section('page-title', 'Editar Compra Coletiva')
@section('page-subtitle', $compra->titulo)

@section('header-actions')
<a href="{{ route('admin.compras-coletivas.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-4xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-sm text-red-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.compras-coletivas.update', $compra->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informações Básicas -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    Informações Básicas
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Produto do Catálogo *</label>
                        <select name="produto_catalogo_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                            @foreach($produtos as $produto)
                                <option value="{{ $produto->id }}" {{ $compra->produto_catalogo_id == $produto->id ? 'selected' : '' }}>
                                    {{ $produto->nome }} ({{ $produto->unidade_medida }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Título da Compra *</label>
                        <input type="text" name="titulo" required value="{{ old('titulo', $compra->titulo) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Descrição</label>
                        <textarea name="descricao" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('descricao', $compra->descricao) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Quantidade Mínima *</label>
                        <input type="number" name="quantidade_minima" step="0.01" min="0.01" required value="{{ old('quantidade_minima', $compra->quantidade_minima) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Data de Início *</label>
                            <input type="date" name="data_inicio" required value="{{ old('data_inicio', $compra->data_inicio->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Data de Encerramento *</label>
                            <input type="date" name="data_fim" required value="{{ old('data_fim', $compra->data_fim->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Observações Internas</label>
                        <textarea name="observacoes" rows="2"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('observacoes', $compra->observacoes) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Status *</label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                            <option value="ativa" {{ $compra->status === 'ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="encerrada" {{ $compra->status === 'encerrada' ? 'selected' : '' }}>Encerrada</option>
                            <option value="cancelada" {{ $compra->status === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                    <i data-lucide="bar-chart" class="w-5 h-5"></i>
                    Estatísticas
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-blue-700 font-medium">Quantidade Atual</p>
                        <p class="text-2xl font-bold text-blue-900">{{ number_format($compra->quantidade_atual, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 font-medium">Participantes</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $compra->participantes_count }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 font-medium">Ofertas</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $compra->ofertas_count }}</p>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base flex-1">
                    <i data-lucide="save" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Salvar Alterações</span>
                </button>
                <a href="{{ route('admin.compras-coletivas.index') }}" 
                   class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base flex-1">
                    <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cancelar</span>
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
