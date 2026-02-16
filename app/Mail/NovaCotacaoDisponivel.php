<?php

namespace App\Mail;

use App\Models\CotacaoModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NovaCotacaoDisponivel extends Mailable
{
    use Queueable, SerializesModels;

    public CotacaoModel $cotacao;
    public string $nomeComprador;

    /**
     * Criar nova instância de mensagem
     */
    public function __construct(CotacaoModel $cotacao, string $nomeComprador)
    {
        $this->cotacao = $cotacao;
        $this->nomeComprador = $nomeComprador;
    }

    /**
     * Construir a mensagem
     */
    public function build()
    {
        return $this->subject("Nova Cotação Disponível: {$this->cotacao->produto}")
                    ->view('emails.nova-cotacao');
    }
}
