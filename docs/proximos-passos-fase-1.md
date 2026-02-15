# Próximos Passos - Fase 1.1 (Autenticação)

## 🎯 Objetivo

Implementar sistema completo de autenticação com:
- Login para admin, restaurantes e fornecedores
- Cadastro com aprovação manual
- Middleware de proteção
- Gestão de perfis

---

## 📋 Checklist de Implementação

### 1. Database e Models

#### 1.1 Migration: Estender tabela users
```bash
php artisan make:migration add_profile_fields_to_users_table
```

**Campos a adicionar:**
- [x] `role` ENUM('admin', 'restaurante', 'fornecedor')
- [x] `status` ENUM('pendente', 'aprovado', 'rejeitado', 'inativo')
- [x] `plano` ENUM('comum', 'vip') NULL
- [x] `nome_estabelecimento` VARCHAR
- [x] `telefone` VARCHAR
- [x] `whatsapp` VARCHAR
- [x] `cidade` VARCHAR
- [x] `tipo_negocio` VARCHAR
- [x] `categorias` JSON (para fornecedores)
- [x] `descricao` TEXT
- [x] `logo_path` VARCHAR
- [x] `site_url` VARCHAR
- [x] `colaboradores` INT
- [x] Indexes: role, status, plano

#### 1.2 Atualizar Model User
```bash
app/Models/User.php
```

**Adicionar:**
- [x] Fillable com novos campos
- [x] Casts (categorias → array, status/role → enum)
- [x] Accessor `logo_url` para Storage::url()
- [x] Scopes: `aprovados()`, `pendentes()`, `porRole()`
- [x] Boot: deletar logo ao deletar user

#### 1.3 Seeder: Admin padrão
```bash
php artisan make:seeder AdminSeeder
```

**Criar:**
- [x] Admin padrão (email, senha)
- [x] Status aprovado
- [x] Role admin

---

### 2. Middleware

#### 2.1 CheckApproved
```bash
php artisan make:middleware CheckApproved
```

**Função:**
- Verifica se `status = 'aprovado'`
- Se pendente → redireciona para `/aguardando-aprovacao`
- Se rejeitado → logout + mensagem
- Se inativo → redireciona para `/assinatura-suspensa`

#### 2.2 CheckRole
```bash
php artisan make:middleware CheckRole
```

**Função:**
- Verifica se user tem role necessário
- Uso: `CheckRole:admin` ou `CheckRole:restaurante,fornecedor`
- Se não tem → abort(403)

#### 2.3 Registrar no Kernel
```php
// app/Http/Kernel.php
protected $middlewareAliases = [
    'approved' => \App\Http\Middleware\CheckApproved::class,
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

---

### 3. Controllers

#### 3.1 AuthController
```bash
php artisan make:controller AuthController
```

**Métodos:**
- [x] `showLogin()` - Exibe tela de login
- [x] `login()` - Autentica usuário
- [x] `showCadastro()` - Exibe form de cadastro
- [x] `cadastro()` - Cria usuário pendente
- [x] `logout()` - Desloga
- [x] `aguardando()` - Tela "aguardando aprovação"

#### 3.2 DashboardController
```bash
php artisan make:controller DashboardController
```

**Métodos:**
- [x] `index()` - Dashboard principal (área de membros)
- Renderiza dashboard diferente por role

---

### 4. Views

#### 4.1 Tela de Login
```bash
resources/views/auth/login.blade.php
```

**Elementos:**
- [x] Logo
- [x] Formulário: email, senha, lembrar
- [x] Link "Esqueci minha senha"
- [x] Link "Não tem conta? Cadastre-se"
- [x] Botão "Entrar"
- [x] Design mobile-first
- [x] Validação frontend

#### 4.2 Tela de Cadastro
```bash
resources/views/auth/cadastro.blade.php
```

**Formulário:**
- [x] Tipo: [Restaurante] [Fornecedor]
- [x] Nome completo
- [x] Email
- [x] Senha + confirmação
- [x] WhatsApp
- [x] Nome do estabelecimento
- [x] Cidade
- [x] Descrição (textarea)
- [x] Categorias (se fornecedor - checkboxes múltiplos)
- [x] Botão "Solicitar Entrada"
- [x] Termos de uso (checkbox)

#### 4.3 Aguardando Aprovação
```bash
resources/views/auth/aguardando.blade.php
```

**Conteúdo:**
- [x] Mensagem amigável
- [x] "Seu cadastro foi recebido"
- [x] "Análise em até 24h"
- [x] "Você receberá email"
- [x] Botão voltar para home
- [x] Logout

#### 4.4 Dashboard Principal
```bash
resources/views/dashboard.blade.php
```

**Estrutura:**
- [x] Boas-vindas: "Olá, {nome}!"
- [x] Grid de módulos (8 ou 7 dependendo do role)
- [x] Diferentes por role (restaurante vs fornecedor)
- [x] Bottom nav funcional
- [x] Badge VIP (se aplicável)

---

### 5. Rotas

#### 5.1 Rotas públicas
```php
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/cadastro', [AuthController::class, 'showCadastro'])->name('cadastro');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/cadastro', [AuthController::class, 'cadastro']);
```

#### 5.2 Rotas autenticadas
```php
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/aguardando-aprovacao', [AuthController::class, 'aguardando'])->name('aguardando');
    
    // Área de membros (apenas aprovados)
    Route::middleware('approved')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});
