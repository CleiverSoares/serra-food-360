# 🚨 Violações de Arquitetura Encontradas

## Regra: QUERIES SÓ NO REPOSITORY

---

## ❌ CONTROLLERS COM QUERIES DIRETAS

### 1. `TalentosController.php` (PÚBLICO)
- **Linhas 30-66**: `TalentoModel::where()` e queries Eloquent diretas
- **Linhas 69-81**: `TalentoModel` queries para buscar filtros
- **Linha 106**: `TalentoModel::findOrFail()`
- **SOLUÇÃO**: Criar `TalentoRepository`, mover queries, injetar no controller

### 2. `AuthController.php`
- **Linhas 67-69**: `SegmentoModel::where()` - query direta
- **Linhas 83-91**: Validações usam 'telefone', 'whatsapp', 'cidade' (CAMPOS QUE NÃO EXISTEM MAIS!)
- **SOLUÇÃO**: Usar `SegmentoRepository`, remover validações de campos antigos

### 3. `Admin\AdminTalentosController.php`
- **Linhas 24-73**: Múltiplas queries `TalentoModel` diretas
- **Linhas 107, 124, 136, 145, 154, 207, 219, 231**: `new TalentoModel()`, `findOrFail()`, `->save()`
- **SOLUÇÃO**: Usar `TalentoRepository` e `TalentoService`

### 4. `Admin\AdminCompradoresController.php`
- **Linhas 34-44**: `\App\Models\UserModel::where()` - QUERY DIRETA!
- **Linhas 71-73**: `SegmentoModel::where()` - QUERY DIRETA!
- **Linhas 87-91, 159-161**: Validações com 'telefone', 'whatsapp', 'cidade' (CAMPOS ANTIGOS!)
- **Linhas 172-174**: `UserService` recebe campos antigos
- **Linha 180**: `$comprador->segmentos()->sync()` - query direta
- **SOLUÇÃO**: Usar Repositories, atualizar para usar tabelas `enderecos` e `contatos`

### 5. `Admin\AdminFornecedoresController.php`
- **Mesmas violações** de `AdminCompradoresController.php`
- **SOLUÇÃO**: Mesmas correções

### 6. `Admin\AdminUsuariosController.php`
- **Linhas 40-42**: `SegmentoModel::where()` - QUERY DIRETA
- **Linhas 56-62**: Validações com 'telefone', 'whatsapp', 'cidade' (CAMPOS ANTIGOS!)
- **SOLUÇÃO**: Usar `SegmentoRepository`, atualizar validações

### 7. `Admin\AdminSegmentosController.php`
- **Linhas 16-18**: `SegmentoModel::withCount()`
- **Linha 54**: `SegmentoModel::create()`
- **Linhas 66, 76, 108, 128, 141**: `SegmentoModel::findOrFail()`
- **Linhas 96, 116, 129, 142**: `$segmento->update()`, `delete()`
- **SOLUÇÃO**: Criar/usar `SegmentoService` e `SegmentoRepository`

---

## ❌ SERVICES COM PROBLEMAS

### 1. `AuthService.php`
- **Linhas 64-66**: Usa 'telefone', 'whatsapp', 'cidade' - **CAMPOS QUE NÃO EXISTEM MAIS!**
- **Linha 74**: `$usuario->segmentos()->attach()` - query direta (deveria estar no Repository)
- **SOLUÇÃO**: Usar tabelas `enderecos` e `contatos`, mover `attach()` para Repository

### 2. `FilterService.php`
- **Linha 83**: `where('cidade')` - **COLUNA NÃO EXISTE MAIS!**
- **SOLUÇÃO**: Usar `whereHas('enderecos', ...)` para filtrar por cidade

---

## 📋 RESUMO DE CORREÇÕES NECESSÁRIAS

| Arquivo | Violações | Prioridade |
|---------|-----------|------------|
| `TalentosController.php` | Queries diretas de TalentoModel | 🔴 ALTA |
| `Admin\AdminTalentosController.php` | Queries diretas, new Model, save() | 🔴 ALTA |
| `Admin\AdminCompradoresController.php` | Queries diretas, campos antigos | 🔴 CRÍTICA |
| `Admin\AdminFornecedoresController.php` | Queries diretas, campos antigos | 🔴 CRÍTICA |
| `Admin\AdminUsuariosController.php` | Queries diretas, campos antigos | 🔴 CRÍTICA |
| `Admin\AdminSegmentosController.php` | Queries diretas | 🟡 MÉDIA |
| `AuthController.php` | Query direta, campos antigos | 🔴 CRÍTICA |
| `AuthService.php` | Campos antigos, query direta | 🔴 CRÍTICA |
| `FilterService.php` | Filtro de cidade quebrado | 🔴 CRÍTICA |

---

## ✅ ARQUIVOS OK

- `DashboardController.php` ✅
- `Admin\AdminDashboardController.php` ✅
- `CompradoresController.php` ✅ (já refatorado)
- `FornecedoresController.php` ✅ (já refatorado)
- `UserService.php` ✅
- `TalentoService.php` ✅
