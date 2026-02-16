<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialModel extends Model
{
    use HasFactory;

    protected $table = 'materiais';

    protected $fillable = [
        'titulo',
        'descricao',
        'categoria',
        'tipo',
        'arquivo_path',
        'video_url',
        'link_externo',
        'thumbnail_url',
        'apenas_vip',
        'ativo',
        'views_count',
        'downloads_count',
        'criado_por',
    ];

    protected $casts = [
        'apenas_vip' => 'boolean',
        'ativo' => 'boolean',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
    ];

    /**
     * Criador do material
     */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'criado_por');
    }

    /**
     * Usuários que favoritaram
     */
    public function usuariosFavoritos(): BelongsToMany
    {
        return $this->belongsToMany(
            UserModel::class,
            'materiais_favoritos',
            'material_id',
            'user_id'
        )->withTimestamps();
    }

    /**
     * Acessos do material
     */
    public function acessos(): HasMany
    {
        return $this->hasMany(MaterialAcessoModel::class, 'material_id');
    }
}
