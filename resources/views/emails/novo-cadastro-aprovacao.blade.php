<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Cadastro - Serra Food 360</title>
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
        .header img {
            max-width: 180px;
            height: auto;
        }
        .content {
            padding: 40px 30px;
        }
        .alert-box {
            background-color: #FEF3C7;
            border-left: 4px solid: #F59E0B;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .cta-button {
            display: inline-block;
            background-color: #22C55E;
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 30px 0;
        }
        .user-info {
            background-color: #F9FAFB;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-row {
            padding: 10px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #6B7280;
            display: inline-block;
            min-width: 100px;
        }
        .value {
            color: #1F2937;
            font-weight: 500;
        }
        .footer {
            background-color: #F9FAFB;
            padding: 30px;
            text-align: center;
            color: #6B7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo-serra-food.png') }}" alt="Serra Food 360">
        </div>

        <!-- Content -->
        <div class="content">
            <h1 style="color: #1F2937; font-size: 24px; margin: 0 0 20px 0;">
                Novo Cadastro Pendente 🔔
            </h1>

            <p style="color: #4B5563; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                Um novo usuário acabou de se cadastrar na plataforma <strong>Serra Food 360</strong> e está aguardando aprovação.
            </p>

            <!-- Alert Box -->
            <div class="alert-box">
                <p style="margin: 0; color: #92400E; font-weight: 600;">
                    ⏱️ Ação necessária: Aprove ou rejeite este cadastro
                </p>
            </div>

            <!-- User Info -->
            <div class="user-info">
                <h3 style="margin: 0 0 15px 0; color: #1F2937;">Dados do Usuário:</h3>
                <div class="info-row">
                    <span class="label">Nome:</span>
                    <span class="value">{{ $nomeUsuario }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value">{{ $emailUsuario }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Tipo:</span>
                    <span class="value">{{ $roleUsuario }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Data:</span>
                    <span class="value">{{ $dataCadastro }}</span>
                </div>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ $linkAprovacao }}" class="cta-button">
                    Acessar Painel de Aprovações
                </a>
            </div>

            <p style="color: #6B7280; font-size: 14px; line-height: 1.6; margin: 30px 0 0 0;">
                <strong>Importante:</strong> Acesse o painel administrativo para revisar os dados do novo usuário e aprovar ou rejeitar o cadastro.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                <strong>Serra Food 360</strong><br>
                Sistema de Gestão e Conectividade
            </p>
            <p style="margin: 10px 0; font-size: 12px; color: #9CA3AF;">
                Este é um e-mail automático. Por favor, não responda diretamente a esta mensagem.
            </p>
        </div>
    </div>
</body>
</html>
