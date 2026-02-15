@extends('layouts.app')

@section('titulo', 'Bem-vindo')
@section('conteudo')

{{-- Hero com impacto visual --}}
<section class="relative bg-[var(--cor-verde-serra)] overflow-hidden">
    {{-- Pattern decorativo --}}
    <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 32px 32px;"></div>
    
    <div class="relative w-full mx-auto px-4 sm:px-6 lg:px-12 py-12 sm:py-16 lg:py-20 max-w-7xl">
        <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-16 items-center">
            {{-- Conteúdo --}}
            <div class="w-full">
                {{-- Badge região --}}
                <div class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-white/15 backdrop-blur-sm text-white text-xs sm:text-sm font-semibold mb-4 sm:mb-6 animate-pulse">
                    <i data-lucide="map-pin" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                    <span>Região Serrana</span>
                </div>
                
                {{-- Headline --}}
                <h1 class="font-display text-[28px] sm:text-4xl md:text-5xl lg:text-7xl font-bold text-white mb-4 sm:mb-6 leading-[1.15] sm:leading-[1.05] tracking-tight">
                    Seu restaurante merece o melhor apoio
                </h1>
                
                {{-- Subheadline --}}
                <p class="text-[15px] sm:text-lg md:text-xl lg:text-2xl text-white/95 mb-6 sm:mb-8 leading-relaxed">
                    Hub completo que conecta restaurantes, fornecedores e talentos. Economize tempo e dinheiro.
                </p>
                
                {{-- Social proof rápido --}}
                <div class="flex flex-wrap items-center gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-10 text-white/90 text-[11px] sm:text-sm">
                    <div class="flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                        <i data-lucide="users" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0"></i>
                        <span>50+ restaurantes</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                        <i data-lucide="truck" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0"></i>
                        <span>100+ fornecedores</span>
                    </div>
                    <div class="flex items-center gap-1.5 sm:gap-2 whitespace-nowrap">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0"></i>
                        <span>30% economia</span>
                    </div>
                </div>
                
                {{-- CTAs --}}
                <div class="flex flex-col gap-3 w-full">
                    <a href="{{ route('cadastro') }}" class="group flex items-center justify-center gap-2 px-6 py-4 bg-white text-[var(--cor-verde-serra)] rounded-xl font-bold text-[15px] hover:shadow-2xl active:scale-95 transition-all w-full">
                        <i data-lucide="user-plus" class="w-4 h-4 flex-shrink-0"></i>
                        <span>Criar conta grátis</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 flex-shrink-0 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#como-funciona" class="flex items-center justify-center gap-2 px-6 py-4 border-2 border-white/30 text-white rounded-xl font-bold text-[15px] hover:bg-white/10 hover:border-white/50 active:scale-95 transition-all backdrop-blur-sm w-full">
                        <span>Como funciona</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 flex-shrink-0"></i>
                    </a>
                </div>
            </div>
            
            {{-- Hero Image --}}
            <div class="relative hidden lg:block">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white/20">
                    <img src="/images/hero-restaurante.jpg" 
                         alt="Restaurante profissional na Serra" 
                         class="w-full h-full object-cover aspect-[4/3]"
                         onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22600%22%3E%3Crect fill=%22%23f5f2ed%22 width=%22800%22 height=%22600%22/%3E%3Ctext fill=%22%235c524a%22 font-family=%22system-ui%22 font-size=%2224%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3EImagem Hero%3Cbr/%3ERestaurante na Serra%3C/text%3E%3C/svg%3E';" />
                </div>
                {{-- Elemento flutuante decorativo --}}
                <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-2xl p-4 animate-bounce" style="animation-duration: 3s;">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-[var(--cor-verde-serra)] flex items-center justify-center">
                            <i data-lucide="sparkles" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <p class="text-xs text-[var(--cor-texto-muted)] font-medium">Novo módulo</p>
                            <p class="text-sm font-bold text-[var(--cor-texto)]">Consultor IA 24/7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Como funciona (3 passos) --}}
