<?php

namespace App\Services;

use App\Mail\AvisoVencimentoPlano;
use App\Models\AssinaturaModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Enviar aviso de vencimento de plano
     */
    public function enviarAvisoVencimento(
        UserModel $usuario, 
        AssinaturaModel $assinatura, 
        int $diasRestantes
    ): void {
        Mail::to($usuario->email)->send(
            new AvisoVencimentoPlano($usuario, $assinatura, $diasRestantes)
        );
    }

    /**
     * Enviar email de renovação bem-sucedida
     */
    public function enviarConfirmacaoRenovacao(
        UserModel $usuario, 
        AssinaturaModel $assinatura
    ): void {
        // TODO: Criar mailable para confirmação de renovação
        // Mail::to($usuario->email)->send(new RenovacaoConfirmada($usuario, $assinatura));
    }

    /**
     * Enviar email de cancelamento
     */
    public function enviarConfirmacaoCancelamento(
        UserModel $usuario, 
        AssinaturaModel $assinatura
    ): void {
        // TODO: Criar mailable para confirmação de cancelamento
        // Mail::to($usuario->email)->send(new AssinaturaCancelada($usuario, $assinatura));
    }
}
