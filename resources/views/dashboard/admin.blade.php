@extends('layouts.app')

@section('titulo', 'Dashboard Admin')

@section('conteudo')
<div class="min-h-screen bg-[var(--cor-fundo)] py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-[var(--cor-texto)] mb-4">Dashboard Admin</h1>
        <p class="text-[var(--cor-texto-secundario)]">Bem-vindo, {{ auth()->user()->name }}</p>
        
        <div class="mt-8">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-semibold hover:opacity-90 transition-all">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Ir para Painel Admin
            </a>
        </div>
    </div>
</div>
@endsection
