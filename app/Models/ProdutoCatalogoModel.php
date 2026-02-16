<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdutoCatalogoModel extends Model
{
    use HasFactory;

    protected $table = 'produtos_catalogo';

    protected $fillable = [
        'nome',
        'descricao',
        'unidade_medida',
        'categoria_id',
        'imagem_url',
        'ativo',
        'propostas_count',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'propostas_count' => 'integer',
    ];

    /**
     * Categoria (segmento)
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(SegmentoModel::class, 'categoria_id');
    }

    /**
     * Propostas deste produto
     */
    public function propostas(): HasMany
    {
        return $this->hasMany(CompraColetivaPropostaModel::class, 'produto_catalogo_id');
    }

    /**
     * Compras coletivas deste produto
     */
    public function comprasColetivas(): HasMany
    {
        return $this->hasMany(CompraColetivaModel::class, 'produto_catalogo_id');
    }
}
