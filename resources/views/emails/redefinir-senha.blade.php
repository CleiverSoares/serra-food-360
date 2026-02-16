<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha - Serra Food 360</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background-color: #22C55E;
            padding: 40px 20px;
            text-align: center;
        }
        .header-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0 0 0;
        }
        .content {
            padding: 40px 30px;
        }
        h1 {
            color: #1F2937;
            font-size: 24px;
            margin: 0 0 20px 0;
        }
        p {
            color: #4B5563;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 15px 0;
        }
        .alert-box {
            background-color: #DBEAFE;
            border-left: 4px solid: #3B82F6;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .alert-text {
            font-size: 14px;
            color: #1E40AF;
            margin: 0;
        }
        .button-container {
            text-align: center;
            margin: 40px 0;
        }
        .button {
            display: inline-block;
            background-color: #22C55E;
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #16A34A;
        }
        .info-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        .info-label {
            font-size: 12px;
            color: #6B7280;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }
        .info-value {
            font-size: 14px;
            color: #1F2937;
            margin: 0 0 15px 0;
        }
        .info-value:last-child {
            margin-bottom: 0;
        }
        .footer {
            background-color: #F9FAFB;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #E5E7EB;
        }
        .footer-text {
            color: #6B7280;
            font-size: 14px;
            margin: 5px 0;
        }
        .footer-link {
            color: #22C55E;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .button {
                padding: 14px 24px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div style="background-color: #ffffff; border-radius: 12px; padding: 15px; display: inline-block;">
                <svg width="120" height="40" viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                    <text x="10" y="28" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="#22C55E">Serra Food</text>
                    <text x="92" y="28" font-family="Arial, sans-serif" font-size="16" fill="#666">360</text>
                </svg>
            </div>
            <h2 class="header-title">Redefinição de Senha</h2>
        </div>

        <!-- Content -->
        <div class="content">
            <h1>Esqueceu sua senha?</h1>
            
            <p>Olá!</p>
            
            <p>Recebemos uma solicitação para redefinir a senha da sua conta no <strong>Serra Food 360</strong>.</p>

            <p>Para criar uma nova senha, clique no botão abaixo:</p>

            <!-- Botão CTA -->
            <div class="button-container">
                <a href="{{ route('password.reset', ['token' => $token, 'email' => request()->email ?? '']) }}" class="button">
                    🔒 Redefinir Minha Senha
                </a>
            </div>

            <!-- Alert Box -->
            <div class="alert-box">
                <p class="alert-text">
                    <strong>⏱️ Link válido por 1 hora</strong><br>
                    Por questões de segurança, este link expira em 1 hora.
                </p>
            </div>

            <!-- Informações -->
            <p>Se o botão acima não funcionar, copie e cole o seguinte link no seu navegador:</p>
            
            <div class="info-box">
                <p class="info-value" style="word-break: break-all; font-size: 12px; color: #3B82F6;">
                    {{ route('password.reset', ['token' => $token, 'email' => request()->email ?? '']) }}
                </p>
            </div>

            <!-- Segurança -->
            <hr style="border: none; border-top: 1px solid #E5E7EB; margin: 30px 0;">
            
            <p style="font-size: 14px; color: #6B7280;">
                <strong>Não solicitou esta redefinição?</strong><br>
                Se você não pediu para redefinir sua senha, ignore este email. Sua senha atual permanecerá inalterada.
            </p>

            <p style="font-size: 14px; color: #6B7280;">
                Por segurança, nunca compartilhe este link com ninguém.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">
                <strong>Serra Food 360</strong><br>
                Conectando o setor de food service da região serrana
            </p>
            <p class="footer-text">
                Dúvidas? <a href="mailto:contato@serrafood360.com.br" class="footer-link">contato@serrafood360.com.br</a>
            </p>
            <p class="footer-text" style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} Serra Food 360. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>
