@extends('layouts.dashboard')

@section('titulo', 'Configurações do Sistema')
@section('page-title', 'Configurações')
@section('page-subtitle', 'Gerenciar configurações globais da plataforma')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-5xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.configuracoes.salvar') }}" class="space-y-6">
            @csrf

            @foreach($grupos as $nomeGrupo => $configs)
                <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-6">
                    <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                        @if($nomeGrupo === 'geral')
                            <i data-lucide="settings" class="w-5 h-5 text-[var(--cor-verde-serra)]"></i>
                            Configurações Gerais
                        @elseif($nomeGrupo === 'contato')
                            <i data-lucide="phone" class="w-5 h-5 text-blue-600"></i>
                            Informações de Contato
                        @elseif($nomeGrupo === 'email')
                            <i data-lucide="mail" class="w-5 h-5 text-purple-600"></i>
                            Configurações de Email
                        @else
                            <i data-lucide="box" class="w-5 h-5 text-gray-600"></i>
                            {{ ucfirst($nomeGrupo) }}
                        @endif
                    </h3>

                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($configs as $config)
                            <div class="{{ $config->tipo === 'textarea' ? 'md:col-span-2' : '' }}">
                                <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">
                                    {{ $config->label }}
                                    @if($config->descricao)
                                        <span class="block text-xs text-[var(--cor-texto-muted)] font-normal mt-1">
                                            {{ $config->descricao }}
                                        </span>
                                    @endif
                                </label>

                                @if($config->tipo === 'textarea')
                                    <textarea name="{{ $config->chave }}" 
                                              rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old($config->chave, $config->valor) }}</textarea>
                                @else
                                    <input type="{{ $config->tipo }}" 
                                           name="{{ $config->chave }}" 
                                           value="{{ old($config->chave, $config->valor) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Botões -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Salvar Configurações
                </button>
                <a href="{{ route('admin.configuracoes.historico') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i data-lucide="history" class="w-4 h-4"></i>
                    Ver Histórico de Preços
                </a>
                <a href="{{ route('admin.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Voltar
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
