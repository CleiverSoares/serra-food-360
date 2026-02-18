@extends('layouts.dashboard')

@section('titulo', 'Detalhes do Talento')
@section('page-title', $talento->nome)
@section('page-subtitle', $talento->cargo)

@section('header-actions')
<div class="flex items-center gap-3">
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.talentos.edit', $talento->id) }}" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-colors" style="background-color: #2D5F3F; color: #ffffff;">
            <i data-lucide="edit" class="w-4 h-4"></i>
            Editar
        </a>
    @endif
    <a href="{{ auth()->user()->role === 'admin' ? route('admin.talentos.index') : route('talentos.index') }}" class="flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-[#2D5F3F] transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Voltar
    </a>
</div>
@endsection

@section('mobile-header-actions')
<a href="{{ auth()->user()->role === 'admin' ? route('admin.talentos.index') : route('talentos.index') }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Voltar">
    <i data-lucide="arrow-left" class="w-5 h-5 text-[var(--cor-texto)]"></i>
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
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden"
             x-data="{ modalFoto: false }">
            
            <!-- Header com Foto -->
            <div class="bg-amber-50 p-4 md:p-6 border-b border-[var(--cor-borda)]">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-6">
                    @if($talento->foto_path)
                        <div class="w-20 h-20 md:w-28 md:h-28 rounded-xl overflow-hidden shadow-md flex-shrink-0 cursor-pointer group relative mx-auto sm:mx-0"
                             @click="modalFoto = true">
                            <img src="{{ asset('storage/' . $talento->foto_path) }}" 
                                 alt="{{ $talento->nome }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i data-lucide="zoom-in" class="w-8 h-8 text-white"></i>
                            </div>
                        </div>
                    @else
                        <div class="w-20 h-20 md:w-28 md:h-28 rounded-xl bg-amber-600 text-white flex items-center justify-center text-3xl md:text-5xl font-bold shadow-md flex-shrink-0 mx-auto sm:mx-0">
                            {{ strtoupper(substr($talento->nome, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0 text-center sm:text-left w-full">
                        <h2 class="text-xl md:text-2xl font-bold text-[var(--cor-texto)] mb-1">{{ $talento->nome }}</h2>
                        <p class="text-base md:text-lg text-amber-700 font-medium mb-3">{{ $talento->cargo }}</p>
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
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

            <!-- Modal de Foto -->
            @if($talento->foto_path)
                <div x-show="modalFoto" 
                     x-transition
                     @click.self="modalFoto = false"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
                     style="display: none;">
                    <div class="relative max-w-4xl w-full">
                        <button @click="modalFoto = false" 
                                class="absolute -top-12 right-0 p-2 text-white hover:text-gray-300 transition-colors">
                            <i data-lucide="x" class="w-8 h-8"></i>
                        </button>
                        <img src="{{ asset('storage/' . $talento->foto_path) }}" 
                             alt="{{ $talento->nome }}" 
                             class="w-full h-auto max-h-[85vh] object-contain rounded-lg">
                    </div>
                </div>
            @endif

            <!-- Conteúdo -->
            <div class="p-4 md:p-6 space-y-4">
                
                <!-- Botão WhatsApp Destacado -->
                <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $talento->whatsapp) }}" 
                   target="_blank"
                   class="flex items-center justify-center gap-2 px-6 py-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-sm w-full">
                    <i data-lucide="message-circle" class="w-5 h-5"></i>
                    Entrar em Contato via WhatsApp
                </a>

                <!-- Mini Currículo -->
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <h3 class="font-semibold text-purple-900 mb-3 flex items-center gap-2">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        Sobre o Profissional
                    </h3>
                    <p class="text-sm text-purple-800 whitespace-pre-line leading-relaxed">{{ $talento->mini_curriculo }}</p>
                </div>

                <!-- Documentos Anexados -->
                @if($talento->curriculo_path || $talento->carta_recomendacao_path)
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                            <i data-lucide="paperclip" class="w-5 h-5"></i>
                            Documentos
                        </h3>
                        <div class="space-y-3">
                            @if($talento->curriculo_path)
                                <a href="{{ asset('storage/' . $talento->curriculo_path) }}" 
                                   target="_blank"
                                   class="flex items-center gap-3 px-4 py-3 bg-white rounded-lg hover:bg-blue-50 transition-colors border border-blue-200">
                                    <i data-lucide="file-text" class="w-5 h-5 text-blue-600 flex-shrink-0"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-blue-900">Currículo Completo</p>
                                        <p class="text-xs text-blue-700">Baixar PDF</p>
                                    </div>
                                    <i data-lucide="download" class="w-5 h-5 text-blue-600 flex-shrink-0"></i>
                                </a>
                            @endif
                            
                            @if($talento->carta_recomendacao_path)
                                <a href="{{ asset('storage/' . $talento->carta_recomendacao_path) }}" 
                                   target="_blank"
                                   class="flex items-center gap-3 px-4 py-3 bg-white rounded-lg hover:bg-blue-50 transition-colors border border-blue-200">
                                    <i data-lucide="award" class="w-5 h-5 text-purple-600 flex-shrink-0"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-purple-900">Carta de Recomendação</p>
                                        <p class="text-xs text-purple-700">Baixar PDF</p>
                                    </div>
                                    <i data-lucide="download" class="w-5 h-5 text-purple-600 flex-shrink-0"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Ações Admin (apenas se for admin) -->
                @if(auth()->user()->role === 'admin')
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-[var(--cor-borda)]">
                        @if($talento->ativo)
                            <form action="{{ route('admin.talentos.inativar', $talento->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition-colors font-medium text-sm border border-orange-200">
                                    <i data-lucide="pause-circle" class="w-4 h-4"></i>
                                    Inativar
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.talentos.ativar', $talento->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors font-medium text-sm border border-green-200">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    Ativar
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.talentos.destroy', $talento->id) }}" method="POST" class="flex-1" 
                              onsubmit="return confirm('Tem certeza que deseja excluir este talento?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors font-medium text-sm border border-red-200">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                Excluir
                            </button>
                        </form>
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
