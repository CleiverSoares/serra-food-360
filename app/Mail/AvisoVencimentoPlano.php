<?php

namespace App\Mail;

use App\Models\AssinaturaModel;
use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AvisoVencimentoPlano extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public UserModel $usuario,
        public AssinaturaModel $assinatura,
        public int $diasRestantes
    ) {}

    /**
     * Envelope do email
     */
    public function envelope(): Envelope
    {
        $assunto = match($this->diasRestantes) {
            7 => 'Seu plano vence em 7 dias - Serra Food 360',
            3 => 'Seu plano vence em 3 dias - Serra Food 360',
            1 => 'Seu plano vence amanhã - Serra Food 360',
            default => 'Aviso de vencimento do plano - Serra Food 360',
        };

        return new Envelope(
            subject: $assunto,
        );
    }

    /**
     * Conteúdo do email
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.aviso-vencimento-plano',
            with: [
                'nomeUsuario' => $this->usuario->name,
                'plano' => ucfirst($this->assinatura->plano),
                'dataVencimento' => $this->assinatura->data_fim->format('d/m/Y'),
                'diasRestantes' => $this->diasRestantes,
                'tipoPagamento' => $this->assinatura->tipo_pagamento === 'anual' ? 'Anual' : 'Mensal',
            ]
        );
    }
}