```

#### 5.3 Rotas admin
```php
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/usuarios', [AdminUsuariosController::class, 'index'])->name('admin.usuarios.index');
    Route::post('/usuarios/{id}/aprovar', [AdminUsuariosController::class, 'aprovar'])->name('admin.usuarios.aprovar');
    Route::post('/usuarios/{id}/rejeitar', [AdminUsuariosController::class, 'rejeitar'])->name('admin.usuarios.rejeitar');
});
```

---

### 6. Validações e Requests

#### 6.1 LoginRequest
```bash
php artisan make:request LoginRequest
```

**Validações:**
- email: required, email, exists:users
- password: required, min:8

#### 6.2 CadastroRequest
```bash
php artisan make:request CadastroRequest
```

**Validações:**
- name: required, string, max:255
- email: required, email, unique:users
- password: required, min:8, confirmed
- role: required, in:restaurante,fornecedor
- whatsapp: required, string
- nome_estabelecimento: required, string
- cidade: required, string
- categorias: required_if:role,fornecedor, array

---

### 7. Emails (Opcional v1)

#### 7.1 Email de Aprovação
```bash
php artisan make:mail UsuarioAprovado
```

**Conteúdo:**
- Boas-vindas
- Link para escolher plano
- Instruções de acesso

#### 7.2 Email de Rejeição
```bash
php artisan make:mail UsuarioRejeitado
```

**Conteúdo:**
- Mensagem educada
- Motivo (opcional)
- Contato para dúvidas

---

### 8. Testes

#### 8.1 Fluxo de Cadastro
- [x] Visitante acessa `/cadastro`
- [x] Preenche formulário (restaurante)
- [x] Submete
- [x] Fica com status `pendente`
- [x] Redireciona para `/aguardando-aprovacao`
- [x] Tenta acessar `/dashboard` → bloqueado

#### 8.2 Fluxo de Aprovação
- [x] Admin acessa painel
- [x] Vê lista de pendentes
- [x] Clica "Aprovar"
- [x] User fica com status `aprovado`
- [x] User recebe email (opcional)
- [x] User pode fazer login → acessa dashboard

#### 8.3 Fluxo de Login
- [x] User aprovado acessa `/login`
- [x] Insere email e senha
- [x] Sistema autentica
- [x] Redireciona para `/dashboard`
- [x] Dashboard mostra módulos corretos por role

---

## 🗂️ Estrutura de Arquivos a Criar

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          ← CRIAR
│   │   ├── DashboardController.php     ← CRIAR
│   │   └── Admin/
│   │       ├── AdminDashboardController.php  ← CRIAR
│   │       └── AdminUsuariosController.php   ← CRIAR
│   ├── Middleware/
│   │   ├── CheckApproved.php           ← CRIAR
│   │   └── CheckRole.php               ← CRIAR
│   └── Requests/
│       ├── LoginRequest.php            ← CRIAR
│       └── CadastroRequest.php         ← CRIAR
├── Models/
│   └── User.php                        ← ATUALIZAR
└── Mail/
    ├── UsuarioAprovado.php             ← CRIAR (opcional)
    └── UsuarioRejeitado.php            ← CRIAR (opcional)

database/
├── migrations/
│   └── xxxx_add_profile_fields_to_users_table.php  ← CRIAR
└── seeders/
    └── AdminSeeder.php                 ← CRIAR

resources/views/
├── auth/
│   ├── login.blade.php                 ← CRIAR
│   ├── cadastro.blade.php              ← CRIAR
│   └── aguardando.blade.php            ← CRIAR
├── dashboard.blade.php                 ← CRIAR
└── admin/
    ├── layout.blade.php                ← CRIAR
    ├── dashboard.blade.php             ← CRIAR
    └── usuarios/
        └── index.blade.php             ← CRIAR

routes/
└── web.php                             ← ATUALIZAR
```

