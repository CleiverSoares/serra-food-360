<?php

namespace App\Repositories;

use App\Models\ProdutoCatalogoModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProdutoCatalogoRepository
{
    /**
     * Buscar produtos ativos com filtros
     */
    public function buscarComFiltros(array $filtros = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = ProdutoCatalogoModel::query()->where('ativo', true);

        if (!empty($filtros['busca'])) {
            $query->where('nome', 'like', "%{$filtros['busca']}%");
        }

        if (!empty($filtros['categoria_id'])) {
            $query->where('categoria_id', $filtros['categoria_id']);
        }

        return $query->with('categoria')
            ->orderBy('propostas_count', 'desc')
            ->orderBy('nome')
            ->paginate($perPage);
    }

    /**
     * Buscar por ID
     */
    public function buscarPorId(int $id): ?ProdutoCatalogoModel
    {
        return ProdutoCatalogoModel::with('categoria')->find($id);
    }

    /**
     * Criar produto no catálogo
     */
    public function criar(array $dados): ProdutoCatalogoModel
    {
        return ProdutoCatalogoModel::create($dados);
    }

    /**
     * Atualizar produto
     */
    public function atualizar(int $id, array $dados): bool
    {
        return ProdutoCatalogoModel::where('id', $id)->update($dados);
    }

    /**
     * Deletar produto
     */
    public function deletar(int $id): bool
    {
        return ProdutoCatalogoModel::destroy($id) > 0;
    }

    /**
     * Buscar produtos similares por nome (anti-duplicata)
     */
    public function buscarSimilares(string $nome, int $limite = 5): Collection
    {
        return ProdutoCatalogoModel::where('ativo', true)
            ->where('nome', 'like', "%{$nome}%")
            ->orderBy('propostas_count', 'desc')
            ->limit($limite)
            ->get();
    }

    /**
     * Incrementar contador de propostas
     */
    public function incrementarPropostas(int $id): bool
    {
        return ProdutoCatalogoModel::where('id', $id)->increment('propostas_count');
    }

    /**
     * Buscar todos ativos para autocomplete
     */
    public function buscarParaAutocomplete(string $termo): Collection
    {
        return ProdutoCatalogoModel::where('ativo', true)
            ->where('nome', 'like', "%{$termo}%")
            ->orderBy('propostas_count', 'desc')
            ->limit(10)
            ->get(['id', 'nome', 'unidade_medida']);
    }
}
