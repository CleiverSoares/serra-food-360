@extends('layouts.dashboard')

@section('titulo', 'Dashboard Restaurante')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Bem-vindo ao seu restaurante')

@section('sidebar-nav')
<a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-medium">
    <i data-lucide="home" class="w-5 h-5"></i>
    <span>Início</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="utensils" class="w-5 h-5"></i>
    <span>Restaurantes</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span>Fornecedores</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span>Talentos</span>
</a>
@endsection

@section('bottom-nav')
<a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-verde-serra)]">
    <i data-lucide="home" class="w-5 h-5"></i>
    <span class="text-[10px] font-semibold">Início</span>
</a>
<a href="#" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="utensils" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Restaurantes</span>
</a>
<a href="#" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="package" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Fornecedores</span>
</a>
<a href="#" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Talentos</span>
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <p class="text-yellow-800 font-semibold">Dashboard em desenvolvimento...</p>
        </div>
    </div>
</div>
@endsection