<section id="como-funciona" class="py-12 sm:py-16 md:py-20 lg:py-28 bg-[var(--cor-fundo)]">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-12 max-w-7xl">
        <div class="text-center mx-auto mb-10 sm:mb-16 md:mb-20">
            <div class="inline-block px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-[var(--cor-verde-serra)]/10 text-[var(--cor-verde-serra)] text-[10px] sm:text-xs font-bold tracking-wider mb-4 sm:mb-6">
                SIMPLES E RÁPIDO
            </div>
            <h2 class="font-display text-[26px] sm:text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--cor-texto)] mb-3 sm:mb-4 md:mb-6 px-4">
                Como funciona
            </h2>
            <p class="text-[14px] sm:text-lg md:text-xl text-[var(--cor-texto-secundario)] leading-relaxed px-4">
                Três passos para transformar seu restaurante
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-6 sm:gap-8 lg:gap-12 relative">
            {{-- Linha conectora (desktop) --}}
            <div class="hidden md:block absolute top-20 left-0 right-0 h-1 bg-[var(--cor-verde-serra)] opacity-20"></div>
            
            {{-- Passo 1 --}}
            <div class="relative">
                <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-10 shadow-lg hover:shadow-2xl transition-all border border-[var(--cor-borda)] hover:border-[var(--cor-verde-serra)] group">
                    <div class="absolute -top-4 sm:-top-6 left-1/2 -translate-x-1/2 w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-[var(--cor-verde-serra)] flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                        <span class="text-white font-display text-2xl sm:text-3xl font-bold">1</span>
                    </div>
                    <div class="pt-4 sm:pt-6 text-center">
                        <i data-lucide="user-plus" class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-4 sm:mb-6 text-[var(--cor-verde-serra)]"></i>
                        <h3 class="font-display text-xl sm:text-2xl font-bold text-[var(--cor-texto)] mb-3 sm:mb-4">Solicite entrada</h3>
                        <p class="text-sm sm:text-base text-[var(--cor-texto-secundario)] leading-relaxed">
                            Entre em contato pelo WhatsApp. Nossa equipe valida seu perfil e libera acesso em até 24h.
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- Passo 2 --}}
            <div class="relative">
                <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-10 shadow-lg hover:shadow-2xl transition-all border border-[var(--cor-borda)] hover:border-[var(--cor-terra)] group">
                    <div class="absolute -top-4 sm:-top-6 left-1/2 -translate-x-1/2 w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-[var(--cor-terra)] flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                        <span class="text-white font-display text-2xl sm:text-3xl font-bold">2</span>
                    </div>
                    <div class="pt-4 sm:pt-6 text-center">
                        <i data-lucide="compass" class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-4 sm:mb-6 text-[var(--cor-terra)]"></i>
                        <h3 class="font-display text-xl sm:text-2xl font-bold text-[var(--cor-texto)] mb-3 sm:mb-4">Explore o hub</h3>
                        <p class="text-sm sm:text-base text-[var(--cor-texto-secundario)] leading-relaxed">
                            Acesse 8 módulos completos: fornecedores, cotações, talentos, gestão, IA e muito mais.
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- Passo 3 --}}
            <div class="relative">
                <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 md:p-10 shadow-lg hover:shadow-2xl transition-all border border-[var(--cor-borda)] hover:border-[var(--cor-verde-serra)] group">
                    <div class="absolute -top-4 sm:-top-6 left-1/2 -translate-x-1/2 w-12 h-12 sm:w-16 sm:h-16 rounded-xl sm:rounded-2xl bg-[var(--cor-verde-serra)] flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                        <span class="text-white font-display text-2xl sm:text-3xl font-bold">3</span>
                    </div>
                    <div class="pt-4 sm:pt-6 text-center">
                        <i data-lucide="rocket" class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-4 sm:mb-6 text-[var(--cor-verde-serra)]"></i>
                        <h3 class="font-display text-xl sm:text-2xl font-bold text-[var(--cor-texto)] mb-3 sm:mb-4">Economize e cresça</h3>
                        <p class="text-sm sm:text-base text-[var(--cor-texto-secundario)] leading-relaxed">
                            Negocie melhores preços, encontre talentos e profissionalize sua gestão.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Benefícios com números --}}
<section class="py-12 sm:py-16 md:py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 md:gap-16 items-center">
            {{-- Conteúdo --}}
            <div class="max-w-xl">
                <div class="inline-block px-4 py-2 rounded-full bg-[var(--cor-terra)]/10 text-[var(--cor-terra)] text-xs font-bold tracking-wider mb-6">
                    RESULTADOS REAIS
                </div>
                <h2 class="font-display text-[26px] sm:text-4xl lg:text-5xl font-bold text-[var(--cor-texto)] mb-6 leading-tight">
                    Seu restaurante no próximo nível
                </h2>
                <p class="text-xl text-[var(--cor-texto-secundario)] mb-10 leading-relaxed">
                    Mais do que uma plataforma: um ecossistema que conecta, informa e impulsiona seu negócio.
                </p>
                
                {{-- Lista de benefícios --}}
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[var(--cor-verde-serra)]/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="trending-down" class="w-6 h-6 text-[var(--cor-verde-serra)]"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-1">Reduza custos em até 30%</h3>
                            <p class="text-[var(--cor-texto-secundario)]">Negociações coletivas e comparativo de preços semanal</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[var(--cor-terra)]/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="clock" class="w-6 h-6 text-[var(--cor-terra)]"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-1">Economize 10h por semana</h3>
                            <p class="text-[var(--cor-texto-secundario)]">Tudo centralizado: fornecedores, preços, talentos e gestão</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[var(--cor-verde-serra)]/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="shield-check" class="w-6 h-6 text-[var(--cor-verde-serra)]"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-1">Fornecedores validados</h3>
                            <p class="text-[var(--cor-texto-secundario)]">100% dos parceiros verificados e aprovados pela comunidade</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[var(--cor-terra)]/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="graduation-cap" class="w-6 h-6 text-[var(--cor-terra)]"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-1">Capacitação contínua</h3>
                            <p class="text-[var(--cor-texto-secundario)]">Materiais de gestão, mentorias e workshops práticos</p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-[var(--cor-verde-serra)] rounded-3xl p-8 text-white shadow-2xl">
                    <div class="text-5xl font-display font-bold mb-2">50+</div>
                    <p class="text-white/90 font-medium">Restaurantes conectados</p>
                </div>
                
                <div class="bg-[var(--cor-terra)] rounded-3xl p-8 text-white shadow-2xl">
                    <div class="text-5xl font-display font-bold mb-2">100+</div>
                    <p class="text-white/90 font-medium">Fornecedores ativos</p>
                </div>
                
                <div class="bg-[var(--cor-terra)] rounded-3xl p-8 text-white shadow-2xl">
                    <div class="text-5xl font-display font-bold mb-2">30%</div>
                    <p class="text-white/90 font-medium">Economia média</p>
                </div>
                
                <div class="bg-[var(--cor-verde-serra)] rounded-3xl p-8 text-white shadow-2xl">
                    <div class="text-5xl font-display font-bold mb-2">24/7</div>
                    <p class="text-white/90 font-medium">Suporte IA</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Módulos (8 funcionalidades) --}}
