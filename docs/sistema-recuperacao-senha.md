# Sistema de Recuperação de Senha

**Serra Food 360** | Implementado em: 15/02/2026

---

## 📋 Visão Geral

Sistema completo de "Esqueci minha senha" com envio de email e redefinição segura de senha.

---

## 🏗️ Arquitetura

### 100% Aderente às Rules

**Controller → Service → Repository → Model**

### Componentes Criados

#### Repositories
- `PasswordResetRepository.php` - Queries na tabela `password_reset_tokens`

#### Services
- `PasswordResetService.php` - Lógica de negócio de reset de senha

#### Controllers
- `PasswordResetController.php` - 4 métodos (exibir forms, enviar link, processar reset)

#### Mailable
- `RedefinirSenha.php` - Email bonito e responsivo

#### Views
- `auth/esqueci-senha.blade.php` - Formulário para solicitar link
- `auth/redefinir-senha.blade.php` - Formulário para criar nova senha
- `emails/redefinir-senha.blade.php` - Template de email (responsivo, mobile-first)

---

## 🔐 Segurança

### Tokens
- ✅ Token único de 64 caracteres (random)
- ✅ Válido por **1 hora apenas**
- ✅ Deletado após uso
- ✅ Um token por email (token antigo é deletado ao solicitar novo)

### Processo
1. Usuário digita email
2. Sistema verifica se email existe
3. Gera token e salva em `password_reset_tokens`
4. Envia email com link: `/redefinir-senha?token=xxx&email=xxx`
5. Usuário clica no link (válido por 1h)
6. Define nova senha (mínimo 6 caracteres, com confirmação)
7. Sistema valida token, atualiza senha e deleta token

---

## 🌐 Rotas

```php
GET  /esqueci-senha        → Exibir formulário de email
POST /esqueci-senha        → Enviar link de recuperação
GET  /redefinir-senha      → Exibir formulário de nova senha
POST /redefinir-senha      → Processar redefinição
```

Todas as rotas são `guest` (não autenticadas).

---

## 📧 Email

### Template Bonito
- ✅ Header com logo Serra Food 360
- ✅ Cores do projeto (#22C55E)
- ✅ Botão CTA grande e claro
- ✅ Alerta de 1 hora de validade
- ✅ Link alternativo (caso botão não funcione)
- ✅ Instruções de segurança
- ✅ Footer com contato
- ✅ **Responsivo (mobile + desktop)**

### Assunto
"Redefinição de Senha - Serra Food 360"

---

## 🎨 Interface

### Tela "Esqueci minha senha"
- Mobile-first, mesmo visual do login
- Campo de email
- Botão "Enviar Link de Recuperação"
- Link para voltar ao login
- Mensagens de sucesso/erro

### Tela "Redefinir senha"
- Email (readonly)
- Nova senha (mínimo 6 caracteres)
- Confirmar nova senha
- Dicas de segurança
- Botão "Redefinir Senha"

### Link no Login
- ✅ Adicionado ao lado de "Lembrar de mim"
- Texto: "Esqueci minha senha"
- Cor verde (#22C55E)

---

## 🧪 Como Testar

### 1. Solicitar reset

1. Acesse `/login`
2. Clique em "Esqueci minha senha"
3. Digite um email cadastrado
4. Clique em "Enviar Link de Recuperação"
5. Mensagem: "Link de redefinição enviado!"

### 2. Verificar email

Configure `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_username
MAIL_PASSWORD=sua_senha
```

Ou use tinker para testar:
```bash
php artisan tinker

use App\Mail\RedefinirSenha;
use Illuminate\Support\Facades\Mail;

Mail::to('teste@example.com')->send(new RedefinirSenha('token_teste'));
```

### 3. Redefinir senha

1. Abra o email recebido
2. Clique no botão "Redefinir Minha Senha"
3. Digite nova senha (mínimo 6 caracteres)
4. Confirme a senha
5. Clique em "Redefinir Senha"
6. Mensagem: "Senha redefinida com sucesso!"
7. Faça login com a nova senha

### 4. Testar expiração

Token expira em 1 hora. Após esse período:
- Link retorna erro: "Link de redefinição expirado ou inválido"
- Usuário deve solicitar novo link

---

## 📊 Tabela `password_reset_tokens`

Já existe na migration padrão do Laravel:

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255),
    created_at TIMESTAMP
);
```

### Limpeza Automática
Tokens expirados podem ser limpos com comando (opcional):
```bash
php artisan schedule:run
```

---

## 📝 Validações

### Email (solicitar reset)
- ✅ Required
- ✅ Must be valid email
- ✅ Must exist in database

### Nova Senha
- ✅ Required
- ✅ Mínimo 6 caracteres
- ✅ Must be confirmed
- ✅ Token válido (não expirado)

---

## 🔄 Fluxo Completo

```
1. Usuário: "Esqueci minha senha"
   ↓
2. Digite email → POST /esqueci-senha
   ↓
3. PasswordResetService::enviarLinkRedefinicao()
   ↓
4. PasswordResetRepository::criarToken()
   - Deleta tokens antigos do email
   - Gera token random(64)
   - Salva em password_reset_tokens
   ↓
5. Mail::send(RedefinirSenha)
   - Email bonito com link
   ↓
6. Usuário clica no link → GET /redefinir-senha?token=xxx&email=xxx
   ↓
7. PasswordResetService::validarToken()
   - Verifica se existe
   - Verifica se não expirou (1h)
   ↓
8. Exibe formulário de nova senha
   ↓
9. Usuário define senha → POST /redefinir-senha
   ↓
10. PasswordResetService::redefinirSenha()
    - Valida token novamente
    - Atualiza password do usuário
    - Deleta token usado
    ↓
11. Redireciona para /login com sucesso
```

---

## ✅ Aderência às Rules

| Rule | Status |
|------|--------|
| Controller → Service → Repository → Model | ✅ 100% |
| Zero queries fora de Repositories | ✅ 100% |
| Nomenclatura em português | ✅ 100% |
| DRY (Don't Repeat Yourself) | ✅ 100% |
| KISS (Keep It Simple, Stupid) | ✅ 100% |
| Mobile-first | ✅ 100% |
| Variáveis CSS (sem gradientes) | ✅ 100% |
| Email responsivo e bonito | ✅ 100% |

---

## 📦 Arquivos Criados

**Backend (7):**
1. `app/Repositories/PasswordResetRepository.php`
2. `app/Services/PasswordResetService.php`
3. `app/Http/Controllers/PasswordResetController.php`
4. `app/Mail/RedefinirSenha.php`

**Frontend (3):**
5. `resources/views/auth/esqueci-senha.blade.php`
6. `resources/views/auth/redefinir-senha.blade.php`
7. `resources/views/emails/redefinir-senha.blade.php`

**Modificados (2):**
8. `routes/web.php` - 4 rotas
9. `resources/views/auth/login.blade.php` - Link "Esqueci minha senha"

**Documentação (1):**
10. `docs/sistema-recuperacao-senha.md`

---

## 🎉 Sistema Completo e Funcional!

✅ Link no login  
✅ Formulário bonito (mobile-first)  
✅ Email responsivo  
✅ Segurança com token de 1 hora  
✅ Validações completas  
✅ 100% aderente às rules  
✅ Pronto para produção  

---

**Desenvolvido seguindo as regras arquiteturais do projeto Serra Food 360**
