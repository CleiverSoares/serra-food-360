# Logo e Favicon - Serra Food 360

## 🎨 Arquivos de Identidade Visual

### Localizações Atuais

```
public/images/
├── logo-serra.png        → Logo principal (navbar desktop)
└── fiveicon-360.svg      → Favicon (aba do navegador)
```

---

## 🔷 Favicon

### O que é?
O ícone que aparece na aba do navegador, favoritos e atalhos.

### Arquivo Atual
- **Nome:** `fiveicon-360.svg`
- **Tipo:** SVG (vetor)
- **Local:** `public/images/fiveicon-360.svg`

### Como Substituir

1. **Prepare seu favicon:**
   - Formato recomendado: SVG ou ICO
   - Tamanho: 32x32px ou 64x64px
   - Pode ser PNG também

2. **Substitua o arquivo:**
   - Salve como `fiveicon-360.svg` (ou outro nome)
   - Coloque em `public/images/`

3. **Se mudar o nome, atualize no layout:**
   
   Edite `resources/views/layouts/app.blade.php`:
   ```blade
   <link rel="icon" type="image/svg+xml" href="/images/SEU-FAVICON.svg">
   <link rel="shortcut icon" href="/images/SEU-FAVICON.svg">
   ```

### Tipos de Favicon Suportados

```html
<!-- SVG (recomendado - escala perfeitamente) -->
<link rel="icon" type="image/svg+xml" href="/images/favicon.svg">

<!-- PNG (boa compatibilidade) -->
<link rel="icon" type="image/png" href="/images/favicon.png">

<!-- ICO (máxima compatibilidade) -->
<link rel="icon" href="/images/favicon.ico">

<!-- Múltiplos tamanhos (Apple/Android) -->
<link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
```

---

## 🖼️ Logo Principal

### Arquivo Atual
- **Nome:** `logo-serra.png`
- **Tipo:** PNG
- **Local:** `public/images/logo-serra.png`
- **Uso:** Navbar desktop (topo esquerdo)

### Especificações Recomendadas

**Dimensões:**
- Altura: 48-64px (ajusta automaticamente)
- Largura: Proporcional ao design
- Formato: PNG com fundo transparente

**Qualidade:**
- Resolução: 2x para telas Retina (96-128px altura)
- Compressão: Otimizada para web (< 100KB)

### Como Substituir

1. **Prepare seu logo:**
   - Fundo transparente (PNG)
   - Alta qualidade
   - Otimizado para web

2. **Substitua o arquivo:**
   - Salve como `logo-serra.png`
   - Coloque em `public/images/`

3. **Teste a visualização:**
   - Recarregue a página
   - Verifique se está alinhado corretamente
   - Teste o hover effect

### Se Quiser Mudar o Nome/Local

Edite `resources/views/layouts/app.blade.php`:

```blade
<img src="/images/MEU-LOGO.png" 
     alt="Serra Food 360" 
     class="h-12 w-auto group-hover:scale-105 transition-transform">
```

### Ajustar Tamanho

Mude a altura no Tailwind:

```blade
<!-- Pequeno -->
<img src="/images/logo-serra.png" class="h-8 w-auto">

<!-- Médio (atual) -->
<img src="/images/logo-serra.png" class="h-12 w-auto">

<!-- Grande -->
<img src="/images/logo-serra.png" class="h-16 w-auto">
```

---

## 🎨 Formatos de Logo Suportados

### PNG (Recomendado)
- ✅ Fundo transparente
- ✅ Boa qualidade
- ✅ Amplamente suportado

### SVG (Ideal)
- ✅ Escala perfeitamente
- ✅ Arquivo leve
- ✅ Pode mudar cores via CSS

```blade
<img src="/images/logo-serra.svg" 
     alt="Serra Food 360" 
     class="h-12 w-auto">
```

### JPG (Não recomendado)
- ❌ Sem transparência
- ❌ Perde qualidade ao redimensionar

---

## 🔄 Fallback (Segurança)

O código atual tem um fallback automático:

```blade
<img src="/images/logo-serra.png" 
     onerror="this.onerror=null; this.style.display='none'; 
              this.nextElementSibling.style.display='flex';">

<!-- Se a imagem falhar, mostra este ícone -->
<div class="hidden w-10 h-10 rounded-xl bg-[var(--cor-verde-serra)] 
            items-center justify-center">
    <i data-lucide="utensils" class="w-6 h-6 text-white"></i>
</div>
```

Se o `logo-serra.png` não carregar, aparece um ícone verde com talheres.

---

## 🎯 Onde Cada Imagem Aparece

### Favicon (`fiveicon-360.svg`)
- ✅ Aba do navegador
- ✅ Barra de favoritos
- ✅ Histórico
- ✅ Atalhos da área de trabalho
- ✅ Tela inicial mobile (se configurado)

### Logo (`logo-serra.png`)
- ✅ Navbar desktop (topo esquerdo)
- ✅ Clicável (vai para home)
- ✅ Hover effect (aumenta 5%)

---

## 🖥️ Testando as Mudanças

### Favicon
1. Salve o novo favicon
2. Recarregue a página (Ctrl+F5)
3. Limpe o cache se não aparecer
4. Verifique na aba do navegador

### Logo
1. Salve o novo logo
2. Recarregue a página
3. Verifique:
   - Alinhamento
   - Tamanho
   - Qualidade
   - Hover effect

---

## 📱 Mobile

**Importante:** O logo atualmente só aparece na navbar desktop.

Se quiser adicionar logo no mobile, edite `landing.blade.php` na seção `@section('bottom-nav')`.

---

## 🛠️ Ferramentas Úteis

### Criar Favicon
- **Favicon.io:** https://favicon.io/
- **RealFaviconGenerator:** https://realfavicongenerator.net/
- **Canva:** Design de ícones

### Otimizar Logo
- **TinyPNG:** https://tinypng.com/
- **Squoosh:** https://squoosh.app/
- **ImageOptim:** App para Mac/Windows

### Converter Formatos
- **CloudConvert:** https://cloudconvert.com/
- **Convertio:** https://convertio.co/

---

## 📋 Checklist de Qualidade

### Favicon
- [ ] Tamanho adequado (32x32 ou 64x64)
- [ ] Formato otimizado (SVG ou PNG)
- [ ] Visível em fundo claro e escuro
- [ ] Simples e reconhecível
- [ ] Arquivo leve (< 50KB)

### Logo
- [ ] Alta resolução (2x para Retina)
- [ ] Fundo transparente (PNG)
- [ ] Cores consistentes com a marca
- [ ] Legível no tamanho atual
- [ ] Otimizado (< 100KB)
- [ ] Proporcional e alinhado

---

## 🎨 Dicas de Design

### Favicon
- Mantenha simples (poucos detalhes)
- Use cores da marca
- Teste em vários tamanhos
- Evite texto pequeno

### Logo
- Use versão horizontal para navbar
- Mantenha proporção original
- Contraste adequado com fundo branco
- Evite sombras pesadas

---

**Seus arquivos de identidade visual estão prontos!** 🎨✨

Qualquer dúvida sobre como trocar ou otimizar, consulte este guia.
