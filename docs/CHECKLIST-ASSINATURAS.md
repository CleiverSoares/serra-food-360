# ✅ CHECKLIST - Sistema de Assinaturas

**Status:** Backend 100% implementado  
**Pendente:** Views e configurações de produção

---

## ✅ Implementado (Completo)

### Backend
- [x] Migration `criar_tabela_assinaturas` criada e rodada
- [x] `AssinaturaModel.php` criado
- [x] `AssinaturaRepository.php` criado (15 métodos)
- [x] `AssinaturaService.php` criado
- [x] `EmailService.php` criado
- [x] `UserModel.php` atualizado com relacionamentos
- [x] `UserRepository.php` atualizado

### Controllers
- [x] `AdminAssinaturasController.php` criado
- [x] 7 rotas admin registradas
- [x] Rotas testadas e funcionando

### Jobs & Commands
- [x] `VerificarAssinaturasVencidas.php` criado
- [x] `VerificarAssinaturasCommand.php` criado
- [x] Agendamento configurado em `routes/console.php`

### Middleware
- [x] `CheckAssinaturaAtiva.php` criado
- [x] Middleware registrado no `bootstrap/app.php`

### Email
- [x] `AvisoVencimentoPlano.php` (Mailable) criado
- [x] Template `aviso-vencimento-plano.blade.php` criado
- [x] Design responsivo e bonito

### Documentação
- [x] `docs/sistema-assinaturas.md` - Documentação técnica completa
- [x] `docs/RESUMO-ASSINATURAS.md` - Resumo executivo
- [x] `docs/CHECKLIST-ASSINATURAS.md` - Este checklist

---

## ⏳ Pendente (Opcional - Frontend)

### Views Admin
- [ ] `resources/views/admin/assinaturas/index.blade.php`
  - Lista todas assinaturas
  - Filtros por status, plano, usuário
  - Ações: Ver, Renovar, Cancelar
  
- [ ] `resources/views/admin/assinaturas/criar.blade.php`
  - Formulário de criação
  - Campos: plano, tipo_pagamento
  - Validação frontend
  
- [ ] `resources/views/admin/assinaturas/exibir.blade.php`
  - Detalhes completos da assinatura
  - Status visual (ativo/vencido/cancelado)
  - Botões: Renovar, Cancelar
  - Dias restantes em destaque
  
- [ ] `resources/views/admin/assinaturas/historico.blade.php`
  - Timeline de assinaturas do usuário
  - Todas assinaturas (passadas e atuais)
  - Filtros por status

### Menu Admin
- [ ] Adicionar item "Assinaturas" em `partials/menu-items.blade.php`
  ```php
  @if(auth()->user()->role === 'admin')
      <a href="{{ route('admin.assinaturas.index') }}">
          <!-- ícone -->
          Assinaturas
      </a>
  @endif
  ```

### Views Públicas (Futuro)
- [ ] `resources/views/assinatura/vencida.blade.php`
  - Página para usuários com assinatura vencida
  - Opções de renovação
  - Contato com suporte
  
- [ ] `resources/views/assinatura/criar.blade.php`
  - Página para criar primeira assinatura
  - Escolha de planos
  - Integração com gateway de pagamento

---

## ⚠️ Configuração Obrigatória (Produção)

### 1. Configurar Email SMTP

**Arquivo:** `.env`

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.seuservidor.com
MAIL_PORT=587
MAIL_USERNAME=seu_username
MAIL_PASSWORD=sua_senha_segura
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@serrafood360.com.br
MAIL_FROM_NAME="Serra Food 360"
```

**Testar:**
```bash
php artisan tinker
Mail::raw('Teste', fn($msg) => $msg->to('seu@email.com'));
```

---

### 2. Configurar Scheduler (Cron)

#### Linux/Ubuntu (Recomendado)

```bash
# Abrir crontab
crontab -e

# Adicionar linha (ajustar caminho):
* * * * * cd /var/www/serra-food-360 && php artisan schedule:run >> /dev/null 2>&1
```

#### Windows Server (Task Scheduler)

1. Abrir "Agendador de Tarefas"
2. Criar nova tarefa
3. Gatilho: Repetir a cada 1 minuto
4. Ação: Iniciar programa
   - Programa: `php.exe`
   - Argumentos: `C:\caminho\artisan schedule:run`

---

### 3. Verificar Scheduler Funcionando

```bash
# Ver comandos agendados
php artisan schedule:list

