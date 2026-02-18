@extends('layouts.dashboard')

@section('titulo', 'Propostas de Produtos')
@section('page-title', 'Compras Coletivas')
@section('page-subtitle', 'Propostas de produtos para votação')

@section('header-actions')
<div class="flex items-center gap-3">
    <a href="{{ route('compras-coletivas.propostas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-colors font-medium" style="background-color: #2D5F3F; color: #ffffff;">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Propor Produto
    </a>
    <a href="{{ route('compras-coletivas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-[#2D5F3F] transition-colors font-medium">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Voltar
    </a>
</div>
@endsection

@section('mobile-header-actions')
<a href="{{ route('compras-coletivas.index') }}" class="p-2 rounded-lg hover:bg-gray-100 transition-colors" title="Voltar">
    <i data-lucide="arrow-left" class="w-5 h-5 text-[var(--cor-texto)]"></i>
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Propostas em Votação -->
        @if($propostas->count() > 0)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="vote" class="w-5 h-5"></i>
                    Em Votação Agora ({{ $propostas->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($propostas as $proposta)
                        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full" 
                             x-data="{ 
                                jaVotou: {{ $proposta->votos->where('user_id', auth()->id())->count() > 0 ? 'true' : 'false' }},
                                votosCount: {{ $proposta->votos_count }},
                                votando: false
                             }">
                            
                            <!-- Header -->
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="font-bold text-[var(--cor-texto)] mb-2">{{ $proposta->produto->nome }}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded" x-text="votosCount + ' votos'"></span>
                                </div>
                            </div>

                            <!-- Conteúdo -->
                            <div class="p-4 flex-1">
                                <p class="text-sm text-[var(--cor-texto-muted)] mb-3">{{ $proposta->produto->descricao }}</p>

                                <div class="space-y-2 text-xs mb-3">
                                    <p><span class="text-[var(--cor-texto-muted)]">Unidade:</span> <span class="font-medium">{{ $proposta->produto->unidade_medida }}</span></p>
                                    <p><span class="text-[var(--cor-texto-muted)]">Por:</span> <span class="font-medium">{{ $proposta->propositor->nome }}</span></p>
                                    <p><span class="text-[var(--cor-texto-muted)]">Encerra:</span> <span class="font-medium">{{ $proposta->data_votacao_fim->format('d/m/Y') }}</span></p>
                                </div>

                                @if($proposta->justificativa)
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-xs text-[var(--cor-texto)] line-clamp-3">{{ $proposta->justificativa }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Botão de Votar/Remover Voto -->
                            <div class="p-4 border-t border-gray-200">
                                <button 
                                    @click="
                                        if (votando) return;
                                        votando = true;
                                        
                                        const url = !jaVotou 
                                            ? '{{ route('compras-coletivas.propostas.votar', $proposta->id) }}'
                                            : '{{ route('compras-coletivas.propostas.remover-voto', $proposta->id) }}';
                                        
                                        const method = !jaVotou ? 'POST' : 'DELETE';
                                        
                                        fetch(url, { 
                                            method: method, 
                                            headers: { 
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Content-Type': 'application/json'
                                            }
                                        })
                                        .then(r => r.json())
                                        .then(d => {
                                            if (d.success) {
                                                if (!jaVotou) {
                                                    jaVotou = true;
                                                    votosCount++;
                                                } else {
                                                    jaVotou = false;
                                                    votosCount--;
                                                }
                                                setTimeout(() => {
                                                    if (typeof lucide !== 'undefined') lucide.createIcons();
                                                }, 50);
                                            }
                                            votando = false;
                                        })
                                        .catch(() => {
                                            votando = false;
                                        });
                                    "
                                    :disabled="votando"
                                    :style="jaVotou ? 'background-color: #ef4444; color: #ffffff;' : 'background-color: #2D5F3F; color: #ffffff;'"
                                    class="w-full px-4 py-2 rounded-lg font-medium text-sm transition-colors flex items-center justify-center gap-2 disabled:cursor-not-allowed disabled:opacity-60">
                                    <i :data-lucide="jaVotou ? 'x' : 'thumbs-up'" class="w-4 h-4"></i>
                                    <span x-text="votando ? 'Processando...' : (jaVotou ? 'Remover Voto' : 'Votar')"></span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center mb-8">
                <i data-lucide="inbox" class="w-12 h-12 text-yellow-600 mx-auto mb-3"></i>
                <p class="text-yellow-800 font-medium">Nenhuma proposta em votação no momento</p>
            </div>
        @endif

        <!-- Minhas Propostas -->
        @if($minhasPropostas->count() > 0)
            <div>
                <h2 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="lightbulb" class="w-5 h-5"></i>
                    Minhas Propostas ({{ $minhasPropostas->count() }})
                </h2>
                <div class="space-y-3">
                    @foreach($minhasPropostas as $proposta)
                        <div class="bg-white rounded-lg shadow-sm border border-[var(--cor-borda)] p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <h3 class="font-bold text-[var(--cor-texto)]">{{ $proposta->produto->nome }}</h3>
                                        <span class="px-2 py-0.5 text-xs font-bold rounded 
                                            {{ $proposta->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $proposta->status === 'em_votacao' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $proposta->status === 'aprovada' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $proposta->status === 'rejeitada' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $proposta->status)) }}
                                        </span>
                                        @if($proposta->status === 'em_votacao')
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs font-bold rounded">
                                                {{ $proposta->votos_count }} votos
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-xs text-[var(--cor-texto-muted)] mb-2">{{ $proposta->produto->descricao }}</p>

                                    @if($proposta->status === 'em_votacao')
                                        <p class="text-xs text-blue-600 flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            Encerra em {{ $proposta->data_votacao_fim->format('d/m/Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($minhasPropostas->hasPages())
                    <div class="mt-6">
                        {{ $minhasPropostas->links() }}
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
