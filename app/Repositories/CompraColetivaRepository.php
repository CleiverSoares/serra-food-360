<?php

namespace App\Repositories;

use App\Models\CompraColetivaModel;
use App\Models\CompraColetivaAdesaoModel;
use App\Models\CompraColetivaOfertaModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CompraColetivaRepository
{
    /**
     * Buscar compras ativas
     */
    public function buscarAtivas(): Collection
    {
        return CompraColetivaModel::where('status', 'aberta')
            ->where('data_fim', '>=', now())
            ->with(['produto', 'adesoes', 'ofertas'])
            ->orderBy('data_fim')
            ->get();
    }

    /**
     * Buscar com filtros e paginação
     */
    public function buscarComFiltros(array $filtros = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = CompraColetivaModel::query();

        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (!empty($filtros['busca'])) {
            $query->where('titulo', 'like', "%{$filtros['busca']}%");
        }

        return $query->with(['produto', 'adesoes', 'ofertas'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Buscar por ID
     */
    public function buscarPorId(int $id): ?CompraColetivaModel
    {
        return CompraColetivaModel::with(['produto', 'proposta', 'criador', 'adesoes.comprador', 'ofertas.fornecedor'])
            ->find($id);
    }

    /**
     * Criar compra coletiva
     */
    public function criar(array $dados): CompraColetivaModel
    {
        return CompraColetivaModel::create($dados);
    }

    /**
     * Atualizar compra
     */
    public function atualizar(int $id, array $dados): bool
    {
        return CompraColetivaModel::where('id', $id)->update($dados);
    }

    /**
     * Deletar compra
     */
    public function deletar(int $id): bool
    {
        return CompraColetivaModel::destroy($id) > 0;
    }

    /**
     * Adicionar adesão
     */
    public function adicionarAdesao(array $dados): CompraColetivaAdesaoModel
    {
        $adesao = CompraColetivaAdesaoModel::create($dados);

        // Atualizar quantidade atual e contador de participantes
        $compra = CompraColetivaModel::find($dados['compra_coletiva_id']);
        $compra->increment('quantidade_atual', $dados['quantidade']);
        $compra->increment('participantes_count');

        return $adesao;
    }

    /**
     * Atualizar adesão
     */
    public function atualizarAdesao(int $adesaoId, array $dados): bool
    {
        $adesao = CompraColetivaAdesaoModel::find($adesaoId);
        
        if (!$adesao) {
            return false;
        }

        $diferencaQuantidade = ($dados['quantidade'] ?? $adesao->quantidade) - $adesao->quantidade;

        $resultado = $adesao->update($dados);

        // Atualizar quantidade atual da compra
        if ($diferencaQuantidade != 0) {
            CompraColetivaModel::where('id', $adesao->compra_coletiva_id)
                ->increment('quantidade_atual', $diferencaQuantidade);
        }

        return $resultado;
    }

    /**
     * Cancelar adesão
     */
    public function cancelarAdesao(int $adesaoId): bool
    {
        $adesao = CompraColetivaAdesaoModel::find($adesaoId);

        if (!$adesao) {
            return false;
        }

        $adesao->update(['status' => 'cancelada']);

        // Decrementar quantidade e participantes
        $compra = CompraColetivaModel::find($adesao->compra_coletiva_id);
        $compra->decrement('quantidade_atual', $adesao->quantidade);
        $compra->decrement('participantes_count');

        return true;
    }

    /**
     * Adicionar oferta de fornecedor
     */
    public function adicionarOferta(array $dados): CompraColetivaOfertaModel
    {
        return CompraColetivaOfertaModel::create($dados);
    }

    /**
     * Buscar adesão do usuário
     */
    public function buscarAdesaoUsuario(int $compraId, int $userId): ?CompraColetivaAdesaoModel
    {
        return CompraColetivaAdesaoModel::where('compra_coletiva_id', $compraId)
            ->where('comprador_id', $userId)
            ->first();
    }

    /**
     * Buscar ofertas de um fornecedor
     */
    public function buscarOfertasFornecedor(int $fornecedorId): Collection
    {
        return CompraColetivaOfertaModel::where('fornecedor_id', $fornecedorId)
            ->with('compraColetiva.produto')
            ->orderBy('ofertado_em', 'desc')
            ->get();
    }
}
