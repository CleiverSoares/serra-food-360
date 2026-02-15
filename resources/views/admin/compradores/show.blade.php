@extends('layouts.dashboard')

@section('titulo', 'Detalhes do Comprador')
@section('page-title', $comprador->name)
@section('page-subtitle', $comprador->comprador?->nome_negocio)

@section('header-actions')
@if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.compradores.edit', $comprador->id) }}" class="flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all">
        <i data-lucide="edit" class="w-4 h-4"></i>
        Editar
    </a>
@endif
<a href="{{ auth()->user()->role === 'admin' ? route('admin.compradores.index') : route('compradores.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
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
            <div class="bg-green-50 p-4 md:p-6 border-b border-[var(--cor-borda)]">
                <div class="flex items-center gap-4 md:gap-6">
                    @if($comprador->comprador && $comprador->comprador->logo_path)
                        <img src="{{ asset('storage/' . $comprador->comprador->logo_path) }}" 
                             alt="{{ $comprador->name }}"
                             class="w-16 h-16 md:w-24 md:h-24 rounded-xl object-cover shadow-md flex-shrink-0">
                    @else
                        <div class="w-16 h-16 md:w-24 md:h-24 rounded-xl bg-[var(--cor-verde-serra)] text-white flex items-center justify-center text-2xl md:text-4xl font-bold shadow-md flex-shrink-0">
                            {{ strtoupper(substr($comprador->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg md:text-2xl font-bold text-[var(--cor-texto)] mb-1 truncate">{{ $comprador->name }}</h2>
                        @if($comprador->comprador)
                            <p class="text-base md:text-lg text-[var(--cor-texto-muted)] mb-2 truncate">{{ $comprador->comprador->nome_negocio }}</p>
                        @endif
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $comprador->status === 'aprovado' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $comprador->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $comprador->status === 'inativo' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $comprador->status === 'rejeitado' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($comprador->status) }}
                            </span>
                            @php
                                $assinaturaAtiva = $comprador->assinaturaAtiva;
                            @endphp
                            @if($assinaturaAtiva && $assinaturaAtiva->estaAtiva())
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">
                                    {{ ucfirst($assinaturaAtiva->plano) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="p-6">
                
                <!-- Assinatura -->
                @php
                    $assinatura = $comprador->assinaturaAtiva;
                @endphp
                <div class="mb-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 border-2 border-blue-200">
                    <h3 class="text-sm font-bold text-[var(--cor-texto)] mb-3 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-5 h-5 text-blue-600"></i>
                        Assinatura
                    </h3>
                    @if($assinatura)
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Plano:</span>
                                    <span class="font-bold text-blue-900">{{ ucfirst($assinatura->plano) }}</span>
                                </div>
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Tipo:</span>
                                    <span class="font-medium text-[var(--cor-texto)]">{{ ucfirst($assinatura->tipo_pagamento) }}</span>
                                </div>
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Status:</span>
                                    @if($assinatura->estaAtiva())
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-800 rounded-md text-xs font-bold">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            Ativa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-800 rounded-md text-xs font-bold">
                                            <i data-lucide="x-circle" class="w-3 h-3"></i>
                                            Vencida
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Início:</span>
                                    <span class="font-medium text-[var(--cor-texto)]">{{ $assinatura->data_inicio->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Vencimento:</span>
                                    <span class="font-medium text-[var(--cor-texto)]">{{ $assinatura->data_fim->format('d/m/Y') }}</span>
                                </div>
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Dias restantes:</span>
                                    <span class="font-bold {{ $assinatura->diasRestantes() <= 7 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $assinatura->diasRestantes() }} dias
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->role === 'admin')
                            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-blue-200">
                                <button onclick="document.getElementById('form-renovar-{{ $assinatura->id }}').submit()" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                    Renovar
                                </button>
                                <form id="form-renovar-{{ $assinatura->id }}" action="{{ route('admin.assinaturas.renovar', $assinatura->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="tipo_pagamento" value="{{ $assinatura->tipo_pagamento }}">
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <p class="text-[var(--cor-texto-muted)] text-sm mb-3">Este usuário não possui assinatura ativa</p>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.compradores.edit', $comprador->id) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                    Criar Assinatura (ir para Editar)
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                
                <!-- Segmentos -->
                @if($comprador->segmentos->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-[var(--cor-texto)] mb-3 flex items-center gap-2">
                            <i data-lucide="tags" class="w-5 h-5 text-[var(--cor-verde-serra)]"></i>
                            Segmentos de Atuação
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($comprador->segmentos as $segmento)
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
                            <i data-lucide="user" class="w-5 h-5 text-[var(--cor-verde-serra)]"></i>
                            Informações Pessoais
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div>
                                <span class="text-[var(--cor-texto-muted)] block mb-1">Email:</span>
                                <span class="font-medium text-[var(--cor-texto)]">{{ $comprador->email }}</span>
                            </div>
                            <div>
                                <span class="text-[var(--cor-texto-muted)] block mb-1">Telefone:</span>
                                <span class="font-medium text-[var(--cor-texto)]">{{ $comprador->telefonePrincipal?->formatado() ?? 'Não informado' }}</span>
                            </div>
                            <div>
                                <span class="text-[var(--cor-texto-muted)] block mb-1">WhatsApp:</span>
                                @if($comprador->whatsappPrincipal)
                                    <a href="{{ $comprador->whatsappPrincipal->linkWhatsApp() }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 font-medium text-green-600 hover:underline">
                                        {{ $comprador->whatsappPrincipal->formatado() }}
                                        <i data-lucide="external-link" class="w-3 h-3"></i>
                                    </a>
                                @else
                                    <span class="font-medium text-[var(--cor-texto)]">Não informado</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-[var(--cor-texto-muted)] block mb-1">Cidade:</span>
                                <span class="font-medium text-[var(--cor-texto)]">{{ $comprador->enderecoPrincipal?->cidadeEstado() ?? 'Não informado' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna 2: Dados do Negócio -->
                    @if($comprador->comprador)
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                                <i data-lucide="building-2" class="w-5 h-5 text-blue-600"></i>
                                Informações do Negócio
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Estabelecimento:</span>
                                    <span class="font-medium text-[var(--cor-texto)]">{{ $comprador->comprador->nome_negocio }}</span>
                                </div>
                                @if($comprador->comprador->cnpj)
                                    <div>
                                        <span class="text-[var(--cor-texto-muted)] block mb-1">CNPJ:</span>
                                        <span class="font-medium text-[var(--cor-texto)]">{{ $comprador->comprador->cnpj }}</span>
                                    </div>
                                @endif
                                @if($comprador->comprador->tipo_negocio)
                                    <div>
                                        <span class="text-[var(--cor-texto-muted)] block mb-1">Tipo:</span>
                                        <span class="font-medium text-[var(--cor-texto)]">{{ $comprador->comprador->tipo_negocio }}</span>
                                    </div>
                                @endif
                                @if($comprador->comprador->colaboradores)
                                    <div>
                                        <span class="text-[var(--cor-texto-muted)] block mb-1">Colaboradores:</span>
                                        <span class="font-medium text-[var(--cor-texto)]">{{ $comprador->comprador->colaboradores }}</span>
                                    </div>
                                @endif
                                @if($comprador->comprador->site_url)
                                <div>
                                    <span class="text-[var(--cor-texto-muted)] block mb-1">Site:</span>
                                    <a href="{{ $comprador->comprador->site_url }}" target="_blank" class="text-blue-600 hover:underline break-all text-sm">
                                        {{ $comprador->comprador->site_url }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Descrição -->
                @if($comprador->comprador && $comprador->comprador->descricao)
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <h3 class="font-semibold text-purple-900 mb-2 flex items-center gap-2">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            Descrição
                        </h3>
                        <p class="text-sm text-purple-800">{{ $comprador->comprador->descricao }}</p>
                    </div>
                @endif

                <!-- Info de Aprovação -->
                @if($comprador->aprovado_em)
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <h3 class="font-semibold text-green-900 mb-2 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            Aprovação
                        </h3>
                        <div class="text-sm text-green-800 space-y-1">
                            <p><strong>Data:</strong> {{ $comprador->aprovado_em->format('d/m/Y') }} às {{ $comprador->aprovado_em->format('H:i') }}</p>
                            @if($comprador->aprovador)
                                <p><strong>Por:</strong> {{ $comprador->aprovador->name }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Ações -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-[var(--cor-borda)]">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.compradores.edit', $comprador->id) }}" 
                           class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base">
                            <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                            <span class="whitespace-nowrap">Editar Comprador</span>
                        </a>
                    @endif
                    
                    <a href="{{ $comprador->whatsappPrincipal?->linkWhatsApp() ?? '#' }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm md:text-base">
                        <i data-lucide="message-circle" class="w-4 h-4 flex-shrink-0"></i>
                        <span class="whitespace-nowrap">WhatsApp</span>
                    </a>
                    
                    @if(auth()->user()->role === 'admin')
                        @if($comprador->status === 'inativo')
                            <form action="{{ route('admin.compradores.ativar', $comprador->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors font-medium text-sm md:text-base">
                                    <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ativar</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.compradores.inativar', $comprador->id) }}" method="POST" class="inline">
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
