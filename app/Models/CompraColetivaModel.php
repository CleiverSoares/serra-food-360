<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompraColetivaModel extends Model
{
    use HasFactory;

    protected $table = 'compras_coletivas';

    protected $fillable = [
        'produto_catalogo_id',
        'proposta_id',
        'titulo',
        'descricao',
        'quantidade_minima',
        'quantidade_atual',
        'data_inicio',
        'data_fim',
        'status',
        'criado_por',
        'participantes_count',
    ];

    protected $casts = [
        'quantidade_minima' => 'decimal:2',
        'quantidade_atual' => 'decimal:2',
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'participantes_count' => 'integer',
    ];

    /**
     * Produto do catálogo
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(ProdutoCatalogoModel::class, 'produto_catalogo_id');
    }

    /**
     * Proposta que originou esta compra
     */
    public function proposta(): BelongsTo
    {
        return $this->belongsTo(CompraColetivaPropostaModel::class, 'proposta_id');
    }

    /**
     * Criador (admin)
     */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'criado_por');
    }

    /**
     * Adesões dos compradores
     */
    public function adesoes(): HasMany
    {
        return $this->hasMany(CompraColetivaAdesaoModel::class, 'compra_coletiva_id');
    }

    /**
     * Ofertas dos fornecedores
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(CompraColetivaOfertaModel::class, 'compra_coletiva_id');
    }
}
