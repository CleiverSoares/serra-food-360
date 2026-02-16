<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompraColetivaOfertaModel extends Model
{
    use HasFactory;

    protected $table = 'compras_coletivas_ofertas';

    public $timestamps = false;

    protected $fillable = [
        'compra_coletiva_id',
        'fornecedor_id',
        'preco_unitario',
        'quantidade_minima',
        'condicoes',
        'status',
        'ofertado_em',
    ];

    protected $casts = [
        'preco_unitario' => 'decimal:2',
        'quantidade_minima' => 'decimal:2',
        'ofertado_em' => 'datetime',
    ];

    /**
     * Compra coletiva
     */
    public function compraColetiva(): BelongsTo
    {
        return $this->belongsTo(CompraColetivaModel::class, 'compra_coletiva_id');
    }

    /**
     * Fornecedor
     */
    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'fornecedor_id');
    }
}
