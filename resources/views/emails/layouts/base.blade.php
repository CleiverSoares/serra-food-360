<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Serra Food 360')</title>
    <style>
        /* Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        
        /* Container Principal */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        /* Header */
        .header {
            background-color: #22C55E;
            padding: 40px 20px;
            text-align: center;
        }
        .header-logo-box {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 15px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .header-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .header-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin: 8px 0 0 0;
        }
        
        /* Content */
        .content {
            padding: 40px 30px;
        }
        
        /* Typography */
        h1 {
            color: #1F2937;
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 20px 0;
        }
        h2 {
            color: #1F2937;
            font-size: 20px;
            font-weight: bold;
            margin: 30px 0 15px 0;
        }
        p {
            color: #4B5563;
            font-size: 16px;
            margin: 0 0 15px 0;
        }
        
        /* Alert Boxes */
        .alert-box {
            border-left: 4px solid;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .alert-box.info {
            background-color: #DBEAFE;
            border-left-color: #3B82F6;
        }
        .alert-box.success {
            background-color: #D1FAE5;
            border-left-color: #10B981;
        }
        .alert-box.warning {
            background-color: #FEF3C7;
            border-left-color: #F59E0B;
        }
        .alert-box.danger {
            background-color: #FEE2E2;
            border-left-color: #EF4444;
        }
        .alert-title {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
            margin: 0 0 8px 0;
        }
        .alert-text {
            font-size: 14px;
            color: #4B5563;
            margin: 0;
        }
        
        /* Info Box */
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
            letter-spacing: 0.5px;
            margin: 0 0 5px 0;
        }
        .info-value {
            font-size: 14px;
            color: #1F2937;
            font-weight: 500;
            margin: 0 0 15px 0;
        }
        .info-value:last-child {
            margin-bottom: 0;
        }
        
        /* Details Box (tabela de dados) */
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
            font-size: 14px;
        }
        .detail-value {
            font-weight: 600;
            color: #1F2937;
            font-size: 14px;
            text-align: right;
        }
        
        /* Button */
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
        .button.secondary {
            background-color: #3B82F6;
        }
        .button.secondary:hover {
            background-color: #2563EB;
        }
        
        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #E5E7EB;
            margin: 30px 0;
        }
        
        /* Footer */
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
        .footer-link:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            .button {
                padding: 14px 24px;
                font-size: 14px;
            }
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo-box">
                <svg width="120" height="40" viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                    <text x="10" y="28" font-family="Arial, sans-serif" font-size="20" font-weight="bold" fill="#22C55E">Serra Food</text>
                    <text x="92" y="28" font-family="Arial, sans-serif" font-size="16" fill="#666">360</text>
                </svg>
            </div>
            @if(trim($__env->yieldContent('header-title')))
                <h2 class="header-title">@yield('header-title')</h2>
            @endif
            @if(trim($__env->yieldContent('header-subtitle')))
                <p class="header-subtitle">@yield('header-subtitle')</p>
            @endif
        </div>

        <!-- Content -->
        <div class="content">
            @yield('content')
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
            @if(trim($__env->yieldContent('footer-extra')))
                @yield('footer-extra')
            @endif
            <p class="footer-text" style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} Serra Food 360. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>
