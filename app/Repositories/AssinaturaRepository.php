<?php

namespace App\Repositories;

use App\Models\AssinaturaModel;
use Illuminate\Support\Collection;

class AssinaturaRepository
{
    /**
     * Buscar assinatura ativa de um usuário
     */
    public function buscarAtivaPorUserId(int $userId): ?AssinaturaModel
    {
        return AssinaturaModel::where('user_id', $userId)
            ->where('status', 'ativo')
            ->latest('data_fim')
            ->first();
    }

    /**
     * Buscar todas assinaturas de um usuário
     */
    public function buscarPorUserId(int $userId): Collection
    {
        return AssinaturaModel::where('user_id', $userId)
            ->orderBy('data_inicio', 'desc')
            ->get();
    }

    /**
     * Buscar assinaturas que estão próximas do vencimento
     * 
     * @param int $dias Número de dias para vencimento
     */
    public function buscarProximasDoVencimento(int $dias): Collection
    {
        $dataLimite = now()->addDays($dias);
        
        return AssinaturaModel::where('status', 'ativo')
            ->whereDate('data_fim', '=', $dataLimite)
            ->with('usuario')
            ->get();
    }

    /**
     * Buscar assinaturas vencidas
     */
    public function buscarVencidas(): Collection
    {
        return AssinaturaModel::where('status', 'ativo')
            ->whereDate('data_fim', '<', now())
            ->with('usuario')
            ->get();
    }

    /**
     * Buscar assinaturas que devem receber aviso
     */
    public function buscarParaEnviarAviso(): Collection
    {
        $hoje = now()->startOfDay();
        
        // Avisos em 7, 3 e 1 dia
        $datas = [
            now()->addDays(7)->toDateString(),
            now()->addDays(3)->toDateString(),
            now()->addDays(1)->toDateString(),
        ];
        
        return AssinaturaModel::where('status', 'ativo')
            ->whereIn('data_fim', $datas)
            ->where(function($query) use ($hoje) {
                $query->whereNull('ultimo_aviso_enviado')
                    ->orWhere('ultimo_aviso_enviado', '<', $hoje);
            })
            ->with('usuario')
            ->get();
    }

    /**
     * Criar nova assinatura
     */
    public function criar(array $dados): AssinaturaModel
    {
        return AssinaturaModel::create($dados);
    }

    /**
     * Atualizar assinatura
     */
    public function atualizar(int $id, array $dados): bool
    {
        return AssinaturaModel::where('id', $id)->update($dados);
    }

    /**
     * Marcar assinatura como vencida
     */
    public function marcarComoVencida(int $id): bool
    {
        return $this->atualizar($id, ['status' => 'vencido']);
    }

    /**
     * Marcar aviso como enviado
     */
    public function marcarAvisoEnviado(int $id): bool
    {
        return $this->atualizar($id, ['ultimo_aviso_enviado' => now()]);
    }

    /**
     * Cancelar assinatura
     */
    public function cancelar(int $id): bool
    {
        return $this->atualizar($id, ['status' => 'cancelado']);
    }

    /**
     * Renovar assinatura
     */
    public function renovar(int $id, string $tipoPagamento): bool
    {
        $meses = $tipoPagamento === 'anual' ? 12 : 1;
        
        return $this->atualizar($id, [
            'tipo_pagamento' => $tipoPagamento,
            'data_inicio' => now(),
            'data_fim' => now()->addMonths($meses),
            'status' => 'ativo',
            'ultimo_aviso_enviado' => null,
        ]);
    }

    /**
     * Buscar por ID
     */
    public function buscarPorId(int $id): ?AssinaturaModel
    {
        return AssinaturaModel::find($id);
    }

    /**
     * Deletar assinatura
     */
    public function deletar(int $id): bool
    {
        return AssinaturaModel::where('id', $id)->delete();
    }
}
