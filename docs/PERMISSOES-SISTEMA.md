# 🔐 Matriz de Permissões - Serra Food 360

**Data:** 15/02/2026  
**Status:** ✅ Implementado

---

## 📋 Resumo de Acessos

| Funcionalidade | Admin | Comprador | Fornecedor |
|---|---|---|---|
| **Dashboard próprio** | ✅ | ✅ | ✅ |
| **Ver Fornecedores** | ✅ Gerenciar | ✅ Ver lista | ✅ Ver lista |
| **Ver Compradores** | ✅ Gerenciar | ✅ Ver lista | ✅ Ver lista |
| **Ver Talentos** | ✅ Gerenciar | ✅ Ver lista | ❌ Não acessa |
| **Criar/Editar Usuários** | ✅ | ❌ | ❌ |
| **Aprovar Cadastros** | ✅ | ❌ | ❌ |
| **Gerenciar Segmentos** | ✅ | ❌ | ❌ |
| **Cotações** | ✅ Gerenciar | ✅ Ver/Participar | ✅ Ver/Responder |
| **Compras Coletivas** | ✅ Gerenciar | ✅ Sinalizar interesse | ✅ Ver demanda total |
| **Material de Gestão** | ✅ Gerenciar | ✅ Ver/Baixar | ✅ Ver/Baixar |
| **Classificados** | ✅ Moderar | ✅ Criar/Ver | ✅ Criar/Ver |
| **Consultor IA** | ✅ | ✅ | ✅ |
| **Editar próprio perfil** | ✅ | ✅ | ✅ |

---

## 🎭 Detalhamento por Perfil

### 1️⃣ **ADMIN** (Administrador Serra Food)

**Prefixo de rotas:** `/admin/*`

**Pode:**
- ✅ Gerenciar TUDO (CRUD completo)
- ✅ Aprovar/rejeitar cadastros
- ✅ Criar usuários diretamente (já aprovados)
- ✅ Inativar/ativar qualquer conta
- ✅ Gerenciar segmentos
- ✅ Ver estatísticas e relatórios
- ✅ Configurar sistema

**Middleware:** `auth`, `approved`, `role:admin`

**Rotas:**
```
GET    /admin                          (Dashboard Admin)
GET    /admin/usuarios                 (Aprovações pendentes)
GET    /admin/compradores              (Lista + CRUD)
GET    /admin/fornecedores             (Lista + CRUD)
GET    /admin/talentos                 (Lista + CRUD)
GET    /admin/segmentos                (Lista + CRUD)
POST   /admin/usuarios/{id}/aprovar    (Aprovar)
POST   /admin/compradores/{id}/inativar (Inativar)
... (todos os métodos POST/PUT/DELETE)
```

---

### 2️⃣ **COMPRADOR** (Ex-Restaurante)

**Prefixo de rotas:** `/comprador/*` ou `/dashboard`

**Pode:**
- ✅ Ver diretório de **Fornecedores** (filtrar por segmento)
- ✅ Ver diretório de **Compradores** (networking)
- ✅ Ver banco de **Talentos** (contratar)
- ✅ Ver **Cotações da Semana**
- ✅ **Sinalizar interesse** em compras coletivas
- ✅ Criar/ver **Classificados**
- ✅ Acessar **Material de Gestão**
- ✅ Usar **Consultor IA**
- ✅ Contato via WhatsApp
- ✅ Editar **próprio perfil**

**NÃO pode:**
- ❌ Editar perfis de outros
- ❌ Criar/editar fornecedores, talentos, segmentos
- ❌ Aprovar usuários
- ❌ Ver área administrativa

**Middleware:** `auth`, `approved`, `role:comprador`

**Rotas (apenas leitura + ações próprias):**
```
GET    /dashboard                      (Dashboard Comprador)
GET    /fornecedores                   (Ver lista pública)
GET    /fornecedores/{id}              (Ver perfil detalhado)
GET    /compradores                    (Ver outros compradores)
GET    /compradores/{id}               (Ver perfil)
GET    /talentos                       (Ver banco de talentos)
GET    /talentos/{id}                  (Ver perfil)
GET    /cotacoes                       (Ver cotações)
GET    /compras-coletivas              (Ver + sinalizar interesse)
POST   /compras-coletivas/{id}/participar
GET    /material-gestao                (Ver conteúdos)
GET    /classificados                  (Ver anúncios)
POST   /classificados                  (Criar próprio anúncio)
GET    /perfil                         (Ver próprio perfil)
PUT    /perfil                         (Editar próprio perfil)
```

---

