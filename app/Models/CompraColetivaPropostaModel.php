<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompraColetivaPropostaModel extends Model
{
    use HasFactory;

    protected $table = 'compras_coletivas_propostas';

    protected $fillable = [
        'produto_catalogo_id',
        'proposto_por',
        'justificativa',
        'status',
        'data_votacao_inicio',
        'data_votacao_fim',
        'votos_count',
    ];

    protected $casts = [
        'data_votacao_inicio' => 'date',
        'data_votacao_fim' => 'date',
        'votos_count' => 'integer',
    ];

    /**
     * Produto do catálogo
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(ProdutoCatalogoModel::class, 'produto_catalogo_id');
    }

    /**
     * Usuário que propôs
     */
    public function propositor(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'proposto_por');
    }

    /**
     * Votos recebidos
     */
    public function votos(): HasMany
    {
        return $this->hasMany(CompraColetivaVotoModel::class, 'proposta_id');
    }

    /**
     * Compra coletiva gerada (se aprovada)
     */
    public function compraColetiva(): BelongsTo
    {
        return $this->belongsTo(CompraColetivaModel::class, 'proposta_id');
    }
}
