@extends('layouts.dashboard')

@section('titulo', 'Propostas de Produtos')
@section('page-title', 'Compras Coletivas')
@section('page-subtitle', 'Propostas de produtos para votação')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        <!-- Botão Propor -->
        <div class="mb-6">
            <a href="{{ route('compras-coletivas.propostas.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium shadow-sm hover:shadow-md">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span class="whitespace-nowrap">Propor Produto</span>
            </a>
        </div>

        <!-- Propostas em Votação -->
        @if($propostas->count() > 0)
            <div class="mb-8">
                <h2 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="vote" class="w-5 h-5 text-blue-600"></i>
                    Em Votação Agora ({{ $propostas->count() }})
                </h2>
                <div class="space-y-4">
                    @foreach($propostas as $proposta)
                        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 lg:p-6 hover:shadow-md transition-shadow" 
                             x-data="{ jaVotou: {{ $proposta->votos->where('user_id', auth()->id())->count() > 0 ? 'true' : 'false' }} }">
                            <div class="flex flex-col md:flex-row md:items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <h3 class="text-lg font-bold text-[var(--cor-texto)]">{{ $proposta->produto->nome }}</h3>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                            {{ $proposta->votos_count }} votos
                                        </span>
                                    </div>

                                    <p class="text-sm text-[var(--cor-texto-muted)] mb-3">{{ $proposta->produto->descricao }}</p>

                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm mb-3">
                                        <div>
                                            <span class="text-[var(--cor-texto-muted)]">Unidade:</span>
                                            <span class="font-medium ml-1">{{ $proposta->produto->unidade_medida }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[var(--cor-texto-muted)]">Proposto por:</span>
                                            <span class="font-medium ml-1">{{ $proposta->propositor->nome }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[var(--cor-texto-muted)]">Encerra:</span>
                                            <span class="font-medium ml-1">{{ $proposta->data_votacao_fim->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-sm text-[var(--cor-texto)]"><strong>Justificativa:</strong> {{ $proposta->justificativa }}</p>
                                    </div>
                                </div>

                                <!-- Botão de Votar -->
                                <button 
                                    @click="if (!jaVotou) { 
                                        fetch('{{ route('compras-coletivas.propostas.votar', $proposta->id) }}', { 
                                            method: 'POST', 
                                            headers: { 
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Content-Type': 'application/json'
                                            }
                                        })
                                        .then(r => r.json())
                                        .then(d => {
                                            if (d.success) {
                                                jaVotou = true;
                                                location.reload();
                                            }
                                        });
                                    }"
                                    :disabled="jaVotou"
                                    :class="jaVotou ? 'bg-gray-100 text-gray-700 cursor-not-allowed' : 'bg-[var(--cor-verde-serra)] text-white hover:bg-green-700'"
                                    class="w-full md:w-auto px-6 py-3 rounded-lg font-bold text-sm whitespace-nowrap transition-all flex items-center justify-center gap-2">
                                    <i :data-lucide="jaVotou ? 'check' : 'thumbs-up'" class="w-4 h-4"></i>
                                    <span x-text="jaVotou ? 'Já Votou' : 'Votar'"></span>
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
                    <i data-lucide="lightbulb" class="w-5 h-5 text-green-600"></i>
                    Minhas Propostas ({{ $minhasPropostas->count() }})
                </h2>
                <div class="space-y-4">
                    @foreach($minhasPropostas as $proposta)
                        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 lg:p-6">
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                <h3 class="text-lg font-bold text-[var(--cor-texto)]">{{ $proposta->produto->nome }}</h3>
                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                    {{ $proposta->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $proposta->status === 'em_votacao' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $proposta->status === 'aprovada' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $proposta->status === 'rejeitada' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $proposta->status)) }}
                                </span>
                                @if($proposta->status === 'em_votacao')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                        {{ $proposta->votos_count }} votos
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-[var(--cor-texto-muted)] mb-3">{{ $proposta->produto->descricao }}</p>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-sm text-[var(--cor-texto)]"><strong>Justificativa:</strong> {{ $proposta->justificativa }}</p>
                            </div>

                            @if($proposta->status === 'em_votacao')
                                <p class="text-xs text-blue-600 mt-3 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    Votação encerra em {{ $proposta->data_votacao_fim->format('d/m/Y') }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Paginação -->
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
