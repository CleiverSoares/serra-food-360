# Serra Food 360 — Especificação Completa do Projeto

> Ecossistema digital para donos de restaurantes, fornecedores e prestadores de serviços da região serrana.  
> Domínio: **serrafood360.com.br**

---

## 1. Objetivo

Criar um **hub** que conecta **compradores** (restaurantes, pet shops, estabelecimentos diversos) e **fornecedores** do setor comercial da região serrana, oferecendo:

- Informações centralizadas
- **Segmentação inteligente** (cruzamentos por segmento de mercado)
- Negociações coletivas
- Ferramentas de gestão
- Conexão direta via WhatsApp

**Nota importante:**
- O sistema usa **"Comprador"** ao invés de "Restaurante" para ser mais genérico e escalável
- Cada usuário pertence a um ou mais **segmentos** (alimentação, pet-shop, construção, varejo, serviços)
- Fornecedores aparecem apenas para compradores com segmentos em comum

---

## 2. Formato e Tecnologia

| Item | Especificação |
|------|---------------|
| Plataforma | Site responsivo (web) — celular e computador |
| Painel Admin | Dashboard para alimentar dados, editar conteúdo e gerenciar de forma autônoma |
| Domínio | serrafood360.com.br |

---

## 3. Módulos e Telas (com descrições de imagens)

### 3.1 Portal de Acesso e Boas-Vindas

#### Tela de Login/Cadastro
- Acesso restrito a usuários pagantes e aprovados
- Campos de login para membros validados

#### Landing Page (não membros)
- Explicação dos benefícios do ecossistema
- Como solicitar entrada (contato com administrador)
- Fluxo de validação manual

**Descrição de foto para Gemini (hero da landing):**
> Imagem moderna e acolhedora de um ambiente de restaurante ou cozinha profissional em Teresópolis / Região Serrana Fluminense. Atmosfera clean, tons quentes, pratos ou ingredientes de qualidade em destaque. Sensação de comunidade e profissionalismo. Estilo fotográfico editorial, não stock genérico. Sugestão: cozinha com vista para montanhas ou produtores locais.

---

### 3.2 Dashboard Principal (Home)

- **Navegação por ícones:** botões grandes para: Compradores, Fornecedores, Cotações, Talentos, Gestão, Consultor de IA
- **Destaques VIP:** banners/avisos exclusivos para Plano 2X (mentorias Zoom, promoções)
- **Bottom navigation** (estilo app mobile): 3 ícones principais + menu hambúrguer
- **Sidebar desktop** (estilo ERP): menu lateral completo com todos os módulos
- **Drawer lateral mobile:** menu deslizante com swipe para fechar, overlay com blur

**Descrição de foto para Gemini (ilustração de boas-vindas):**
> Ilustração flat ou 3D suave de um chef ou dono de restaurante sorridente, cercado por ícones de comida, pratos e ingredientes. Cores da marca, tom profissional e acolhedor. Sensação de "seu parceiro no dia a dia".

---

### 3.3 Banco de Talentos (Extras) — ✅ IMPLEMENTADO

- **Público:** profissionais universitários para trabalho extra, freelancers, avulsos
- **Lista por cargos:** garçom, cozinheiro, recepcionista, barman, sommelier, etc.
- **Filtros avançados:**
  - Busca por nome, cargo ou telefone
  - Filtro por cargo
  - Filtro por disponibilidade (finais de semana, noites, eventos, etc.)
  - **Filtro por tipo de cobrança:** Por Hora ou Por Dia
  - **Range de valor:** R$ mínimo e máximo
- **Cards de perfil:** foto (ou avatar placeholder), mini currículo, pretensão, tipo cobrança, badges coloridas
- **Tela de detalhes:** currículo completo, download de PDF, carta de recomendação
- **Ação:** botão direto para WhatsApp
- **Gerenciamento admin:** CRUD completo (criar, editar, ativar/inativar, deletar)

