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
        return CotacaoModel::with(['segmento', 'criador', 'ofertasOrdenadas.fornecedor.fornecedor'])
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
            ->with(['segmento', 'ofertasOrdenadas.fornecedor.fornecedor'])
            ->orderBy('data_inicio', 'desc')
            ->get();
    }

    /**
     * Buscar cotações por segmento (para compradores)
     */
    public function buscarPorSegmento(int $segmentoId, bool $apenasAtivas = true): Collection
    {
        $query = CotacaoModel::where('segmento_id', $segmentoId)
            ->with(['segmento', 'ofertasOrdenadas.fornecedor.fornecedor', 'ofertasOrdenadas.fornecedor.enderecoPrincipal']);

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
            ->with(['segmento', 'ofertasOrdenadas.fornecedor.fornecedor', 'ofertasOrdenadas.fornecedor.enderecoPrincipal']);

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
        return CotacaoModel::with(['segmento', 'criador', 'ofertasOrdenadas.fornecedor.fornecedor', 'ofertasOrdenadas.fornecedor.enderecoPrincipal'])
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

    /**
     * Buscar cotações com filtros (para comprador)
     */
    public function buscarComFiltros(array $segmentosIds, array $filtros = []): Collection
    {
        $query = CotacaoModel::whereIn('segmento_id', $segmentosIds)
            ->with([
                'segmento', 
                'ofertasOrdenadas.fornecedor.fornecedor', 
                'ofertasOrdenadas.fornecedor.enderecoPrincipal'
            ]);

        // Filtro por busca (nome/título/produto)
        if (!empty($filtros['busca'])) {
            $busca = $filtros['busca'];
            $query->where(function($q) use ($busca) {
                $q->where('titulo', 'like', "%{$busca}%")
                  ->orWhere('produto', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%");
            });
        }

        // Filtro por segmento específico
        if (!empty($filtros['segmento_id'])) {
            $query->where('segmento_id', $filtros['segmento_id']);
        }

        // Filtro por status
        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        } else {
            // Por padrão mostra apenas ativos
            $query->where('status', 'ativo')
                  ->whereDate('data_fim', '>=', now());
        }

        return $query->orderBy('data_inicio', 'desc')->get();
    }
}
