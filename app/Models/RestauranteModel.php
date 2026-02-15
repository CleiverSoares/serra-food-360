<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RestauranteModel extends Model
{
    protected $table = 'restaurantes';

    protected $fillable = [
        'user_id',
        'nome_estabelecimento',
        'tipo_cozinha',
        'capacidade',
        'logo_path',
        'site_url',
        'colaboradores',
        'descricao',
    ];

    /**
     * Accessor: URL completa do logo
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }

    /**
     * Relacionamento: Usuário dono do restaurante
     */
    public function usuario()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * Boot: deletar logo ao deletar restaurante
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($restaurante) {
            if ($restaurante->logo_path && Storage::disk('public')->exists($restaurante->logo_path)) {
                Storage::disk('public')->delete($restaurante->logo_path);
            }
        });
    }
}
