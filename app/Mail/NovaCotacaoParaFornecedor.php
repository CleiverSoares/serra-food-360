<?php

namespace App\Mail;

use App\Models\CotacaoModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NovaCotacaoParaFornecedor extends Mailable
{
    use Queueable, SerializesModels;

    public CotacaoModel $cotacao;
    public string $nomeFornecedor;

    /**
     * Criar nova instância de mensagem
     */
    public function __construct(CotacaoModel $cotacao, string $nomeFornecedor)
    {
        $this->cotacao = $cotacao;
        $this->nomeFornecedor = $nomeFornecedor;
    }

    /**
     * Construir a mensagem
     */
    public function build()
    {
        return $this->subject("Nova Oportunidade: {$this->cotacao->produto}")
                    ->view('emails.nova-cotacao-fornecedor');
    }
}
