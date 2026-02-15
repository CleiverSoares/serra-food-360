<?php

namespace App\Mail;

use App\Models\UserModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NovoCadastroParaAprovacao extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public UserModel $usuario
    ) {}

    /**
     * Envelope do email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo Cadastro Aguardando Aprovação - Serra Food 360',
        );
    }

    /**
     * Conteúdo do email
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.novo-cadastro-aprovacao',
            with: [
                'nomeUsuario' => $this->usuario->name,
                'emailUsuario' => $this->usuario->email,
                'roleUsuario' => ucfirst($this->usuario->role),
                'dataCadastro' => $this->usuario->created_at->format('d/m/Y H:i'),
                'linkAprovacao' => route('admin.usuarios.index'),
            ]
        );
    }
}
