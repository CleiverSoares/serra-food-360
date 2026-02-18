<?php

namespace App\Repositories;

use App\Models\CompraColetivaPropostaModel;
use App\Models\CompraColetivaVotoModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompraColetivaPropostaRepository
{
    /**
     * Buscar propostas com filtros
     */
    public function buscarComFiltros(array $filtros = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = CompraColetivaPropostaModel::query();

        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (!empty($filtros['proposto_por'])) {
            $query->where('proposto_por', $filtros['proposto_por']);
        }

        return $query->with(['produto', 'propositor'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Buscar propostas em votação ativas
     */
    public function buscarEmVotacao(): Collection
    {
        return CompraColetivaPropostaModel::where('status', 'em_votacao')
            ->where('data_votacao_fim', '>=', now())
            ->with(['produto', 'propositor'])
            ->orderBy('data_votacao_fim')
            ->get();
    }

    /**
     * Buscar proposta por ID
     */
    public function buscarPorId(int $id): ?CompraColetivaPropostaModel
    {
        return CompraColetivaPropostaModel::with(['produto', 'propositor', 'votos'])->find($id);
    }

    /**
     * Criar proposta
     */
    public function criar(array $dados): CompraColetivaPropostaModel
    {
        return CompraColetivaPropostaModel::create($dados);
    }

    /**
     * Atualizar proposta
     */
    public function atualizar(int $id, array $dados): bool
    {
        return CompraColetivaPropostaModel::where('id', $id)->update($dados);
    }

    /**
     * Deletar proposta
     */
    public function deletar(int $id): bool
    {
        return CompraColetivaPropostaModel::destroy($id) > 0;
    }

    /**
     * Adicionar voto
     */
    public function adicionarVoto(int $propostaId, int $userId): CompraColetivaVotoModel
    {
        $voto = CompraColetivaVotoModel::create([
            'proposta_id' => $propostaId,
            'user_id' => $userId,
            'votado_em' => now(),
        ]);

        // Incrementar contador
        CompraColetivaPropostaModel::where('id', $propostaId)->increment('votos_count');

        return $voto;
    }

    /**
     * Verificar se usuário já votou
     */
    public function jaVotou(int $propostaId, int $userId): bool
    {
        return CompraColetivaVotoModel::where('proposta_id', $propostaId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Remover voto
     */
    public function removerVoto(int $propostaId, int $userId): bool
    {
        $deletado = CompraColetivaVotoModel::where('proposta_id', $propostaId)
            ->where('user_id', $userId)
            ->delete();

        if ($deletado > 0) {
            // Decrementar contador
            CompraColetivaPropostaModel::where('id', $propostaId)->decrement('votos_count');
            return true;
        }

        return false;
    }

    /**
     * Buscar propostas com votação encerrada (para processar)
     */
    public function buscarVotacoesEncerradas(): Collection
    {
        return CompraColetivaPropostaModel::where('status', 'em_votacao')
            ->where('data_votacao_fim', '<', now())
            ->with(['produto', 'propositor'])
            ->get();
    }
}