<section id="modulos" class="py-12 sm:py-16 md:py-20 lg:py-32 bg-[var(--cor-fundo)] relative overflow-hidden">
    {{-- Background decorativo --}}
    <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=\&quot;60\&quot; height=\&quot;60\&quot; viewBox=\&quot;0 0 60 60\&quot; xmlns=\&quot;http://www.w3.org/2000/svg\&quot;%3E%3Cg fill=\&quot;none\&quot; fill-rule=\&quot;evenodd\&quot;%3E%3Cg fill=\&quot;%231a5c3a\&quot; fill-opacity=\&quot;1\&quot;%3E%3Cpath d=\&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16 md:mb-20">
            <div class="inline-block px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-[var(--cor-verde-serra)]/10 text-[var(--cor-verde-serra)] text-[10px] sm:text-xs font-bold tracking-wider mb-4 sm:mb-6">
                8 MÓDULOS PODEROSOS
            </div>
            <h2 class="font-display text-[26px] sm:text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--cor-texto)] mb-3 sm:mb-4 md:mb-6 px-4">
                Tudo em um só lugar
            </h2>
            <p class="text-[14px] sm:text-lg md:text-xl text-[var(--cor-texto-secundario)] leading-relaxed px-4">
                Cada módulo resolve problemas reais do seu restaurante
            </p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            @php
            $modulos = [
                ['Restaurantes', 'Vitrine completa dos estabelecimentos da região', 'utensils-crossed', 'verde', 'Conexão entre restaurantes'],
                ['Fornecedores', 'Diretório categorizado por tipo de produto', 'truck', 'terra', '100+ fornecedores validados'],
                ['Cotações', 'Comparativo semanal de preços de insumos', 'bar-chart-2', 'verde', 'Economize até 30%'],
                ['Talentos', 'Banco de profissionais para trabalhos extras', 'users', 'terra', 'Contratação ágil'],
                ['Compras Coletivas', 'Negociação em grupo para grandes volumes', 'shopping-cart', 'verde', 'Poder de barganha'],
                ['Material de Gestão', 'Treinamentos, DRE, CMV e mais', 'book-open', 'terra', 'Capacitação contínua'],
                ['Consultor IA', 'Assistente especializado disponível 24/7', 'sparkles', 'verde', 'Suporte inteligente'],
                ['Troca de Equipamentos', 'Marketplace de compra e venda', 'refresh-cw', 'terra', 'Economia circular'],
            ];
            @endphp

            @foreach($modulos as $index => $mod)
            @php
            $isVerde = $mod[3] === 'verde';
            $bgColor = $isVerde ? 'bg-[var(--cor-verde-serra)]' : 'bg-[var(--cor-terra)]';
            $borderColor = $isVerde ? 'hover:border-[var(--cor-verde-serra)]' : 'hover:border-[var(--cor-terra)]';
            $badgeBg = $isVerde ? 'bg-[var(--cor-verde-serra)]/10 text-[var(--cor-verde-serra)]' : 'bg-[var(--cor-terra)]/10 text-[var(--cor-terra)]';
            @endphp
            <div class="group relative p-5 sm:p-6 md:p-8 rounded-2xl sm:rounded-3xl bg-white border-2 border-[var(--cor-borda)] {{ $borderColor }} hover:shadow-2xl hover:-translate-y-1 sm:hover:-translate-y-2 transition-all duration-300 active:scale-95">
                {{-- Badge destaque --}}
                <div class="absolute -top-2 -right-2 sm:-top-3 sm:-right-3 {{ $badgeBg }} px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[9px] sm:text-xs font-bold">
                    {{ $mod[4] }}
                </div>
                
                {{-- Ícone --}}
                <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-xl sm:rounded-2xl {{ $bgColor }} flex items-center justify-center mb-4 sm:mb-5 md:mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                    <i data-lucide="{{ $mod[2] }}" class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-white"></i>
                </div>
                
                <h3 class="font-display text-base sm:text-lg md:text-xl font-bold text-[var(--cor-texto)] mb-2 sm:mb-3 group-hover:text-[var(--cor-{{ $mod[3] === 'verde' ? 'verde-serra' : 'terra' }})] transition-colors">
                    {{ $mod[0] }}
                </h3>
                
                <p class="text-xs sm:text-sm md:text-base text-[var(--cor-texto-secundario)] leading-relaxed">
                    {{ $mod[1] }}
                </p>
                
                {{-- Indicador de interação (apenas desktop) --}}
                <div class="hidden sm:flex mt-4 md:mt-6 items-center gap-2 text-xs sm:text-sm font-medium {{ $isVerde ? 'text-[var(--cor-verde-serra)]' : 'text-[var(--cor-terra)]' }} opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>Explorar módulo</span>
                    <i data-lucide="arrow-right" class="w-3 h-3 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- CTA após módulos --}}
        <div class="mt-8 sm:mt-12 md:mt-16 text-center px-4">
            <a href="https://wa.me/5551999999999?text=Olá!%20Quero%20conhecer%20os%20módulos%20do%20Serra%20Food%20360" target="_blank" rel="noopener" class="inline-flex items-center gap-2 sm:gap-3 px-6 sm:px-8 md:px-10 py-4 sm:py-5 bg-[var(--cor-verde-serra)] text-white rounded-xl sm:rounded-2xl font-bold text-sm sm:text-base md:text-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all">
                <i data-lucide="message-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                <span class="hidden sm:inline">Quero acessar esses módulos</span>
                <span class="sm:hidden">Acessar módulos</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
            </a>
        </div>
    </div>
