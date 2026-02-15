<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssinaturaModel extends Model
{
    protected $table = 'assinaturas';

    protected $fillable = [
        'user_id',
        'plano',
        'tipo_pagamento',
        'data_inicio',
        'data_fim',
        'status',
        'ultimo_aviso_enviado',
    ];

    protected function casts(): array
    {
        return [
            'data_inicio' => 'date',
            'data_fim' => 'date',
            'ultimo_aviso_enviado' => 'datetime',
        ];
    }

    /**
     * Relacionamento com usuário
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * Verifica se assinatura está ativa
     */
    public function estaAtiva(): bool
    {
        return $this->status === 'ativo' 
            && $this->data_fim 
            && $this->data_fim->isFuture();
    }

    /**
     * Verifica se assinatura está vencida
     */
    public function estaVencida(): bool
    {
        return $this->data_fim && $this->data_fim->isPast();
    }

    /**
     * Dias restantes da assinatura
     */
    public function diasRestantes(): int
    {
        if (!$this->data_fim) {
            return 0;
        }

        $dias = now()->diffInDays($this->data_fim, false);
        return max(0, $dias);
    }

    /**
     * Verifica se deve enviar aviso (7, 3 ou 1 dia antes)
     */
    public function deveEnviarAviso(): bool
    {
        $dias = $this->diasRestantes();
        
        // Avisos em 7, 3 e 1 dia
        if (!in_array($dias, [7, 3, 1])) {
            return false;
        }

        // Não enviar se já enviou hoje
        if ($this->ultimo_aviso_enviado && $this->ultimo_aviso_enviado->isToday()) {
            return false;
        }

        return true;
    }

    /**
     * Marcar aviso como enviado
     */
    public function marcarAvisoEnviado(): void
    {
        $this->update(['ultimo_aviso_enviado' => now()]);
    }

    /**
     * Renovar assinatura
     */
    public function renovar(string $tipoPagamento): void
    {
        $meses = $tipoPagamento === 'anual' ? 12 : 1;
        
        $this->update([
            'tipo_pagamento' => $tipoPagamento,
            'data_inicio' => now(),
            'data_fim' => now()->addMonths($meses),
            'status' => 'ativo',
            'ultimo_aviso_enviado' => null,
        ]);
    }
}
