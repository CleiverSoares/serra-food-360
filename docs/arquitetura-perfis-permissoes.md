# Arquitetura de Perfis e Permissões

## 🎭 Tipos de Usuário

### 1. **Admin** (Administrador)
**Quem é:** Equipe Serra Food 360
**Acesso:** Total

**Permissões:**
- ✅ Acesso ao painel administrativo (`/admin`)
- ✅ Aprovar/rejeitar novos cadastros
- ✅ Gerenciar todos os usuários
- ✅ CRUD completo de:
  - Restaurantes
  - Fornecedores
  - Talentos
  - Cotações
  - Material de gestão
  - Classificados
  - Compras coletivas
- ✅ Configurar temas e cores
- ✅ Adicionar/remover módulos
- ✅ Gerenciar planos e pagamentos
- ✅ Ver estatísticas e relatórios

**NÃO tem acesso:**
- ❌ Área de membros comum (usa painel admin separado)

---

### 2. **Restaurante** (Dono de Restaurante)
**Quem é:** Proprietário ou gestor de restaurante
**Acesso:** Área de membros (dashboard principal)

**Permissões:**
- ✅ Dashboard com navegação por ícones
- ✅ Ver diretório de **Fornecedores** (todos)
- ✅ Ver diretório de **Restaurantes** (todos)
- ✅ Ver **Cotações da Semana**
- ✅ Ver e contratar **Talentos** (banco de profissionais)
- ✅ **Sinalizar interesse** em compras coletivas
- ✅ Ver **volume total de demanda** em compras coletivas
- ✅ Acessar **Material de Gestão** (vídeos, PDFs)
- ✅ Usar **Consultor IA**
- ✅ Ver **Classificados** (equipamentos)
- ✅ **Criar anúncios** de equipamentos (venda/troca)
- ✅ Contato via WhatsApp com todos
- ✅ Ver benefícios do seu plano (Comum ou VIP)

**Benefícios VIP adicionais:**
- ⭐ Mentorias mensais (Zoom)
- ⭐ Promoções exclusivas
- ⭐ Workshops práticos
- ⭐ Selo VIP no diretório
- ⭐ Suporte prioritário

**NÃO tem acesso:**
- ❌ Painel administrativo
- ❌ Visão especial de fornecedor
- ❌ Aprovar usuários
- ❌ Editar cotações

---

### 3. **Fornecedor** (Fornecedor/Prestador de Serviço)
**Quem é:** Fornecedor de insumos ou prestador de serviços
**Acesso:** Área de membros (dashboard principal)

**Permissões:**
- ✅ Dashboard com navegação por ícones
- ✅ Ver diretório de **Restaurantes** (clientes em potencial)
- ✅ Ver diretório de **Fornecedores** (concorrentes/parceiros)
- ✅ Aparecer no diretório de fornecedores (seu perfil)
- ✅ Ver **Cotações da Semana** (onde aparece)
- ✅ **Visão especial de Compras Coletivas:**
  - Ver volume TOTAL de interesse por item
  - Ver lista de restaurantes interessados
  - Negociar em grupo
- ✅ Ver **Classificados** (equipamentos)
- ✅ **Criar anúncios** de equipamentos
- ✅ Acessar **Material de Gestão**
- ✅ Usar **Consultor IA**
- ✅ Contato via WhatsApp

**Benefícios VIP adicionais:**
- ⭐ Destaque no diretório
- ⭐ Promoções em banner para restaurantes VIP
- ⭐ Workshops e networking

**NÃO tem acesso:**
- ❌ Painel administrativo
- ❌ Banco de Talentos (não precisa contratar)
- ❌ Editar cotações diretamente

---

### 4. **Talento** (Profissional Extra - SEM LOGIN)
**Quem é:** Universitário/profissional disponível para trabalhos extras
**Acesso:** NENHUM - apenas dados cadastrais

**Importante:**
- ❌ **NÃO tem login** no sistema
- ❌ **NÃO acessa** nenhuma área
- ✅ **Cadastrado e gerenciado APENAS pelo Admin**
- ✅ Aparece no módulo "Banco de Talentos" para restaurantes
- ✅ Admin insere: foto, currículo, pretensão, disponibilidade
- ✅ Contato direto via WhatsApp (não pelo sistema)

