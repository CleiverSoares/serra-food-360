<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FornecedorModel extends Model
{
    protected $table = 'fornecedores';

    protected $fillable = [
        'user_id',
        'nome_empresa',
        'categorias',
        'logo_path',
        'site_url',
        'descricao',
    ];

    protected function casts(): array
    {
        return [
            'categorias' => 'array',
        ];
    }

    /**
     * Accessor: URL completa do logo
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }

    /**
     * Relacionamento: Usuário dono do fornecedor
     */
    public function usuario()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * Boot: deletar logo ao deletar fornecedor
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($fornecedor) {
            if ($fornecedor->logo_path && Storage::disk('public')->exists($fornecedor->logo_path)) {
                Storage::disk('public')->delete($fornecedor->logo_path);
            }
        });
    }
}