---

## 🔄 Ordem de Implementação

### Passo a Passo

1. ✅ **Migration** → Cria campos no banco
2. ✅ **Model User** → Atualiza com fillable, casts, scopes
3. ✅ **Seeder Admin** → Cria admin padrão
4. ✅ **Middleware** → CheckApproved + CheckRole
5. ✅ **Requests** → Validações de login e cadastro
6. ✅ **Views** → Telas de auth (login, cadastro, aguardando)
7. ✅ **AuthController** → Lógica de login/cadastro
8. ✅ **DashboardController** → Dashboard de membros
9. ✅ **Rotas** → Conecta tudo
10. ✅ **Admin básico** → Painel de aprovação de usuários
11. ✅ **Testes** → Validar todo fluxo

---

## 🎨 Design das Telas de Auth

### Login - Wireframe

```
┌─────────────────────────────────────────┐
│              [LOGO]                      │
│                                          │
│         Bem-vindo de volta               │
│                                          │
│  Email                                   │
│  [___________________________]           │
│                                          │
│  Senha                                   │
│  [___________________________] [👁]      │
│                                          │
│  [ ] Lembrar de mim                      │
│                                          │
│  [      Entrar      ]                    │
│                                          │
│  Esqueceu sua senha?                     │
│  Não tem conta? Cadastre-se              │
│                                          │
└─────────────────────────────────────────┘
```

### Cadastro - Wireframe

```
┌─────────────────────────────────────────┐
│              [LOGO]                      │
│                                          │
│         Solicitar Entrada                │
│                                          │
│  Você é:                                 │
│  ( ) Restaurante  ( ) Fornecedor         │
│                                          │
│  Nome Completo                           │
│  [___________________________]           │
│                                          │
│  Email                                   │
│  [___________________________]           │
│                                          │
│  Senha                                   │
│  [___________________________] [👁]      │
│                                          │
│  Confirmar Senha                         │
│  [___________________________] [👁]      │
│                                          │
│  WhatsApp                                │
│  [___________________________]           │
│                                          │
│  Nome do Estabelecimento                 │
│  [___________________________]           │
│                                          │
│  Cidade                                  │
│  [___________________________]           │
│                                          │
│  Descrição (opcional)                    │
│  [___________________________]           │
│  [___________________________]           │
│                                          │
│  [SE FORNECEDOR]                         │
│  Categorias:                             │
│  [ ] Bebidas  [ ] Laticínios             │
│  [ ] Hortifrúti  [ ] Carnes              │
│  [ ] Manutenção  [ ] Outros              │
│                                          │
│  [ ] Aceito os termos de uso             │
│                                          │
│  [   Solicitar Entrada   ]               │
│                                          │
│  Já tem conta? Faça login                │
│                                          │
└─────────────────────────────────────────┘
```

### Aguardando Aprovação

```
┌─────────────────────────────────────────┐
│              [LOGO]                      │
│                                          │
│              [✓ ÍCONE]                   │
│                                          │
│      Cadastro Recebido!                  │
│                                          │
│  Nossa equipe está analisando seu       │
│  cadastro e irá liberar seu acesso       │
│  em até 24 horas.                        │
│                                          │
│  Você receberá um email quando for       │
│  aprovado.                               │
│                                          │
│  [    Voltar para Home    ]              │
│  [    Fazer Logout    ]                  │
│                                          │
└─────────────────────────────────────────┘
```

