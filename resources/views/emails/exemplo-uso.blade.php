{{-- 
    EXEMPLO DE USO DO TEMPLATE BASE DE EMAIL
    Este arquivo serve apenas como referência, não será usado em produção
--}}

@extends('emails.layouts.base')

@section('titulo', 'Título do Email - Serra Food 360')

@section('header-title', 'Bem-vindo!')
@section('header-subtitle', 'Estamos felizes em ter você conosco')

@section('content')
    <h1>Olá, {{ $usuario->name ?? 'Usuário' }}!</h1>
    
    <p>Este é um exemplo de como usar o template base de email.</p>

    <p>Você pode usar diversos componentes pré-estilizados:</p>

    <!-- Alert Box -->
    <div class="alert-box info">
        <p class="alert-title">💡 Informação</p>
        <p class="alert-text">Use a classe "info", "success", "warning" ou "danger"</p>
    </div>

    <div class="alert-box success">
        <p class="alert-title">✅ Sucesso</p>
        <p class="alert-text">Sua ação foi concluída com sucesso!</p>
    </div>

    <div class="alert-box warning">
        <p class="alert-title">⚠️ Atenção</p>
        <p class="alert-text">Revise as informações antes de prosseguir</p>
    </div>

    <div class="alert-box danger">
        <p class="alert-title">🚨 Urgente</p>
        <p class="alert-text">Ação imediata necessária</p>
    </div>

    <!-- Botão CTA -->
    <div class="button-container">
        <a href="#" class="button">
            Botão Principal
        </a>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <p class="info-label">Informação</p>
        <p class="info-value">Valor da informação</p>
        
        <p class="info-label">Outra Info</p>
        <p class="info-value">Outro valor</p>
    </div>

    <!-- Details Box (tabela) -->
    <div class="details-box">
        <div class="detail-row">
            <span class="detail-label">Nome:</span>
            <span class="detail-value">João Silva</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value">joao@example.com</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Data:</span>
            <span class="detail-value">15/02/2026</span>
        </div>
    </div>

    <!-- Divisor -->
    <hr class="divider">

    <!-- Parágrafo final -->
    <p style="font-size: 14px; color: #6B7280;">
        Se você tiver dúvidas, não hesite em entrar em contato conosco.
    </p>
@endsection

@section('footer-extra')
    <p class="footer-text">
        <a href="#" class="footer-link">Gerenciar Preferências</a> | 
        <a href="#" class="footer-link">Cancelar Inscrição</a>
    </p>
@endsection
