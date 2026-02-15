# Arquitetura de Segmentos e Cruzamentos

## 🎯 Problema a Resolver

**Situação atual:**
- "Restaurante" e "Fornecedor" são roles genéricos
- Um fornecedor de pet shop apareceria para restaurantes (não faz sentido!)
- Não há forma de filtrar por tipo de negócio/segmento

**Solução:**
- Implementar **SEGMENTOS** (nichos de mercado)
- Permitir que usuários pertençam a um ou mais segmentos
- Fazer **cruzamentos inteligentes** (só mostrar fornecedores relevantes)

---

## 📊 Nova Arquitetura Proposta

### 1. Renomear Roles

**Antes:**
- `admin` → mantém
- `restaurante` → **mudar para `comprador`** (mais genérico)
- `fornecedor` → mantém

**Por quê?**
- "Comprador" é mais genérico que "restaurante"
- Um pet shop também é um comprador
- Uma construtora também é um comprador
- Escalável para qualquer nicho

### 2. Tabela de Segmentos

```sql
CREATE TABLE segmentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL, -- "Alimentação", "Pet Shop", "Construção"
    slug VARCHAR(100) UNIQUE NOT NULL, -- "alimentacao", "pet-shop", "construcao"
    descricao TEXT NULL,
    icone VARCHAR(50) NULL, -- nome do ícone lucide
    cor VARCHAR(20) NULL, -- cor hex para identificação visual
    ativo BOOLEAN DEFAULT true,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_slug (slug),
    INDEX idx_ativo (ativo)
);
```

**Segmentos Iniciais:**
```sql
INSERT INTO segmentos (nome, slug, descricao, icone, cor) VALUES
('Alimentação', 'alimentacao', 'Restaurantes, bares, lanchonetes e food service', 'utensils', '#16A34A'),
('Pet Shop', 'pet-shop', 'Pet shops, clínicas veterinárias e serviços pet', 'dog', '#EA580C'),
('Construção', 'construcao', 'Construtoras, materiais de construção e reformas', 'hammer', '#0284C7'),
('Varejo', 'varejo', 'Lojas, comércio e varejo em geral', 'shopping-bag', '#7C3AED'),
('Serviços', 'servicos', 'Prestadores de serviços diversos', 'briefcase', '#059669');
```

### 3. Tabela Pivot: Usuário ↔ Segmentos

```sql
CREATE TABLE user_segmentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    segmento_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (segmento_id) REFERENCES segmentos(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_user_segmento (user_id, segmento_id),
    INDEX idx_user (user_id),
    INDEX idx_segmento (segmento_id)
);
```

**Por quê pivot?**
- Um fornecedor pode atuar em MÚLTIPLOS segmentos
- Exemplo: Fornecedor de embalagens → serve Alimentação + Pet Shop + Varejo
- Um comprador também pode ter negócios em múltiplos segmentos

### 4. Atualizar Tabelas Existentes

#### Tabela `restaurantes` → renomear para `compradores`

```sql
-- Migration: renomear tabela
RENAME TABLE restaurantes TO compradores;

-- Atualizar campos
ALTER TABLE compradores 
    CHANGE COLUMN nome_estabelecimento nome_negocio VARCHAR(255) NOT NULL,
    CHANGE COLUMN tipo_cozinha tipo_negocio VARCHAR(100) NULL,
    DROP COLUMN capacidade; -- campo específico de restaurante, não genérico
```

#### Tabela `fornecedores` → manter nome, ajustar campos

```sql
ALTER TABLE fornecedores 
    DROP COLUMN categorias; -- categorias agora são segmentos
```

---

## 🔄 Fluxo de Cruzamentos

### Exemplo 1: Fornecedor Multi-Segmento

**Cenário:**
- Fornecedor: "Distribuidora de Embalagens XYZ"
- Segmentos: `['alimentacao', 'pet-shop', 'varejo']`

**Resultado:**
- Aparece para compradores de Alimentação
- Aparece para compradores de Pet Shop  
- Aparece para compradores de Varejo
- **NÃO** aparece para compradores de Construção

### Exemplo 2: Comprador Único Segmento

**Cenário:**
- Comprador: "Restaurante Sabor da Serra"
- Segmento: `['alimentacao']`

**O que vê:**
- Fornecedores com segmento `alimentacao`
- Outros compradores do segmento `alimentacao` (networking)
- Cotações do segmento `alimentacao`
- Talentos do segmento `alimentacao`

**O que NÃO vê:**
- Fornecedores só de `pet-shop`
- Compradores de outros segmentos

### Exemplo 3: Pet Shop

**Cenário:**
- Comprador: "Pet Mania Serra"
- Segmento: `['pet-shop']`

**O que vê:**
- Fornecedores de ração, remédios, brinquedos (segmento pet-shop)
- Outros pet shops da região
- Cotações de produtos pet
- Banco de talentos (se aplicável: veterinários, banhistas)

---

## 🛠️ Implementação Técnica

### Models

