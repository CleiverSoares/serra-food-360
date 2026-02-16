<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialFavoritoModel extends Model
{
    use HasFactory;

    protected $table = 'materiais_favoritos';

    protected $fillable = [
        'user_id',
        'material_id',
    ];

    /**
     * Usuário que favoritou
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * Material favoritado
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(MaterialModel::class, 'material_id');
    }
}