</section>

{{-- Planos (comparação clara) --}}
<section id="planos" class="py-12 sm:py-16 md:py-20 lg:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16 md:mb-20">
            <div class="inline-block px-3 sm:px-4 py-1.5 sm:py-2 rounded-full bg-[var(--cor-terra)]/10 text-[var(--cor-terra)] text-[10px] sm:text-xs font-bold tracking-wider mb-4 sm:mb-6">
                ESCOLHA SEU PLANO
            </div>
            <h2 class="font-display text-[26px] sm:text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--cor-texto)] mb-3 sm:mb-4 md:mb-6 px-4">
                Invista no crescimento
            </h2>
            <p class="text-[14px] sm:text-lg md:text-xl text-[var(--cor-texto-secundario)] leading-relaxed px-4">
                Planos flexíveis que se adaptam ao seu momento
            </p>
        </div>
        
        <div class="grid lg:grid-cols-2 gap-6 sm:gap-8 max-w-6xl mx-auto">
            {{-- Plano Comum --}}
            <div class="group p-6 sm:p-8 md:p-10 lg:p-12 rounded-2xl sm:rounded-3xl bg-[var(--cor-fundo)] border-2 border-[var(--cor-borda)] hover:border-[var(--cor-verde-serra)] hover:shadow-2xl transition-all">
                <div class="flex items-start justify-between mb-6 sm:mb-8">
                    <div>
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-[var(--cor-verde-serra)]/10 flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 transition-transform">
                            <i data-lucide="check-circle" class="w-6 h-6 sm:w-7 sm:h-7 text-[var(--cor-verde-serra)]"></i>
                        </div>
                        <h3 class="font-display text-2xl sm:text-3xl font-bold text-[var(--cor-texto)] mb-2">Plano Comum</h3>
                        <p class="text-sm sm:text-base text-[var(--cor-texto-secundario)] leading-relaxed">
                            Essencial para começar
                        </p>
                    </div>
                </div>
                
                <div class="mb-6 sm:mb-8 pb-6 sm:pb-8 border-b border-[var(--cor-borda)]">
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="font-display text-4xl sm:text-5xl font-bold text-[var(--cor-texto)]">R$ X</span>
                        <span class="text-sm sm:text-base text-[var(--cor-texto-secundario)]">/mês</span>
                    </div>
                    <p class="text-xs sm:text-sm text-[var(--cor-texto-muted)]">Cobrança recorrente mensal</p>
                </div>
                
                <ul class="space-y-3 sm:space-y-4 mb-8 sm:mb-10">
                    <li class="flex items-start gap-2 sm:gap-3">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-0.5 sm:mt-1"></i>
                        <span class="text-sm sm:text-base text-[var(--cor-texto)]"><strong>Todos os 8 módulos</strong></span>
                    </li>
                    <li class="flex items-start gap-2 sm:gap-3">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-0.5 sm:mt-1"></i>
                        <span class="text-sm sm:text-base text-[var(--cor-texto)]">Acesso completo ao diretório de fornecedores</span>
                    </li>
                    <li class="flex items-start gap-2 sm:gap-3">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-0.5 sm:mt-1"></i>
                        <span class="text-sm sm:text-base text-[var(--cor-texto)]">Cotações semanais de insumos</span>
                    </li>
                    <li class="flex items-start gap-2 sm:gap-3">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-0.5 sm:mt-1"></i>
                        <span class="text-sm sm:text-base text-[var(--cor-texto)]">Banco de talentos profissionais</span>
                    </li>
                    <li class="flex items-start gap-2 sm:gap-3">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-0.5 sm:mt-1"></i>
                        <span class="text-sm sm:text-base text-[var(--cor-texto)]">Compras coletivas para economia</span>
                    </li>
                    <li class="flex items-start gap-2 sm:gap-3">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-0.5 sm:mt-1"></i>
                        <span class="text-sm sm:text-base text-[var(--cor-texto)]">Material de gestão (DRE, CMV, treinamentos)</span>
                    </li>
                    <li class="flex items-start gap-2 sm:gap-3">
                        <i data-lucide="check" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-0.5 sm:mt-1"></i>
                        <span class="text-sm sm:text-base text-[var(--cor-texto)]">Consultor IA disponível 24/7</span>
                    </li>
                </ul>
                
                <a href="https://wa.me/5551999999999?text=Olá!%20Quero%20contratar%20o%20Plano%20Comum" target="_blank" rel="noopener" class="block w-full text-center px-6 sm:px-8 py-3.5 sm:py-4 bg-[var(--cor-verde-serra)] text-white rounded-xl font-bold text-sm sm:text-base hover:bg-[var(--cor-verde-serra-hover)] active:scale-95 transition-all shadow-lg">
                    Solicitar Plano Comum
                </a>
            </div>

            {{-- Plano VIP (destaque) --}}
            <div class="relative group p-6 sm:p-8 md:p-10 lg:p-12 rounded-2xl sm:rounded-3xl bg-[var(--cor-secundaria-clara)] border-2 border-[var(--cor-terra)] hover:shadow-2xl hover:scale-[1.02] transition-all">
                {{-- Badge recomendado --}}
                <div class="absolute -top-3 sm:-top-4 left-1/2 -translate-x-1/2 px-4 sm:px-6 py-1.5 sm:py-2 rounded-full bg-[var(--cor-terra)] text-white text-xs sm:text-sm font-bold shadow-lg">
                    ⭐ MAIS POPULAR
                </div>
                
                <div class="flex items-start justify-between mb-6 sm:mb-8">
                    <div>
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-[var(--cor-terra)]/20 flex items-center justify-center mb-4 sm:mb-6 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                            <i data-lucide="crown" class="w-6 h-6 sm:w-7 sm:h-7 text-[var(--cor-terra)]"></i>
                        </div>
                        <h3 class="font-display text-2xl sm:text-3xl font-bold text-[var(--cor-texto)] mb-2">Plano VIP</h3>
                        <p class="text-sm sm:text-base text-[var(--cor-texto-secundario)] leading-relaxed">
                            Para quem quer crescer acelerado
                        </p>
                    </div>
                </div>
                
                <div class="mb-6 sm:mb-8 pb-6 sm:pb-8 border-b border-[var(--cor-terra)]/20">
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="font-display text-4xl sm:text-5xl font-bold text-[var(--cor-texto)]">R$ 2X</span>
                        <span class="text-sm sm:text-base text-[var(--cor-texto-secundario)]">/mês</span>
                    </div>
                    <p class="text-xs sm:text-sm text-[var(--cor-terra)] font-medium">Melhor custo-benefício</p>
                </div>
                
                <ul class="space-y-4 mb-10">
                    <li class="flex items-start gap-3">
                        <i data-lucide="check" class="w-5 h-5 text-[var(--cor-terra)] flex-shrink-0 mt-1"></i>
                        <span class="text-[var(--cor-texto)]"><strong>Tudo do Plano Comum +</strong></span>
                    </li>
                    <li class="flex items-start gap-3 bg-[var(--cor-terra)]/5 -mx-4 px-4 py-2 rounded-lg">
                        <i data-lucide="video" class="w-5 h-5 text-[var(--cor-terra)] flex-shrink-0 mt-1"></i>
                        <span class="text-[var(--cor-texto)]"><strong>Mentorias mensais em grupo</strong> via Zoom com especialistas</span>
                    </li>
                    <li class="flex items-start gap-3 bg-[var(--cor-terra)]/5 -mx-4 px-4 py-2 rounded-lg">
                        <i data-lucide="gift" class="w-5 h-5 text-[var(--cor-terra)] flex-shrink-0 mt-1"></i>
                        <span class="text-[var(--cor-texto)]"><strong>Promoções exclusivas</strong> de fornecedores parceiros</span>
                    </li>
                    <li class="flex items-start gap-3 bg-[var(--cor-terra)]/5 -mx-4 px-4 py-2 rounded-lg">
                        <i data-lucide="presentation" class="w-5 h-5 text-[var(--cor-terra)] flex-shrink-0 mt-1"></i>
                        <span class="text-[var(--cor-texto)]"><strong>Workshops práticos</strong> presenciais e online</span>
                    </li>
                    <li class="flex items-start gap-3 bg-[var(--cor-terra)]/5 -mx-4 px-4 py-2 rounded-lg">
                        <i data-lucide="headphones" class="w-5 h-5 text-[var(--cor-terra)] flex-shrink-0 mt-1"></i>
                        <span class="text-[var(--cor-texto)]"><strong>Suporte prioritário</strong> para dúvidas e demandas</span>
                    </li>
                    <li class="flex items-start gap-3 bg-[var(--cor-terra)]/5 -mx-4 px-4 py-2 rounded-lg">
                        <i data-lucide="badge-check" class="w-5 h-5 text-[var(--cor-terra)] flex-shrink-0 mt-1"></i>
                        <span class="text-[var(--cor-texto)]"><strong>Selo VIP</strong> no diretório de restaurantes</span>
                    </li>
                </ul>
                
                <a href="https://wa.me/5551999999999?text=Olá!%20Quero%20contratar%20o%20Plano%20VIP" target="_blank" rel="noopener" class="block w-full text-center px-6 sm:px-8 py-4 sm:py-5 bg-[var(--cor-terra)] text-white rounded-xl font-bold hover:shadow-2xl hover:scale-105 active:scale-95 transition-all text-base sm:text-lg">
                    Quero ser VIP
                </a>
            </div>
        </div>
        
        {{-- Garantia/benefícios --}}
        <div class="mt-8 sm:mt-12 md:mt-16 text-center px-4">
            <div class="inline-flex items-center gap-4 sm:gap-6 md:gap-8 flex-wrap justify-center text-xs sm:text-sm text-[var(--cor-texto-muted)]">
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)]"></i>
                    <span>Acesso imediato</span>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <i data-lucide="lock" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)]"></i>
                    <span>Dados seguros</span>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <i data-lucide="refresh-ccw" class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--cor-verde-serra)]"></i>
                    <span>Cancele quando quiser</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Depoimentos / Prova Social --}}
