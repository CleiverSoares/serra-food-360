# Implementação do Sistema de Talentos

**Data:** 15/02/2026  
**Fase:** 4 (Banco de Talentos)  
**Status:** ✅ Completa

---

## 📋 OBJETIVO

Criar um banco de talentos para profissionais avulsos/extras, facilitando a contratação temporária de garçons, cozinheiros, recepcionistas, bartenders, etc.

**Público-alvo:**
- Universitários procurando trabalho extra
- Profissionais de eventos
- Freelancers da área gastronômica

**Diferencial:**
- Sistema de cobrança flexível (hora ou dia)
- Filtros avançados (cargo, disponibilidade, tipo de cobrança, faixa de valor)
- Upload de currículo e cartas de recomendação
- Contato direto via WhatsApp

---

## 🎯 FUNCIONALIDADES IMPLEMENTADAS

### 1. Campos do Talento

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `nome` | string | Nome completo do profissional |
| `whatsapp` | string | Telefone com DDD para contato direto |
| `cargo` | string | Ex: Garçom, Cozinheira, Barman, Recepcionista |
| `mini_curriculo` | text | Resumo de experiências (200-500 caracteres) |
| `pretensao` | decimal | Valor em R$ (por hora ou por dia) |
| `tipo_cobranca` | enum | `'hora'` ou `'dia'` |
| `disponibilidade` | string | Ex: "Finais de semana", "Noites", "Eventos" |
| `ativo` | boolean | Status do talento (ativo/inativo) |
| `foto_path` | string | Caminho da foto (storage) |
| `curriculo_pdf_path` | string | Caminho do currículo PDF |
| `carta_recomendacao_path` | string | Caminho da carta de recomendação PDF |

---

### 2. Filtros Avançados

#### Filtro por Busca
- Busca por **nome**, **cargo** ou **telefone**
- Utilizando `LIKE %termo%` no banco

#### Filtro por Cargo
- Dropdown com valores únicos do banco
- Ex: "Garçom", "Cozinheira", "Barman"

#### Filtro por Disponibilidade
- Dropdown com valores únicos do banco
- Ex: "Finais de semana", "Durante a semana", "Noites"

#### Filtro por Tipo de Cobrança
- Dropdown com 2 opções:
  - `hora` = Por Hora ⏰
  - `dia` = Por Dia 📅

#### Filtro por Range de Valor
- **Valor Mínimo:** Input numérico (R$)
- **Valor Máximo:** Input numérico (R$)
- Query: `WHERE pretensao BETWEEN min AND max`

**Exemplo de uso:**
```
Busca: "garçom"
Tipo: "Por Hora"
Valor: R$50 - R$100
Disponibilidade: "Finais de semana"

Resultado: Garçons que cobram entre R$50 e R$100 por hora, disponíveis aos finais de semana
```

---

### 3. UI/UX

#### Cores Temáticas
Para diferenciar do resto do admin, foi usado **Amber/Laranja**:
- Botões: `bg-amber-600 hover:bg-amber-700`
- Ícones: `text-amber-600`
- Badges especiais: `bg-amber-100 text-amber-800`

#### Badges Coloridas
| Badge | Cor | Emoji | Exemplo |
|-------|-----|-------|---------|
| Ativo | Verde (`bg-green-100 text-green-800`) | ✅ | ✅ Ativo |
| Inativo | Vermelho (`bg-red-100 text-red-800`) | ❌ | ❌ Inativo |
| Por Hora | Roxo (`bg-purple-100 text-purple-800`) | ⏰ | ⏰ Hora |
| Por Dia | Azul (`bg-blue-100 text-blue-800`) | 📅 | 📅 Dia |
| Valor | Esmeralda (`bg-emerald-100 text-emerald-800`) | 💰 | 💰 R$ 80,00/h |
| Disponibilidade | Cinza (`bg-gray-100 text-gray-800`) | 📅 | 📅 Finais de semana |

#### Cards Responsivos
- **Mobile:** 1 coluna, informações empilhadas
- **Tablet:** 2 colunas
- **Desktop:** 3 colunas

**Estrutura do Card:**
```
┌─────────────────────────┐
│ [Foto]  Nome            │
│         Cargo           │
│                         │
│ ✅ Ativo  ⏰ Hora       │
│ 💰 R$ 80/h              │
│ 📅 Finais de semana     │
│                         │
│ "Mini currículo..."     │
│                         │
│ [WhatsApp] [Editar]     │
└─────────────────────────┘
```

#### Tela de Detalhes (`show.blade.php`)
- Foto grande (ou avatar placeholder)
- Badges de status, tipo cobrança, valor, disponibilidade
- Mini currículo completo
- Botões de ação:
  - 📱 Chamar no WhatsApp
  - 📄 Baixar Currículo PDF (se existir)
  - 📨 Baixar Carta de Recomendação (se existir)
  - ✏️ Editar
  - 🔴 Inativar / 🟢 Ativar
  - 🗑️ Deletar

