# 🚨 START HERE - Leia ANTES de qualquer tarefa

## Checklist Obrigatório

Antes de começar QUALQUER trabalho neste projeto:

### ☑️ 1. Ler TODAS as Rules
```bash
# Listar
.cursor/rules/

# Ler cada uma:
- 0-LEIA-PRIMEIRO.mdc
- entender-planejar-executar.mdc
- laravel-backend.mdc
- frontend-blade.mdc
```

### ☑️ 2. Seguir a Metodologia
**Entender → Planejar → Executar**

NUNCA pule direto para execução.

### ☑️ 3. Confirmar com o Usuário
Antes de começar a codar:
- Confirme que entendeu a tarefa
- Apresente o plano de execução
- Aguarde aprovação

---

## 🏗️ Arquitetura do Projeto

### Backend (Laravel)
**Controller → Service → Repository → Model**

- Queries APENAS no Repository
- Lógica de negócio APENAS no Service
- Nomenclatura em PT-BR
- Sufixos: `Model`, `Service`, `Repository`, `Controller`

### Frontend (Blade + Tailwind + Alpine)
- Mobile-first OBRIGATÓRIO
- Componentes reutilizáveis (DRY)
- Variáveis CSS para cores (NUNCA hardcoded)
- NUNCA usar gradientes
- Bottom navigation no mobile

### Princípios
- **KISS** - Keep It Simple
- **SOLID** - Responsabilidade única
- **DRY** - Don't Repeat Yourself

---

## ⛔ NÃO faça:

- ❌ Duplicar código existente
- ❌ Criar controllers/views/services duplicados
- ❌ Hardcoded de cores (use variáveis CSS)
- ❌ Queries fora do Repository
- ❌ Lógica de negócio no Controller
- ❌ Começar sem ler as rules
- ❌ Pular o planejamento

---

## ✅ SEMPRE faça:

- ✅ Reutilizar código existente
- ✅ Perguntar quando tiver dúvida
- ✅ Seguir os padrões estabelecidos
- ✅ Confirmar entendimento antes de executar
- ✅ Ler TODAS as rules antes de começar

---

## 📁 Estrutura do Projeto

```
serra-food-360/
├── .cursor/rules/          ← LEIA PRIMEIRO
├── START-HERE.md          ← ESTE ARQUIVO
├── docs/                  ← Documentação do projeto
├── app/
│   ├── Http/Controllers/  ← Apenas delegação
│   ├── Services/          ← Lógica de negócio
│   ├── Repositories/      ← Queries
│   └── Models/            ← Entidades
├── resources/
│   └── views/
│       ├── layouts/       ← Estrutura base
│       ├── partials/      ← Componentes reutilizáveis
│       └── admin/         ← Views do admin
```

---

## 🎯 Lembre-se:

**As rules existem para evitar refatorações desnecessárias.**

Se você as seguir desde o início, economiza tempo e evita frustrações.

---

**Última atualização:** 15/02/2026