**Nota:** Talentos são apenas "registros" no banco de dados, não são usuários do sistema.

---

### 5. **Visitante** (Não Autenticado)
**Quem é:** Qualquer pessoa que acessa o site
**Acesso:** Landing page pública

**Permissões:**
- ✅ Ver landing page
- ✅ Conhecer os módulos
- ✅ Ver planos
- ✅ Solicitar entrada via WhatsApp
- ✅ Fazer cadastro (fica pendente)

**NÃO tem acesso:**
- ❌ Qualquer área de membros
- ❌ Dashboard
- ❌ Módulos

---

## 🔐 Estrutura de Banco de Dados

### Tabela: `users`

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    
    -- Tipo de usuário (APENAS 3 tipos com login)
    role ENUM('admin', 'restaurante', 'fornecedor') NOT NULL DEFAULT 'restaurante',
    
    -- Status de aprovação
    status ENUM('pendente', 'aprovado', 'rejeitado', 'inativo') NOT NULL DEFAULT 'pendente',
    
    -- Plano (apenas para restaurantes e fornecedores)
    plano ENUM('comum', 'vip') NULL DEFAULT 'comum',
    
    -- Dados do negócio
    nome_estabelecimento VARCHAR(255) NULL,
    telefone VARCHAR(20) NULL,
    whatsapp VARCHAR(20) NULL,
    cidade VARCHAR(100) NULL,
    tipo_negocio VARCHAR(100) NULL, -- ex: "Restaurante", "Distribuidora", etc.
    
    -- Categorias (para fornecedores)
    categorias JSON NULL, -- ex: ["Bebidas", "Laticínios"]
    
    -- Dados adicionais
    descricao TEXT NULL,
    logo_path VARCHAR(255) NULL, -- storage/app/public/restaurantes/logos/ ou fornecedores/logos/
    site_url VARCHAR(255) NULL,
    colaboradores INT NULL, -- quantidade de funcionários
    
    -- Campos padrão Laravel
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_role (role),
    INDEX idx_status (status),
    INDEX idx_plano (plano)
);
```

### Tabela: `talentos` (Fase 4 - Banco de Talentos)

```sql
CREATE TABLE talentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    telefone VARCHAR(20) NULL,
    whatsapp VARCHAR(20) NOT NULL,
    
    -- Cargo/função
    cargo ENUM('garcom', 'cozinheiro', 'auxiliar_cozinha', 'recepcionista', 'outro') NOT NULL,
    cargo_outro VARCHAR(100) NULL,
    
    -- Dados profissionais
    mini_curriculo TEXT NULL,
    pretensao_salarial DECIMAL(10,2) NULL,
    dias_disponiveis JSON NULL, -- ["segunda", "terca", "quarta"]
    horarios_disponiveis VARCHAR(255) NULL, -- "19h às 23h"
    
    -- Arquivos armazenados no servidor
    foto_path VARCHAR(255) NULL, -- storage/app/public/talentos/fotos/
    curriculo_pdf_path VARCHAR(255) NULL, -- storage/app/public/talentos/curriculos/
    carta_recomendacao_path VARCHAR(255) NULL, -- storage/app/public/talentos/cartas/
    
    -- Status
    ativo BOOLEAN DEFAULT true,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_cargo (cargo),
    INDEX idx_ativo (ativo)
);
```

**Armazenamento de Arquivos:**
```
storage/app/public/
├── talentos/
│   ├── fotos/           → Fotos dos talentos
│   ├── curriculos/      → PDFs de currículo
│   └── cartas/          → Cartas de recomendação
├── restaurantes/
│   └── logos/           → Logos dos restaurantes
├── fornecedores/
│   └── logos/           → Logos dos fornecedores
└── classificados/
    └── equipamentos/    → Fotos de equipamentos
