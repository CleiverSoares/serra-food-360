<?php

namespace App\Repositories;

use App\Models\CotacaoModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CotacaoRepository
{
    /**
     * Buscar todas as cotações
     */
    public function buscarTodas(): Collection
    {
        return CotacaoModel::with(['segmento', 'criador', 'ofertas.fornecedor.fornecedor'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar cotações ativas
     */
    public function buscarAtivas(): Collection
    {
        return CotacaoModel::where('status', 'ativo')
            ->whereDate('data_fim', '>=', now())
            ->with(['segmento', 'ofertas.fornecedor.fornecedor'])
            ->orderBy('data_inicio', 'desc')
            ->get();
    }

    /**
     * Buscar cotações por segmento (para compradores)
     */
    public function buscarPorSegmento(int $segmentoId, bool $apenasAtivas = true): Collection
    {
        $query = CotacaoModel::where('segmento_id', $segmentoId)
            ->with(['segmento', 'ofertas.fornecedor.fornecedor', 'ofertas.fornecedor.enderecoPrincipal']);

        if ($apenasAtivas) {
            $query->where('status', 'ativo')
                  ->whereDate('data_fim', '>=', now());
        }

        return $query->orderBy('data_inicio', 'desc')->get();
    }

    /**
     * Buscar cotações por segmentos múltiplos
     */
    public function buscarPorSegmentos(array $segmentosIds, bool $apenasAtivas = true): Collection
    {
        $query = CotacaoModel::whereIn('segmento_id', $segmentosIds)
            ->with(['segmento', 'ofertas.fornecedor.fornecedor', 'ofertas.fornecedor.enderecoPrincipal']);

        if ($apenasAtivas) {
            $query->where('status', 'ativo')
                  ->whereDate('data_fim', '>=', now());
        }

        return $query->orderBy('data_inicio', 'desc')->get();
    }

    /**
     * Buscar cotação por ID
     */
    public function buscarPorId(int $id): ?CotacaoModel
    {
        return CotacaoModel::with(['segmento', 'criador', 'ofertas.fornecedor.fornecedor', 'ofertas.fornecedor.enderecoPrincipal'])
            ->find($id);
    }

    /**
     * Criar cotação
     */
    public function criar(array $dados): CotacaoModel
    {
        return CotacaoModel::create($dados);
    }

    /**
     * Atualizar cotação
     */
    public function atualizar(CotacaoModel $cotacao, array $dados): bool
    {
        return $cotacao->update($dados);
    }

    /**
     * Deletar cotação
     */
    public function deletar(CotacaoModel $cotacao): bool
    {
        return $cotacao->delete();
    }

    /**
     * Encerrar cotação
     */
    public function encerrar(int $id): bool
    {
        return CotacaoModel::where('id', $id)->update(['status' => 'encerrado']);
    }

    /**
     * Buscar cotações com paginação (admin)
     */
    public function buscarComPaginacao(int $porPagina = 15): LengthAwarePaginator
    {
        return CotacaoModel::with(['segmento', 'criador', 'ofertas'])
            ->orderBy('created_at', 'desc')
            ->paginate($porPagina);
    }
}
