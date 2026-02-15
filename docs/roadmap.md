# Serra Food 360 — Roadmap Completo

> Guia de execução em fases, sem deixar nada de fora da ideia do projeto.

---

## Como usar este roadmap

| Ação | Onde | Quando |
|------|------|--------|
| **Marcar como feito** | Checkboxes `[ ]` → `[x]` neste arquivo | Ao concluir cada item |
| **Documentar o que foi construído** | `documentacao-implementacao.md` | Ao concluir cada item ou fase |

**Regra:** Tudo que for construído deve ser marcado no roadmap e detalhado na documentação de implementação (arquivos, modelos, rotas, decisões técnicas).

**Imagens:** Em cada tela que usa imagem, o roadmap indica o tipo/descrição para criar no Gemini de forma personalizada.

---

## Princípios do Roadmap

- **Versão 1.0:** "básico bem feito" para validar o modelo de negócio
- **Mobile-first:** cada entrega deve funcionar bem no celular
- **Independência do admin:** ele deve conseguir editar o conteúdo via painel
- **Escalabilidade:** estrutura preparada para replicar (ex.: Rio Food 360)

---

## Visão Geral das Fases

| Fase | Nome | Objetivo principal |
|------|------|--------------------|
| 0 | Fundação | Ambiente, stack, variáveis CSS, estrutura base |
| 1 | Autenticação e Portal Público | Login, aprovação manual, landing para não membros |
| 2 | Portal de Membros | Dashboard, bottom nav, navegação por ícones |
| 3 | Diretórios | Restaurantes e Fornecedores (vitrines + WhatsApp) |
| 4 | Banco de Talentos | Candidatos, cargos, currículos, WhatsApp |
| 5 | Cotações e Compras Coletivas | Cotação da semana + Interesse do cliente |
| 6 | Material de Gestão | Vídeos YouTube, PDFs, temas (Financeiro, Cozinha, etc.) |
| 7 | Consultor de IA e Classificados | Chat com IA + Troca de equipamentos |
| 8 | Painel Administrativo | CRUD, aprovação, temas, configs, ícones |
| 9 | Monetização | Planos Comum/VIP, Asaas, destaques VIP |
| 10 | Polimento e Entrega | Domínio, responsivo, usabilidade, deploy |

---

---

## Fase 0 — Fundação

**Objetivo:** Ter o ambiente rodando com stack definida e padrões estabelecidos.

### 0.1 Ambiente e Stack

- [x] Projeto Laravel criado
- [x] Tailwind CSS configurado
- [x] Alpine.js integrado (+ plugin collapse)
- [x] Estrutura de pastas (resources/views, app, etc.)
- [x] Configuração para domínio serrafood360.com.br (env, etc.)
- [x] Lucide Icons integrado via CDN
- [x] Fonts otimizadas (Fraunces + Plus Jakarta Sans)

### 0.2 Sistema de Variáveis CSS (Temas)

- [x] Arquivo de variáveis em `:root` com todas as cores semânticas:
  - `--cor-primaria`, `--cor-secundaria`, `--cor-fundo`, `--cor-texto`, `--cor-destaque`
  - Cores de botões, bordas, estados (hover, ativo)
- [x] Pelo menos um tema padrão funcionando
- [x] Tailwind configurado para usar variáveis (ou fallback)
- [x] Documentação de quais variáveis existem e onde usar

### 0.3 Layout Base

- [x] Layout Blade principal (app público)
- [x] Layout Blade do painel admin (estrutura separada)
- [x] Layout Dashboard (ERP desktop + App mobile) para área logada
- [x] Estrutura mobile-first: viewport, touch targets 44px+
- [x] Bottom navigation (estilo app com ícones)
- [x] Sidebar desktop (estilo ERP)

---

## Fase 1 — Autenticação e Portal Público

**Objetivo:** Visitantes veem a landing; membros aprovados fazem login.

### 1.1 Autenticação

- [ ] Sistema de login (email/senha)
- [ ] Cadastro de novo usuário (aguarda aprovação)
- [ ] Tabela/modelo de usuários com campos: ativo, aprovado, tipo (restaurante/fornecedor/prestador)
- [ ] Middleware para verificar se usuário está aprovado antes de acessar área de membros
- [ ] Fluxo de “aguardando aprovação” para quem acabou de se cadastrar