```

**Link simbólico:** `php artisan storage:link`
- Cria: `public/storage → storage/app/public`
- Acesso: `/storage/talentos/fotos/nome-do-arquivo.jpg`

**Nota:** Talentos NÃO são usuários do sistema, são apenas cadastros gerenciados pelo admin.

### Tabela: `assinaturas` (Fase 9 - Monetização)

```sql
CREATE TABLE assinaturas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    plano ENUM('comum', 'vip') NOT NULL,
    status ENUM('ativa', 'cancelada', 'suspensa', 'vencida') NOT NULL DEFAULT 'ativa',
    
    -- Integração Asaas
    asaas_subscription_id VARCHAR(255) NULL,
    asaas_customer_id VARCHAR(255) NULL,
    
    -- Datas
    inicio_em TIMESTAMP NOT NULL,
    proxima_cobranca_em TIMESTAMP NULL,
    cancelada_em TIMESTAMP NULL,
    
    -- Valores
    valor_mensal DECIMAL(10,2) NOT NULL,
    
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status)
);
```

---

## 🛡️ Middleware e Guards

### Middleware Necessários

1. **`CheckApproved`**
   - Verifica se `status = 'aprovado'`
   - Redireciona para "aguardando aprovação" se pendente
   - Aplica em todas as rotas de membros

2. **`CheckRole`**
   - Verifica se usuário tem role específico
   - Ex: `CheckRole:admin` para painel admin
   - Ex: `CheckRole:restaurante,fornecedor` para área de membros

3. **`CheckPlan`**
   - Verifica se usuário tem plano necessário
   - Ex: `CheckPlan:vip` para mentorias
   - Aplica em features exclusivas VIP

4. **`CheckActive`**
   - Verifica se assinatura está ativa
   - Bloqueia acesso se pagamento atrasado
   - Redireciona para página de cobrança

### Estrutura de Rotas

```php
// Público
Route::get('/', [LandingController::class, 'index']);
Route::get('/cadastro', [AuthController::class, 'cadastro']);
Route::post('/cadastro', [AuthController::class, 'store']);

// Autenticação
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout']);

// Aguardando aprovação
Route::get('/aguardando-aprovacao', [AuthController::class, 'aguardando'])
    ->middleware('auth');

// Área de Membros (aprovados)
Route::middleware(['auth', 'approved', 'active'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Acessível por restaurantes e fornecedores
    Route::get('/restaurantes', [RestaurantesController::class, 'index']);
    Route::get('/fornecedores', [FornecedoresController::class, 'index']);
    Route::get('/cotacoes', [CotacoesController::class, 'index']);
    Route::get('/gestao', [GestaoController::class, 'index']);
    Route::get('/ia', [IAController::class, 'index']);
    Route::get('/classificados', [ClassificadosController::class, 'index']);
    
    // Apenas restaurantes
    Route::middleware('role:restaurante')->group(function () {
        Route::get('/talentos', [TalentosController::class, 'index']);
    });
    
    // Compras coletivas (visões diferentes)
    Route::get('/compras-coletivas', [ComprasColetivasController::class, 'index']);
    
    // VIP apenas
    Route::middleware('plan:vip')->group(function () {
        Route::get('/mentorias', [MentoriasController::class, 'index']);
    });
});

// Painel Admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index']);
    Route::resource('usuarios', AdminUsuariosController::class);
    Route::post('usuarios/{id}/aprovar', [AdminUsuariosController::class, 'aprovar']);
    Route::post('usuarios/{id}/rejeitar', [AdminUsuariosController::class, 'rejeitar']);
    // ... outros CRUDs
});
```

---

## 🎯 Regras de Negócio por Perfil

### Admin
- Pode fazer tudo
- Acessa painel separado (`/admin`)
- Não aparece em diretórios públicos

### Restaurante
- Pode ver TODOS os fornecedores
- Pode ver TODOS os restaurantes (networking)
- Pode sinalizar interesse em compras coletivas
- Pode contratar talentos
- **Importante:** Vê volume total de demanda, mas não vê detalhes dos fornecedores respondendo

### Fornecedor
- Pode ver TODOS os restaurantes (prospects)
- Aparece no diretório de fornecedores
- **Diferencial:** Vê DETALHES das compras coletivas:
  - Quantos restaurantes interessados
  - Qual o volume total
  - Lista de contatos para negociar
- Não precisa do módulo Talentos

### Compras Coletivas - Visões Diferentes

**Restaurante vê:**
```
Item: Fardo de Trigo (50kg)
Meu interesse: 10 fardos
Volume total: 150 fardos
Status: 5 restaurantes interessados

