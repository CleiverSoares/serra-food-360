@extends('layouts.dashboard')

@section('titulo', 'Detalhes do Talento')
@section('page-title', $talento->nome)
@section('page-subtitle', $talento->cargo)

@section('header-actions')
@if(auth()->user()->role === 'admin')
    <a href="{{ route('admin.talentos.edit', $talento->id) }}" class="flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all">
        <i data-lucide="edit" class="w-4 h-4"></i>
        Editar
    </a>
@endif
<a href="{{ auth()->user()->role === 'admin' ? route('admin.talentos.index') : route('talentos.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-5xl mx-auto space-y-6">

        @if(session('sucesso'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Card Principal -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden">
            
            <!-- Header com Foto -->
            <div class="bg-amber-50 p-4 md:p-6 border-b border-[var(--cor-borda)]">
                <div class="flex items-center gap-4 md:gap-6">
                    @if($talento->foto_path)
                        <img src="{{ asset('storage/' . $talento->foto_path) }}" 
                             alt="{{ $talento->nome }}"
                             class="w-16 h-16 md:w-24 md:h-24 rounded-xl object-cover shadow-md flex-shrink-0">
                    @else
                        <div class="w-16 h-16 md:w-24 md:h-24 rounded-xl bg-amber-600 text-white flex items-center justify-center text-2xl md:text-4xl font-bold shadow-md flex-shrink-0">
                            {{ strtoupper(substr($talento->nome, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg md:text-2xl font-bold text-[var(--cor-texto)] mb-1 truncate">{{ $talento->nome }}</h2>
                        <p class="text-base md:text-lg text-amber-700 font-medium mb-2 truncate">{{ $talento->cargo }}</p>
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $talento->ativo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $talento->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold
                                {{ $talento->tipo_cobranca === 'hora' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $talento->tipo_cobranca === 'hora' ? '⏰ Por Hora' : '📅 Por Dia' }}
                            </span>
                            @if($talento->pretensao)
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">
                                    💰 R$ {{ number_format($talento->pretensao, 2, ',', '.') }}/{{ $talento->tipo_cobranca === 'hora' ? 'h' : 'dia' }}
                                </span>
                            @endif
                            @if($talento->disponibilidade)
                                <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold">
                                    <i data-lucide="clock" class="w-3 h-3 inline"></i>
                                    {{ $talento->disponibilidade }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="p-4 md:p-6">
                
                <!-- Informações de Contato -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                        <i data-lucide="phone" class="w-5 h-5 text-amber-600"></i>
                        Contato
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-[var(--cor-texto-muted)] block mb-1">WhatsApp:</span>
                            <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $talento->whatsapp) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-1 font-medium text-green-600 hover:underline">
                                {{ $talento->whatsapp }}
                                <i data-lucide="external-link" class="w-3 h-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mini Currículo -->
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200 mb-6">
                    <h3 class="font-semibold text-purple-900 mb-2 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        Sobre o Profissional
                    </h3>
                    <p class="text-sm text-purple-800 whitespace-pre-line">{{ $talento->mini_curriculo }}</p>
                </div>

                <!-- Documentos Anexados -->
                @if($talento->curriculo_path || $talento->carta_recomendacao_path)
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200 mb-6">
                        <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                            <i data-lucide="paperclip" class="w-5 h-5"></i>
                            Documentos
                        </h3>
                        <div class="space-y-2">
                            @if($talento->curriculo_path)
                                <a href="{{ asset('storage/' . $talento->curriculo_path) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 px-4 py-3 bg-white rounded-lg hover:bg-blue-50 transition-colors">
                                    <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-blue-900">Currículo Completo</p>
                                        <p class="text-xs text-blue-700">Clique para visualizar ou baixar (PDF)</p>
                                    </div>
                                    <i data-lucide="download" class="w-4 h-4 text-blue-600 flex-shrink-0"></i>
                                </a>
                            @endif
                            
                            @if($talento->carta_recomendacao_path)
                                <a href="{{ asset('storage/' . $talento->carta_recomendacao_path) }}" 
                                   target="_blank"
                                   class="flex items-center gap-2 px-4 py-3 bg-white rounded-lg hover:bg-blue-50 transition-colors">
                                    <i data-lucide="award" class="w-5 h-5 text-purple-600"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-purple-900">Carta de Recomendação</p>
                                        <p class="text-xs text-purple-700">Clique para visualizar ou baixar (PDF)</p>
                                    </div>
                                    <i data-lucide="download" class="w-4 h-4 text-purple-600 flex-shrink-0"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Ações -->
                <div class="flex flex-wrap gap-3 pt-6 border-t border-[var(--cor-borda)]">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.talentos.edit', $talento->id) }}" 
                           class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base">
                            <i data-lucide="edit" class="w-4 h-4 flex-shrink-0"></i>
                            <span class="whitespace-nowrap">Editar Talento</span>
                        </a>
                    @endif
                    
                    <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $talento->whatsapp) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm md:text-base">
                        <i data-lucide="message-circle" class="w-4 h-4 flex-shrink-0"></i>
                        <span class="whitespace-nowrap">WhatsApp</span>
                    </a>
                    
                    @if(auth()->user()->role === 'admin')
                        @if($talento->ativo)
                            <form action="{{ route('admin.talentos.inativar', $talento->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors font-medium text-sm md:text-base">
                                    <i data-lucide="pause-circle" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Inativar</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.talentos.ativar', $talento->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors font-medium text-sm md:text-base">
                                    <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                                    <span class="whitespace-nowrap">Ativar</span>
                                </button>
                            </form>
                        @endif
                    @endif
                    <form action="{{ route('admin.talentos.destroy', $talento->id) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Tem certeza que deseja excluir este talento? Esta ação não pode ser desfeita.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors font-medium text-sm md:text-base">
                            <i data-lucide="trash-2" class="w-4 h-4 flex-shrink-0"></i>
                            <span class="whitespace-nowrap">Excluir</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