---

## 💻 Dashboard Principal (Área de Membros)

### Restaurante - Wireframe

```
┌─────────────────────────────────────────────────────────┐
│ [LOGO]  Serra Food 360        Olá, João!  [Sair]        │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Bem-vindo ao seu hub, João! 👋                          │
│                                                          │
│  [BADGE VIP] Você é membro VIP ⭐                        │
│  Próxima mentoria: 20/02 às 19h                         │
│                                                          │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│  │  🍽️     │ │  📦     │ │  📊     │ │  👥     │  │
│  │Restauran │ │Fornecedo │ │ Cotações │ │ Talentos │  │
│  │   tes    │ │   res    │ │          │ │          │  │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
│                                                          │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│  │  🛒     │ │  📚     │ │  🤖     │ │  🔄     │  │
│  │ Compras  │ │ Gestão  │ │   IA    │ │  Troca  │  │
│  │ Coletiva │ │         │ │         │ │Equipamen │  │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
│                                                          │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  [🏠 Início] [🎯 Módulos] [💰 Planos] [💬 Chat] [👤 Perfil] │
└─────────────────────────────────────────────────────────┘
```

### Fornecedor - Wireframe

```
┌─────────────────────────────────────────────────────────┐
│ [LOGO]  Serra Food 360        Olá, Maria!  [Sair]       │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Bem-vindo ao seu hub, Maria! 👋                         │
│                                                          │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐                │
│  │  🍽️     │ │  📦     │ │  📊     │                │
│  │Restauran │ │Fornecedo │ │ Cotações │                │
│  │   tes    │ │   res    │ │          │                │
│  └──────────┘ └──────────┘ └──────────┘                │
│                                                          │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐  │
│  │  🛒     │ │  📚     │ │  🤖     │ │  🔄     │  │
│  │Demandas  │ │ Gestão  │ │   IA    │ │  Troca  │  │
│  │(Compras) │ │         │ │         │ │Equipamen │  │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘  │
│                                                          │
│  [SEM MÓDULO DE TALENTOS]                               │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

---

## 🔐 Seeder Admin Padrão

### AdminSeeder.php

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador Serra Food 360',
            'email' => 'admin@serrafood360.com.br',
            'password' => Hash::make('admin123'), // MUDAR EM PRODUÇÃO
            'role' => 'admin',
            'status' => 'aprovado',
            'email_verified_at' => now(),
        ]);
        
        $this->command->info('Admin criado com sucesso!');
        $this->command->info('Email: admin@serrafood360.com.br');
        $this->command->info('Senha: admin123');
        $this->command->warn('⚠️  IMPORTANTE: Alterar senha em produção!');
    }
}
```

**Executar:**
```bash
php artisan db:seed --class=AdminSeeder
```

---

## 📝 Checklist Final Fase 1.1

### Database
- [ ] Migration executada
- [ ] Admin seeder executado
- [ ] Estrutura de storage criada
- [ ] Link simbólico criado

### Backend
- [ ] Model User atualizado
- [ ] Middleware criados e registrados
- [ ] Controllers implementados
- [ ] Requests de validação criados
- [ ] Rotas configuradas

### Frontend
- [ ] Tela de login
- [ ] Tela de cadastro
- [ ] Tela aguardando aprovação
- [ ] Dashboard principal
- [ ] Layout admin básico

### Testes
- [ ] Cadastro de restaurante funciona
- [ ] Cadastro de fornecedor funciona
- [ ] Login funciona
- [ ] Middleware bloqueia corretamente
- [ ] Dashboard mostra módulos por role
- [ ] Admin consegue aprovar usuários

---

## 🚀 Começar Implementação

**Ordem sugerida:**

1. **Migration** (5min)
2. **Model User** (10min)
3. **Seeder Admin** (5min)
4. **Middleware** (15min)
5. **Views Auth** (30min)
6. **AuthController** (20min)
7. **Dashboard** (20min)
8. **Rotas** (10min)
9. **Admin básico** (30min)
10. **Testes** (15min)

**Total estimado:** ~2.5h de desenvolvimento

---

**Pronto para começar!** 🎯

Tudo documentado, arquitetura definida, upload de arquivos planejado.