[Botão: Atualizar meu interesse]
```

**Fornecedor vê:**
```
Item: Fardo de Trigo (50kg)
Volume total: 150 fardos
Restaurantes interessados: 5

Lista:
1. Restaurante Sabor da Serra - 10 fardos - [WhatsApp]
2. Bistrô Montanha - 20 fardos - [WhatsApp]
3. Pizzaria Bella Vista - 15 fardos - [WhatsApp]
...

[Botão: Fazer proposta para o grupo]
```

---

## 📊 Fluxo de Cadastro e Aprovação

### 1. Visitante acessa landing
↓
### 2. Clica em "Solicitar entrada"
↓
### 3. Preenche formulário:
- Nome completo
- Email
- Senha
- Telefone/WhatsApp
- **Tipo:** Restaurante ou Fornecedor
- Nome do estabelecimento
- Cidade
- Breve descrição

↓
### 4. Sistema cria usuário com:
- `status = 'pendente'`
- `role = tipo escolhido`
- `plano = NULL` (será escolhido após aprovação)

↓
### 5. Usuário vê tela "Aguardando Aprovação"
```
"Seu cadastro foi recebido!

Nossa equipe irá validar suas informações 
e liberar seu acesso em até 24 horas.

Você receberá um email quando for aprovado.

[Voltar para home]
```

↓
### 6. Admin recebe notificação
- Email/dashboard com novo cadastro pendente

↓
### 7. Admin analisa e decide:
**APROVAR:**
- `status = 'aprovado'`
- Envia email de boas-vindas
- Usuário pode escolher plano (Comum ou VIP)
- Após escolher plano, redireciona para pagamento (Asaas)
- Após pagamento confirmado, acessa dashboard

**REJEITAR:**
- `status = 'rejeitado'`
- Envia email com motivo (opcional)
- Usuário não consegue mais fazer login

---

## 🎨 Personalização por Perfil

### Dashboard Principal

**Restaurante vê:**
```
[ÍCONES PRINCIPAIS]
🍽️ Restaurantes    📦 Fornecedores    📊 Cotações    👥 Talentos
🛒 Compras Coletivas    📚 Gestão    🤖 IA    🔄 Classificados

[BANNER VIP] (se for VIP)
🎓 Próxima Mentoria: 20/02 às 19h
```

**Fornecedor vê:**
```
[ÍCONES PRINCIPAIS]
🍽️ Restaurantes    📦 Fornecedores    📊 Cotações
🛒 Demandas (especial)    📚 Gestão    🤖 IA    🔄 Classificados

[SEM BANNER DE TALENTOS]
```

**Admin vê:**
```
[PAINEL SEPARADO]
Dashboard Admin com:
- Usuários pendentes: 3
- Usuários ativos: 45
- Cotações para atualizar
- Conteúdo para gerenciar
```

---

## 🔒 Segurança e Validação

### Checklist de Segurança

- [ ] Password hash com bcrypt
- [ ] CSRF protection em todos os forms
- [ ] Rate limiting em login
- [ ] Email verification (opcional v1)
- [ ] Logs de acesso ao admin
- [ ] Soft deletes em usuários
- [ ] Validação de permissões em cada ação
- [ ] Sanitização de inputs

### Validações de Cadastro (Restaurantes e Fornecedores)

```php
// Comum para todos
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email',
'password' => 'required|min:8|confirmed',
'role' => 'required|in:restaurante,fornecedor',
'telefone' => 'required|string',
'whatsapp' => 'required|string',
'nome_estabelecimento' => 'required|string',
'cidade' => 'required|string',
'descricao' => 'nullable|string|max:500',

