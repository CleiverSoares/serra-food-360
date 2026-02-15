<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Vencimento - Serra Food 360</title>
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
            border-left: 4px solid #F59E0B;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .alert-box.urgent {
            background-color: #FEE2E2;
            border-left-color: #EF4444;
        }
        .alert-title {
            font-size: 18px;
            font-weight: bold;
            color: #1F2937;
            margin: 0 0 10px 0;
        }
        .alert-text {
            font-size: 16px;
            color: #4B5563;
            margin: 0;
            line-height: 1.6;
        }
        .details-box {
            background-color: #F9FAFB;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6B7280;
        }
        .detail-value {
            font-weight: 600;
            color: #1F2937;
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
        .footer {
            background-color: #F9FAFB;
            padding: 30px;
            text-align: center;
            color: #6B7280;
            font-size: 14px;
        }
        .footer a {
            color: #22C55E;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
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
                Olá, {{ $nomeUsuario }}!
            </h1>

            <p style="color: #4B5563; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                Estamos entrando em contato para informar sobre o vencimento do seu plano <strong>{{ $plano }}</strong> na plataforma Serra Food 360.
            </p>

            <!-- Alert Box -->
            <div class="alert-box {{ $diasRestantes <= 1 ? 'urgent' : '' }}">
                <p class="alert-title">
                    @if($diasRestantes === 1)
                        ⚠️ Seu plano vence amanhã!
                    @elseif($diasRestantes === 3)
                        ⏰ Seu plano vence em 3 dias
                    @else
                        📅 Seu plano vence em {{ $diasRestantes }} dias
                    @endif
                </p>
                <p class="alert-text">
                    Para continuar aproveitando todos os recursos da plataforma, renove sua assinatura antes de <strong>{{ $dataVencimento }}</strong>.
                </p>
            </div>

            <!-- Details -->
            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Plano Atual:</span>
                    <span class="detail-value">{{ $plano }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipo de Pagamento:</span>
                    <span class="detail-value">{{ $tipoPagamento }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Data de Vencimento:</span>
                    <span class="detail-value">{{ $dataVencimento }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Dias Restantes:</span>
                    <span class="detail-value" style="color: {{ $diasRestantes <= 1 ? '#EF4444' : '#F59E0B' }};">
                        {{ $diasRestantes }} {{ $diasRestantes === 1 ? 'dia' : 'dias' }}
                    </span>
                </div>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="cta-button">
                    Renovar Minha Assinatura
                </a>
            </div>

            <p style="color: #6B7280; font-size: 14px; line-height: 1.6; margin: 30px 0 0 0;">
                <strong>O que acontece se meu plano vencer?</strong><br>
                Caso seu plano não seja renovado até {{ $dataVencimento }}, seu acesso à plataforma será suspenso automaticamente. Você poderá reativar sua conta a qualquer momento renovando sua assinatura.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                <strong>Serra Food 360</strong><br>
                Conectando produtores, fornecedores e talentos
            </p>
            <p style="margin: 10px 0;">
                <a href="{{ url('/') }}">Visitar site</a> • 
                <a href="mailto:contato@serrafood360.com.br">Contato</a>
            </p>
            <p style="margin: 10px 0; font-size: 12px; color: #9CA3AF;">
                Este é um e-mail automático. Por favor, não responda diretamente a esta mensagem.
            </p>
        </div>
    </div>
</body>
</html>