### 1.2 Landing Page (visitantes não membros)

- [x] Página inicial pública em `/` ou rota dedicada
- [x] Hero com imagem (placeholder SVG, pronto para substituir)
  - **Imagem (criar no Gemini):** Cozinha/restaurante profissional na serra gaúcha. Atmosfera clean, tons quentes, pratos ou ingredientes de qualidade. Sensação de comunidade e profissionalismo. Estilo editorial, não stock. Cozinha com vista para montanhas ou produtores locais.
- [x] Texto explicando benefícios do ecossistema
- [x] Como solicitar entrada (contato com administrador, validação manual)
- [x] CTA para cadastro ou contato (WhatsApp do admin)
- [x] Design responsivo, mobile-first
- [x] 8 módulos apresentados com ícones
- [x] Seção "Como funciona" (3 passos)
- [x] Comparação de planos (Comum vs VIP)
- [x] Depoimentos de clientes
- [x] FAQ com accordion
- [x] CTA final com urgência
- [x] Otimizações mobile completas
- [x] Navbar desktop com logo
- [x] Bottom navigation mobile (5 itens incluindo Login)
- [x] Favicon configurado
- [x] Logo implementado

### 1.3 Tela de Login/Cadastro

- [ ] Formulário de login para usuários aprovados
- [ ] Formulário de cadastro (pré-filtro: dados básicos, tipo de negócio)
- [ ] Mensagens claras: “Aguardando aprovação”, “Acesso negado”, etc.
- [ ] Redirect após login para Dashboard (Fase 2)

---

## Fase 2 — Portal de Membros (Dashboard Principal)

**Objetivo:** Área logada com navegação por ícones e bottom nav.

### 2.1 Dashboard (Home)

- [ ] Tela de boas-vindas após login
- [ ] Ilustração de boas-vindas
  - **Imagem (criar no Gemini):** Chef ou dono de restaurante sorridente, cercado por ícones de comida, pratos e ingredientes. Ilustração flat ou 3D suave. Cores da marca, tom profissional e acolhedor. Sensação de "seu parceiro no dia a dia".
- [ ] Navegação por ícones grandes (estilo "bolinhas"): Restaurantes, Fornecedores, Cotações, Compras Coletivas, Talentos, Gestão, Consultor de IA, Troca de Equipamentos
- [ ] Ícones personalizados (um por módulo — criar no Gemini):
  - **Restaurantes:** Prato, talheres ou restaurante. Minimalista.
  - **Fornecedores:** Caminhão de entregas, caixas ou handshake. Supply chain.
  - **Cotações:** Planilha, gráfico de barras ou balança. Preço e comparação.
  - **Compras Coletivas:** Grupo de pessoas ou carrinho compartilhado. União e volume.
  - **Talentos:** Pessoas ou rede de profissionais. Pode ter currículo ou chapéu de chef.
  - **Gestão:** Pasta, documento ou gráfico. Organização e conhecimento.
  - **Consultor IA:** Assistentes virtuais ou IA com toque gastronômico.
  - **Troca:** Setas circulares ou equipamento. Permuta e marketplace.
- [ ] Preparar espaço para “Destaques VIP” (será ligado na Fase 9)

### 2.2 Bottom Navigation (estilo app)

- [ ] Barra fixa na parte inferior
- [ ] Botões/ícones principais: Home + módulos prioritários
- [ ] Menu hambúrguer para ações secundárias (se necessário)
- [ ] Funcionar bem em mobile (touch, altura adequada)

### 2.3 Controle de Acesso

- [ ] Rotas protegidas: apenas usuários aprovados
- [ ] Diferenciar tipos de usuário (restaurante vs fornecedor) quando necessário

---

## Fase 3 — Diretórios (Restaurantes e Fornecedores)

**Objetivo:** Vitrines com fotos, dados e botão WhatsApp.

### 3.1 Diretório de Compradores (ex-Restaurantes)

