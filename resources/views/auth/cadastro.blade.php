@extends('layouts.app')

@section('titulo', 'Cadastro')

@section('conteudo')
<div class="min-h-screen flex items-center justify-center bg-[var(--cor-verde-serra)] px-4 py-12">
    <div class="max-w-2xl w-full">
        <!-- Logo e título -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl mb-4 shadow-lg">
                <img src="{{ asset('images/fiveicon-360.svg') }}" alt="Serra Food 360" class="w-10 h-10">
            </div>
            <h1 class="text-[28px] md:text-3xl font-bold text-white mb-2">Crie sua conta</h1>
            <p class="text-[15px] md:text-base text-white/80">Junte-se à rede gastronômica da Serra Capixaba</p>
        </div>

        <!-- Card do formulário -->
        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8">
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

            <form action="{{ route('cadastro') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ role: '{{ old('role', 'restaurante') }}' }">
                @csrf

                <!-- Tipo de Perfil -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Tipo de Perfil *
                    </label>
                    <div class="grid md:grid-cols-2 gap-4">
                        <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all"
                               :class="role === 'restaurante' ? 'border-[var(--cor-verde-serra)] bg-green-50' : 'border-gray-200 hover:border-[var(--cor-verde-serra)]'">
                            <input 
                                type="radio" 
                                name="role" 
                                value="restaurante" 
                                x-model="role"
                                required
                                class="w-5 h-5 text-[var(--cor-verde-serra)] border-gray-300 focus:ring-[var(--cor-verde-serra)] mt-0.5"
                            >
                            <div class="ml-3">
                                <div class="flex items-center mb-1">
                                    <i data-lucide="utensils" class="w-5 h-5 text-[var(--cor-verde-serra)] mr-2"></i>
                                    <span class="font-semibold text-gray-900">Restaurante</span>
                                </div>
                                <p class="text-sm text-gray-600">Gerencio um estabelecimento gastronômico</p>
                            </div>
                        </label>

                        <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all"
                               :class="role === 'fornecedor' ? 'border-[var(--cor-verde-serra)] bg-green-50' : 'border-gray-200 hover:border-[var(--cor-verde-serra)]'">
                            <input 
                                type="radio" 
                                name="role" 
                                value="fornecedor" 
                                x-model="role"
                                required
                                class="w-5 h-5 text-[var(--cor-verde-serra)] border-gray-300 focus:ring-[var(--cor-verde-serra)] mt-0.5"
                            >
                            <div class="ml-3">
                                <div class="flex items-center mb-1">
                                    <i data-lucide="package" class="w-5 h-5 text-[var(--cor-verde-serra)] mr-2"></i>
                                    <span class="font-semibold text-gray-900">Fornecedor</span>
                                </div>
                                <p class="text-sm text-gray-600">Forneço produtos e insumos</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo *
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                        placeholder="Ex: João Silva"
                    >
                </div>

                <!-- Nome do Estabelecimento -->
                <div>
                    <label for="nome_estabelecimento" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Estabelecimento *
                    </label>
                    <input 
                        type="text" 
                        id="nome_estabelecimento" 
                        name="nome_estabelecimento" 
                        value="{{ old('nome_estabelecimento') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                        placeholder="Ex: Restaurante Pedra Azul"
                    >
                </div>

                <!-- Email, Telefone e WhatsApp -->
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                            placeholder="seu@email.com"
                        >
                    </div>

                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                            Telefone *
                        </label>
                        <input 
                            type="text" 
                            id="telefone" 
                            name="telefone" 
                            value="{{ old('telefone') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                            placeholder="(27) 99999-9999"
                        >
                    </div>

                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                            WhatsApp *
                        </label>
                        <input 
                            type="text" 
                            id="whatsapp" 
                            name="whatsapp" 
                            value="{{ old('whatsapp') }}"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                            placeholder="(27) 99999-9999"
                        >
                    </div>
                </div>

                <!-- Cidade -->
                <div>
                    <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">
                        Cidade *
                    </label>
                    <input 
                        type="text" 
                        id="cidade" 
                        name="cidade" 
                        value="{{ old('cidade') }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                        placeholder="Ex: Domingos Martins"
                    >
                </div>

                <!-- Categorias (apenas para fornecedores) -->
                <div x-show="role === 'fornecedor'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Categorias * (selecione pelo menos uma)
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach(['Bebidas', 'Laticínios', 'Hortifrúti', 'Manutenção', 'Carnes', 'Massas', 'Panificação', 'Descartáveis', 'Equipamentos'] as $categoria)
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[var(--cor-verde-serra)] transition-all">
                                <input 
                                    type="checkbox" 
                                    name="categorias[]" 
                                    value="{{ $categoria }}"
                                    {{ in_array($categoria, old('categorias', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[var(--cor-verde-serra)] border-gray-300 rounded focus:ring-[var(--cor-verde-serra)]"
                                >
                                <span class="ml-2 text-sm text-gray-700">{{ $categoria }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição (opcional)
                    </label>
                    <textarea 
                        id="descricao" 
                        name="descricao" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                        placeholder="Conte um pouco sobre seu negócio..."
                    >{{ old('descricao') }}</textarea>
                </div>

                <!-- Logo -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Logo (opcional)
                    </label>
                    <div class="flex items-center space-x-4">
                        <label class="flex-1 flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-[var(--cor-verde-serra)] transition-all">
                            <i data-lucide="upload" class="w-5 h-5 text-gray-400 mr-2"></i>
                            <span class="text-sm text-gray-600">Clique para fazer upload</span>
                            <input 
                                type="file" 
                                id="logo" 
                                name="logo" 
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="hidden"
                            >
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">JPG, JPEG, PNG ou WEBP. Máximo 2MB.</p>
                </div>

                <!-- Senha -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha *
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                            placeholder="Mínimo 6 caracteres"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Senha *
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                            placeholder="Digite a senha novamente"
                        >
                    </div>
                </div>

                <!-- Informação sobre aprovação -->
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <strong>Seu cadastro será analisado.</strong> Após o envio, nossa equipe irá revisar suas informações. Você receberá um email quando sua conta for aprovada.
                        </div>
                    </div>
                </div>

                <!-- Botão de cadastro -->
                <button 
                    type="submit"
                    class="w-full bg-[var(--cor-verde-serra)] text-white py-3 rounded-lg font-semibold hover:bg-[var(--cor-verde-escuro)] transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
                >
                    Criar Conta
                </button>
            </form>

            <!-- Divisor -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">Já tem uma conta?</span>
                </div>
            </div>

            <!-- Link para login -->
            <a 
                href="{{ route('login') }}"
                class="block w-full text-center py-3 border-2 border-[var(--cor-verde-serra)] text-[var(--cor-verde-serra)] rounded-lg font-semibold hover:bg-[var(--cor-verde-serra)] hover:text-white transition-all"
            >
                Fazer Login
            </a>
        </div>

        <!-- Voltar para home -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-white hover:text-white/80 transition-colors text-sm inline-flex items-center">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Voltar para o início
            </a>
        </div>
    </div>
</div>
@endsection
