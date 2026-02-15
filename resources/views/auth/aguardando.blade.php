@extends('layouts.app')

@section('titulo', 'Aguardando Aprovação')

@section('conteudo')
<div class="min-h-screen flex items-center justify-center bg-[var(--cor-verde-serra)] px-4 py-12">
    <div class="max-w-lg w-full">
        <!-- Card principal -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Ícone de relógio animado -->
            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-6 relative">
                <i data-lucide="clock" class="w-10 h-10 text-yellow-600 animate-pulse"></i>
                <div class="absolute inset-0 border-4 border-yellow-200 rounded-full animate-ping opacity-20"></div>
            </div>

            <!-- Título e mensagem -->
            <h1 class="text-2xl font-bold text-gray-900 mb-3">
                Cadastro Recebido!
            </h1>
            <p class="text-gray-600 mb-6">
                Sua conta está sendo analisada por nossa equipe. Isso pode levar até 24 horas.
            </p>

            <!-- Card de informações -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 text-left">
                <div class="flex items-start mb-4">
                    <i data-lucide="mail" class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <strong>Email de confirmação</strong><br>
                        Você receberá um email quando sua conta for aprovada.
                    </div>
                </div>
                <div class="flex items-start">
                    <i data-lucide="user-check" class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5"></i>
                    <div class="text-sm text-blue-800">
                        <strong>Seus dados</strong><br>
                        Nome: <strong>{{ auth()->user()->name }}</strong><br>
                        Email: <strong>{{ auth()->user()->email }}</strong><br>
                        Perfil: <strong class="capitalize">{{ auth()->user()->role }}</strong>
                    </div>
                </div>
            </div>

            <!-- O que acontece agora -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <i data-lucide="list-checks" class="w-5 h-5 text-[var(--cor-verde-serra)] mr-2"></i>
                    O que acontece agora?
                </h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-[var(--cor-verde-serra)] text-white rounded-full text-xs font-bold mr-3 flex-shrink-0">1</span>
                        <span>Nossa equipe irá verificar seus dados</span>
                    </li>
                    <li class="flex items-start">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-[var(--cor-verde-serra)] text-white rounded-full text-xs font-bold mr-3 flex-shrink-0">2</span>
                        <span>Você receberá um email de aprovação</span>
                    </li>
                    <li class="flex items-start">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-[var(--cor-verde-serra)] text-white rounded-full text-xs font-bold mr-3 flex-shrink-0">3</span>
                        <span>Faça login e comece a usar a plataforma!</span>
                    </li>
                </ul>
            </div>

            <!-- Botões de ação -->
            <div class="space-y-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button 
                        type="submit"
                        class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition-all"
                    >
                        Sair
                    </button>
                </form>

                <a 
                    href="{{ route('home') }}"
                    class="block w-full text-center py-3 border-2 border-[var(--cor-verde-serra)] text-[var(--cor-verde-serra)] rounded-lg font-semibold hover:bg-[var(--cor-verde-serra)] hover:text-white transition-all"
                >
                    Voltar para o início
                </a>
            </div>

            <!-- Precisa de ajuda -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-3">Precisa de ajuda?</p>
                <a 
                    href="https://wa.me/5527999999999?text=Olá! Estou aguardando aprovação do meu cadastro no Serra Food 360" 
                    target="_blank"
                    class="inline-flex items-center text-sm text-[var(--cor-verde-serra)] hover:text-[var(--cor-verde-escuro)] font-medium transition-colors"
                >
                    <i data-lucide="message-circle" class="w-4 h-4 mr-2"></i>
                    Fale conosco no WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
