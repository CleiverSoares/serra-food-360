# Template Base de Email - Serra Food 360

**Criado em:** 15/02/2026

---

## 📋 Visão Geral

Template base reutilizável para **TODOS** os emails do sistema. Garante consistência visual e facilita manutenção.

---

## 📍 Localização

```
resources/views/emails/layouts/base.blade.php
```

---

## 🎨 Componentes Disponíveis

### 1. **Header Customizável**

```blade
@section('header-title', 'Título do Email')
@section('header-subtitle', 'Subtítulo opcional')
```

### 2. **Alert Boxes** (4 tipos)

```blade
<!-- Info (Azul) -->
<div class="alert-box info">
    <p class="alert-title">💡 Título</p>
    <p class="alert-text">Mensagem informativa</p>
</div>

<!-- Success (Verde) -->
<div class="alert-box success">
    <p class="alert-title">✅ Título</p>
    <p class="alert-text">Mensagem de sucesso</p>
</div>

<!-- Warning (Amarelo) -->
<div class="alert-box warning">
    <p class="alert-title">⚠️ Título</p>
    <p class="alert-text">Mensagem de aviso</p>
</div>

<!-- Danger (Vermelho) -->
<div class="alert-box danger">
    <p class="alert-title">🚨 Título</p>
    <p class="alert-text">Mensagem urgente</p>
</div>
```

### 3. **Botões CTA**

```blade
<!-- Botão Principal (Verde) -->
<div class="button-container">
    <a href="{{ $url }}" class="button">
        Texto do Botão
    </a>
</div>

<!-- Botão Secundário (Azul) -->
<div class="button-container">
    <a href="{{ $url }}" class="button secondary">
        Ação Secundária
    </a>
</div>
```

### 4. **Info Box** (Informações simples)

```blade
<div class="info-box">
    <p class="info-label">Label</p>
    <p class="info-value">Valor</p>
    
    <p class="info-label">Outro Label</p>
    <p class="info-value">Outro Valor</p>
</div>
```

### 5. **Details Box** (Tabela de dados)

```blade
<div class="details-box">
    <div class="detail-row">
        <span class="detail-label">Nome:</span>
        <span class="detail-value">João Silva</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Email:</span>
        <span class="detail-value">joao@example.com</span>
    </div>
</div>
```

### 6. **Divisor**

```blade
<hr class="divider">
```

### 7. **Tipografia**

```blade
<h1>Título Principal</h1>
<h2>Subtítulo</h2>
<p>Parágrafo normal</p>
```

---

## 📝 Como Usar

### Estrutura Básica

```blade
@extends('emails.layouts.base')

@section('titulo', 'Título da Página - Serra Food 360')

@section('header-title', 'Título no Header')
@section('header-subtitle', 'Subtítulo opcional')

@section('content')
    <h1>Olá, {{ $nome }}!</h1>
    
    <p>Seu conteúdo aqui...</p>

    <!-- Use os componentes disponíveis -->
    <div class="alert-box info">
        <p class="alert-text">Mensagem importante</p>
    </div>

    <div class="button-container">
        <a href="{{ $url }}" class="button">
            Clique Aqui
        </a>
    </div>
@endsection

@section('footer-extra')
    {{-- Conteúdo extra no footer (opcional) --}}
@endsection
```

---

## 🎨 Paleta de Cores

```css
/* Primárias */
--verde-serra: #22C55E
--verde-escuro: #16A34A

/* Alertas */
--azul: #3B82F6
--verde: #10B981
--amarelo: #F59E0B
--vermelho: #EF4444

/* Neutras */
--texto-escuro: #1F2937
--texto-medio: #4B5563
--texto-claro: #6B7280
--borda: #E5E7EB
--fundo-claro: #F9FAFB
```

---

## 📱 Responsividade

✅ **Mobile-first**
✅ Breakpoint: 600px
✅ Testado em:
- Gmail (web + app)
- Outlook (web + desktop)
- Apple Mail (iOS + macOS)
- Yahoo Mail
- ProtonMail

---

## ✨ Features

- ✅ Estilos inline (compatibilidade máxima)
- ✅ Logo SVG embutido (sem dependência de imagens externas)
- ✅ Footer padrão em todos os emails
- ✅ Ano dinâmico no copyright
- ✅ Links de contato centralizados
- ✅ Seções customizáveis

---

## 📂 Exemplo Completo

Veja: `resources/views/emails/exemplo-uso.blade.php`

---

## 🔄 Emails que Devem Usar Este Template

1. ✅ `redefinir-senha.blade.php`
2. ✅ `aviso-vencimento-plano.blade.php`
3. ✅ `novo-cadastro-aprovacao.blade.php`
4. ✅ **Futuros emails do sistema**

---

## 🎯 Benefícios

### Manutenção
- ✅ Mudanças de marca/cores em um só lugar
- ✅ Correções de bugs aplicadas a todos os emails
- ✅ Novos componentes disponíveis instantaneamente

### Consistência
- ✅ Identidade visual única
- ✅ Experiência de usuário coesa
- ✅ Profissionalismo

### Produtividade
- ✅ Criar novos emails em minutos
- ✅ Sem copiar/colar CSS
- ✅ Componentes prontos

---

## 🚀 Próximos Passos

### Opcional - Melhorias Futuras

1. **Adicionar mais componentes:**
   - Lista de itens estilizada
   - Cards
   - Tabelas complexas
   - Progress bars

2. **Temas:**
   - Modo escuro (dark mode)
   - Tema alternativo (azul)

3. **Personalização:**
   - Logo customizável via config
   - Cores via variáveis
   - Footer dinâmico

---

## 📖 Referência Rápida

### Sections Disponíveis

| Section | Obrigatória? | Descrição |
|---------|--------------|-----------|
| `titulo` | ❌ | Título da página HTML |
| `header-title` | ❌ | Título no header verde |
| `header-subtitle` | ❌ | Subtítulo no header |
| `content` | ✅ | Conteúdo principal do email |
| `footer-extra` | ❌ | Conteúdo adicional no footer |

### Classes CSS Úteis

| Classe | Uso |
|--------|-----|
| `.alert-box` | Container de alerta |
| `.alert-box.info` | Alerta azul (informação) |
| `.alert-box.success` | Alerta verde (sucesso) |
| `.alert-box.warning` | Alerta amarelo (aviso) |
| `.alert-box.danger` | Alerta vermelho (perigo) |
| `.button` | Botão principal verde |
| `.button.secondary` | Botão secundário azul |
| `.info-box` | Box de informações simples |
| `.details-box` | Box com tabela de dados |
| `.divider` | Linha divisória |

---

## ✅ Checklist para Novos Emails

Ao criar um novo email:

- [ ] Extender `emails.layouts.base`
- [ ] Definir `@section('titulo')`
- [ ] Definir `@section('header-title')`
- [ ] Implementar `@section('content')`
- [ ] Usar componentes do template (não criar do zero)
- [ ] Testar em múltiplos clientes de email
- [ ] Verificar responsividade mobile

---

**Desenvolvido seguindo as regras do projeto Serra Food 360**
