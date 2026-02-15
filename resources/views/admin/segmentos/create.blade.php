@extends('layouts.dashboard')

@section('titulo', 'Novo Segmento')
@section('page-title', 'Criar Novo Segmento')
@section('page-subtitle', 'Adicione um novo segmento de atuação')

@section('header-actions')
<a href="{{ route('admin.segmentos.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6 lg:p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-red-800 mb-1">Corrija os erros abaixo:</h3>
                            <ul class="text-sm text-red-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.segmentos.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nome e Slug -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome *
                        </label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            value="{{ old('nome') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                            placeholder="Ex: Alimentação"
                        >
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                            Slug *
                        </label>
                        <input 
                            type="text" 
                            id="slug" 
                            name="slug" 
                            value="{{ old('slug') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                            placeholder="Ex: alimentacao"
                        >
                        <p class="text-xs text-gray-500 mt-1">Apenas letras minúsculas, números e hífens</p>
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição
                    </label>
                    <textarea 
                        id="descricao" 
                        name="descricao" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                        placeholder="Descreva o segmento..."
                    >{{ old('descricao') }}</textarea>
                </div>

                <!-- Ícone e Cor -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="icone" class="block text-sm font-medium text-gray-700 mb-2">
                            Ícone (Emoji) *
                        </label>
                        <input 
                            type="text" 
                            id="icone" 
                            name="icone" 
                            value="{{ old('icone') }}"
                            required
                            maxlength="10"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent text-3xl text-center"
                            placeholder="🍽️"
                        >
                        <p class="text-xs text-gray-500 mt-1">Copie e cole um emoji</p>
                    </div>

                    <div>
                        <label for="cor" class="block text-sm font-medium text-gray-700 mb-2">
                            Cor (HEX) *
                        </label>
                        <div class="flex gap-2">
                            <input 
                                type="color" 
                                id="cor_picker" 
                                value="{{ old('cor', '#10b981') }}"
                                class="w-16 h-12 border border-gray-300 rounded-lg cursor-pointer"
                                onchange="document.getElementById('cor').value = this.value"
                            >
                            <input 
                                type="text" 
                                id="cor" 
                                name="cor" 
                                value="{{ old('cor', '#10b981') }}"
                                required
                                maxlength="7"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                                placeholder="#10b981"
                                oninput="document.getElementById('cor_picker').value = this.value"
                            >
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Cor usada nos badges e destaques</p>
                    </div>
                </div>

                <!-- Status Ativo -->
                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <input 
                        type="checkbox" 
                        id="ativo" 
                        name="ativo" 
                        value="1"
                        {{ old('ativo', true) ? 'checked' : '' }}
                        class="w-5 h-5 text-[var(--cor-verde-serra)] border-gray-300 rounded focus:ring-[var(--cor-verde-serra)]"
                    >
                    <label for="ativo" class="text-sm font-medium text-gray-700">
                        Segmento ativo (aparece nos formulários de cadastro)
                    </label>
                </div>

                <!-- Preview -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-blue-900 mb-3">Preview do Badge:</h4>
                    <div class="flex items-center gap-2">
                        <span 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border-2"
                            x-data="{ 
                                icone: '{{ old('icone', '🍽️') }}',
                                nome: '{{ old('nome', 'Alimentação') }}',
                                cor: '{{ old('cor', '#10b981') }}'
                            }"
                            x-init="
                                document.getElementById('icone').addEventListener('input', (e) => icone = e.target.value);
                                document.getElementById('nome').addEventListener('input', (e) => nome = e.target.value);
                                document.getElementById('cor').addEventListener('input', (e) => cor = e.target.value);
                                document.getElementById('cor_picker').addEventListener('input', (e) => cor = e.target.value);
                            "
                            :style="`background-color: ${cor}10; border-color: ${cor}30; color: ${cor}`">
                            <span class="text-lg" x-text="icone"></span>
                            <span x-text="nome"></span>
                        </span>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-semibold hover:opacity-90 transition-all">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Criar Segmento
                    </button>
                    <a href="{{ route('admin.segmentos.index') }}" class="flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
