@extends('layouts.dashboard')

@section('titulo', 'Admin - Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Visão geral do sistema')

@section('sidebar-nav')
<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg font-medium">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span>Dashboard</span>
</a>
<a href="{{ route('admin.usuarios.index') }}" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="users" class="w-5 h-5"></i>
    <span>Usuários</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span>Talentos</span>
</a>
<a href="#" class="flex items-center gap-3 px-4 py-3 text-[var(--cor-texto-secundario)] hover:bg-gray-50 rounded-lg font-medium transition-all">
    <i data-lucide="settings" class="w-5 h-5"></i>
    <span>Configurações</span>
</a>
@endsection

@section('bottom-nav')
<a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-verde-serra)]">
    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
    <span class="text-[10px] font-semibold">Início</span>
</a>
<a href="{{ route('admin.usuarios.index') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="users" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Usuários</span>
</a>
<a href="#" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="briefcase" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Talentos</span>
</a>
<a href="#" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="settings" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Config</span>
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-[var(--cor-texto)]">{{ $estatisticas['pendentes'] }}</span>
                </div>
                <h3 class="text-sm font-medium text-[var(--cor-texto-secundario)]">Usuários Pendentes</h3>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-[var(--cor-texto)]">{{ $estatisticas['aprovados'] }}</span>
                </div>
                <h3 class="text-sm font-medium text-[var(--cor-texto-secundario)]">Usuários Aprovados</h3>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="utensils" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-[var(--cor-texto)]">{{ $estatisticas['restaurantes'] }}</span>
                </div>
                <h3 class="text-sm font-medium text-[var(--cor-texto-secundario)]">Restaurantes</h3>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="package" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <span class="text-2xl font-bold text-[var(--cor-texto)]">{{ $estatisticas['fornecedores'] }}</span>
                </div>
                <h3 class="text-sm font-medium text-[var(--cor-texto-secundario)]">Fornecedores</h3>
            </div>
        </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
        <h2 class="text-xl font-bold text-[var(--cor-texto)] mb-6">Ações Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.usuarios.index', ['filtro' => 'pendentes']) }}" class="flex items-center gap-4 p-4 border-2 border-[var(--cor-borda)] rounded-lg hover:border-[var(--cor-verde-serra)] transition-all group">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i data-lucide="user-check" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[var(--cor-texto)]">Aprovar Usuários</h3>
                        <p class="text-sm text-[var(--cor-texto-secundario)]">{{ $estatisticas['pendentes'] }} pendentes</p>
                    </div>
                </a>

                <a href="{{ route('admin.usuarios.index', ['filtro' => 'aprovados']) }}" class="flex items-center gap-4 p-4 border-2 border-[var(--cor-borda)] rounded-lg hover:border-[var(--cor-verde-serra)] transition-all group">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[var(--cor-texto)]">Ver Todos Usuários</h3>
                        <p class="text-sm text-[var(--cor-texto-secundario)]">{{ $estatisticas['aprovados'] }} ativos</p>
                    </div>
                </a>

                <a href="#" class="flex items-center gap-4 p-4 border-2 border-[var(--cor-borda)] rounded-lg hover:border-[var(--cor-verde-serra)] transition-all group">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i data-lucide="briefcase" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-[var(--cor-texto)]">Gerenciar Talentos</h3>
                        <p class="text-sm text-[var(--cor-texto-secundario)]">{{ $estatisticas['talentos'] ?? 0 }} cadastrados</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
