<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompradorModel extends Model
{
    protected $table = 'compradores';

    protected $fillable = [
        'user_id',
        'cnpj',
        'nome_negocio',
        'tipo_negocio',
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
     * Relacionamento: Usuário dono do negócio
     */
    public function usuario()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * Boot: deletar logo ao deletar comprador
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($comprador) {
            if ($comprador->logo_path && Storage::disk('public')->exists($comprador->logo_path)) {
                Storage::disk('public')->delete($comprador->logo_path);
            }
        });
    }
}
