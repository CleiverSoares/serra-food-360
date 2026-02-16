<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CotacaoFornecedorModel extends Model
{
    protected $table = 'cotacao_fornecedores';

    protected $fillable = [
        'cotacao_id',
        'fornecedor_id',
        'preco_unitario',
        'observacoes',
        'prazo_entrega',
        'quantidade_disponivel',
        'destaque',
    ];

    protected function casts(): array
    {
        return [
            'preco_unitario' => 'decimal:2',
            'quantidade_disponivel' => 'decimal:2',
            'destaque' => 'boolean',
        ];
    }

    /**
     * Relacionamento com cotação
     */
    public function cotacao(): BelongsTo
    {
        return $this->belongsTo(CotacaoModel::class, 'cotacao_id');
    }

    /**
     * Relacionamento com fornecedor
     */
    public function fornecedor(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'fornecedor_id');
    }
}
