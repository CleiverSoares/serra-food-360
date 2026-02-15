<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContatoModel extends Model
{
    protected $table = 'contatos';
    
    protected $fillable = [
        'user_id',
        'tipo',
        'valor',
        'is_principal',
        'verificado_em'
    ];

    protected $casts = [
        'is_principal' => 'boolean',
        'verificado_em' => 'datetime'
    ];

    /**
     * Relacionamento com usuário
     */
    public function usuario()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    /**
     * Retorna telefone formatado (brasileiro)
     */
    public function formatado(): string
    {
        $valor = preg_replace('/[^0-9]/', '', $this->valor);
        
        // Celular (11 dígitos): (27) 99888-7766
        if (strlen($valor) === 11) {
            return '(' . substr($valor, 0, 2) . ') ' . substr($valor, 2, 5) . '-' . substr($valor, 7);
        }
        
        // Fixo (10 dígitos): (27) 3267-1234
        if (strlen($valor) === 10) {
            return '(' . substr($valor, 0, 2) . ') ' . substr($valor, 2, 4) . '-' . substr($valor, 6);
        }

        return $this->valor;
    }

    /**
     * Retorna link do WhatsApp
     */
    public function linkWhatsApp(): string
    {
        if ($this->tipo !== 'whatsapp') {
            return '#';
        }

        $numero = preg_replace('/[^0-9]/', '', $this->valor);
        return "https://wa.me/55{$numero}";
    }

    /**
     * Apenas números do telefone
     */
    public function apenasNumeros(): string
    {
        return preg_replace('/[^0-9]/', '', $this->valor);
    }
}
