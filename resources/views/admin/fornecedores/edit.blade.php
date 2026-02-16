@extends('layouts.dashboard')

@section('titulo', 'Editar Fornecedor')
@section('page-title', 'Editar Fornecedor')
@section('page-subtitle', $fornecedor->name)

@section('header-actions')
<a href="{{ route('admin.fornecedores.show', $fornecedor->id) }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

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

        <form method="POST" action="{{ route('admin.fornecedores.update', $fornecedor->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Dados Pessoais -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-purple-600"></i>
                    Dados Pessoais
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Nome Completo *</label>
                        <input type="text" name="name" value="{{ old('name', $fornecedor->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $fornecedor->email) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Telefone *</label>
                        <input type="tel" name="telefone" value="{{ old('telefone', $fornecedor->telefone) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">WhatsApp *</label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp', $fornecedor->whatsapp) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Cidade *</label>
                        <input type="text" name="cidade" value="{{ old('cidade', $fornecedor->cidade) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Dados da Empresa -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-blue-600"></i>
                    Dados da Empresa
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Nome da Empresa *</label>
                        <input type="text" name="nome_empresa" value="{{ old('nome_empresa', $fornecedor->fornecedor?->nome_empresa) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">CNPJ</label>
                        <input type="text" name="cnpj" value="{{ old('cnpj', $fornecedor->fornecedor?->cnpj) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Site</label>
                        <input type="url" name="site_url" value="{{ old('site_url', $fornecedor->fornecedor?->site_url) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Descrição</label>
                        <textarea name="descricao" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('descricao', $fornecedor->fornecedor?->descricao) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Logo</label>
                        @if($fornecedor->fornecedor && $fornecedor->fornecedor->logo_path)
                            <img src="{{ asset('storage/' . $fornecedor->fornecedor->logo_path) }}" 
                                 alt="Logo atual"
                                 class="w-20 h-20 rounded-lg object-cover mb-2">
                        @endif
                        <input type="file" name="logo" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Segmentos -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="tags" class="w-5 h-5 text-purple-600"></i>
                    Segmentos de Atuação
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($segmentos as $segmento)
                        <label class="flex items-center gap-2 p-3 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                               style="border-color: {{ $segmento->cor }}40;">
                            <input type="checkbox" name="segmentos[]" value="{{ $segmento->id }}"
                                   {{ in_array($segmento->id, old('segmentos', $fornecedor->segmentos->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded" style="accent-color: {{ $segmento->cor }};">
                            <span class="text-sm font-medium" style="color: {{ $segmento->cor }};">
                                <i data-lucide="{{ $segmento->icone }}" class="w-4 h-4 inline"></i>
                                {{ $segmento->nome }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Gerenciar Assinatura -->
            @php
                $assinatura = $fornecedor->assinaturaAtiva;
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-blue-600"></i>
                    Gerenciar Assinatura
                </h3>
                @if($assinatura)
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 mb-4">
                        <div class="grid md:grid-cols-3 gap-4 text-sm mb-4">
                            <div>
                                <span class="text-gray-600 block mb-1">Plano:</span>
                                <span class="font-bold text-blue-900">{{ ucfirst($assinatura->plano) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 block mb-1">Vencimento:</span>
                                <span class="font-medium text-gray-900">{{ $assinatura->data_fim->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 block mb-1">Status:</span>
                                @if($assinatura->estaAtiva())
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                                        Ativa ({{ $assinatura->diasRestantes() }}d)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-bold">
                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                        Vencida
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="document.getElementById('form-renovar-{{ $assinatura->id }}').submit()" 
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                Renovar Agora
                            </button>
                            <button type="button" onclick="if(confirm('Tem certeza que deseja cancelar esta assinatura?')) document.getElementById('form-cancelar-{{ $assinatura->id }}').submit()" 
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                Cancelar
                            </button>
                        </div>
                        <form id="form-renovar-{{ $assinatura->id }}" action="{{ route('admin.assinaturas.renovar', $assinatura->id) }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="tipo_pagamento" value="{{ $assinatura->tipo_pagamento }}">
                        </form>
                        <form id="form-cancelar-{{ $assinatura->id }}" action="{{ route('admin.assinaturas.cancelar', $assinatura->id) }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800 font-medium mb-4">Este usuário não possui assinatura ativa. Crie uma agora:</p>
                        <form action="{{ route('admin.assinaturas.armazenar', $fornecedor->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid md:grid-cols-2 gap-3">
                                <select name="plano" required class="px-3 py-2 border border-yellow-300 rounded-lg text-sm">
                                    <option value="">Selecione o plano</option>
                                    <option value="comum">Comum (X)</option>
                                    <option value="vip">VIP (2X)</option>
                                </select>
                                <select name="tipo_pagamento" required class="px-3 py-2 border border-yellow-300 rounded-lg text-sm">
                                    <option value="">Tipo de pagamento</option>
                                    <option value="mensal">Mensal (1 mês)</option>
                                    <option value="anual">Anual (12 meses)</option>
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Criar Assinatura
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Botões -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base">
                    <i data-lucide="save" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Salvar Alterações</span>
                </button>
                <a href="{{ route('admin.fornecedores.show', $fornecedor->id) }}" 
                   class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                    <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cancelar</span>
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