**Descrição de foto para Gemini (placeholder de candidato):**
> Silhueta ou avatar genérico de pessoa profissional em ambiente de restaurante. Tom neutro, profissional, adequado para placeholder quando o candidato não tiver foto. Formato quadrado, fundo suave.

**Descrição de foto para Gemini (ícone do módulo Talentos):**
> Ícone minimalista de pessoas ou rede de profissionais. Linhas limpas, cor da marca. Pode incluir pequeno símbolo de currículo ou chapéu de chef. Estilo app moderno.

---

### 3.4 Cotação da Semana

- Comparativo de preços de insumos (ex.: filé mignon) entre fornecedores
- **Layout:** Kanban (prioritário), planilha ou gráficos
- **Ficha do item:** unidade de medida, quantidade mínima, observações por fornecedor

**Descrição de foto para Gemini (ícone do módulo Cotações):**
> Ícone de planilha, gráfico de barras ou balança. Representar preço e comparação. Linhas clean, minimalista. Cores da marca.

---

### 3.5 Interesse do Cliente (Compras Coletivas)

- **Painel de demandas:** donos sinalizam interesse em itens de alto volume (ex.: fardos de trigo)
- **Interface fornecedor:** visão do volume total de interesse para negociação com o grupo

**Descrição de foto para Gemini (ícone do módulo Compras Coletivas):**
> Ícone de grupo de pessoas ou carrinho compartilhado. Ideia de união e volume. Estilo minimalista, cor da marca.

---

### 3.6 Diretório de Compradores (ex-Restaurantes)

- **Vitrine:** estabelecimentos pagantes com foto e descrição
- **Segmentação:** cada comprador pertence a 1+ segmentos (alimentação, pet-shop, etc.)
- **Dados:** quantidade de colaboradores, CNPJ, cidade, tipo de negócio
- **Contato:** botão WhatsApp em cada card
- **Cruzamento inteligente:** fornecedores veem apenas compradores com segmentos em comum

**Descrição de foto para Gemini (placeholder de restaurante):**
> Fachada genérica de restaurante aconchegante na serra. Arquitetura típica, madeira e pedra. Ambiente gastronômico. Placeholder para quando não houver foto do estabelecimento.

**Descrição de foto para Gemini (ícone do módulo Restaurantes):**
> Ícone de prato, talheres ou restaurante. Representar estabelecimento gastronômico. Estilo app, minimalista.

---

### 3.7 Diretório de Fornecedores e Serviços

- **Segmentos:** Alimentação, Pet Shop, Construção, Varejo, Serviços (não "categorias")
- Um fornecedor pode pertencer a vários **segmentos** (many-to-many)
- **Perfil:** nome do representante, marcas atendidas, CNPJ, e-mail, site, botão WhatsApp
- **Cruzamento inteligente:** compradores veem apenas fornecedores com segmentos em comum

**Descrição de foto para Gemini (placeholder de fornecedor):**
> Logotipo genérico ou imagem de caixas/insumos organizados. Tom profissional, neutro. Placeholder para fornecedor sem foto.

**Descrição de foto para Gemini (ícone do módulo Fornecedores):**
> Ícone de caminhão de entregas, caixas ou handshake. Representar supply chain e parcerias. Minimalista.

---

### 3.8 Material de Gestão

- **Temas:** Financeiro, Cozinha, Legislação, Gestão de Equipe
- **Vídeos:** incorporados do YouTube
- **Downloads:** PDFs de DRE, CMV, sinalizações obrigatórias, treinamentos

**Descrição de foto para Gemini (ícone do módulo Gestão):**
> Ícone de pasta, documento ou gráfico de gestão. Representar organização e conhecimento. Estilo clean.

---

### 3.9 Consultor de IA

- **Chat:** assistente de IA especializado em food service
- Integração via API (Gemini ou ChatGPT)
- Suporte técnico em tempo real

**Descrição de foto para Gemini (avatar do Consultor IA):**
> Avatar amigável de assistente virtual. Pode ser robô simpático ou ícone de IA com toque gastronômico (chapéu de chef, prato). Cores da marca, acolhedor.