- [ ] Listagem de estabelecimentos pagantes (com filtro por **segmento**)
- [ ] Cards com: foto, nome, descrição, quantidade de colaboradores, **badges de segmentos**
- [ ] Placeholder de foto quando não houver
  - **Imagem (criar no Gemini):** Fachada genérica de estabelecimento aconchegante na serra. Arquitetura típica, madeira e pedra. Ambiente profissional. Placeholder para estabelecimento sem foto.
- [ ] Botão WhatsApp em cada card (link automatizado)
- [x] Modelo `CompradorModel` (ex-RestauranteModel), Repository, Service, Controller
- [ ] Filtros básicos: cidade, tipo, **segmento**
- [x] **Cruzamento inteligente:** Só mostra fornecedores com segmentos em comum

### 3.2 Diretório de Fornecedores e Serviços

- [x] **Segmentos substituem categorias:** alimentacao, pet-shop, construcao, varejo, servicos
- [x] Um fornecedor pode estar em vários **segmentos** (many-to-many)
- [ ] Perfil: nome do representante, marcas atendidas, e-mail, site, botão WhatsApp
- [ ] Placeholder de foto/logo quando não houver
  - **Imagem (criar no Gemini):** Logotipo genérico ou imagem de caixas/insumos organizados. Tom profissional, neutro. Placeholder para fornecedor sem foto.
- [x] Modelo, Repository, Service, Controller
- [ ] Navegação por **segmento** (tabs ou filtros)
- [x] **Cruzamento inteligente:** Só aparece para compradores com segmentos em comum

### 3.3 Dados Administrados

- [ ] Admin consegue cadastrar/editar restaurantes e fornecedores (CRUD será completo na Fase 8)
- [ ] Campos persistidos conforme doc (colaboradores, telefone WhatsApp, etc.)

---

## Fase 4 — Banco de Talentos (Extras)

**Objetivo:** Candidatos universitários para trabalho extra, com WhatsApp.

### 4.1 Cadastro de Talentos

- [ ] Cadastro inicialmente manual (admin ou formulário controlado)
- [ ] Campos: foto, mini currículo, pretensão salarial, dias disponíveis
- [ ] Anexo: currículo original PDF ou carta de recomendação
- [ ] Organização por cargos: garçom, cozinheiro, recepcionista

### 4.2 Listagem e Detalhes

- [ ] Lista de candidatos por cargo (filtro/tabs)
- [ ] Cards: foto, mini currículo, pretensão, dias/horários
- [ ] Placeholder de foto quando candidato não tiver
  - **Imagem (criar no Gemini):** Silhueta ou avatar genérico de pessoa profissional em ambiente de restaurante. Tom neutro, profissional. Formato quadrado, fundo suave.
- [ ] Tela de detalhes: currículo completo, link para PDF
- [ ] Botão WhatsApp em cada card/detalhe
- [ ] Modelo, Repository, Service, Controller

### 4.3 Atualização Ágil

- [ ] Admin consegue atualizar currículos e dados de forma ágil (CRUD na Fase 8)

---

## Fase 5 — Cotações e Compras Coletivas

**Objetivo:** Comparativo de preços + interesse em itens de alto volume.

### 5.1 Cotação da Semana

- [ ] Listagem de preços de insumos (ex.: filé mignon) por fornecedor
- [ ] Layout Kanban (prioritário) ou planilha/gráficos
- [ ] Ficha do item: unidade de medida, quantidade mínima, observações por fornecedor
- [ ] Admin atualiza cotações semanalmente (CRUD na Fase 8)
- [ ] Modelo, Repository, Service, Controller

### 5.2 Interesse do Cliente (Compras Coletivas)

- [ ] Painel de demandas: donos de restaurante sinalizam interesse em itens (ex.: fardos de trigo)
- [ ] Formulário: item, quantidade de interesse, contato
- [ ] Listagem de demandas para visualização
- [ ] Interface exclusiva para fornecedores: ver volume total de interesse por item
- [ ] Fornecedores conseguem negociar com o grupo (ex.: contato via WhatsApp ou lista de interessados)
- [ ] Modelo, Repository, Service, Controller
- [ ] Permissão: fornecedores veem visão “fornecedor”; restantes veem “demandas”