---

### 4. CRUD Completo

#### Criar (`create.blade.php`)
- Formulário com todos os campos
- Upload de arquivos (foto, currículo PDF, carta PDF)
- Validação:
  - Nome: obrigatório, min 3 caracteres
  - WhatsApp: obrigatório, formato celular
  - Cargo: obrigatório, min 3 caracteres
  - Mini currículo: obrigatório, max 1000 caracteres
  - Tipo de cobrança: obrigatório, enum (`hora` ou `dia`)
  - Pretensão: opcional, numérico, min 0
  - Foto: opcional, max 2MB, jpg/jpeg/png
  - Currículo PDF: opcional, max 5MB, pdf
  - Carta PDF: opcional, max 5MB, pdf

#### Editar (`edit.blade.php`)
- Formulário pré-preenchido com dados atuais
- Upload de novos arquivos (substitui os antigos)
- Visualização dos arquivos atuais (links para download)
- Mesmas validações do `create`

#### Ativar/Inativar
- Atualiza campo `ativo` (boolean)
- Não deleta do banco, apenas oculta da listagem pública
- Admin sempre vê (com badge vermelho "❌ Inativo")

#### Deletar
- Remove o registro do banco de dados
- **Remove automaticamente os arquivos anexados:**
  - `foto_path`
  - `curriculo_pdf_path`
  - `carta_recomendacao_path`
- Confirmação obrigatória via JavaScript (`confirm()`)

---

## 🗂️ ARQUITETURA

### Camada de Dados

**Migration:**
```php
2014_10_12_000004_create_talentos_table.php
2026_02_15_054103_add_ativo_and_disponibilidade_to_talentos_table.php
2026_02_15_055044_add_tipo_cobranca_to_talentos_table.php
```

**Model:**
```php
app/Models/TalentoModel.php

- Table: talentos
- Fillable: todos os campos
- Casts:
  - pretensao: decimal:2
  - ativo: boolean
- Relationships: nenhum (talentos são gerenciados apenas pelo admin)
```

**Repository:**
```php
app/Repositories/TalentoRepository.php

- buscarPorId(int $id): ?TalentoModel
- listarAtivos(): Collection
- listarTodos(): Collection
- salvar(array $dados): TalentoModel
- atualizar(int $id, array $dados): TalentoModel
- deletar(int $id): bool
- buscarComFiltros(array $filtros): LengthAwarePaginator
```

---

### Camada de Negócio

**Controller:**
```php
app/Http/Controllers/Admin/AdminTalentosController.php

Rotas:
- GET    /admin/talentos              → index   (listar com filtros)
- GET    /admin/talentos/{id}         → show    (detalhes)
- GET    /admin/talentos/criar        → create  (formulário)
- POST   /admin/talentos              → store   (salvar novo)
- GET    /admin/talentos/{id}/editar  → edit    (formulário)
- PUT    /admin/talentos/{id}         → update  (salvar edição)
- DELETE /admin/talentos/{id}         → destroy (deletar)
- POST   /admin/talentos/{id}/ativar  → ativar  (ativar)
- POST   /admin/talentos/{id}/inativar→ inativar(inativar)
```

**Lógica de Filtros:**
```php
// Dentro de index()
$query = TalentoModel::query();

// Filtro de busca (nome, cargo, whatsapp)
if ($busca) {
    $query->where(function($q) use ($busca) {
        $q->where('nome', 'LIKE', "%{$busca}%")
          ->orWhere('cargo', 'LIKE', "%{$busca}%")
          ->orWhere('whatsapp', 'LIKE', "%{$busca}%");
    });
}

// Filtro por cargo
if ($cargo) {
    $query->where('cargo', $cargo);
}

// Filtro por disponibilidade
if ($disponibilidade) {
    $query->where('disponibilidade', $disponibilidade);
}

// Filtro por tipo de cobrança
if ($tipoCobranca) {
    $query->where('tipo_cobranca', $tipoCobranca);
}

// Filtro por valor mínimo
if ($valorMin !== '' && is_numeric($valorMin)) {
    $query->where('pretensao', '>=', $valorMin);
}

// Filtro por valor máximo
if ($valorMax !== '' && is_numeric($valorMax)) {
    $query->where('pretensao', '<=', $valorMax);
}

// Ordenação e paginação
$talentos = $query->orderBy('created_at', 'desc')->paginate(12);
```

---

### Camada de Apresentação

**Views:**
```
resources/views/admin/talentos/
├── index.blade.php   → Lista com filtros
├── show.blade.php    → Detalhes do talento
├── create.blade.php  → Formulário de criação
└── edit.blade.php    → Formulário de edição
```

