@extends('emails.layouts.base')

@section('titulo', 'Nova Oportunidade de Venda!')

@section('header-title', '🎯 Nova Cotação Disponível!')
@section('header-subtitle', 'Adicione sua oferta e aumente suas vendas')

@section('content')
    <p style="margin: 0 0 16px 0; color: #374151; font-size: 16px; line-height: 1.6;">
        Olá, <strong>{{ $nomeFornecedor }}</strong>!
    </p>

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 16px; line-height: 1.6;">
        Temos uma <strong>nova oportunidade de venda</strong> para você! Uma cotação foi aberta no segmento <span style="background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 6px; font-weight: 600;">{{ $cotacao->segmento->nome }}</span>.
    </p>

    <!-- Card do Produto -->
    <div style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); border-radius: 16px; padding: 24px; margin: 0 0 24px 0; color: white;">
        <h2 style="margin: 0 0 8px 0; font-size: 28px; font-weight: 700; color: white;">
            {{ $cotacao->produto }}
        </h2>
        <p style="margin: 0 0 20px 0; font-size: 18px; color: rgba(255, 255, 255, 0.95);">
            {{ $cotacao->titulo }}
        </p>
        
        <div style="display: flex; gap: 20px; flex-wrap: wrap; font-size: 14px; color: rgba(255, 255, 255, 0.9);">
            <div style="display: flex; align-items: center; gap: 6px;">
                <span>📦</span>
                <span><strong>Unidade:</strong> {{ $cotacao->unidade }}</span>
            </div>
            
            @if($cotacao->quantidade_minima)
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span>📊</span>
                    <span><strong>Mín:</strong> {{ number_format($cotacao->quantidade_minima, 0, ',', '.') }} {{ $cotacao->unidade }}</span>
                </div>
            @endif
            
            <div style="display: flex; align-items: center; gap: 6px;">
                <span>📅</span>
                <span><strong>Válido até:</strong> {{ $cotacao->data_fim->format('d/m/Y') }}</span>
            </div>

            <div style="display: flex; align-items: center; gap: 6px;">
                <span>🏆</span>
                <span><strong>Ofertas:</strong> {{ $cotacao->ofertas->count() }}</span>
            </div>
        </div>
    </div>

    @if($cotacao->descricao)
        <div style="background: #F3F4F6; border-left: 4px solid #10B981; padding: 16px; border-radius: 8px; margin: 0 0 24px 0;">
            <p style="margin: 0; color: #374151; font-size: 14px; line-height: 1.6;">
                <strong style="color: #10B981;">ℹ️ Detalhes:</strong><br>
                {{ $cotacao->descricao }}
            </p>
        </div>
    @endif

    <!-- Melhor Oferta Atual -->
    @php
        $melhorOferta = $cotacao->melhorOferta();
    @endphp

    @if($melhorOferta)
        <div style="background: #FEF3C7; border: 2px solid #F59E0B; border-radius: 12px; padding: 20px; margin: 0 0 24px 0;">
            <p style="margin: 0 0 8px 0; color: #92400E; font-size: 14px; font-weight: 600;">
                💡 Melhor oferta atual:
            </p>
            <p style="margin: 0; font-size: 32px; font-weight: 900; color: #059669;">
                R$ {{ number_format($melhorOferta->preco_unitario, 2, ',', '.') }}
                <span style="font-size: 16px; font-weight: 600; color: #6B7280;">/{{ $cotacao->unidade }}</span>
            </p>
        </div>
    @endif

    <!-- Benefícios -->
    <div style="background: #EFF6FF; border-radius: 12px; padding: 20px; margin: 0 0 32px 0;">
        <p style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700; color: #1E40AF;">
            💼 Por que participar?
        </p>
        <ul style="margin: 0; padding-left: 20px; color: #1E3A8A; font-size: 14px; line-height: 1.8;">
            <li><strong>Aumente suas vendas:</strong> Compradores comparando preços agora</li>
            <li><strong>Destaque automático:</strong> Melhor oferta recebe posição de destaque</li>
            <li><strong>Contato direto:</strong> Compradores podem contatar via WhatsApp</li>
            <li><strong>Sem compromisso:</strong> Você pode editar ou remover sua oferta a qualquer momento</li>
        </ul>
    </div>

    <!-- Call to Action -->
    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ route('cotacoes.index') }}" 
           style="display: inline-block; background: #10B981; color: white; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 18px; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);">
            💰 Adicionar Minha Oferta
        </a>
    </div>

    <p style="margin: 24px 0 0 0; padding-top: 24px; border-top: 2px solid #E5E7EB; color: #6B7280; font-size: 14px; line-height: 1.6; text-align: center;">
        Seja rápido! Quanto antes você adicionar sua oferta, maiores suas chances de fechar negócio.<br>
        <strong style="color: #10B981;">A cotação encerra em {{ $cotacao->data_fim->format('d/m/Y') }}.</strong>
    </p>
@endsection

@section('footer')
    <p style="margin: 0 0 8px 0; color: #9CA3AF; font-size: 13px; line-height: 1.5;">
        Você recebeu este email porque está cadastrado no segmento <strong>{{ $cotacao->segmento->nome }}</strong> no Serra Food 360.
    </p>
    <p style="margin: 0; color: #9CA3AF; font-size: 13px; line-height: 1.5;">
        Aproveite esta oportunidade para aumentar suas vendas! 🚀
    </p>
@endsection
