# Próximos Passos - Normalização de Endereços e Contatos

## ✅ Concluído

1. **Banco de Dados**
   - ✅ Tabelas `enderecos` e `contatos` criadas
   - ✅ Dados migrados de `users` para novas tabelas
   - ✅ Colunas antigas removidas de `users`

2. **Backend (seguindo rules - Repository pattern)**
   - ✅ `EnderecoRepository` criado
   - ✅ `ContatoRepository` criado
   - ✅ `UserRepository` atualizado com métodos de filtro
   - ✅ Controllers públicos refatorados (SEM queries diretas)
   - ✅ Models com relacionamentos

3. **Views de Visualização**
   - ✅ `admin/compradores/index.blade.php` - usa relacionamentos
   - ✅ `admin/compradores/show.blade.php` - usa relacionamentos
   - ✅ `admin/fornecedores/index.blade.php` - usa relacionamentos
   - ✅ `admin/fornecedores/show.blade.php` - usa relacionamentos

4. **UI**
   - ✅ Logo centralizada e aumentada no sidebar

---

## 🚧 Pendente - FORMULÁRIOS DE EDIÇÃO/CRIAÇÃO

### Arquivos que AINDA usam campos antigos:

#### Compradores
- ❌ `resources/views/admin/compradores/edit.blade.php`
- ❌ `resources/views/admin/compradores/create.blade.php`

#### Fornecedores
- ❌ `resources/views/admin/fornecedores/edit.blade.php`
- ❌ `resources/views/admin/fornecedores/create.blade.php`

#### Talentos
- ❌ `resources/views/admin/talentos/edit.blade.php`
- ❌ `resources/views/admin/talentos/create.blade.php`

#### Usuários e Auth
- ❌ `resources/views/admin/usuarios/criar.blade.php`
- ❌ `resources/views/admin/usuarios/index.blade.php`
- ❌ `resources/views/auth/cadastro.blade.php`

---

## 📋 Tarefas para Refatoração de Formulários

### 1. Atualizar Views de Formulários

Cada formulário precisa:

```blade
{{-- Ao invés de: --}}
<input name="telefone" value="{{ $user->telefone }}">
<input name="whatsapp" value="{{ $user->whatsapp }}">
<input name="cidade" value="{{ $user->cidade }}">

{{-- Usar: --}}
<input name="contatos[telefone][valor]" value="{{ $user->telefonePrincipal?->valor }}">
<input name="contatos[whatsapp][valor]" value="{{ $user->whatsappPrincipal?->valor }}">

{{-- Endereço expandido: --}}
<input name="endereco[cep]" value="{{ $user->enderecoPrincipal?->cep }}">
<input name="endereco[logradouro]" value="{{ $user->enderecoPrincipal?->logradouro }}">
<input name="endereco[numero]" value="{{ $user->enderecoPrincipal?->numero }}">
<input name="endereco[complemento]" value="{{ $user->enderecoPrincipal?->complemento }}">
<input name="endereco[bairro]" value="{{ $user->enderecoPrincipal?->bairro }}">
<input name="endereco[cidade]" value="{{ $user->enderecoPrincipal?->cidade }}">
<input name="endereco[estado]" value="{{ $user->enderecoPrincipal?->estado }}">
```

### 2. Atualizar Controllers Admin

**SEGUINDO AS RULES: Controller → Service → Repository**

Exemplo para `AdminCompradoresController`:

```php
// ❌ ERRADO (query direto)
$user->update(['telefone' => $request->telefone]);

// ✅ CORRETO (usar Service)
$this->compradorService->atualizarContatos($userId, $request->contatos);
$this->compradorService->atualizarEndereco($userId, $request->endereco);
```

### 3. Criar Services (se não existirem)

```php
// app/Services/CompradorService.php
public function atualizarContatos(int $userId, array $contatos): void
{
    foreach ($contatos as $tipo => $dados) {
        $this->contatoRepository->atualizarOuCriar($userId, $tipo, $dados);
    }
}

public function atualizarEndereco(int $userId, array $endereco): void
{
    $this->enderecoRepository->atualizarOuCriarPrincipal($userId, $endereco);
}
```

### 4. Adicionar ao Repository

```php
// EnderecoRepository
public function atualizarOuCriarPrincipal(int $userId, array $dados): EnderecoModel
{
    return EnderecoModel::updateOrCreate(
        ['user_id' => $userId, 'is_padrao' => true],
        $dados
    );
}

// ContatoRepository
public function atualizarOuCriar(int $userId, string $tipo, array $dados): ContatoModel
{
    return ContatoModel::updateOrCreate(
        ['user_id' => $userId, 'tipo' => $tipo, 'is_principal' => true],
        ['valor' => $dados['valor']]
    );
}
```

---

## 🎯 Ordem de Execução Recomendada

1. **Entender** - Ler este doc e confirmar arquitetura
2. **Planejar** - Definir estrutura de formulários e validações
3. **Executar**:
   1. Adicionar métodos nos Repositories
   2. Criar/atualizar Services
   3. Atualizar Controllers Admin
   4. Refatorar views de formulários
   5. Testar cada CRUD completamente

---

## ⚠️ Importante

- **NUNCA** colocar queries direto no Controller
- **SEMPRE** usar: Controller → Service → Repository → Model
- **TESTAR** cada formulário após refatorar
- **VALIDAR** dados antes de salvar (Request classes)
