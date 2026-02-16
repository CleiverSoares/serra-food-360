<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialAcessoModel extends Model
{
    use HasFactory;

    protected $table = 'materiais_acessos';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'material_id',
        'tipo_acesso',
        'acessado_em',
    ];

    protected $casts = [
        'acessado_em' => 'datetime',
    ];

    /**
     * Usuário que acessou
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * Material acessado
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(MaterialModel::class, 'material_id');
    }
}