// Apenas fornecedores
'categorias' => 'required_if:role,fornecedor|array',
'categorias.*' => 'in:Bebidas,Laticínios,Hortifrúti,Manutenção,Carnes,Massas,Panificação,Descartáveis,Equipamentos',

// Upload de logo (opcional no cadastro, pode adicionar depois)
'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB
```

**Armazenamento de logos:**
- Restaurantes → `storage/app/public/restaurantes/logos/`
- Fornecedores → `storage/app/public/fornecedores/logos/`

**Acesso:** `/storage/restaurantes/logos/1-sabor-da-serra.png`


### Validações de Talento (Admin apenas)

```php
'nome' => 'required|string|max:255',
'whatsapp' => 'required|string',
'cargo' => 'required|in:garcom,cozinheiro,auxiliar_cozinha,recepcionista,outro',
'mini_curriculo' => 'nullable|string|max:1000',
'pretensao_salarial' => 'nullable|numeric|min:0',
'dias_disponiveis' => 'nullable|array',
'horarios_disponiveis' => 'nullable|string|max:100',

// Uploads de arquivos
'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB - armazena no servidor
'curriculo_pdf' => 'nullable|mimes:pdf|max:5120', // 5MB - armazena no servidor
'carta_recomendacao' => 'nullable|mimes:pdf|max:5120', // 5MB - armazena no servidor
```

**Armazenamento:**
- Fotos → `storage/app/public/talentos/fotos/`
- Currículos → `storage/app/public/talentos/curriculos/`
- Cartas → `storage/app/public/talentos/cartas/`

**Acesso público via:**
- `/storage/talentos/fotos/1-joao-silva.jpg`
- `/storage/talentos/curriculos/1-curriculo-joao.pdf`


---

## 📁 Sistema de Upload de Arquivos

### Laravel Storage

**Disco padrão:** `public`
**Config:** `config/filesystems.php`

```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

### Estrutura de Diretórios

```
storage/app/public/
├── talentos/
│   ├── fotos/
│   │   ├── 1-joao-silva.jpg
│   │   └── 2-maria-santos.jpg
│   ├── curriculos/
│   │   ├── 1-curriculo-joao.pdf
│   │   └── 2-curriculo-maria.pdf
│   └── cartas/
│       └── 1-carta-joao.pdf
├── restaurantes/
│   └── logos/
│       ├── 1-sabor-da-serra.png
│       └── 2-bistro-montanha.jpg
├── fornecedores/
│   └── logos/
│       ├── 1-distribuidora-x.png
│       └── 2-laticinio-y.jpg
├── classificados/
│   └── equipamentos/
│       ├── 1-fatiadora.jpg
│       └── 2-geladeira.jpg
└── gestao/
    └── materiais/
        ├── dre-exemplo.pdf
        └── cmv-planilha.pdf
```

### Como Usar no Código

#### Upload (Controller)

```php
// Exemplo: Admin cadastrando talento
public function store(Request $request)
{
    $validated = $request->validate([
        'foto' => 'nullable|image|max:2048', // 2MB
        'curriculo_pdf' => 'nullable|mimes:pdf|max:5120', // 5MB
        // ...
    ]);
    
    $talento = new Talento();
    $talento->nome = $request->nome;
    // ...
    
    // Upload da foto
    if ($request->hasFile('foto')) {
        $path = $request->file('foto')->store('talentos/fotos', 'public');
        $talento->foto_path = $path;
    }
    
    // Upload do currículo
    if ($request->hasFile('curriculo_pdf')) {
        $path = $request->file('curriculo_pdf')->store('talentos/curriculos', 'public');
        $talento->curriculo_pdf_path = $path;
    }
    
    $talento->save();
}
```

#### Exibir (Blade)

```blade
{{-- Foto do talento --}}
@if($talento->foto_path)
    <img src="{{ Storage::url($talento->foto_path) }}" 
         alt="{{ $talento->nome }}"
         class="w-full h-full object-cover">
@else
    {{-- Placeholder --}}
    <div class="w-full h-full bg-[var(--cor-fundo)] flex items-center justify-center">
        <i data-lucide="user" class="w-12 h-12 text-[var(--cor-texto-muted)]"></i>
    </div>
@endif

{{-- Link para PDF --}}
@if($talento->curriculo_pdf_path)
    <a href="{{ Storage::url($talento->curriculo_pdf_path) }}" 
       target="_blank"
       class="...">
        <i data-lucide="file-text"></i>
        Ver Currículo PDF
    </a>
@endif
```

