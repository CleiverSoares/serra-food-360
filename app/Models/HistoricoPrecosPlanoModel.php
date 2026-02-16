<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoricoPrecosPlanoModel extends Model
{
    protected $table = 'historico_precos_planos';

    const UPDATED_AT = null; // Tabela de histórico não tem updated_at

    protected $fillable = [
        'chave_configuracao',
        'valor_antigo',
        'valor_novo',
        'alterado_por',
    ];

    protected function casts(): array
    {
        return [
            'valor_antigo' => 'decimal:2',
            'valor_novo' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relacionamento com usuário que fez a alteração
     */
    public function alteradoPor(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'alterado_por');
    }

    /**
     * Obter nome legível do plano
     */
    public function getNomePlanoAttribute(): string
    {
        return match($this->chave_configuracao) {
            'plano_comum_mensal' => 'Comum Mensal',
            'plano_comum_anual' => 'Comum Anual',
            'plano_vip_mensal' => 'VIP Mensal',
            'plano_vip_anual' => 'VIP Anual',
            default => $this->chave_configuracao,
        };
    }
}
