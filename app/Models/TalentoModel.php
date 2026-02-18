<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TalentoModel extends Model
{
    protected $table = 'talentos';

    protected $fillable = [
        'nome',
        'whatsapp',
        'cargo',
        'mini_curriculo',
        'pretensao',
        'tipo_cobranca',
        'foto_path',
        'curriculo_path',
        'carta_recomendacao_path',
        'ativo',
        'disponibilidade',
    ];

    protected function casts(): array
    {
        return [
            'pretensao' => 'decimal:2',
            'ativo' => 'boolean',
        ];
    }

    /**
     * Accessor: URL completa da foto
     */
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_path ? Storage::url($this->foto_path) : null;
    }

    /**
     * Accessor: URL completa do currículo
     */
    public function getCurriculoUrlAttribute(): ?string
    {
        return $this->curriculo_pdf_path ? Storage::url($this->curriculo_pdf_path) : null;
    }

    /**
     * Accessor: URL completa da carta de recomendação
     */
    public function getCartaRecomendacaoUrlAttribute(): ?string
    {
        return $this->carta_recomendacao_path ? Storage::url($this->carta_recomendacao_path) : null;
    }

    /**
     * Boot: deletar arquivos ao deletar talento
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($talento) {
            $disk = Storage::disk('public');
            
            if ($talento->foto_path && $disk->exists($talento->foto_path)) {
                $disk->delete($talento->foto_path);
            }
            
            if ($talento->curriculo_pdf_path && $disk->exists($talento->curriculo_pdf_path)) {
                $disk->delete($talento->curriculo_pdf_path);
            }
            
            if ($talento->carta_recomendacao_path && $disk->exists($talento->carta_recomendacao_path)) {
                $disk->delete($talento->carta_recomendacao_path);
            }
        });
    }
}
