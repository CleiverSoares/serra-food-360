<?php

namespace App\Repositories;

use App\Models\CotacaoFornecedorModel;
use Illuminate\Database\Eloquent\Collection;

class CotacaoFornecedorRepository
{
    /**
     * Buscar ofertas de uma cotação
     */
    public function buscarPorCotacao(int $cotacaoId): Collection
    {
        return CotacaoFornecedorModel::where('cotacao_id', $cotacaoId)
            ->with(['fornecedor.fornecedor', 'fornecedor.enderecoPrincipal'])
            ->orderBy('preco_unitario', 'asc')
            ->get();
    }

    /**
     * Buscar oferta específica
     */
    public function buscarPorId(int $id): ?CotacaoFornecedorModel
    {
        return CotacaoFornecedorModel::with(['fornecedor.fornecedor', 'cotacao'])
            ->find($id);
    }

    /**
     * Verificar se fornecedor já tem oferta nesta cotação
     */
    public function fornecedorTemOferta(int $cotacaoId, int $fornecedorId): bool
    {
        return CotacaoFornecedorModel::where('cotacao_id', $cotacaoId)
            ->where('fornecedor_id', $fornecedorId)
            ->exists();
    }

    /**
     * Buscar oferta específica por cotação e fornecedor
     */
    public function buscarPorCotacaoEFornecedor(int $cotacaoId, int $fornecedorId): ?CotacaoFornecedorModel
    {
        return CotacaoFornecedorModel::where('cotacao_id', $cotacaoId)
            ->where('fornecedor_id', $fornecedorId)
            ->first();
    }

    /**
     * Criar oferta de fornecedor
     */
    public function criar(array $dados): CotacaoFornecedorModel
    {
        return CotacaoFornecedorModel::create($dados);
    }

    /**
     * Atualizar oferta
     */
    public function atualizar(CotacaoFornecedorModel $oferta, array $dados): bool
    {
        return $oferta->update($dados);
    }

    /**
     * Deletar oferta
     */
    public function deletar(CotacaoFornecedorModel $oferta): bool
    {
        return $oferta->delete();
    }

    /**
     * Marcar oferta como destaque (melhor preço)
     */
    public function marcarDestaque(int $cotacaoId, int $ofertaId): void
    {
        // Remove destaque de todas as ofertas da cotação
        CotacaoFornecedorModel::where('cotacao_id', $cotacaoId)
            ->update(['destaque' => false]);

        // Marca apenas a oferta escolhida
        CotacaoFornecedorModel::where('id', $ofertaId)
            ->update(['destaque' => true]);
    }
}
