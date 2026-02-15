@extends('layouts.dashboard')

@section('titulo', 'Detalhes do Fornecedor')
@section('page-title', $fornecedor->name)
@section('page-subtitle', $fornecedor->fornecedor?->nome_empresa)

@section('header-actions')
@if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.fornecedores.edit', $fornecedor->id) }}" class="flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all">
        <i data-lucide="edit" class="w-4 h-4"></i>
        Editar
    </a>
@endif
<a href="{{ auth()->user()->role === 'admin' ? route('admin.fornecedores.index') : route('fornecedores.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Card Principal -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden">
            
            <!-- Header com Logo -->
            <div class="bg-purple-50 p-4 md:p-6 border-b border-[var(--cor-borda)]">
                <div class="flex items-center gap-4 md:gap-6">
                    @if($fornecedor->fornecedor && $fornecedor->fornecedor->logo_path)
                        <img src="{{ asset('storage/' . $fornecedor->fornecedor->logo_path) }}" 
                             alt="{{ $fornecedor->name }}"
                             class="w-16 h-16 md:w-24 md:h-24 rounded-xl object-cover shadow-md flex-shrink-0">
                    @else
                        <div class="w-16 h-16 md:w-24 md:h-24 rounded-xl bg-purple-600 text-white flex items-center justify-center text-2xl md:text-4xl font-bold shadow-md flex-shrink-0">
                            {{ strtoupper(substr($fornecedor->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg md:text-2xl font-bold text-[var(--cor-texto)] mb-1 truncate">{{ $fornecedor->name }}</h2>
                        @if($fornecedor->fornecedor)
                            <p class="text-base md:text-lg text-[var(--cor-texto-muted)] mb-2 truncate">{{ $fornecedor->fornecedor->nome_empresa }}</p>
                        @endif
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $fornecedor->status === 'aprovado' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $fornecedor->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $fornecedor->status === 'inativo' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $fornecedor->status === 'rejeitado' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($fornecedor->status) }}
                            </span>
                            @if($fornecedor->plano)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold uppercase">
                                    {{ $fornecedor->plano }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="p-6">
                
                <!-- Segmentos -->
                @if($fornecedor->segmentos->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-[var(--cor-texto)] mb-3 flex items-center gap-2">
                            <i data-lucide="tags" class="w-5 h-5 text-purple-600"></i>
                            Segmentos de Atuação
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($fornecedor->segmentos as $segmento)
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium"
                                      style="background-color: {{ $segmento->cor }}20; color: {{ $segmento->cor }}; border: 2px solid {{ $segmento->cor }}40;">
                                    <i data-lucide="{{ $segmento->icone }}" class="w-4 h-4"></i>
                                    {{ $segmento->nome }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Grid de Informações -->
                <div class="grid md:grid-cols-2 gap-6">
                    
                    <!-- Coluna 1: Dados Pessoais -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                            <i data-lucide="user" class="w-5 h-5 text-purple-600"></i>
                            Informações Pessoais
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-[var(--cor-texto-muted)] block mb-1">Email:</span>
                                <span class="font-medium text-[var(--cor-texto)]">{{ $fornecedor->email }}</span>
                            </div>
                            <div>
                                <span class="text-[var(--cor-texto-muted)] block mb-1">Telefone:</span>
                                <span class="font-medium text-[var(--cor-texto)]">{{ $fornecedor->telefonePrincipal?->formatado() ?? 'Não informado' }}</span>
                            </div>
                            <div>
                                <span class="text-[var(--cor-texto-muted)] block mb-1">WhatsApp:</span>
                                @if($fornecedor->whatsappPrincipal)
                                    <a href="{{ $fornecedor->whatsappPrincipal->linkWhatsApp() }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 font-medium text-green-600 hover:underline">
                                        {{ $fornecedor->whatsappPrincipal->formatado() }}
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                @else
                                    <span class="font-medium text-[var(--cor-texto)]">Não informado</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-[var(--cor-texto-muted)] block mb-1">Cidade:</span>
                                <span class="font-medium text-[var(--cor-texto)]">{{ $fornecedor->enderecoPrincipal?->cidadeEstado() ?? 'Não informado' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna 2: Dados do Negócio -->
                    @if($fornecedor->fornecedor)
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                                <i data-lucide="building-2" class="w-5 h-5 text-blue-600"></i>
                                Informações da Empresa
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Empresa:</span>
                                    <span class="font-medium text-[var(--cor-texto)]">{{ $fornecedor->fornecedor->nome_empresa }}</span>
                                </div>
                                @if($fornecedor->fornecedor->cnpj)
                                    <div>
                                        <span class="text-[var(--cor-texto-muted)] block mb-1">CNPJ:</span>
                                        <span class="font-medium text-[var(--cor-texto)]">{{ $fornecedor->fornecedor->cnpj }}</span>
                                    </div>
                                @endif
                                @if($fornecedor->fornecedor->site_url)
                                    <div>
                                        <span class="text-[var(--cor-texto-muted)] block mb-1">Site:</span>
                                        <a href="{{ $fornecedor->fornecedor->site_url }}" target="_blank" class="text-blue-600 hover:underline break-all text-sm">
                                            {{ $fornecedor->fornecedor->site_url }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Descrição -->
                @if($fornecedor->fornecedor && $fornecedor->fornecedor->descricao)
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <h3 class="font-semibold text-purple-900 mb-2 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            Descrição
                        </h3>
                        <p class="text-sm text-purple-800">{{ $fornecedor->fornecedor->descricao }}</p>
                    </div>
                @endif

                <!-- Info de Aprovação -->
                @if($fornecedor->aprovado_em)
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <h3 class="font-semibold text-green-900 mb-2 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            Aprovação
                        </h3>
                        <div class="text-sm text-green-800 space-y-1">
                            <p><strong>Data:</strong> {{ $fornecedor->aprovado_em->format('d/m/Y') }} às {{ $fornecedor->aprovado_em->format('H:i') }}</p>
                            @if($fornecedor->aprovador)
                                <p><strong>Por:</strong> {{ $fornecedor->aprovador->name }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Ações -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-[var(--cor-borda)]">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.fornecedores.edit', $fornecedor->id) }}" 
                           class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base">
                            <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                            <span class="whitespace-nowrap">Editar Fornecedor</span>
                        </a>
                    @endif
                    
                    <a href="{{ $fornecedor->whatsappPrincipal?->linkWhatsApp() ?? '#' }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm md:text-base">
                        <i data-lucide="message-circle" class="w-4 h-4 flex-shrink-0"></i>
                        <span class="whitespace-nowrap">WhatsApp</span>
                    </a>
                    
                    @if(auth()->user()->role === 'admin')
                        @if($fornecedor->status === 'inativo')
                            <form action="{{ route('admin.fornecedores.ativar', $fornecedor->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors font-medium text-sm md:text-base">
                                    <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ativar</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.fornecedores.inativar', $fornecedor->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors font-medium text-sm md:text-base">
                                    <i data-lucide="pause-circle" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Inativar</span>
                                </button>
                            </form>
                        @endif
                    @endif
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
