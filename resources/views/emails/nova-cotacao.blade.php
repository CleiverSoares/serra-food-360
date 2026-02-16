@extends('emails.layouts.base')

@section('titulo', 'Nova Cotação Disponível!')

@section('header-title', '🎯 Nova Cotação!')
@section('header-subtitle', 'Compare preços e economize agora')

@section('content')
    <p style="margin: 0 0 16px 0; color: #374151; font-size: 16px; line-height: 1.6;">
        Olá, <strong>{{ $nomeComprador }}</strong>!
    </p>

    <p style="margin: 0 0 24px 0; color: #374151; font-size: 16px; line-height: 1.6;">
        Temos uma <strong>nova cotação disponível</strong> no segmento <span style="background: #D1FAE5; color: #065F46; padding: 4px 12px; border-radius: 6px; font-weight: 600;">{{ $cotacao->segmento->nome }}</span>!
    </p>

    <!-- Card da Cotação -->
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
        </div>
    </div>

    @if($cotacao->descricao)
        <div style="background: #F3F4F6; border-left: 4px solid #10B981; padding: 16px; border-radius: 8px; margin: 0 0 24px 0;">
            <p style="margin: 0; color: #374151; font-size: 14px; line-height: 1.6;">
                <strong style="color: #10B981;">ℹ️ Informações:</strong><br>
                {{ $cotacao->descricao }}
            </p>
        </div>
    @endif

    <!-- Ofertas Disponíveis -->
    @if($cotacao->ofertas->isNotEmpty())
        <h3 style="margin: 0 0 16px 0; font-size: 20px; font-weight: 700; color: #1F2937;">
            💰 {{ $cotacao->ofertas->count() }} Oferta(s) Disponível(is)
        </h3>

        @php
            $melhorOferta = $cotacao->melhorOferta();
        @endphp

        @if($melhorOferta)
            <div style="background: #FEF3C7; border: 2px solid #F59E0B; border-radius: 12px; padding: 20px; margin: 0 0 16px 0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <span style="font-size: 20px;">🏆</span>
                    <strong style="color: #92400E; font-size: 16px;">Melhor Oferta</strong>
                </div>
                
                <p style="margin: 0 0 8px 0; font-size: 32px; font-weight: 900; color: #059669;">
                    R$ {{ number_format($melhorOferta->preco_unitario, 2, ',', '.') }}
                    <span style="font-size: 16px; font-weight: 600; color: #6B7280;">/{{ $cotacao->unidade }}</span>
                </p>
                
                <p style="margin: 0; color: #374151; font-size: 14px;">
                    <strong>Fornecedor:</strong> {{ $melhorOferta->fornecedor->fornecedor->nome_empresa }}
                </p>
                
                @if($melhorOferta->prazo_entrega)
                    <p style="margin: 8px 0 0 0; color: #6B7280; font-size: 14px;">
                        🚚 Entrega: {{ $melhorOferta->prazo_entrega }}
                    </p>
                @endif
            </div>

            <!-- Economia -->
            @if($cotacao->ofertas->count() > 1)
                @php
                    $segundoMelhor = $cotacao->ofertas->sortBy('preco_unitario')->skip(1)->first();
                    if ($segundoMelhor) {
                        $economia = $segundoMelhor->preco_unitario - $melhorOferta->preco_unitario;
                        $percentual = ($economia / $segundoMelhor->preco_unitario) * 100;
                    }
                @endphp
                
                @if(isset($economia) && $economia > 0)
                    <div style="background: #D1FAE5; border-radius: 8px; padding: 12px; margin: 0 0 24px 0;">
                        <p style="margin: 0; color: #065F46; font-size: 14px; text-align: center;">
                            💡 <strong>Economia de R$ {{ number_format($economia, 2, ',', '.') }}</strong> ({{ number_format($percentual, 1) }}%) em relação à segunda melhor oferta!
                        </p>
                    </div>
                @endif
            @endif
        @endif
    @endif

    <!-- Call to Action -->
    <div style="text-align: center; margin: 32px 0;">
        <a href="{{ route('cotacoes.show', $cotacao->id) }}" 
           style="display: inline-block; background: #10B981; color: white; padding: 16px 32px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 18px; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);">
            📊 Ver Todas as Ofertas
        </a>
    </div>

    <p style="margin: 24px 0 0 0; padding-top: 24px; border-top: 2px solid #E5E7EB; color: #6B7280; font-size: 14px; line-height: 1.6; text-align: center;">
        Compare os preços e escolha a melhor oferta para seu negócio!<br>
        <strong style="color: #10B981;">A diferença pode representar economia significativa no mês.</strong>
    </p>
@endsection

@section('footer')
    <p style="margin: 0 0 8px 0; color: #9CA3AF; font-size: 13px; line-height: 1.5;">
        Você recebeu este email porque está cadastrado no segmento <strong>{{ $cotacao->segmento->nome }}</strong> no Serra Food 360.
    </p>
    <p style="margin: 0; color: #9CA3AF; font-size: 13px; line-height: 1.5;">
        Esta cotação é válida até <strong>{{ $cotacao->data_fim->format('d/m/Y') }}</strong>. Não perca!
    </p>
@endsection