**Layout:**
```blade
@extends('layouts.dashboard')

@section('content')
    <!-- Conteúdo específico da página -->
@endsection
```

---

## 📦 STORAGE DE ARQUIVOS

### Estrutura de Pastas

```
storage/app/public/
└── talentos/
    ├── fotos/
    │   ├── 1_foto.jpg
    │   ├── 2_foto.png
    │   └── ...
    ├── curriculos/
    │   ├── 1_curriculo.pdf
    │   ├── 2_curriculo.pdf
    │   └── ...
    └── cartas/
        ├── 1_carta.pdf
        ├── 2_carta.pdf
        └── ...
```

### Upload de Arquivos

**Código no Controller:**
```php
// Upload de foto
if ($request->hasFile('foto')) {
    $fotoPath = $request->file('foto')->store('talentos/fotos', 'public');
    $validated['foto_path'] = $fotoPath;
}

// Upload de currículo PDF
if ($request->hasFile('curriculo_pdf')) {
    $curriculoPath = $request->file('curriculo_pdf')->store('talentos/curriculos', 'public');
    $validated['curriculo_pdf_path'] = $curriculoPath;
}

// Upload de carta de recomendação
if ($request->hasFile('carta_recomendacao')) {
    $cartaPath = $request->file('carta_recomendacao')->store('talentos/cartas', 'public');
    $validated['carta_recomendacao_path'] = $cartaPath;
}
```

### Remoção de Arquivos

**Ao deletar talento:**
```php
public function destroy($id)
{
    $talento = TalentoModel::findOrFail($id);

    // Remover foto
    if ($talento->foto_path && Storage::disk('public')->exists($talento->foto_path)) {
        Storage::disk('public')->delete($talento->foto_path);
    }

    // Remover currículo
    if ($talento->curriculo_pdf_path && Storage::disk('public')->exists($talento->curriculo_pdf_path)) {
        Storage::disk('public')->delete($talento->curriculo_pdf_path);
    }

    // Remover carta
    if ($talento->carta_recomendacao_path && Storage::disk('public')->exists($talento->carta_recomendacao_path)) {
        Storage::disk('public')->delete($talento->carta_recomendacao_path);
    }

    $talento->delete();
}
```

**Ao atualizar talento (substitui arquivos antigos):**
```php
// Se upload novo, deleta o antigo
if ($request->hasFile('foto') && $talento->foto_path) {
    Storage::disk('public')->delete($talento->foto_path);
}
```

---

## 🌐 INTEGRAÇÃO WHATSAPP

### Formato do Link

```blade
<a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $talento->whatsapp) }}"
   target="_blank"
   class="...">
    Chamar no WhatsApp
</a>
```

**Exemplo:**
- Telefone cadastrado: `(54) 99123-4567`
- Regex remove parênteses, espaços, traços: `5499123456`
- Link final: `https://wa.me/555499123456`

---

## 📊 DADOS DE TESTE

### Seeder: `PopularTalentos.php`

**Comando Artisan:**
```bash
php artisan popular:talentos
```

**O que faz:**
1. Limpa a tabela `talentos` (`truncate`)
2. Insere 10 talentos de teste:
   - 5 cobram por hora (`tipo_cobranca = 'hora'`)
   - 5 cobram por dia (`tipo_cobranca = 'dia'`)
   - 8 ativos, 1 inativo
   - Cargos variados: Garçom, Cozinheira, Auxiliar, Recepcionista, Barman, Gerente, Sommelier, Confeiteira, Chapeiro, Cumim
   - Valores entre R$50 e R$350
   - Disponibilidades diversas

**Talentos incluídos:**
1. João Silva (Garçom, R$80/h, Finais de semana)
2. Maria Santos (Cozinheira, R$200/dia, Durante a semana)
3. Pedro Oliveira (Auxiliar de Cozinha, R$50/h, Noites)
4. Ana Costa (Recepcionista, R$60/h, Eventos)
5. Lucas Ferreira (Barman, R$250/dia, Finais de semana e eventos)
6. Fernanda Lima (Gerente de Salão, R$300/dia, Qualquer dia)
7. Rafael Alves (Sommelier, R$100/h, Eventos e jantares especiais)
8. Juliana Martins (Confeiteira, R$180/dia, Durante a semana)
9. Roberto Mendes Silva (Chapeiro, R$70/h, Noites e finais de semana) - **Inativo**
10. Camila Rodrigues Pinto (Cumim, R$45/h, Finais de semana)

---

## ✅ CHECKLIST DE IMPLEMENTAÇÃO

