@extends('layouts.dashboard')

@section('titulo', 'Meu Perfil')
@section('page-title', 'Meu Perfil')
@section('page-subtitle', 'Visualize e edite suas informações')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-4xl mx-auto">

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-sm text-red-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                {{ session('sucesso') }}
            </div>
        @endif

        <form method="POST" action="{{ route('perfil.atualizar') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Dados Pessoais -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-[var(--cor-verde-serra)]"></i>
                    Dados Pessoais
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Nome Completo *</label>
                        <input type="text" name="name" value="{{ old('name', $perfil->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $perfil->email) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Telefone</label>
                        <input type="tel" name="telefone" value="{{ old('telefone', $dados['telefone']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">WhatsApp</label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp', $dados['whatsapp']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Cidade</label>
                        <input type="text" name="cidade" value="{{ old('cidade', $dados['cidade']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Estado</label>
                        <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                            <option value="ES" {{ old('estado', $dados['estado'] ?? 'ES') === 'ES' ? 'selected' : '' }}>ES - Espírito Santo</option>
                            <option value="MG" {{ old('estado', $dados['estado'] ?? '') === 'MG' ? 'selected' : '' }}>MG - Minas Gerais</option>
                            <option value="RJ" {{ old('estado', $dados['estado'] ?? '') === 'RJ' ? 'selected' : '' }}>RJ - Rio de Janeiro</option>
                            <option value="SP" {{ old('estado', $dados['estado'] ?? '') === 'SP' ? 'selected' : '' }}>SP - São Paulo</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Dados do Negócio -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-blue-600"></i>
                    Dados do Negócio
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Nome do Estabelecimento</label>
                        <input type="text" name="nome_negocio" value="{{ old('nome_negocio', $perfil->comprador?->nome_negocio ?? $perfil->fornecedor?->nome_negocio) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">CNPJ</label>
                        <input type="text" name="cnpj" value="{{ old('cnpj', $perfil->comprador?->cnpj ?? $perfil->fornecedor?->cnpj) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Tipo de Negócio</label>
                        <input type="text" name="tipo_negocio" value="{{ old('tipo_negocio', $perfil->comprador?->tipo_negocio ?? $perfil->fornecedor?->tipo_negocio) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Colaboradores</label>
                        <input type="number" name="colaboradores" value="{{ old('colaboradores', $perfil->comprador?->colaboradores ?? $perfil->fornecedor?->colaboradores) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Site</label>
                        <input type="url" name="site_url" value="{{ old('site_url', $perfil->comprador?->site_url ?? $perfil->fornecedor?->site_url) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Descrição</label>
                        <textarea name="descricao" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('descricao', $perfil->comprador?->descricao ?? $perfil->fornecedor?->descricao) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Segmentos -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="tag" class="w-5 h-5 text-purple-600"></i>
                    Segmentos de Interesse
                </h3>
                <div class="grid md:grid-cols-3 gap-3">
                    @foreach($segmentos as $segmento)
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-[var(--cor-verde-serra)] hover:bg-green-50 transition-colors cursor-pointer">
                            <input type="checkbox" name="segmentos[]" value="{{ $segmento->id }}"
                                   {{ in_array($segmento->id, old('segmentos', $segmentosIds)) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded accent-[var(--cor-verde-serra)]">
                            <span class="text-sm font-medium text-[var(--cor-texto)]">
                                {{ $segmento->nome }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base">
                    <i data-lucide="save" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Salvar Alterações</span>
                </button>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                    <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cancelar</span>
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