#### Deletar arquivo ao remover registro

```php
public function destroy($id)
{
    $talento = Talento::findOrFail($id);
    
    // Deleta arquivos
    if ($talento->foto_path) {
        Storage::disk('public')->delete($talento->foto_path);
    }
    if ($talento->curriculo_pdf_path) {
        Storage::disk('public')->delete($talento->curriculo_pdf_path);
    }
    if ($talento->carta_recomendacao_path) {
        Storage::disk('public')->delete($talento->carta_recomendacao_path);
    }
    
    $talento->delete();
}
```

### Validações de Arquivo

```php
// Imagens
'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB

// PDFs
'curriculo_pdf' => 'nullable|mimes:pdf|max:5120', // 5MB

// Dimensões de imagem (opcional)
'foto' => 'nullable|image|dimensions:min_width=200,min_height=200|max:2048',
```

### Nomenclatura de Arquivos

**Padrão:** `{id}-{nome-slugificado}.{extensao}`

```php
use Illuminate\Support\Str;

// Gera nome único
$fileName = $talento->id . '-' . Str::slug($talento->nome) . '.' . $request->file('foto')->extension();

// Salva com nome customizado
$path = $request->file('foto')->storeAs('talentos/fotos', $fileName, 'public');
```

### Otimização de Imagens (Recomendado)

**Package sugerido:** `intervention/image`

```bash
composer require intervention/image
```

```php
use Intervention\Image\Facades\Image;

// Redimensionar e otimizar ao fazer upload
$image = Image::make($request->file('foto'));
$image->resize(800, null, function ($constraint) {
    $constraint->aspectRatio();
    $constraint->upsize();
});
$image->encode('jpg', 85); // 85% qualidade
$image->save(storage_path('app/public/talentos/fotos/' . $fileName));
```

---

## 📝 Próximos Passos

### Para implementar Fase 1.1:

1. ✅ Migration da tabela `users` (estendida)
2. ✅ Model `User` com relations e scopes
3. ✅ Middleware: `CheckApproved`, `CheckRole`
4. ✅ Controllers: Auth, Dashboard
5. ✅ Views: login, cadastro, aguardando, dashboard
6. ✅ Rotas protegidas
7. ✅ Seeders: criar admin padrão
8. ✅ Emails: aprovação, rejeição (opcional v1)

### Para Fase 9 (Monetização):

1. Migration `assinaturas`
2. Model `Assinatura`
3. Middleware `CheckActive`
4. Integração Asaas
5. Webhooks de pagamento

---

## 👥 Gestão de Talentos pelo Admin

### Como Funciona

1. **Admin acessa:** `/admin/talentos`
2. **Clica em:** "Novo Talento"
3. **Preenche formulário:**
   - Nome completo
   - WhatsApp (obrigatório)
   - Email (opcional)
   - Cargo (garçom, cozinheiro, etc.)
   - Mini currículo (texto curto)
   - Pretensão salarial
   - Dias disponíveis (segunda, terça, etc.)
   - Horários disponíveis
   - Upload foto (opcional)
   - Upload currículo PDF (opcional)
   - Upload carta de recomendação (opcional)

4. **Salva:** Talento aparece no módulo "Banco de Talentos"
5. **Restaurantes veem:** Cards com foto, currículo, pretensão, disponibilidade
6. **Contato:** Botão WhatsApp direto (link externo)

### CRUD no Painel Admin

```php
// Rotas do Admin para Talentos
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('talentos', AdminTalentosController::class);
    Route::post('talentos/{id}/ativar', [AdminTalentosController::class, 'ativar']);
    Route::post('talentos/{id}/desativar', [AdminTalentosController::class, 'desativar']);
});
```

### Interface Admin - Lista de Talentos

