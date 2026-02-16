<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CotacaoModel extends Model
{
    protected $table = 'cotacoes';

    protected $fillable = [
        'titulo',
        'descricao',
        'foto_url',
        'produto',
        'unidade',
        'quantidade_minima',
        'data_inicio',
        'data_fim',
        'status',
        'segmento_id',
        'criado_por',
    ];

    protected function casts(): array
    {
        return [
            'data_inicio' => 'date',
            'data_fim' => 'date',
            'quantidade_minima' => 'decimal:2',
        ];
    }

    /**
     * Relacionamento com segmento
     */
    public function segmento(): BelongsTo
    {
        return $this->belongsTo(SegmentoModel::class, 'segmento_id');
    }

    /**
     * Relacionamento com admin que criou
     */
    public function criador(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'criado_por');
    }

    /**
     * Relacionamento com ofertas dos fornecedores
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(CotacaoFornecedorModel::class, 'cotacao_id');
    }

    /**
     * Verifica se cotação está ativa
     */
    public function estaAtiva(): bool
    {
        return $this->status === 'ativo' 
            && $this->data_fim 
            && $this->data_fim->isFuture();
    }

    /**
     * Obter melhor oferta (menor preço)
     */
    public function melhorOferta(): ?CotacaoFornecedorModel
    {
        return $this->ofertas()->orderBy('preco_unitario', 'asc')->first();
    }

    /**
     * Obter ofertas ordenadas por preço
     */
    public function ofertasOrdenadas()
    {
        return $this->ofertas()->orderBy('preco_unitario', 'asc');
    }
}
