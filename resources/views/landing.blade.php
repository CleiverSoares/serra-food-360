@extends('layouts.app')

@section('titulo', 'Bem-vindo')
@section('conteudo')
<div class="min-h-screen">
    {{-- Hero — layout split no desktop, full no mobile --}}
    <section class="relative min-h-[85vh] lg:min-h-[90vh] flex flex-col lg:flex-row lg:items-center overflow-hidden">
        {{-- Fundo — gradiente mesh mais rico --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-[500px] h-[500px] lg:w-[700px] lg:h-[700px] rounded-full bg-[var(--cor-primaria-clara)] opacity-50 blur-[100px]"></div>
            <div class="absolute top-1/3 -left-40 w-[400px] h-[400px] lg:w-[600px] lg:h-[600px] rounded-full bg-[var(--cor-secundaria-clara)] opacity-40 blur-[80px]"></div>
            <div class="absolute bottom-0 right-1/4 w-64 h-64 rounded-full bg-[var(--cor-destaque)] opacity-20 blur-[60px]"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-[var(--cor-fundo)]"></div>
        </div>

        {{-- Conteúdo — centralizado no mobile, split no desktop --}}
        <div class="relative flex-1 flex flex-col justify-center px-4 pt-16 pb-20 lg:pt-24 lg:pb-32 lg:px-12 xl:px-24 max-w-7xl mx-auto w-full">
            <div class="grid lg:grid-cols-2 lg:gap-16 xl:gap-24 lg:items-center">
                {{-- Texto --}}
                <div class="text-center lg:text-left order-2 lg:order-1">
                    <p class="text-[var(--cor-primaria)] font-semibold tracking-wider uppercase text-sm mb-4">Região Serrana</p>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-bold tracking-tight text-[var(--cor-texto)] leading-[1.1]">
                        Seu ecossistema
                        <span class="block text-[var(--cor-primaria)] mt-2">de food service</span>
                    </h1>
                    <p class="mt-6 md:mt-8 text-base md:text-lg lg:text-xl text-[var(--cor-texto-secundario)] max-w-xl lg:max-w-none mx-auto lg:mx-0 leading-relaxed">
                        Conectamos donos de restaurantes, fornecedores e prestadores de serviços.
                        Cotações, talentos, compras coletivas e muito mais em um só lugar.
                    </p>
                    <div class="mt-10 lg:mt-12 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="https://wa.me/5551999999999?text=Olá!%20Gostaria%20de%20saber%20como%20entrar%20no%20Serra%20Food%20360."
                           target="_blank" rel="noopener"
                           class="inline-flex items-center justify-center gap-3 min-h-[52px] px-10 py-4 rounded-2xl bg-[var(--cor-primaria)] text-white font-semibold text-lg hover:bg-[var(--cor-primaria-hover)] transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Solicitar entrada
                        </a>
                        <a href="#beneficios"
                           class="inline-flex items-center justify-center min-h-[52px] px-10 py-4 rounded-2xl border-2 border-[var(--cor-borda)] text-[var(--cor-texto)] font-semibold text-lg hover:border-[var(--cor-primaria)] hover:text-[var(--cor-primaria)] hover:bg-[var(--cor-primaria-clara)]/30 transition-all duration-300">
                            Conhecer benefícios
                        </a>
                    </div>
                </div>
                {{-- Visual direito (desktop) — placeholder para hero image --}}
                <div class="order-1 lg:order-2 flex justify-center lg:justify-end mt-8 lg:mt-0">
                    <div class="relative w-full max-w-md lg:max-w-none lg:w-[120%] aspect-square rounded-3xl bg-gradient-to-br from-[var(--cor-primaria-clara)] to-[var(--cor-secundaria-clara)] opacity-60 flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-4 rounded-2xl border-2 border-[var(--cor-borda)]/50 flex items-center justify-center">
                            <span class="text-[var(--cor-texto-muted)] text-sm text-center px-4">[ Imagem hero — gerar no Gemini ]</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefícios — grid bento no desktop, cards no mobile --}}
    <section id="beneficios" class="py-20 md:py-28 lg:py-36 px-4 bg-[var(--cor-fundo-secundario)]">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 lg:mb-24">
                <p class="text-[var(--cor-primaria)] font-semibold tracking-wider uppercase text-sm mb-3">O que você encontra</p>
                <h2 class="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-[var(--cor-texto)]">
                    Tudo que o dono de restaurante precisa
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                @foreach([
                    ['Restaurantes', 'Diretório de estabelecimentos da região com dados estratégicos e contato direto.', 'M4 6h16M4 12h16M4 18h16', 'lg:row-span-1'],
                    ['Cotações', 'Preços semanais de insumos comparados entre fornecedores. Kanban, planilha ou gráfico.', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'lg:row-span-1'],
                    ['Talentos', 'Banco de profissionais universitários para trabalho extra. Conecte-se via WhatsApp.', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'lg:row-span-1'],
                    ['Compras Coletivas', 'Manifeste interesse em itens de alto volume. Fornecedores negociam preços para o grupo.', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'lg:row-span-1'],
                    ['Material de Gestão', 'Vídeos, PDFs e treinamentos: DRE, CMV, legislação e gestão de equipe.', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'lg:row-span-1'],
                    ['Consultor IA', 'Assistente especializado em food service para tirar dúvidas técnicas em tempo real.', 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z', 'lg:row-span-1'],
                ] as $item)
                <div class="group p-6 md:p-8 lg:p-10 rounded-2xl lg:rounded-3xl bg-[var(--cor-superficie)] border border-[var(--cor-borda)] hover:border-[var(--cor-primaria)]/40 hover:shadow-2xl transition-all duration-300 {{ $item[3] }}">
                    <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-2xl bg-[var(--cor-primaria-clara)] flex items-center justify-center mb-5 lg:mb-6 group-hover:bg-[var(--cor-primaria)] group-hover:scale-110 transition-all duration-300">
                        <svg class="w-7 h-7 lg:w-8 lg:h-8 text-[var(--cor-primaria)] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item[2] }}"/></svg>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-bold text-[var(--cor-texto)] mb-3">{{ $item[0] }}</h3>
                    <p class="text-[var(--cor-texto-secundario)] text-base lg:text-lg leading-relaxed">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA final — destaque em ambas as telas --}}
    <section class="py-20 md:py-28 lg:py-36 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="relative rounded-3xl lg:rounded-[2rem] p-8 md:p-12 lg:p-16 overflow-hidden"
                 style="background: linear-gradient(135deg, var(--cor-primaria-clara) 0%, var(--cor-secundaria-clara) 100%);">
                <div class="absolute inset-0 opacity-30">
                    <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-[var(--cor-primaria)] blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full bg-[var(--cor-secundaria)] blur-3xl"></div>
                </div>
                <div class="relative text-center">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-[var(--cor-texto)] mb-4 lg:mb-6">
                        Acesso restrito a membros aprovados
                    </h2>
                    <p class="text-[var(--cor-texto-secundario)] text-base md:text-lg lg:text-xl mb-8 lg:mb-10 max-w-2xl mx-auto">
                        O ecossistema é nichado ao setor de alimentação. Entre em contato para solicitar sua entrada.
                    </p>
                    <a href="https://wa.me/5551999999999?text=Olá!%20Gostaria%20de%20solicitar%20entrada%20no%20Serra%20Food%20360."
                       target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center gap-3 min-h-[56px] px-12 py-4 rounded-2xl bg-[var(--cor-primaria)] text-white font-semibold text-lg lg:text-xl hover:bg-[var(--cor-primaria-hover)] transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-[1.02]">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Falar com o administrador
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('bottom-nav')
<a href="#beneficios" class="flex flex-col items-center gap-1 min-w-[48px] min-h-[48px] justify-center text-[var(--cor-texto-muted)] hover:text-[var(--cor-primaria)] transition-colors">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
    <span class="text-xs font-medium">Benefícios</span>
</a>
<a href="https://wa.me/5551999999999" target="_blank" rel="noopener" class="flex flex-col items-center gap-1 min-w-[48px] min-h-[48px] justify-center text-[var(--cor-primaria)]">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    <span class="text-xs font-semibold">WhatsApp</span>
</a>
@endsection
