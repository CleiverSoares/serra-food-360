@extends('layouts.dashboard')

@section('titulo', 'Detalhes do Comprador')
@section('page-title', $comprador->name)
@section('page-subtitle', $comprador->comprador?->nome_negocio)

@section('header-actions')
<div class="flex items-center gap-3">
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.compradores.edit', $comprador->id) }}" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors" style="background-color: #2D5F3F; color: #ffffff;">
            <i data-lucide="edit" class="w-4 h-4"></i>
            Editar
        </a>
    @endif
    <a href="{{ auth()->user()->role === 'admin' ? route('admin.compradores.index') : route('compradores.index') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-[#2D5F3F] transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Voltar
    </a>
</div>
@endsection

@section('mobile-header-actions')
<a href="{{ auth()->user()->role === 'admin' ? route('admin.compradores.index') : route('compradores.index') }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Voltar">
    <i data-lucide="arrow-left" class="w-5 h-5 text-[var(--cor-texto)]"></i>
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Card Principal -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden"
             x-data="{ modalLogo: false }">
            
            <!-- Header com Logo -->
            <div class="bg-green-50 p-4 md:p-6 border-b border-[var(--cor-borda)]">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-6">
                    @if($comprador->comprador && $comprador->comprador->logo_path)
                        <div class="w-20 h-20 md:w-28 md:h-28 rounded-xl overflow-hidden shadow-md flex-shrink-0 cursor-pointer group relative mx-auto sm:mx-0"
                             @click="modalLogo = true">
                            <img src="{{ asset('storage/' . $comprador->comprador->logo_path) }}" 
                                 alt="{{ $comprador->name }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i data-lucide="zoom-in" class="w-8 h-8 text-white"></i>
                            </div>
                        </div>
                    @else
                        <div class="w-20 h-20 md:w-28 md:h-28 rounded-xl text-white flex items-center justify-center text-3xl md:text-5xl font-bold shadow-md flex-shrink-0 mx-auto sm:mx-0" style="background-color: #2D5F3F;">
                            {{ strtoupper(substr($comprador->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0 text-center sm:text-left w-full">
                        <h2 class="text-xl md:text-2xl font-bold text-[var(--cor-texto)] mb-1">{{ $comprador->name }}</h2>
                        @if($comprador->comprador)
                            <p class="text-base md:text-lg text-[var(--cor-texto-muted)] mb-3">{{ $comprador->comprador->nome_negocio }}</p>
                        @endif
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
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

            <!-- Modal de Logo -->
            @if($comprador->comprador && $comprador->comprador->logo_path)
                <div x-show="modalLogo" 
                     x-transition
                     @click.self="modalLogo = false"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
                     style="display: none;">
                    <div class="relative max-w-4xl w-full">
                        <button @click="modalLogo = false" 
                                class="absolute -top-12 right-0 p-2 text-white hover:text-gray-300 transition-colors">
                            <i data-lucide="x" class="w-8 h-8"></i>
                        </button>
                        <img src="{{ asset('storage/' . $comprador->comprador->logo_path) }}" 
                             alt="{{ $comprador->name }}" 
                             class="w-full h-auto max-h-[85vh] object-contain rounded-lg">
                    </div>
                </div>
            @endif

            <!-- Conteúdo -->
            <div class="p-4 md:p-6 space-y-4 md:space-y-6">
                
                <!-- Assinatura (apenas admin e o próprio usuário) -->
                @if(auth()->user()->role === 'admin' || auth()->id() === $comprador->id)
                    @php
                        $assinatura = $comprador->assinaturaAtiva;
                    @endphp
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 border-2 border-blue-200">
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
                @endif
                
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
                <div class="grid md:grid-cols-2 gap-4 md:gap-6">
                    
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

                <!-- Botão WhatsApp Destacado -->
                @if($comprador->whatsappPrincipal)
                    <a href="{{ $comprador->whatsappPrincipal->linkWhatsApp() }}" 
                       target="_blank"
                       class="flex items-center justify-center gap-2 px-6 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-sm w-full">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        Entrar em Contato via WhatsApp
                    </a>
                @endif

                <!-- Ações Admin -->
                @if(auth()->user()->role === 'admin')
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-[var(--cor-borda)]">
                        @if($comprador->status === 'inativo')
                            <form action="{{ route('admin.compradores.ativar', $comprador->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors font-medium text-sm border border-green-200">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Ativar
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.compradores.inativar', $comprador->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors font-medium text-sm border border-orange-200">
                                    <i data-lucide="pause-circle" class="w-4 h-4"></i>
                                    Inativar
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endpush
