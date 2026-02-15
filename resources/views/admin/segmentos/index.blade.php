@extends('layouts.dashboard')

@section('titulo', 'Segmentos')
@section('page-title', 'Segmentos de Atuação')
@section('page-subtitle', 'Gerencie os segmentos do sistema')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

        @if(session('sucesso'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('sucesso') }}</p>
            </div>
        @endif

        @if(session('erro'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800">{{ session('erro') }}</p>
            </div>
        @endif

        <!-- Botão Criar -->
        <div class="mb-6">
            <a href="{{ route('admin.segmentos.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-green-700 transition-all font-medium shadow-sm hover:shadow-md">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Novo Segmento</span>
            </a>
        </div>

        <!-- Lista de Segmentos -->
        <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Segmento</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descrição</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Usuários</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($segmentos as $segmento)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Segmento -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <span class="text-3xl">{{ $segmento->icone }}</span>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $segmento->nome }}</div>
                                            <div class="text-sm text-gray-500">{{ $segmento->slug }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Descrição -->
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 max-w-md">{{ $segmento->descricao ?: '—' }}</p>
                                </td>

                                <!-- Usuários -->
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        <i data-lucide="users" class="w-4 h-4"></i>
                                        {{ $segmento->users_count }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                        {{ $segmento->ativo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $segmento->ativo ? '✅ Ativo' : '❌ Inativo' }}
                                    </span>
                                </td>

                                <!-- Ações -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Editar -->
                                        <a href="{{ route('admin.segmentos.edit', $segmento->id) }}" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors text-sm font-medium"
                                           title="Editar">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                            <span class="hidden sm:inline">Editar</span>
                                        </a>

                                        <!-- Ativar/Inativar -->
                                        @if($segmento->ativo)
                                            <form action="{{ route('admin.segmentos.inativar', $segmento->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium"
                                                        title="Inativar">
                                                    <i data-lucide="pause" class="w-4 h-4"></i>
                                                    <span class="hidden sm:inline">Inativar</span>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.segmentos.ativar', $segmento->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm font-medium"
                                                        title="Ativar">
                                                    <i data-lucide="play" class="w-4 h-4"></i>
                                                    <span class="hidden sm:inline">Ativar</span>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Deletar -->
                                        @if($segmento->users_count == 0)
                                            <form action="{{ route('admin.segmentos.destroy', $segmento->id) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Tem certeza que deseja deletar este segmento?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium"
                                                        title="Deletar">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    <span class="hidden sm:inline">Deletar</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed"
                                                  title="Não é possível deletar (tem usuários associados)">
                                                <i data-lucide="lock" class="w-4 h-4"></i>
                                                <span class="hidden sm:inline">Bloqueado</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <i data-lucide="inbox" class="w-12 h-12 text-gray-300"></i>
                                        <p class="text-gray-500">Nenhum segmento cadastrado.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