---

## Fase 6 — Material de Gestão

**Objetivo:** Repositório de vídeos e PDFs para gestão.

### 6.1 Estrutura de Conteúdo

- [ ] Categorias: Financeiro, Cozinha, Legislação, Gestão de Equipe
- [ ] Vídeos: links do YouTube (incorporados, não hospedar no servidor)
- [ ] PDFs: DRE, CMV, sinalizações obrigatórias, treinamentos
- [ ] Modelo, Repository, Service, Controller

### 6.2 Interface

- [ ] Navegação por tema ( Financeiro, Cozinha, etc.)
- [ ] Galeria de vídeos (embed YouTube)
- [ ] Lista de downloads (PDFs)
- [ ] Admin consegue adicionar/editar/remover vídeos e PDFs (CRUD na Fase 8)

---

## Fase 7 — Consultor de IA e Troca de Equipamentos

**Objetivo:** Chat com IA + classificados de equipamentos.

### 7.1 Consultor de IA

- [ ] Interface de chat
- [ ] Integração com API (Gemini ou ChatGPT)
- [ ] Prompt/system especializado em food service
- [ ] Avatar do assistente
  - **Imagem (criar no Gemini):** Avatar amigável de assistente virtual. Robô simpático ou ícone de IA com toque gastronômico (chapéu de chef, prato). Cores da marca, acolhedor.
- [ ] Suporte técnico em tempo real (dentro do possível da API)
- [ ] Histórico de conversa por sessão (opcional na v1)

### 7.2 Troca de Equipamentos (Classificados)

- [ ] Anúncios: compra, venda ou permuta de equipamentos usados
- [ ] Exemplos: fatiadoras, mesas
- [ ] Entre restaurantes da região
- [ ] Campos: tipo (compra/venda/permuta), título, descrição, foto
- [ ] Placeholder de foto quando anúncio não tiver imagem
  - **Imagem (criar no Gemini):** Equipamento de cozinha profissional genérico (fatiadora, geladeira industrial). Ilustração ou foto clean. Placeholder para anúncio sem imagem.
- [ ] Botão WhatsApp para contato
- [ ] Modelo, Repository, Service, Controller

---

## Fase 8 — Painel Administrativo (Retaguarda)

**Objetivo:** Admin gerencia tudo de forma autônoma.

### 8.1 Acesso e Layout

- [ ] Rota `/admin` (ou subdomínio) protegida para role admin
- [ ] Layout admin com sidebar/menu
- [ ] Uso de variáveis CSS para temas (já preparado na Fase 0)

### 8.2 Gestão de Usuários

- [x] Lista de usuários (ativos, pendentes, **por segmento**)
- [x] Aprovar/rejeitar novos cadastros manualmente
- [x] Atribuir tipo: comprador (ex-restaurante), fornecedor, etc.
- [x] **Gerenciar segmentos** dos usuários
- [x] Cards expandíveis com Alpine.js (x-collapse)
- [x] Visualização de logos, CNPJ, dados completos
- [ ] Monitorar acessos ativos

### 8.3 CRUD de Conteúdo

- [ ] Restaurantes: criar, editar, remover
- [ ] Fornecedores: criar, editar, remover, categorias
- [ ] Talentos: criar, editar, remover, currículos
- [ ] Cotações: atualizar semanalmente (itens, preços, fornecedores)
- [ ] Materiais de gestão: vídeos, PDFs
- [ ] Compras coletivas: itens de demanda (se admin precisar gerenciar)
- [ ] Classificados: equipamentos (aprovar/remover se necessário)

### 8.4 Configuração de Módulos e Ícones

- [ ] Adicionar/remover ícones no Dashboard (ex.: "Relacionamento Governamental")
- [ ] Ativar/desativar módulos (ex.: esconder “Troca de Equipamentos” se não estiver em uso)
- [ ] Estrutura preparada para novos módulos (escalabilidade)

### 8.5 Personalização do Admin (Temas e Configs)

