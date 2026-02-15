@extends('layouts.dashboard')

@section('titulo', 'Adicionar Talento')
@section('page-title', 'Adicionar Talento')
@section('page-subtitle', 'Cadastrar novo profissional no banco de talentos')

@section('header-actions')
<a href="{{ route('admin.talentos.index') }}" class="flex items-center gap-2 px-4 py-2 text-[var(--cor-texto-secundario)] hover:text-[var(--cor-verde-serra)] transition-colors">
    <i data-lucide="arrow-left" class="w-4 h-4"></i>
    Voltar
</a>
@endsection

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-4xl mx-auto">

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-sm text-red-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.talentos.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Dados Básicos -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-amber-600"></i>
                    Informações Básicas
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Nome Completo *</label>
                        <input type="text" name="nome" value="{{ old('nome') }}" required
                               placeholder="Ex: João Silva Santos"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Cargo *</label>
                        <input type="text" name="cargo" value="{{ old('cargo') }}" required
                               placeholder="Ex: Garçom, Cozinheiro, Barman"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">WhatsApp *</label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" required
                               placeholder="(27) 99999-9999"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Tipo de Cobrança *</label>
                        <select name="tipo_cobranca" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Selecione...</option>
                            <option value="hora" {{ old('tipo_cobranca') === 'hora' ? 'selected' : '' }}>Por Hora</option>
                            <option value="dia" {{ old('tipo_cobranca', 'dia') === 'dia' ? 'selected' : '' }}>Por Dia</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Como o profissional cobra</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Valor Pretendido (R$)</label>
                        <input type="number" name="pretensao" value="{{ old('pretensao') }}" step="0.01" min="0"
                               placeholder="Ex: 80.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Valor por hora ou dia (conforme tipo de cobrança)</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Disponibilidade</label>
                        <select name="disponibilidade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
                            <option value="">Selecione...</option>
                            <option value="Durante a semana" {{ old('disponibilidade') === 'Durante a semana' ? 'selected' : '' }}>Durante a semana</option>
                            <option value="Finais de semana" {{ old('disponibilidade') === 'Finais de semana' ? 'selected' : '' }}>Finais de semana</option>
                            <option value="Noites" {{ old('disponibilidade') === 'Noites' ? 'selected' : '' }}>Noites</option>
                            <option value="Noites e finais de semana" {{ old('disponibilidade') === 'Noites e finais de semana' ? 'selected' : '' }}>Noites e finais de semana</option>
                            <option value="Finais de semana e eventos" {{ old('disponibilidade') === 'Finais de semana e eventos' ? 'selected' : '' }}>Finais de semana e eventos</option>
                            <option value="Integral" {{ old('disponibilidade') === 'Integral' ? 'selected' : '' }}>Integral</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Período de disponibilidade para trabalho</p>
                    </div>
                </div>
            </div>

            <!-- Mini Currículo -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-purple-600"></i>
                    Resumo Profissional
                </h3>
                <div>
                    <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Mini Currículo *</label>
                    <textarea name="mini_curriculo" rows="5" required
                              placeholder="Descreva brevemente a experiência, habilidades e disponibilidade..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">{{ old('mini_curriculo') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Informações que aparecerão na listagem de talentos</p>
                </div>
            </div>

            <!-- Arquivos -->
            <div class="bg-white rounded-xl shadow-sm border border-[var(--cor-borda)] p-4 md:p-6">
                <h3 class="text-lg font-bold text-[var(--cor-texto)] mb-4 flex items-center gap-2">
                    <i data-lucide="paperclip" class="w-5 h-5 text-blue-600"></i>
                    Documentos e Foto
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Foto do Profissional</label>
                        <input type="file" name="foto" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG ou WEBP (máx. 2MB)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Currículo Completo (PDF)</label>
                        <input type="file" name="curriculo_pdf" accept=".pdf"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Arquivo PDF (máx. 5MB)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[var(--cor-texto)] mb-2">Carta de Recomendação (PDF)</label>
                        <input type="file" name="carta_recomendacao" accept=".pdf"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)] focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Arquivo PDF (máx. 5MB)</p>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:opacity-90 transition-all font-medium text-sm md:text-base">
                    <i data-lucide="save" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cadastrar Talento</span>
                </button>
                <a href="{{ route('admin.talentos.index') }}" 
                   class="inline-flex items-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm md:text-base">
                    <i data-lucide="x" class="w-4 h-4 flex-shrink-0"></i>
                    <span class="whitespace-nowrap">Cancelar</span>
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
