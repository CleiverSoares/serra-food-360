<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompraColetivaVotoModel extends Model
{
    use HasFactory;

    protected $table = 'compras_coletivas_votos';

    public $timestamps = false;

    protected $fillable = [
        'proposta_id',
        'user_id',
        'votado_em',
    ];

    protected $casts = [
        'votado_em' => 'datetime',
    ];

    /**
     * Proposta votada
     */
    public function proposta(): BelongsTo
    {
        return $this->belongsTo(CompraColetivaPropostaModel::class, 'proposta_id');
    }

    /**
     * Usuário que votou
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
