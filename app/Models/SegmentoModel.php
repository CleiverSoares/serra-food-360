<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SegmentoModel extends Model
{
    protected $table = 'segmentos';

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'icone',
        'cor',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    /**
     * Usuários deste segmento
     */
    public function usuarios()
    {
        return $this->belongsToMany(UserModel::class, 'user_segmentos', 'segmento_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Escopo: apenas segmentos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