```
┌─────────────────────────────────────────────────────────┐
│ Banco de Talentos                    [+ Novo Talento]   │
├─────────────────────────────────────────────────────────┤
│                                                          │
│ 📸 João Silva          Garçom        ✅ Ativo           │
│    Pretensão: R$ 80/dia                                 │
│    Disponível: Seg, Qua, Sex (19h-23h)                  │
│    [Editar] [Desativar] [Ver Currículo PDF]             │
│                                                          │
│ 📸 Maria Santos        Cozinheira    ✅ Ativo           │
│    Pretensão: R$ 120/dia                                │
│    Disponível: Ter, Qui, Sáb (18h-00h)                  │
│    [Editar] [Desativar] [Ver Currículo PDF]             │
│                                                          │
│ 📸 Pedro Costa         Aux. Cozinha  ❌ Inativo         │
│    [Editar] [Ativar]                                    │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### Interface Restaurante - Ver Talentos

```
┌─────────────────────────────────────────────────────────┐
│ Banco de Talentos                                        │
├─────────────────────────────────────────────────────────┤
│ Filtrar por cargo: [Todos ▼] [Garçom] [Cozinheiro]     │
│                                                          │
│ ┌───────────────────────────────────────────────────┐   │
│ │ 📸 João Silva                                     │   │
│ │ Garçom                                            │   │
│ │                                                   │   │
│ │ "Experiência de 3 anos em restaurantes da serra. │   │
│ │  Pontual, educado, domínio de vinhos."           │   │
│ │                                                   │   │
│ │ 💰 Pretensão: R$ 80/dia                          │   │
│ │ 📅 Disponível: Seg, Qua, Sex                     │   │
│ │ ⏰ Horários: 19h às 23h                           │   │
│ │                                                   │   │
│ │ [📄 Ver Currículo PDF] [💬 Contatar WhatsApp]    │   │
│ └───────────────────────────────────────────────────┘   │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### Fluxo Completo

```
Admin → Cadastra talento
  ↓
Talento aparece em "Banco de Talentos"
  ↓
Restaurante vê talento → Clica em "Ver detalhes"
  ↓
Vê currículo completo + PDF + pretensão + disponibilidade
  ↓
Clica em "Contatar WhatsApp"
  ↓
Abre conversa no WhatsApp (fora do sistema)
  ↓
Restaurante negocia direto com o talento
```

### Por que Talentos NÃO têm login?

1. **Simplicidade:** Talento não precisa acessar o sistema, apenas aparecer nele
2. **v1 MVP:** Foco no que gera valor rápido (conexão restaurante-talento)
3. **Admin controla qualidade:** Valida e cadastra apenas talentos confiáveis
4. **Menos manutenção:** Sem área de membros, sem senha, sem suporte
5. **v2:** Se necessário, portal de talentos pode ser adicionado depois

---

## 💡 Considerações Importantes

### Por que não usar Spatie Permission?
Para v1, roles simples (ENUM) são suficientes. Spatie pode ser adicionado na v2 se precisar de permissões granulares.

### Por que campo `plano` no User?
Para acesso rápido e simplicidade. Tabela `assinaturas` guarda histórico e dados de cobrança.

### E se fornecedor for também restaurante?
Na v1, usuário escolhe UM tipo principal. Na v2, pode ter múltiplos roles com tabela pivot.

### Talento precisa login?
**NÃO**. Talentos são apenas cadastros gerenciados pelo admin. Não são usuários do sistema. Restaurantes veem os talentos e contatam via WhatsApp externo.

### Quantos tipos de usuário COM LOGIN?
**3 tipos apenas:**
1. Admin (acessa painel admin)
2. Restaurante (acessa área de membros)
3. Fornecedor (acessa área de membros)

**Talentos** são registros na tabela `talentos`, não na tabela `users`.

---

**Esta arquitetura está alinhada com:**
- ✅ Documentação do projeto
- ✅ Roadmap (Fases 1, 2, 9)
- ✅ Princípios de escalabilidade
- ✅ Mobile-first e usabilidade

**Pronto para implementar!** 🚀