<section class="py-12 sm:py-16 md:py-20 lg:py-32 bg-[var(--cor-fundo)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16 md:mb-20">
            <div class="inline-block px-4 py-2 rounded-full bg-[var(--cor-verde-serra)]/10 text-[var(--cor-verde-serra)] text-xs font-bold tracking-wider mb-6">
                HISTÓRIAS DE SUCESSO
            </div>
            <h2 class="font-display text-[26px] sm:text-4xl lg:text-6xl font-bold text-[var(--cor-texto)] mb-4 sm:mb-6 px-4">
                Donos de restaurante aprovam
            </h2>
            <p class="text-[14px] sm:text-lg md:text-xl text-[var(--cor-texto-secundario)] leading-relaxed px-4">
                Veja como o Serra Food 360 está transformando negócios na região
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            {{-- Depoimento 1 --}}
            <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all border border-[var(--cor-borda)] hover:border-[var(--cor-verde-serra)]">
                <div class="flex items-center gap-1 mb-6">
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                </div>
                <p class="text-[var(--cor-texto)] leading-relaxed mb-6 italic">
                    "As cotações semanais me ajudaram a economizar 25% em insumos. Além disso, encontro profissionais qualificados rapidamente quando preciso."
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-[var(--cor-verde-serra)] flex items-center justify-center text-white font-bold">
                        MC
                    </div>
                    <div>
                        <p class="font-bold text-[var(--cor-texto)]">Maria Costa</p>
                        <p class="text-sm text-[var(--cor-texto-muted)]">Restaurante Sabor da Serra</p>
                    </div>
                </div>
            </div>
            
            {{-- Depoimento 2 --}}
            <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all border border-[var(--cor-borda)] hover:border-[var(--cor-terra)]">
                <div class="flex items-center gap-1 mb-6">
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                </div>
                <p class="text-[var(--cor-texto)] leading-relaxed mb-6 italic">
                    "O módulo de gestão transformou minha forma de trabalhar. Os materiais são práticos e as mentorias do plano VIP são ouro puro!"
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-[var(--cor-terra)] flex items-center justify-center text-white font-bold">
                        PS
                    </div>
                    <div>
                        <p class="font-bold text-[var(--cor-texto)]">Pedro Silva</p>
                        <p class="text-sm text-[var(--cor-texto-muted)]">Bistrô Montanha</p>
                    </div>
                </div>
            </div>
            
            {{-- Depoimento 3 --}}
            <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all border border-[var(--cor-borda)] hover:border-[var(--cor-verde-serra)]">
                <div class="flex items-center gap-1 mb-6">
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-[var(--cor-terra)] text-[var(--cor-terra)]"></i>
                </div>
                <p class="text-[var(--cor-texto)] leading-relaxed mb-6 italic">
                    "A rede de fornecedores validados me economiza tempo e dor de cabeça. Não troco mais por nada. Comunidade forte e conectada!"
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-[var(--cor-verde-serra)] flex items-center justify-center text-white font-bold">
                        AL
                    </div>
                    <div>
                        <p class="font-bold text-[var(--cor-texto)]">Ana Lima</p>
                        <p class="text-sm text-[var(--cor-texto-muted)]">Pizzaria Bella Vista</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="py-12 sm:py-16 md:py-20 lg:py-32 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="text-center mb-10 sm:mb-16">
            <div class="inline-block px-4 py-2 rounded-full bg-[var(--cor-terra)]/10 text-[var(--cor-terra)] text-xs font-bold tracking-wider mb-6">
                PERGUNTAS FREQUENTES
            </div>
            <h2 class="font-display text-[26px] sm:text-4xl lg:text-5xl font-bold text-[var(--cor-texto)] mb-4 sm:mb-6 px-4">
                Dúvidas comuns
            </h2>
        </div>
        
        <div class="space-y-6" x-data="{ openFaq: 1 }">
            {{-- FAQ 1 --}}
            <div class="border-2 border-[var(--cor-borda)] rounded-2xl overflow-hidden hover:border-[var(--cor-verde-serra)] transition-colors">
                <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full text-left p-6 lg:p-8 flex items-start justify-between gap-4 bg-white hover:bg-[var(--cor-fundo)] transition-colors">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-2">Como funciona o processo de entrada?</h3>
                        <div x-show="openFaq === 1" x-collapse class="text-[var(--cor-texto-secundario)] leading-relaxed mt-4">
                            É simples: entre em contato pelo WhatsApp, nossa equipe valida se você é do setor de alimentação da região serrana, e em até 24h você recebe seu acesso. O objetivo é manter uma comunidade qualificada e segura.
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-1 transition-transform" :class="openFaq === 1 ? 'rotate-180' : ''"></i>
                </button>
            </div>
            
            {{-- FAQ 2 --}}
            <div class="border-2 border-[var(--cor-borda)] rounded-2xl overflow-hidden hover:border-[var(--cor-verde-serra)] transition-colors">
                <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full text-left p-6 lg:p-8 flex items-start justify-between gap-4 bg-white hover:bg-[var(--cor-fundo)] transition-colors">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-2">Posso cancelar a qualquer momento?</h3>
                        <div x-show="openFaq === 2" x-collapse class="text-[var(--cor-texto-secundario)] leading-relaxed mt-4">
                            Sim! Não há fidelidade. Você pode cancelar quando quiser através do painel ou entrando em contato conosco. Acreditamos que a qualidade do serviço é o que deve manter você conosco.
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-1 transition-transform" :class="openFaq === 2 ? 'rotate-180' : ''"></i>
                </button>
            </div>
            
            {{-- FAQ 3 --}}
            <div class="border-2 border-[var(--cor-borda)] rounded-2xl overflow-hidden hover:border-[var(--cor-verde-serra)] transition-colors">
                <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full text-left p-6 lg:p-8 flex items-start justify-between gap-4 bg-white hover:bg-[var(--cor-fundo)] transition-colors">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-2">Vale a pena o Plano VIP?</h3>
                        <div x-show="openFaq === 3" x-collapse class="text-[var(--cor-texto-secundario)] leading-relaxed mt-4">
                            Definitivamente! Além de todos os módulos, você tem mentorias mensais com especialistas, workshops práticos, promoções exclusivas e suporte prioritário. O retorno do investimento é rápido.
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-1 transition-transform" :class="openFaq === 3 ? 'rotate-180' : ''"></i>
                </button>
            </div>
            
            {{-- FAQ 4 --}}
            <div class="border-2 border-[var(--cor-borda)] rounded-2xl overflow-hidden hover:border-[var(--cor-verde-serra)] transition-colors">
                <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full text-left p-6 lg:p-8 flex items-start justify-between gap-4 bg-white hover:bg-[var(--cor-fundo)] transition-colors">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-2">Como funcionam as cotações semanais?</h3>
                        <div x-show="openFaq === 4" x-collapse class="text-[var(--cor-texto-secundario)] leading-relaxed mt-4">
                            Toda semana atualizamos uma lista comparativa de preços dos principais insumos (carnes, laticínios, hortifrúti, etc.) entre diversos fornecedores. Você economiza tempo e dinheiro comprando sempre pelo melhor preço.
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-1 transition-transform" :class="openFaq === 4 ? 'rotate-180' : ''"></i>
                </button>
            </div>
            
            {{-- FAQ 5 --}}
            <div class="border-2 border-[var(--cor-borda)] rounded-2xl overflow-hidden hover:border-[var(--cor-verde-serra)] transition-colors">
                <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full text-left p-6 lg:p-8 flex items-start justify-between gap-4 bg-white hover:bg-[var(--cor-fundo)] transition-colors">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-[var(--cor-texto)] mb-2">O Consultor IA realmente funciona?</h3>
                        <div x-show="openFaq === 5" x-collapse class="text-[var(--cor-texto-secundario)] leading-relaxed mt-4">
                            Sim! É um assistente especializado em food service que responde dúvidas sobre gestão, legislação, receitas, custos e muito mais. Está disponível 24/7 e aprende continuamente com as melhores práticas do setor.
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-[var(--cor-verde-serra)] flex-shrink-0 mt-1 transition-transform" :class="openFaq === 5 ? 'rotate-180' : ''"></i>
                </button>
            </div>
        </div>
        
        {{-- CTA adicional no FAQ --}}
        <div class="mt-16 text-center p-8 rounded-3xl bg-[var(--cor-fundo)] border-2 border-[var(--cor-borda)]">
            <p class="text-lg text-[var(--cor-texto)] mb-4">
                <strong>Ainda tem dúvidas?</strong> Fale conosco diretamente pelo WhatsApp
            </p>
            <a href="https://wa.me/5551999999999?text=Olá!%20Tenho%20dúvidas%20sobre%20o%20Serra%20Food%20360" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-8 py-4 bg-[var(--cor-verde-serra)] text-white rounded-xl font-bold hover:bg-[var(--cor-verde-serra-hover)] transition-all shadow-lg">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                Falar com nossa equipe
            </a>
        </div>
    </div>