### 3️⃣ **FORNECEDOR** (Fornecedor/Prestador)

**Prefixo de rotas:** `/fornecedor/*` ou `/dashboard`

**Pode:**
- ✅ Ver diretório de **Compradores** (clientes potenciais)
- ✅ Ver diretório de **Fornecedores** (concorrentes/parceiros)
- ✅ Ver **Cotações da Semana** (onde aparece)
- ✅ **Ver volume TOTAL** de compras coletivas
- ✅ **Ver lista de interessados** (para negociar)
- ✅ Criar/ver **Classificados**
- ✅ Acessar **Material de Gestão**
- ✅ Usar **Consultor IA**
- ✅ Contato via WhatsApp
- ✅ Editar **próprio perfil**

**NÃO pode:**
- ❌ Ver **Talentos** (não precisa contratar)
- ❌ Editar perfis de outros
- ❌ Criar/editar compradores, segmentos
- ❌ Aprovar usuários
- ❌ Ver área administrativa

**Middleware:** `auth`, `approved`, `role:fornecedor`

**Rotas (apenas leitura + ações próprias):**
```
GET    /dashboard                      (Dashboard Fornecedor)
GET    /compradores                    (Ver clientes potenciais)
GET    /compradores/{id}               (Ver perfil)
GET    /fornecedores                   (Ver outros fornecedores)
GET    /fornecedores/{id}              (Ver perfil)
GET    /cotacoes                       (Ver cotações)
GET    /compras-coletivas              (Ver demanda agregada)
GET    /compras-coletivas/{id}/interessados (Ver quem participou)
GET    /material-gestao                (Ver conteúdos)
GET    /classificados                  (Ver anúncios)
POST   /classificados                  (Criar próprio anúncio)
GET    /perfil                         (Ver próprio perfil)
PUT    /perfil                         (Editar próprio perfil)
```

---

## 🛡️ Implementação Técnica

### Middleware Existente
```php
// app/Http/Middleware/CheckRole.php
// Já implementado e funcionando
```

### Proteção de Rotas

**Admin (já implementado):**
```php
Route::middleware(['auth', 'approved', 'role:admin'])->prefix('admin')->group(...)
```

**Comprador (a implementar):**
```php
Route::middleware(['auth', 'approved', 'role:comprador'])->group(function () {
    Route::get('/fornecedores', [CompradorController::class, 'fornecedores']);
    Route::get('/talentos', [CompradorController::class, 'talentos']);
    // ...
});
```

**Fornecedor (a implementar):**
```php
Route::middleware(['auth', 'approved', 'role:fornecedor'])->group(function () {
    Route::get('/compradores', [FornecedorController::class, 'compradores']);
    Route::get('/compras-coletivas', [FornecedorController::class, 'comprasColetivas']);
    // ...
});
```

**Rotas Compartilhadas (comprador E fornecedor):**
```php
Route::middleware(['auth', 'approved', 'role:comprador,fornecedor'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'show']);
    Route::put('/perfil', [PerfilController::class, 'update']);
    Route::get('/classificados', [ClassificadosController::class, 'index']);
    // ...
});
```

---

## 🎯 Princípios de Design

1. **Separação clara de contextos:**
   - `/admin/*` = apenas admin
   - `/dashboard` = área logada (todos)
   - Rotas públicas sem autenticação = diretórios públicos

2. **Middleware em cascata:**
   ```
   auth → approved → role:xxx
   ```

3. **Nomeação de rotas:**
   - Admin: `admin.recurso.acao`
   - Comprador: `comprador.recurso.acao`
   - Fornecedor: `fornecedor.recurso.acao`
   - Públicas: `recurso.acao`

4. **Controllers dedicados:**
   - `Admin\AdminXxxController` (CRUD completo)
   - `CompradorController` (apenas leitura)
   - `FornecedorController` (apenas leitura)
   - `PerfilController` (edição própria)

5. **Blade Directives:**
   ```blade
   @role('admin')
       <!-- Botões de editar/criar -->
   @endrole
   
   @role('comprador')
       <!-- Ação de sinalizar interesse -->
   @endrole
   ```

---

## 📝 Próximos Passos

- [ ] Criar `CompradorController`
- [ ] Criar `FornecedorController`
- [ ] Criar rotas públicas de visualização (fornecedores, talentos)
- [ ] Implementar filtros por segmento
- [ ] Criar blade directive `@role()`
- [ ] Atualizar menus com links corretos
- [ ] Testar acessos de cada perfil
- [ ] Documentar exemplos de uso

---

**Última atualização:** 15/02/2026