---

### 3.10 Troca de Equipamentos (Classificados)

- Anúncios de compra, venda ou permuta de equipamentos usados (fatiadoras, mesas, etc.)
- Entre restaurantes da região

**Descrição de foto para Gemini (placeholder de equipamento):**
> Equipamento de cozinha profissional genérico (fatiadora, geladeira industrial). Ilustração ou foto clean. Placeholder para anúncio sem imagem.

**Descrição de foto para Gemini (ícone do módulo Troca):**
> Ícone de troca, setas circulares ou equipamento. Representar permuta e marketplace. Minimalista.

---

### 3.11 Painel Administrativo (Retaguarda)

- **Usuários:** aprovar logins, monitorar acessos
- **CRUD:** cotações, currículos, materiais de gestão
- **Configuração:** adicionar/remover ícones e módulos (ex.: "Relacionamento Governamental")

#### Personalização do Admin (temas e configs)

O admin será **altamente personalizável**:

| Recurso | Descrição |
|---------|-----------|
| **Temas** | Múltiplos temas (não só dark/light): serra, oceano, neutro, etc. |
| **Cores** | Todas em variáveis CSS — primária, secundária, fundo, texto editáveis pelo painel |
| **Configurações** | Outras opções de aparência e comportamento configuráveis no admin |

**Obrigatório:** cores sempre em variáveis; nunca valores fixos. Ver regra de frontend.

---

## 4. Gestão de Acessos e Monetização

| Item | Detalhe |
|------|---------|
| **Plano Comum (X)** | Acesso às funcionalidades base |
| **Plano VIP (2X)** | Mentorias mensais em grupo, promoções de fornecedores, workshops |
| **Login** | Restrito, aprovação manual pelo admin, nicho alimentação |
| **Pagamento** | Plataforma Asaas, cobrança recorrente via cartão |

---

## 5. Regras de Negócio

- Novos membros: pré-filtro ou entrevista antes da liberação
- Admin atualiza cotações e currículos de forma ágil
- Código preparado para escalar (ex.: Rio Food 360) e novos módulos
- **Sistema de Segmentos:**
  - Cada usuário (comprador ou fornecedor) pertence a 1+ segmentos
  - 5 segmentos iniciais: alimentação, pet-shop, construção, varejo, serviços
  - Cruzamentos inteligentes: fornecedor só aparece para compradores com segmentos em comum
  - Escalável: novos segmentos podem ser adicionados facilmente
- **Nomenclatura:**
  - "Comprador" ao invés de "Restaurante" (mais genérico, escalável)
  - Retrocompatibilidade mantida no código (aliases)
- **Talentos (Banco de Extras):**
  - Gerenciado apenas pelo admin (talentos não têm login)
  - Cobrança flexível: por hora ou por dia
  - Contato direto via WhatsApp

---

## 6. Princípios do Projeto

- **Usabilidade** em primeiro lugar
- **Independência** do admin para editar conteúdo via dashboard
- **Versão 1.0:** básico bem feito para validar o modelo
- **Mobile-first:** experiência excelente no celular
- **Bottom navigation** tipo app
- **WhatsApp** como canal principal de contato

---

## 7. Referência Rápida — Onde Usar Cada Imagem

| Local | Descrição para Gemini |
|-------|------------------------|
| Hero Landing | Cozinha/restaurante serra, editorial, profissional |
| Dashboard boas-vindas | Chef/parceiro, ilustração acolhedora |
| Placeholder candidato | Avatar profissional neutro, quadrado |
| Placeholder restaurante | Fachada típica serra, gastronomia |
| Placeholder fornecedor | Logo/caixas, neutro |
| Placeholder equipamento | Equipamento cozinha industrial |
| Avatar Consultor IA | Assistente virtual com toque gastronômico |
| Ícones módulos | Ver descrições em cada seção 3.3–3.10 |
