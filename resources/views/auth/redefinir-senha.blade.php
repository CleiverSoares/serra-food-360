@extends('layouts.app')

@section('titulo', 'Redefinir senha')

@section('conteudo')
<div class="min-h-screen flex items-center justify-center bg-[var(--cor-verde-serra)] px-4 py-12">
    <div class="max-w-md w-full">
        <!-- Logo e título -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl mb-4 shadow-lg">
                <img src="{{ asset('images/fiveicon-360.svg') }}" alt="Serra Food 360" class="w-10 h-10">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Definir Nova Senha</h1>
            <p class="text-white/80">Escolha uma senha forte e segura</p>
        </div>

        <!-- Card do formulário -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            
            @if(session('erro'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-red-800">{{ session('erro') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <ul class="text-sm text-red-600 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Email (readonly) -->
                <div>
                    <label for="email_display" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email_display" 
                            value="{{ $email }}"
                            readonly
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600"
                        >
                    </div>
                </div>

                <!-- Nova Senha -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Nova Senha
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            autofocus
                            minlength="6"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                            placeholder="••••••••"
                        >
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Mínimo de 6 caracteres</p>
                </div>

                <!-- Confirmar Senha -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirmar Nova Senha
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            minlength="6"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- Info Box -->
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="shield-check" class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Dica de segurança:</p>
                            <ul class="text-xs space-y-1 list-disc list-inside">
                                <li>Use letras maiúsculas e minúsculas</li>
                                <li>Inclua números e caracteres especiais</li>
                                <li>Evite senhas óbvias ou fáceis de adivinhar</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Botão de redefinir -->
                <button 
                    type="submit"
                    class="w-full bg-[var(--cor-verde-serra)] text-white py-3 rounded-lg font-semibold hover:bg-[var(--cor-verde-escuro)] transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
                >
                    Redefinir Senha
                </button>
            </form>

            <!-- Link para login -->
            <div class="mt-6 text-center">
                <a 
                    href="{{ route('login') }}"
                    class="text-sm text-gray-600 hover:text-[var(--cor-verde-serra)] transition-colors inline-flex items-center"
                >
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                    Voltar para o login
                </a>
            </div>
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
