@extends('layouts.dashboard')

@section('titulo', 'Dashboard')
@section('page-title', 'Olá, ' . auth()->user()->name . '!')
@section('page-subtitle', 'Bem-vindo ao seu painel')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Cards de Atalhos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">
            
            {{-- Compradores --}}
            <a href="{{ route('compradores.index') }}" 
               class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all hover:scale-[1.02] group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <i data-lucide="store" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[var(--cor-texto)] group-hover:text-[var(--cor-verde-serra)] transition-colors">
                            Compradores
                        </h3>
                        <p class="text-sm text-[var(--cor-texto-secundario)]">Clientes potenciais</p>
                    </div>
                </div>
            </a>

            {{-- Fornecedores --}}
            <a href="{{ route('fornecedores.index') }}" 
               class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-all hover:scale-[1.02] group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[var(--cor-texto)] group-hover:text-[var(--cor-verde-serra)] transition-colors">
                            Fornecedores
                        </h3>
                        <p class="text-sm text-[var(--cor-texto-secundario)]">Parceiros e concorrentes</p>
                    </div>
                </div>
            </a>

        </div>

        {{-- Em Breve --}}
        <div class="bg-[var(--cor-verde-serra)] rounded-xl shadow-lg p-6 md:p-8 text-white mb-6">
            <h2 class="text-2xl font-bold mb-3">🚀 Novidades em Breve</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>Cotações da Semana</span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="shopping-basket" class="w-5 h-5"></i>
                    <span>Compras Coletivas</span>
                </div>
                <div class="flex items-center gap-3">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    <span>Material de Gestão</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