- [ ] Múltiplos temas: serra, oceano, neutro, etc.
- [ ] Cores editáveis: primária, secundária, fundo, texto (persistir em config/DB)
- [ ] Aplicar variáveis dinamicamente (inline ou classe no body)
- [ ] Outras configs: nome da plataforma, logo, contato, etc.

---

## Fase 9 — Monetização

**Objetivo:** Planos Comum e VIP, cobrança via Asaas.

### 9.1 Planos de Assinatura

- [ ] Plano Comum (X): funcionalidades base
- [ ] Plano VIP (2X): mentorias mensais, promoções, workshops
- [ ] Tabela/relação usuário–plano
- [ ] Bloquear/liberar acesso conforme plano (ex.: Mentoria só para VIP)

### 9.2 Integração Asaas

- [ ] Cobrança recorrente via cartão de crédito
- [ ] Webhook para atualizar status da assinatura
- [ ] Fluxo de pagamento: novo usuário escolhe plano, paga, é marcado como ativo após confirmação
- [ ] Renovação automática

### 9.3 Destaques VIP no Dashboard

- [ ] Banners/avisos exclusivos para Plano 2X
- [ ] Próximas mentorias mensais no Zoom
- [ ] Promoções especiais de fornecedores
- [ ] Admin consegue editar conteúdo dos banners
- [ ] Imagens dos banners (quando aplicável)
  - **Imagem (criar no Gemini):** Estilo variável conforme uso — mentorias (ícone Zoom/grupo), promoções (destaque visual, oferta). Cores da marca, tom premium.

---

## Fase 10 — Polimento e Entrega

**Objetivo:** Produto pronto para uso e deploy.

### 10.1 Domínio e Deploy

- [ ] Configuração para serrafood360.com.br
- [ ] SSL/HTTPS
- [ ] Ambiente de produção (servidor, PHP, Laravel)
- [ ] Variáveis de ambiente (DB, Asaas, API de IA)

### 10.2 Responsividade e Usabilidade

- [ ] Testes em mobile (320px+) e desktop
- [ ] Touch targets mínimo 44px
- [ ] Bottom nav funcionando em todos os fluxos
- [ ] Botões WhatsApp em todos os pontos de contato
- [ ] Mensagens de erro e feedback claros

### 10.3 Conteúdo e Imagens

- [ ] Substituir placeholders por imagens criadas no Gemini (ver descrições nas fases 1.2, 2.1, 3.1, 3.2, 4.2, 7.1, 7.2, 9.3)
- [ ] Tabela completa de referência em `ideia-do-projeto-completa.md` (seção 7)
- [ ] Revisão de textos e CTAs

### 10.4 Documentação e Manutenção

- [ ] README com instruções de setup
- [ ] Guia para o admin: como atualizar cotações, aprovar usuários, etc.
- [ ] Estrutura de código documentada (para replicar Rio Food 360)

---

## Resumo de Dependências

```
Fase 0 (Fundação)
    ↓
Fase 1 (Auth + Landing)
    ↓
Fase 2 (Dashboard + Bottom Nav)
    ↓
Fases 3 a 7 (Módulos) — podem ter ordem flexível, exceto dependências de modelo
    ↓
Fase 8 (Admin) — depende de modelos das Fases 3–7
    ↓
Fase 9 (Monetização) — depende de Auth e Admin
    ↓
Fase 10 (Polimento)
```

---

## Checklist de Regras de Negócio (não esquecer)

- [ ] Validação de entrada: pré-filtro ou entrevista antes de liberar login
- [ ] Admin atualiza cotações e currículos de forma ágil
- [ ] Código preparado para escalar (outras regiões, novos módulos)
- [ ] Usabilidade em primeiro lugar
- [ ] Independência do admin para editar conteúdo
- [ ] Mobile-first
- [ ] WhatsApp como canal principal de contato

---

## Observações

- **v1.0:** priorizar o que valida o modelo; automações complexas podem ficar para v2
- **Cadastro manual de talentos:** aceitar no início; automação pode vir depois
- **Interface fornecedor em Compras Coletivas:** visão diferenciada é essencial
- **Temas no admin:** desde a Fase 0, todas as cores em variáveis
