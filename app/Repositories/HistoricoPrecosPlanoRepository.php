<?php

namespace App\Repositories;

use App\Models\HistoricoPrecosPlanoModel;
use Illuminate\Database\Eloquent\Collection;

class HistoricoPrecosPlanoRepository
{
    /**
     * Registrar alteração de preço
     */
    public function registrar(string $chaveConfiguracao, ?float $valorAntigo, float $valorNovo, int $alteradoPor): HistoricoPrecosPlanoModel
    {
        return HistoricoPrecosPlanoModel::create([
            'chave_configuracao' => $chaveConfiguracao,
            'valor_antigo' => $valorAntigo,
            'valor_novo' => $valorNovo,
            'alterado_por' => $alteradoPor,
        ]);
    }

    /**
     * Buscar histórico de uma configuração específica
     */
    public function buscarPorChave(string $chaveConfiguracao): Collection
    {
        return HistoricoPrecosPlanoModel::where('chave_configuracao', $chaveConfiguracao)
            ->with('alteradoPor:id,name')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar todo histórico (paginado)
     */
    public function buscarTodos(int $porPagina = 50)
    {
        return HistoricoPrecosPlanoModel::with('alteradoPor:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate($porPagina);
    }

    /**
     * Buscar histórico recente (últimos N dias)
     */
    public function buscarRecente(int $dias = 30): Collection
    {
        return HistoricoPrecosPlanoModel::where('created_at', '>=', now()->subDays($dias))
            ->with('alteradoPor:id,name')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar alterações por usuário
     */
    public function buscarPorUsuario(int $userId): Collection
    {
        return HistoricoPrecosPlanoModel::where('alterado_por', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
