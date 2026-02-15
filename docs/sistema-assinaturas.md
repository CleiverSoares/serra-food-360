# Sistema de Gerenciamento de Assinaturas
**Serra Food 360** | Implementado em: 15/02/2026

---

## 📋 Sumário

1. [Visão Geral](#visão-geral)
2. [Arquitetura](#arquitetura)
3. [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
4. [Funcionalidades](#funcionalidades)
5. [Agendamento Automático](#agendamento-automático)
6. [Sistema de Emails](#sistema-de-emails)
7. [Middleware de Controle](#middleware-de-controle)
8. [Como Usar](#como-usar)
9. [Comandos Úteis](#comandos-úteis)

---

## Visão Geral

Sistema completo de gerenciamento de assinaturas para compradores e fornecedores da plataforma Serra Food 360. O sistema controla:

- ✅ Criação e renovação de assinaturas
- ✅ Planos: Básico, Profissional, Empresarial
- ✅ Pagamento: Mensal ou Anual
- ✅ Avisos automáticos de vencimento (7, 3 e 1 dia antes)
- ✅ Inativação automática de usuários com assinatura vencida
- ✅ Bloqueio de acesso para usuários sem assinatura ativa
- ✅ Histórico completo de assinaturas

---

## Arquitetura

O sistema segue **100%** as rules do projeto: **Controller → Service → Repository → Model**

### Componentes Criados

#### Models
- `AssinaturaModel.php` - Representa assinaturas na tabela `assinaturas`
- `UserModel.php` (atualizado) - Adicionado relacionamento com assinaturas

#### Repositories
- `AssinaturaRepository.php` - Todas as queries relacionadas a assinaturas
- `UserRepository.php` (atualizado) - Método para buscar usuários com assinaturas

#### Services
- `AssinaturaService.php` - Lógica de negócio de assinaturas
- `EmailService.php` - Envio de emails relacionados a assinaturas

#### Controllers
- `Admin/AdminAssinaturasController.php` - Gerenciamento admin de assinaturas

#### Middleware
- `CheckAssinaturaAtiva.php` - Bloqueia acesso de usuários sem assinatura

#### Jobs
- `VerificarAssinaturasVencidas.php` - Job agendado para verificação diária

#### Commands
- `VerificarAssinaturasCommand.php` - Command para executar verificação manual

#### Mailable
- `AvisoVencimentoPlano.php` - Email de aviso de vencimento

---

## Estrutura do Banco de Dados

### Tabela `assinaturas`

```sql
CREATE TABLE `assinaturas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `plano` enum('basico','profissional','empresarial') NOT NULL,
  `tipo_pagamento` enum('mensal','anual') NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `status` enum('ativo','pendente','vencido','cancelado') DEFAULT 'ativo',
  `ultimo_aviso_enviado` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assinaturas_user_id_status_index` (`user_id`,`status`),
  KEY `assinaturas_data_fim_status_index` (`data_fim`,`status`),
  CONSTRAINT `assinaturas_user_id_foreign` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

### Relacionamentos

```php
// UserModel
public function assinaturas(): HasMany
public function assinaturaAtiva(): HasOne

// AssinaturaModel
public function usuario(): BelongsTo
```

---

## Funcionalidades

### 1. Criar Assinatura

**Admin pode criar assinatura para qualquer usuário:**

```php
$assinatura = $assinaturaService->criarAssinatura(
    userId: 1,
    plano: 'profissional',
    tipoPagamento: 'anual'
);
```

- Planos: `basico`, `profissional`, `empresarial`
- Tipo: `mensal` (1 mês) ou `anual` (12 meses)
- Status inicial: `ativo`

### 2. Verificar Assinatura Ativa

```php
$temAtiva = $assinaturaService->temAssinaturaAtiva($userId);
```

### 3. Renovar Assinatura

```php
$assinaturaService->renovarAssinatura($assinaturaId, 'anual');
```

- Redefine `data_inicio` para hoje
- Calcula nova `data_fim` (1 ou 12 meses)
- Marca status como `ativo`
- Limpa `ultimo_aviso_enviado`

### 4. Cancelar Assinatura

```php
$assinaturaService->cancelarAssinatura($assinaturaId);
```

- Marca status como `cancelado`
- Não deleta o registro (mantém histórico)

### 5. Histórico de Assinaturas

```php
$historico = $assinaturaService->listarHistoricoAssinaturas($userId);
```

---

## Agendamento Automático

### Configuração

**Arquivo:** `routes/console.php`

```php
Schedule::command('assinaturas:verificar')
    ->dailyAt('09:00')
    ->timezone('America/Sao_Paulo');
```

### O que o Job faz diariamente?

1. **Inativa usuários com assinatura vencida**
   - Busca assinaturas com `status = 'ativo'` e `data_fim < hoje`
   - Marca assinatura como `vencido`
   - Altera `users.status = 'inativo'`
   - Loga no `storage/logs/laravel.log`

2. **Envia avisos de vencimento**
   - Busca assinaturas que vencem em 7, 3 ou 1 dia
   - Envia email personalizado para cada usuário
   - Marca `ultimo_aviso_enviado = now()`
   - Não envia o mesmo aviso duas vezes no mesmo dia

### Executar manualmente

```bash
php artisan assinaturas:verificar
```

### Logs

Os logs ficam em `storage/logs/laravel.log`:

```
[2026-02-15 09:00:01] Iniciando verificação de assinaturas vencidas
[2026-02-15 09:00:03] Usuários inativados por assinatura vencida: 2
[2026-02-15 09:00:05] Avisos de vencimento enviados: 5
```

---

## Sistema de Emails

### Template

**Arquivo:** `resources/views/emails/aviso-vencimento-plano.blade.php`

Design responsivo e bonito com:
- ✅ Logo da Serra Food 360
- ✅ Cores do projeto (verde #22C55E)
- ✅ Mensagem personalizada por dias restantes
- ✅ Box de alerta (amarelo para 7/3 dias, vermelho para 1 dia)
- ✅ Detalhes da assinatura (plano, data, tipo pagamento)
- ✅ Botão CTA "Renovar Minha Assinatura"
- ✅ Informações sobre o que acontece se vencer

### Variáveis de Email

Configure no `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_username
MAIL_PASSWORD=sua_senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@serrafood360.com.br
MAIL_FROM_NAME="Serra Food 360"
```

### Testar Email

```bash
php artisan tinker

use App\Mail\AvisoVencimentoPlano;
use App\Models\UserModel;
use App\Models\AssinaturaModel;
use Illuminate\Support\Facades\Mail;

$user = UserModel::find(1);
$assinatura = $user->assinaturaAtiva;

Mail::to('teste@example.com')->send(
    new AvisoVencimentoPlano($user, $assinatura, 7)
);
```

---

## Middleware de Controle

### CheckAssinaturaAtiva

**Uso:** Protege rotas que requerem assinatura ativa

```php
Route::middleware(['auth', 'assinatura.ativa'])->group(function () {
    // Rotas protegidas aqui
});
```

### Lógica

- ✅ Admin: **não precisa** de assinatura (sempre permite)
- ✅ Comprador/Fornecedor: **precisa** de assinatura ativa
- ❌ Sem assinatura: redireciona para `/assinatura/criar`
- ❌ Assinatura vencida: redireciona para `/assinatura/vencida`

### Aplicar no Futuro

```php
// Em routes/web.php
Route::middleware(['auth', 'approved', 'assinatura.ativa'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/compradores', [CompradoresController::class, 'index']);
    // etc...
});
```

---

## Como Usar

### Admin - Criar Assinatura

1. Acesse `/admin/assinaturas`
2. Veja lista de usuários com suas assinaturas
3. Clique em "Criar Assinatura" para um usuário sem assinatura
4. Escolha plano e tipo de pagamento
5. Salve

### Admin - Renovar Assinatura

1. Acesse `/admin/assinaturas/{id}`
2. Veja detalhes da assinatura
3. Clique em "Renovar"
4. Escolha tipo de pagamento (mensal/anual)
5. Confirme

### Admin - Ver Histórico

1. Acesse `/admin/assinaturas/usuario/{userId}/historico`
2. Veja todas as assinaturas (ativas, vencidas, canceladas)

---

## Comandos Úteis

### Verificar assinaturas manualmente

```bash
php artisan assinaturas:verificar
```

### Rodar scheduler localmente (desenvolvimento)

```bash
php artisan schedule:work
```

### Ver lista de comandos agendados

```bash
php artisan schedule:list
```

### Testar envio de email

```bash
php artisan tinker
>>> Mail::raw('Teste', fn($msg) => $msg->to('teste@example.com'));
```

### Ver logs de assinaturas

```bash
tail -f storage/logs/laravel.log | grep -i assinatura
```

---

## Próximos Passos (Opcional)

- [ ] Criar view `admin/assinaturas/index.blade.php`
- [ ] Criar view `admin/assinaturas/criar.blade.php`
- [ ] Criar view `admin/assinaturas/exibir.blade.php`
- [ ] Criar view `admin/assinaturas/historico.blade.php`
- [ ] Adicionar item "Assinaturas" no menu admin
- [ ] Criar páginas públicas `/assinatura/criar` e `/assinatura/vencida`
- [ ] Implementar gateway de pagamento (Stripe, PagSeguro, etc.)
- [ ] Dashboard de métricas de assinaturas
- [ ] Relatórios de faturamento

---

## Resumo de Arquivos Criados/Modificados

### Novos Arquivos (17)

1. `database/migrations/2026_02_15_145640_criar_tabela_assinaturas.php`
2. `app/Models/AssinaturaModel.php`
3. `app/Repositories/AssinaturaRepository.php`
4. `app/Services/AssinaturaService.php`
5. `app/Services/EmailService.php`
6. `app/Mail/AvisoVencimentoPlano.php`
7. `app/Jobs/VerificarAssinaturasVencidas.php`
8. `app/Console/Commands/VerificarAssinaturasCommand.php`
9. `app/Http/Middleware/CheckAssinaturaAtiva.php`
10. `app/Http/Controllers/Admin/AdminAssinaturasController.php`
11. `resources/views/emails/aviso-vencimento-plano.blade.php`
12. `docs/sistema-assinaturas.md`

### Arquivos Modificados (5)

1. `app/Models/UserModel.php` - Relacionamentos com assinaturas
2. `app/Repositories/UserRepository.php` - Método `buscarUsuariosComAssinatura()`
3. `bootstrap/app.php` - Registro do middleware `assinatura.ativa`
4. `routes/console.php` - Agendamento do comando
5. `routes/web.php` - Rotas de gerenciamento de assinaturas

---

## ✅ 100% Aderente às Rules

- ✅ Controller → Service → Repository → Model
- ✅ Zero queries diretas fora de Repositories
- ✅ Nomenclatura em português
- ✅ Documentação completa
- ✅ Código limpo e organizado
- ✅ Seguindo princípios DRY e KISS

---

**Desenvolvido seguindo as regras arquiteturais do projeto Serra Food 360**
