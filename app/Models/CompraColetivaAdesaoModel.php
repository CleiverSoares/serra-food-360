<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompraColetivaAdesaoModel extends Model
{
    use HasFactory;

    protected $table = 'compras_coletivas_adesoes';

    protected $fillable = [
        'compra_coletiva_id',
        'comprador_id',
        'quantidade',
        'observacoes',
        'status',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
    ];

    /**
     * Compra coletiva
     */
    public function compraColetiva(): BelongsTo
    {
        return $this->belongsTo(CompraColetivaModel::class, 'compra_coletiva_id');
    }

    /**
     * Comprador
     */
    public function comprador(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'comprador_id');
    }
}
