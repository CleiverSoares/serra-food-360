@extends('layouts.dashboard')

@section('titulo', 'Novo Usuário')
@section('page-title', 'Criar Novo Usuário')
@section('page-subtitle', 'Cadastrar comprador ou fornecedor')

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
@endsection

@section('header-actions')
<a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-4xl mx-auto">
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

            <form action="{{ route('admin.usuarios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ role: '{{ old('role', 'comprador') }}' }">
                @csrf

                <!-- Tipo de Perfil e Status -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de Perfil *</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all"
                                   :class="role === 'comprador' ? 'border-[var(--cor-verde-serra)] bg-green-50' : 'border-gray-200 hover:border-[var(--cor-verde-serra)]'">
                                <input type="radio" name="role" value="comprador" x-model="role" required class="w-4 h-4 text-[var(--cor-verde-serra)]">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-[var(--cor-verde-serra)] mx-2"></i>
                                <span class="font-medium text-gray-900">Comprador</span>
                            </label>
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all"
                                   :class="role === 'fornecedor' ? 'border-[var(--cor-verde-serra)] bg-green-50' : 'border-gray-200 hover:border-[var(--cor-verde-serra)]'">
                                <input type="radio" name="role" value="fornecedor" x-model="role" required class="w-4 h-4 text-[var(--cor-verde-serra)]">
                                <i data-lucide="package" class="w-5 h-5 text-[var(--cor-verde-serra)] mx-2"></i>
                                <span class="font-medium text-gray-900">Fornecedor</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select id="status" name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                            <option value="pendente" {{ old('status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="aprovado" {{ old('status') === 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                            <option value="rejeitado" {{ old('status') === 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                            <option value="inativo" {{ old('status') === 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                </div>

                <!-- Nome e Nome Estabelecimento -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label for="nome_estabelecimento" class="block text-sm font-medium text-gray-700 mb-2">Nome do Estabelecimento *</label>
                        <input type="text" id="nome_estabelecimento" name="nome_estabelecimento" value="{{ old('nome_estabelecimento') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                </div>

                <!-- CNPJ -->
                <div>
                    <label for="cnpj" class="block text-sm font-medium text-gray-700 mb-2">CNPJ</label>
                    <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                           placeholder="00.000.000/0000-00">
                </div>

                <!-- Email e Cidade -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade *</label>
                        <input type="text" id="cidade" name="cidade" value="{{ old('cidade') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                </div>

                <!-- Telefone e WhatsApp -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">Telefone *</label>
                        <input type="text" id="telefone" name="telefone" value="{{ old('telefone') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                               placeholder="(27) 99999-9999">
                    </div>
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp *</label>
                        <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                               placeholder="(27) 99999-9999">
                    </div>
                </div>

                <!-- Senha -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha *</label>
                        <input type="password" id="password" name="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                               placeholder="Mínimo 6 caracteres">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Senha *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                </div>

                <!-- Segmentos de Atuação -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i data-lucide="tag" class="w-4 h-4 inline mr-1"></i>
                        Segmentos de Atuação * (selecione pelo menos um)
                    </label>
                    <p class="text-sm text-gray-600 mb-3">
                        Defina os segmentos onde este usuário atua. Isso permite cruzamentos inteligentes.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($segmentos as $segmento)
                            <label class="relative flex items-start p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[var(--cor-verde-serra)] hover:bg-green-50 transition-all group">
                                <input 
                                    type="checkbox" 
                                    name="segmentos[]" 
                                    value="{{ $segmento->id }}"
                                    {{ in_array($segmento->id, old('segmentos', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[var(--cor-verde-serra)] border-gray-300 rounded focus:ring-[var(--cor-verde-serra)] mt-1 flex-shrink-0"
                                >
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl">{{ $segmento->icone }}</span>
                                        <span class="font-semibold text-sm text-gray-900">{{ $segmento->nome }}</span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent"
                              placeholder="Conte um pouco sobre o negócio...">{{ old('descricao') }}</textarea>
                </div>

                <!-- Logo -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    <input type="file" id="logo" name="logo" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG ou WEBP. Máximo 2MB.</p>
                </div>

                <!-- Botões -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-semibold hover:opacity-90 transition-all">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Criar Usuário
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