#### SegmentoModel.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SegmentoModel extends Model
{
    protected $table = 'segmentos';

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'icone',
        'cor',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
        ];
    }

    /**
     * Usuários deste segmento
     */
    public function usuarios()
    {
        return $this->belongsToMany(UserModel::class, 'user_segmentos', 'segmento_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Escopo: apenas segmentos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
```

#### UserModel.php (adicionar relacionamento)

```php
/**
 * Segmentos do usuário
 */
public function segmentos()
{
    return $this->belongsToMany(SegmentoModel::class, 'user_segmentos', 'user_id', 'segmento_id')
                ->withTimestamps();
}

/**
 * Verifica se usuário pertence a um segmento
 */
public function temSegmento(string $slug): bool
{
    return $this->segmentos()->where('slug', $slug)->exists();
}

/**
 * Verifica se usuário tem algum segmento em comum com outro usuário
 */
public function compartilhaSegmentoCom(UserModel $outroUsuario): bool
{
    $meusSegmentos = $this->segmentos->pluck('id');
    $segmentosOutro = $outroUsuario->segmentos->pluck('id');
    
    return $meusSegmentos->intersect($segmentosOutro)->isNotEmpty();
}
```

### Repository: Cruzamentos Inteligentes

#### UserRepository.php

```php
/**
 * Buscar fornecedores visíveis para um comprador
 * (apenas fornecedores que compartilham segmentos)
 */
public function buscarFornecedoresVisiveis(UserModel $comprador)
{
    $segmentosComprador = $comprador->segmentos->pluck('id');

    return UserModel::where('role', 'fornecedor')
        ->where('status', 'aprovado')
        ->whereHas('segmentos', function ($query) use ($segmentosComprador) {
            $query->whereIn('segmentos.id', $segmentosComprador);
        })
        ->with(['fornecedor', 'segmentos'])
        ->get();
}

/**
 * Buscar compradores visíveis para um fornecedor
 * (apenas compradores que compartilham segmentos)
 */
public function buscarCompradoresVisiveis(UserModel $fornecedor)
{
    $segmentosFornecedor = $fornecedor->segmentos->pluck('id');

    return UserModel::where('role', 'comprador')
        ->where('status', 'aprovado')
        ->whereHas('segmentos', function ($query) use ($segmentosFornecedor) {
            $query->whereIn('segmentos.id', $segmentosFornecedor);
        })
        ->with(['comprador', 'segmentos'])
        ->get();
}

/**
 * Buscar usuários de um segmento específico
 */
public function buscarPorSegmento(string $slug, ?string $role = null)
{
    $query = UserModel::where('status', 'aprovado')
        ->whereHas('segmentos', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });

    if ($role) {
        $query->where('role', $role);
    }

    return $query->with(['segmentos'])->get();
}
```

---

## 📝 Atualizar Formulários

### Cadastro Público (cadastro.blade.php)

**Adicionar seleção de segmento:**

```blade
<!-- Tipo de Perfil -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-3">
        Tipo de Perfil *
    </label>
    <div class="grid md:grid-cols-2 gap-4">
        <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all"
               :class="role === 'comprador' ? '...' : '...'">
            <input type="radio" name="role" value="comprador" x-model="role" required>
            <div class="ml-3">
                <span class="font-semibold text-gray-900">Comprador</span>
                <p class="text-sm text-gray-600">Tenho um negócio e compro produtos/serviços</p>
            </div>
        </label>

        <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-all"
               :class="role === 'fornecedor' ? '...' : '...'">
            <input type="radio" name="role" value="fornecedor" x-model="role" required>
            <div class="ml-3">
                <span class="font-semibold text-gray-900">Fornecedor</span>
                <p class="text-sm text-gray-600">Forneço produtos ou serviços</p>
            </div>
        </label>
    </div>
</div>

<!-- Segmentos -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-3">
        Segmento(s) de Atuação * <span class="text-xs text-gray-500">(selecione pelo menos um)</span>
    </label>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($segmentos as $segmento)
            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[var(--cor-verde-serra)] transition-all">
                <input 
                    type="checkbox" 
                    name="segmentos[]" 
                    value="{{ $segmento->id }}"
                    class="w-4 h-4 text-[var(--cor-verde-serra)] border-gray-300 rounded focus:ring-[var(--cor-verde-serra)]"
                >
                <i data-lucide="{{ $segmento->icone }}" class="w-5 h-5 mx-2" style="color: {{ $segmento->cor }}"></i>
                <span class="text-sm text-gray-700">{{ $segmento->nome }}</span>
            </label>
        @endforeach
    </div>
</div>
```

### Admin: Criar/Editar Usuário

Mesma lógica, permitir admin selecionar múltiplos segmentos para o usuário.

---

## 🎨 UI: Mostrar Segmentos

### Cards de Usuário (com badges de segmento)

```blade
<div class="card">
    <h3>{{ $fornecedor->fornecedor->nome_empresa }}</h3>
    
    {{-- Badges de segmento --}}
    <div class="flex flex-wrap gap-2 mt-2">
        @foreach($fornecedor->segmentos as $segmento)
            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium"
                  style="background-color: {{ $segmento->cor }}20; color: {{ $segmento->cor }};">
                <i data-lucide="{{ $segmento->icone }}" class="w-3 h-3"></i>
                {{ $segmento->nome }}
            </span>
        @endforeach
    </div>
</div>
```

### Filtros por Segmento

```blade
<div class="mb-6">
    <label class="block text-sm font-medium mb-2">Filtrar por segmento:</label>
    <div class="flex flex-wrap gap-2">
        <button @click="filtroSegmento = null" 
                :class="filtroSegmento === null ? 'bg-[var(--cor-verde-serra)] text-white' : 'bg-gray-100'"
                class="px-4 py-2 rounded-lg">
            Todos
        </button>
        @foreach($segmentos as $segmento)
            <button @click="filtroSegmento = '{{ $segmento->slug }}'"
                    :class="filtroSegmento === '{{ $segmento->slug }}' ? 'bg-[var(--cor-verde-serra)] text-white' : 'bg-gray-100'"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg">
                <i data-lucide="{{ $segmento->icone }}" class="w-4 h-4"></i>
                {{ $segmento->nome }}
            </button>
        @endforeach
    </div>
</div>
```

---

## 📊 Escalabilidade Futura

### Plataformas Multi-Segmento

**Serra 360:**
- `serrafood360.com.br` → segmento Alimentação (atual)
- `serrapets360.com.br` → segmento Pet Shop
- `serrabuild360.com.br` → segmento Construção

**Rio 360:**
- `riofood360.com.br` → Alimentação no Rio
- `riopets360.com.br` → Pet Shop no Rio

**Mesma base de código, filtros por:**
1. Segmento
2. Região/Cidade

---

## ✅ Checklist de Implementação

### Fase 1: Estrutura Base
- [ ] Criar migration `create_segmentos_table`
- [ ] Criar migration `create_user_segmentos_table`
- [ ] Criar migration `rename_restaurantes_to_compradores`
- [ ] Criar migration `update_fornecedores_remove_categorias`
- [ ] Criar migration `update_users_role_enum` (adicionar 'comprador', manter 'restaurante' temporariamente para migração)
- [ ] Criar `SegmentoModel`
- [ ] Atualizar `UserModel` com relacionamento `segmentos()`
- [ ] Renomear `RestauranteModel` para `CompradorModel`
- [ ] Criar seeder de segmentos iniciais

### Fase 2: Repositories
- [ ] Criar `SegmentoRepository`
- [ ] Atualizar `UserRepository` com métodos de cruzamento
- [ ] Atualizar `CompradorRepository` (ex-RestauranteRepository)
- [ ] Atualizar `FornecedorRepository`

### Fase 3: Services
- [ ] Criar `SegmentoService`
- [ ] Atualizar `AuthService` para lidar com segmentos no cadastro
- [ ] Atualizar `UserService` com lógica de cruzamentos

### Fase 4: Controllers
- [ ] Atualizar `AuthController` (validação de segmentos)
- [ ] Atualizar `AdminUsuariosController` (CRUD de segmentos do usuário)
- [ ] Criar `Admin\AdminSegmentosController` (CRUD de segmentos)

### Fase 5: Views
- [ ] Atualizar `cadastro.blade.php` (seleção de segmentos)
- [ ] Atualizar `admin/usuarios/criar.blade.php` (seleção de segmentos)
- [ ] Atualizar `admin/usuarios/index.blade.php` (mostrar badges de segmentos)
- [ ] Criar `admin/segmentos/index.blade.php`
- [ ] Criar `admin/segmentos/criar.blade.php`
- [ ] Atualizar cards de fornecedores/compradores (badges de segmentos)

### Fase 6: Migração de Dados
- [ ] Script para migrar usuários com `role = 'restaurante'` para `role = 'comprador'`
- [ ] Script para atribuir segmento 'alimentacao' a todos os usuários existentes
- [ ] Migrar categorias de fornecedores para segmentos (se houver overlap)

### Fase 7: Testes
- [ ] Testar cruzamentos (fornecedor só aparece para compradores do mesmo segmento)
- [ ] Testar cadastro com múltiplos segmentos
- [ ] Testar filtros por segmento

---

## 🚨 Retrocompatibilidade

### Migração Suave

1. **Manter `role = 'restaurante'` temporariamente**
   - Migration atualiza ENUM para incluir `'comprador'`
   - Script migra todos `'restaurante'` → `'comprador'`
   - Após testes, remover `'restaurante'` do ENUM

2. **Categorias → Segmentos**
   - Fornecedores com `categorias` JSON antiga
   - Criar mapeamento: `"Bebidas"` → segmento `alimentacao`
   - Script de migração automática

---

## 💡 Vantagens da Arquitetura

✅ **Escalável**: Adicionar novos segmentos sem alterar código  
✅ **Flexível**: Usuário pode atuar em múltiplos segmentos  
✅ **Inteligente**: Cruzamentos automáticos por segmento  
✅ **Multi-tenant ready**: Cada segmento pode virar uma plataforma própria  
✅ **Genérico**: "Comprador" serve para qualquer nicho, não só alimentação

---

**Esta arquitetura resolve o problema de segmentação e prepara o sistema para escalar para qualquer nicho de mercado! 🚀**
