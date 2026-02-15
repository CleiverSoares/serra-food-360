@extends('layouts.app')

@section('titulo', 'Dashboard Fornecedor')

@section('conteudo')
<div class="min-h-screen bg-[var(--cor-fundo)] py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-[var(--cor-texto)] mb-4">Dashboard Fornecedor</h1>
        <p class="text-[var(--cor-texto-secundario)]">Bem-vindo, {{ auth()->user()->name }}</p>
        
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <p class="text-yellow-800">Dashboard em desenvolvimento...</p>
        </div>
    </div>
</div>
@endsection