</section>

{{-- CTA Final (com urgência e valor) --}}
<section class="py-12 sm:py-16 md:py-20 lg:py-32 bg-[var(--cor-verde-serra)] relative overflow-hidden">
    {{-- Background decorativo --}}
    <div class="absolute inset-0 opacity-[0.05]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 32px 32px;"></div>
    
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-12">
        <div class="text-center">
            {{-- Badge exclusividade --}}
            <div class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-3 rounded-full bg-white/15 backdrop-blur-sm text-white text-xs sm:text-sm font-bold mb-6 sm:mb-8 animate-pulse">
                <i data-lucide="lock" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                COMUNIDADE EXCLUSIVA
            </div>
            
            {{-- Headline poderoso --}}
            <h2 class="font-display text-[28px] sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-white mb-4 sm:mb-6 leading-[1.15] sm:leading-[1.05] px-4">
                Pronto para transformar seu restaurante?
            </h2>
            
            {{-- Subheadline com valor --}}
            <p class="text-[15px] sm:text-lg md:text-xl lg:text-2xl text-white/95 mb-6 sm:mb-8 md:mb-10 leading-relaxed max-w-3xl mx-auto px-4">
                Junte-se a <strong>50+ donos de restaurante</strong> que já economizam tempo e dinheiro com o maior hub da região serrana.
            </p>
            
            {{-- Lista de benefícios rápidos --}}
            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4 md:gap-6 mb-8 sm:mb-10 md:mb-12 text-white/95 text-xs sm:text-sm md:text-base">
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    <span class="font-medium">Acesso imediato</span>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    <span class="font-medium">Sem fidelidade</span>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    <span class="font-medium">Suporte completo</span>
                </div>
            </div>
            
            {{-- CTAs principais --}}
            <div class="flex flex-col gap-3 justify-center items-stretch mb-8 sm:mb-10 md:mb-12 w-full max-w-md mx-auto px-4">
                <a href="{{ route('cadastro') }}" 
                   class="group flex items-center justify-center gap-2 px-8 py-4 bg-white text-[var(--cor-verde-serra)] rounded-xl font-bold text-[15px] hover:shadow-2xl active:scale-95 transition-all shadow-xl w-full">
                    <i data-lucide="user-plus" class="w-5 h-5 flex-shrink-0"></i>
                    <span>Criar conta grátis</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 flex-shrink-0 group-hover:translate-x-1 transition-transform"></i>
                </a>
                
                <a href="#como-funciona" 
                   class="flex items-center justify-center gap-2 px-8 py-4 border-2 border-white/40 text-white rounded-xl font-bold text-[15px] hover:bg-white/10 hover:border-white/60 active:scale-95 transition-all backdrop-blur-sm w-full">
                    <span>Ver como funciona</span>
                    <i data-lucide="chevron-up" class="w-4 h-4 flex-shrink-0"></i>
                </a>
            </div>
            
            {{-- Social proof adicional --}}
            <div class="inline-flex items-center gap-2 sm:gap-3 bg-white/10 backdrop-blur-sm rounded-full px-4 sm:px-6 py-2 sm:py-3 border border-white/20">
                <div class="flex -space-x-2">
                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-white/30 border-2 border-white/50"></div>
                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-white/30 border-2 border-white/50"></div>
                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-white/30 border-2 border-white/50"></div>
                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/50 flex items-center justify-center text-white text-[10px] sm:text-xs font-bold">
                        +50
                    </div>
                </div>
                <p class="text-white/90 text-xs sm:text-sm font-medium">
                    Restaurantes conectados
                </p>
            </div>
        </div>
    </div>
</section>

@endsection

@section('bottom-nav')
<a href="/" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-verde-serra)]">
    <i data-lucide="home" class="w-5 h-5"></i>
    <span class="text-[10px] font-semibold">Início</span>
</a>
<a href="#modulos" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="layout-grid" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Módulos</span>
</a>
<a href="#planos" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="tag" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Planos</span>
</a>
<a href="https://wa.me/5551999999999" target="_blank" rel="noopener" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="message-circle" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Contato</span>
</a>
<a href="{{ route('login') }}" class="flex flex-col items-center gap-1 p-2 text-[var(--cor-texto-muted)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="log-in" class="w-5 h-5"></i>
    <span class="text-[10px] font-medium">Login</span>
</a>
@endsection