# Output esperado:
# 0 9 * * *  php artisan assinaturas:verificar ... Next Due: 14 hours from now
```

---

### 4. Aplicar Middleware (Quando Pronto)

**Em:** `routes/web.php`

```php
// Proteger rotas que precisam de assinatura
Route::middleware(['auth', 'approved', 'assinatura.ativa'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/compradores', [CompradoresController::class, 'index']);
    Route::get('/fornecedores', [FornecedoresController::class, 'index']);
    Route::get('/talentos', [TalentosController::class, 'index']);
    // etc...
});
```

**IMPORTANTE:** Não aplicar ainda se não tiver criado:
- Página de assinatura vencida
- Página de criação de assinatura
- Sistema de pagamento

---

## 🧪 Testes

### Teste 1: Criar Assinatura

```bash
php artisan tinker

use App\Services\AssinaturaService;
$service = app(AssinaturaService::class);
$assinatura = $service->criarAssinatura(1, 'profissional', 'mensal');
echo "Assinatura criada: ID {$assinatura->id}\n";
echo "Vence em: {$assinatura->data_fim->format('d/m/Y')}\n";
```

---

### Teste 2: Verificar Job

```bash
# Executar manualmente
php artisan assinaturas:verificar

# Verificar logs
tail -f storage/logs/laravel.log
```

---

### Teste 3: Enviar Email de Teste

```bash
php artisan tinker

use App\Mail\AvisoVencimentoPlano;
use App\Models\UserModel;
use Illuminate\Support\Facades\Mail;

$user = UserModel::with('assinaturaAtiva')->find(1);
$assinatura = $user->assinaturaAtiva;

if ($assinatura) {
    Mail::to('teste@example.com')->send(
        new AvisoVencimentoPlano($user, $assinatura, 7)
    );
    echo "Email enviado!\n";
} else {
    echo "Usuário não tem assinatura ativa\n";
}
```

---

### Teste 4: Testar Middleware

```php
// Criar rota de teste temporária em routes/web.php
Route::middleware(['auth', 'assinatura.ativa'])->get('/teste-assinatura', function () {
    return 'Você tem assinatura ativa!';
});

// Acessar com usuário COM assinatura: deve funcionar
// Acessar com usuário SEM assinatura: deve redirecionar
```

---

## 📊 Métricas de Sucesso

Após implementação completa, você deve ter:

- ✅ Usuários com assinaturas criadas no banco
- ✅ Job rodando diariamente às 9h
- ✅ Emails sendo enviados 7, 3 e 1 dia antes do vencimento
- ✅ Usuários inativados automaticamente quando assinatura vence
- ✅ Acesso bloqueado para usuários sem assinatura (após aplicar middleware)
- ✅ Logs claros em `storage/logs/laravel.log`

---

## 🆘 Troubleshooting

### Emails não estão sendo enviados

```bash
# Verificar config
php artisan config:cache

# Testar conexão SMTP
php artisan tinker
Mail::raw('Teste', fn($msg) => $msg->to('teste@email.com'));

# Ver erros
tail -f storage/logs/laravel.log
```

---

### Job não está rodando

```bash
# Verificar se scheduler está configurado
php artisan schedule:list

# Rodar manualmente
php artisan assinaturas:verificar

# Ver logs
tail -f storage/logs/laravel.log | grep -i assinatura
```

---

### Middleware bloqueando admin

Verificar se há esta condição no middleware:

```php
// Admin não precisa de assinatura
if ($user->role === 'admin') {
    return $next($request);
}
```

---

## 📚 Referências

- [Documentação Completa](./sistema-assinaturas.md)
- [Resumo Executivo](./RESUMO-ASSINATURAS.md)
- [Laravel Scheduling](https://laravel.com/docs/11.x/scheduling)
- [Laravel Mail](https://laravel.com/docs/11.x/mail)
- [Laravel Middleware](https://laravel.com/docs/11.x/middleware)

---

**🎉 Sistema pronto para produção (após configurar email e cron)!**
