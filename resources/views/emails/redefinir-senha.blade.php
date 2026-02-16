@extends('emails.layouts.base')

@section('titulo', 'Redefinição de Senha - Serra Food 360')

@section('header-title', '🔐 Redefinição de Senha')
@section('header-subtitle', 'Solicitação de nova senha')

@section('content')
    <h1>Esqueceu sua senha?</h1>
    
    <p>Olá! 👋</p>
    
    <p>Recebemos uma solicitação para redefinir a senha da sua conta no <strong>Serra Food 360</strong>.</p>

    <p>Para criar uma nova senha de forma segura, basta clicar no botão abaixo:</p>

    <!-- Botão CTA Principal -->
    <div class="button-container">
        <a href="{{ route('password.reset', ['token' => $token, 'email' => request()->email ?? '']) }}" class="button">
            🔒 Redefinir Minha Senha
        </a>
    </div>

    <!-- Alert de Validade -->
    <div class="alert-box warning">
        <p class="alert-title">⏱️ Link válido por 1 hora</p>
        <p class="alert-text">
            Por questões de segurança, este link de redefinição expira em 1 hora. Se precisar de um novo link, solicite novamente pela plataforma.
        </p>
    </div>

    <!-- Link Alternativo -->
    <h2 style="font-size: 16px; margin-top: 30px;">Link alternativo</h2>
    <p style="font-size: 14px;">Se o botão acima não funcionar, copie e cole o seguinte endereço no seu navegador:</p>
    
    <div class="info-box">
        <p class="info-value" style="word-break: break-all; font-size: 12px; color: #3B82F6; font-family: monospace;">
            {{ route('password.reset', ['token' => $token, 'email' => request()->email ?? '']) }}
        </p>
    </div>

    <hr class="divider">

    <!-- Informações de Segurança -->
    <div class="alert-box info">
        <p class="alert-title">🔒 Dica de Segurança</p>
        <p class="alert-text">
            <strong>Não solicitou esta redefinição?</strong><br>
            Se você não pediu para redefinir sua senha, pode ignorar este email com tranquilidade. Sua senha atual permanecerá inalterada e segura.
        </p>
    </div>

    <div style="background-color: #FEF3C7; border-radius: 8px; padding: 16px; margin-top: 20px; border-left: 4px solid #F59E0B;">
        <p style="font-size: 14px; color: #92400E; margin: 0;">
            <strong>⚠️ Importante:</strong> Nunca compartilhe este link com outras pessoas. Nossa equipe nunca pedirá sua senha por email ou telefone.
        </p>
    </div>

    <p style="margin-top: 30px; font-size: 14px; color: #6B7280; text-align: center;">
        Qualquer dúvida, estamos à disposição! 😊
    </p>
@endsection
