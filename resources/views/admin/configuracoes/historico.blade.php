@extends('layouts.dashboard')

@section('titulo', 'Histórico de Alterações de Preços')
@section('page-title', 'Histórico de Preços')
@section('page-subtitle', 'Auditoria de alterações nos valores dos planos')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <!-- Botões de ação -->
        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.configuracoes.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Voltar para Configurações
            </a>
        </div>

        <!-- Tabela de Histórico -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden">
            <div class="p-6 border-b border-[var(--cor-borda)]">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] flex items-center gap-2">
                    <i data-lucide="history" class="w-5 h-5 text-blue-600"></i>
                    Histórico de Alterações
                </h3>
                <p class="text-sm text-[var(--cor-texto-muted)] mt-1">
                    Registro completo de todas as mudanças nos preços dos planos
                </p>
            </div>

            @if($historico->isEmpty())
                <div class="p-8 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                    <p class="text-[var(--cor-texto-muted)]">Nenhuma alteração registrada ainda</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Plano</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Valor Antigo</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Valor Novo</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Alterado Por</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($historico as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-[var(--cor-texto)]">
                                            {{ $item->nome_plano }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-600">
                                            R$ {{ number_format($item->valor_antigo, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-green-600">
                                            R$ {{ number_format($item->valor_novo, 2, ',', '.') }}
                                        </span>
                                        @php
                                            $diferenca = $item->valor_novo - $item->valor_antigo;
                                            $percentual = $item->valor_antigo > 0 ? ($diferenca / $item->valor_antigo) * 100 : 0;
                                        @endphp
                                        @if($diferenca > 0)
                                            <span class="ml-2 text-xs text-red-600">
                                                (+{{ number_format($percentual, 1) }}%)
                                            </span>
                                        @elseif($diferenca < 0)
                                            <span class="ml-2 text-xs text-green-600">
                                                ({{ number_format($percentual, 1) }}%)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->alteradoPor)
                                            <span class="text-[var(--cor-texto)]">
                                                {{ $item->alteradoPor->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Sistema</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                        <span class="block text-xs text-gray-400">
                                            {{ $item->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <div class="p-4 border-t border-[var(--cor-borda)]">
                    {{ $historico->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
