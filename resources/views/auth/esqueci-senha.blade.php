@extends('layouts.app')

@section('titulo', 'Esqueci minha senha')

@section('conteudo')
<div class="min-h-screen flex items-center justify-center bg-[var(--cor-verde-serra)] px-4 py-12">
    <div class="max-w-md w-full">
        <!-- Logo e título -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl mb-4 shadow-lg">
                <img src="{{ asset('images/fiveicon-360.svg') }}" alt="Serra Food 360" class="w-10 h-10">
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Esqueceu sua senha?</h1>
            <p class="text-white/80">Sem problemas! Enviaremos um link de recuperação</p>
        </div>

        <!-- Card do formulário -->
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            
            @if(session('sucesso'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-3 flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-sm text-green-800">{{ session('sucesso') }}</p>
                            <p class="text-xs text-green-600 mt-2">Verifique sua caixa de entrada e spam.</p>
                        </div>
                    </div>
                </div>
            @endif

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

            <!-- Instruções -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5"></i>
                    <p class="text-sm text-blue-800">
                        Digite seu email cadastrado e enviaremos um link para redefinir sua senha.
                    </p>
                </div>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent transition-all"
                            placeholder="seu@email.com"
                        >
                    </div>
                </div>

                <!-- Botão de enviar -->
                <button 
                    type="submit"
                    class="w-full bg-[var(--cor-verde-serra)] text-white py-3 rounded-lg font-semibold hover:bg-[var(--cor-verde-escuro)] transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
                >
                    Enviar Link de Recuperação
                </button>
            </form>

            <!-- Divisor -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">Lembrou sua senha?</span>
                </div>
            </div>

            <!-- Link para login -->
            <a 
                href="{{ route('login') }}"
                class="block w-full text-center py-3 border-2 border-[var(--cor-verde-serra)] text-[var(--cor-verde-serra)] rounded-lg font-semibold hover:bg-[var(--cor-verde-serra)] hover:text-white transition-all"
            >
                Voltar para o login
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