### Backend
- [x] Migration: `create_talentos_table`
- [x] Migration: `add_ativo_and_disponibilidade_to_talentos_table`
- [x] Migration: `add_tipo_cobranca_to_talentos_table`
- [x] Model: `TalentoModel` com fillable, casts
- [x] Repository: `TalentoRepository` com métodos CRUD
- [x] Controller: `AdminTalentosController` com todas as rotas
- [x] Rotas: `/admin/talentos/*` no `web.php`
- [x] Validação de dados nos métodos `store()` e `update()`
- [x] Upload de arquivos (foto, PDFs)
- [x] Remoção automática de arquivos ao deletar

### Frontend
- [x] View: `index.blade.php` com lista e filtros
- [x] View: `show.blade.php` com detalhes completos
- [x] View: `create.blade.php` com formulário de criação
- [x] View: `edit.blade.php` com formulário de edição
- [x] Badges coloridas (status, tipo cobrança, valor)
- [x] Cards responsivos (mobile/tablet/desktop)
- [x] Botão WhatsApp em cada card/detalhe
- [x] Links para download de PDFs
- [x] Placeholder de avatar quando não houver foto
- [x] Cores temáticas (Amber/Laranja)
- [x] Ícones Lucide (User, Phone, FileText, etc.)

### UX
- [x] Filtros avançados (busca, cargo, disponibilidade, tipo cobrança, range valor)
- [x] Paginação (12 por página)
- [x] Mensagens de sucesso/erro (flash messages)
- [x] Confirmação antes de deletar (JavaScript)
- [x] Responsividade mobile-first
- [x] Touch targets mínimo 44px
- [x] Formulários com placeholders e helper texts

### Integração
- [x] Link no menu admin (sidebar + bottom nav)
- [x] Ícone no menu: `users` (Lucide)
- [x] Cor do menu: Amber (`bg-amber-600`)
- [x] Contadores no dashboard admin (se aplicável)

### Testes
- [x] Seeder: `PopularTalentos` com 10 talentos
- [x] Seeder: `DadosTesteSeeder` com talentos incluídos
- [x] Teste manual: criar, editar, listar, filtrar, deletar
- [x] Teste manual: upload de foto e PDFs
- [x] Teste manual: WhatsApp funcionando
- [x] Teste manual: responsividade mobile/desktop

---

## 🚀 PRÓXIMAS MELHORIAS (v2)

### Funcionalidades Futuras
- [ ] **Área pública de talentos:** Compradores veem lista de talentos (não admin)
- [ ] **Geolocalização:** Filtro por cidade/região
- [ ] **Avaliações:** Sistema de estrelas/reviews por comprador
- [ ] **Favoritos:** Compradores salvam talentos favoritos
- [ ] **Notificações:** Admin recebe notificação quando talento é contatado
- [ ] **Disponibilidade avançada:** Calendário de disponibilidade
- [ ] **Portfólio:** Fotos de pratos/eventos que o talento trabalhou
- [ ] **Certificações:** Upload de certificados (PVPS, manipulação de alimentos)
- [ ] **Idiomas:** Campo para idiomas falados
- [ ] **Auto-cadastro:** Talentos se cadastram sozinhos (aguardando aprovação admin)

### Melhorias de UI
- [ ] **Dark mode:** Suporte a tema escuro
- [ ] **Gráficos:** Dashboard com estatísticas de talentos (cargos mais buscados, faixa salarial)
- [ ] **Exportação:** Exportar lista de talentos em Excel/PDF
- [ ] **Compartilhamento:** Compartilhar perfil de talento via link

---

## 📝 OBSERVAÇÕES IMPORTANTES

1. **Talentos não têm login próprio:**
   - Apenas o admin gerencia os talentos
   - Contato é sempre via WhatsApp
   - Futuro: permitir auto-cadastro com aprovação

2. **Arquivos são obrigatórios?**
   - Foto: opcional (usa placeholder se não houver)
   - Currículo PDF: opcional (mas recomendado)
   - Carta de recomendação: opcional

3. **Cobrança por hora vs por dia:**
   - Flexibilidade para o talento
   - Facilita busca específica para eventos pontuais (hora) ou diárias completas (dia)
   - Alguns talentos aceitam ambos (mas o cadastro define apenas 1)

4. **Disponibilidade é texto livre:**
   - Não é dropdown no cadastro (é input livre)
   - Isso permite flexibilidade ("Noites de sexta e sábado", "Apenas eventos especiais")
   - No filtro, mostra valores únicos já cadastrados

5. **Mobile-first:**
   - Todos os formulários, listas e detalhes são totalmente responsivos
   - Touch targets de 44px mínimo
   - Botões grandes e espaçados

---

**Última atualização:** 15/02/2026 às 06:00  
**Versão:** 1.0 (Fase 4 Completa)  
**Próximo passo:** Implementar UI de segmentos nos formulários de cadastro (Fase 1.1 pendente)
