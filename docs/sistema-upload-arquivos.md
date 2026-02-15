# Sistema de Upload de Arquivos

## 📁 Visão Geral

O projeto armazena **todos os arquivos no servidor** usando Laravel Storage.

**Não usamos URLs externas** para fotos, PDFs e documentos.

---

## 🗂️ Estrutura de Diretórios

```
storage/app/public/
├── talentos/
│   ├── fotos/           → Fotos dos profissionais (JPG, PNG, WEBP)
│   ├── curriculos/      → Currículos em PDF
│   └── cartas/          → Cartas de recomendação PDF
├── restaurantes/
│   └── logos/           → Logos dos restaurantes (PNG, JPG)
├── fornecedores/
│   └── logos/           → Logos dos fornecedores (PNG, JPG)
├── classificados/
│   └── equipamentos/    → Fotos de equipamentos à venda (JPG, PNG)
└── gestao/
    └── materiais/       → PDFs de gestão (DRE, CMV, etc.)
```

### Link Simbólico

**Comando obrigatório após setup:**
```bash
php artisan storage:link
```

**O que faz:**
- Cria link simbólico: `public/storage → storage/app/public`
- Permite acesso público via: `/storage/caminho/arquivo.jpg`

**Verificar se existe:**
```bash
ls -la public/storage  # Linux/Mac
dir public\storage      # Windows
```

---

## 📤 Upload de Arquivos

### No Controller

```php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminTalentosController extends Controller
{
    public function store(Request $request)
    {
        // Validação
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'curriculo_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);
        
        $talento = new Talento();
        $talento->nome = $request->nome;
        // ... outros campos
        
        // Upload da foto
        if ($request->hasFile('foto')) {
            $fileName = $talento->id . '-' . Str::slug($request->nome) . '.' . $request->file('foto')->extension();
            $path = $request->file('foto')->storeAs('talentos/fotos', $fileName, 'public');
            $talento->foto_path = $path;
        }
        
        // Upload do currículo
        if ($request->hasFile('curriculo_pdf')) {
            $fileName = $talento->id . '-curriculo-' . Str::slug($request->nome) . '.pdf';
            $path = $request->file('curriculo_pdf')->storeAs('talentos/curriculos', $fileName, 'public');
            $talento->curriculo_pdf_path = $path;
        }
        
        $talento->save();
        
        return redirect()->route('admin.talentos.index')
            ->with('success', 'Talento cadastrado com sucesso!');
    }
    
    public function update(Request $request, $id)
    {
        $talento = Talento::findOrFail($id);
        
        // ... validação
        
        // Se nova foto foi enviada
        if ($request->hasFile('foto')) {
            // Deleta foto antiga
            if ($talento->foto_path) {
                Storage::disk('public')->delete($talento->foto_path);
            }
            
            // Salva nova foto
            $fileName = $talento->id . '-' . Str::slug($request->nome) . '.' . $request->file('foto')->extension();
            $path = $request->file('foto')->storeAs('talentos/fotos', $fileName, 'public');
            $talento->foto_path = $path;
        }
        
        $talento->save();
    }
    
    public function destroy($id)
    {
        $talento = Talento::findOrFail($id);
        
        // Deleta todos os arquivos
        if ($talento->foto_path) {
            Storage::disk('public')->delete($talento->foto_path);
        }
        if ($talento->curriculo_pdf_path) {
            Storage::disk('public')->delete($talento->curriculo_pdf_path);
        }
        if ($talento->carta_recomendacao_path) {
            Storage::disk('public')->delete($talento->carta_recomendacao_path);
        }
        
        $talento->delete();
    }
}
```

---

## 🖼️ Exibição no Frontend

### Imagens (Blade)

```blade
{{-- Com tratamento de erro --}}
<img src="{{ $talento->foto_path ? Storage::url($talento->foto_path) : '/images/placeholder-talento.png' }}" 
     alt="{{ $talento->nome }}"
     class="w-full h-full object-cover rounded-xl">

{{-- Método alternativo --}}
@if($talento->foto_path && Storage::disk('public')->exists($talento->foto_path))
    <img src="{{ Storage::url($talento->foto_path) }}" alt="{{ $talento->nome }}">
@else
    <div class="placeholder">
        <i data-lucide="user"></i>
    </div>
@endif
```

### Links para PDFs

```blade
@if($talento->curriculo_pdf_path)
    <a href="{{ Storage::url($talento->curriculo_pdf_path) }}" 
       target="_blank" 
       rel="noopener"
       class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg">
        <i data-lucide="file-text" class="w-4 h-4"></i>
        Ver Currículo PDF
    </a>
@endif
```

---

## 🎨 Otimização de Imagens

### Package Recomendado

```bash
composer require intervention/image
```

### Configuração

```php
// config/app.php
'providers' => [
    Intervention\Image\ImageServiceProvider::class,
],

'aliases' => [
    'Image' => Intervention\Image\Facades\Image::class,
],
```

### Uso no Controller

```php
use Intervention\Image\Facades\Image;

public function store(Request $request)
{
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $fileName = uniqid() . '-' . Str::slug($request->nome) . '.jpg';
        $path = storage_path('app/public/talentos/fotos/' . $fileName);
        
        // Redimensiona e otimiza
        $image = Image::make($file);
        $image->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();  // Mantém proporção
            $constraint->upsize();       // Não aumenta se menor
        });
        $image->encode('jpg', 85);       // Comprime para 85%
        $image->save($path);
        
        $talento->foto_path = 'talentos/fotos/' . $fileName;
    }
}
```

### Benefícios

- ✅ Reduz tamanho do arquivo (performance)
- ✅ Padroniza dimensões
- ✅ Converte para JPG (menor que PNG)
- ✅ Melhora carregamento da página

---

## 🔒 Segurança

### Validações Importantes

```php
// NUNCA aceitar tipos perigosos
'arquivo' => 'mimes:jpeg,png,pdf|max:5120',

// NUNCA sem limite de tamanho
'foto' => 'image|max:2048', // sempre definir max

// Sanitizar nome do arquivo
$fileName = Str::slug($request->nome) . '.' . $file->extension();

// NUNCA usar nome original do usuário diretamente
// $file->getClientOriginalName() pode ter ../../../etc
```

### Proteção contra Directory Traversal

```php
// ❌ NUNCA fazer isso
$path = 'talentos/fotos/' . $request->filename;

// ✅ SEMPRE usar métodos seguros do Laravel
$path = $request->file('foto')->store('talentos/fotos', 'public');
// ou
$path = $request->file('foto')->storeAs('talentos/fotos', $safeFileName, 'public');
```

---

## 📊 Tamanhos Recomendados

### Imagens

| Tipo | Dimensão Recomendada | Max Size | Formato |
|------|----------------------|----------|---------|
| Foto Talento | 800x800px | 2MB | JPG/WEBP |
| Logo Restaurante | 400x400px | 1MB | PNG/JPG |
| Logo Fornecedor | 400x400px | 1MB | PNG/JPG |
| Foto Equipamento | 1200x900px | 2MB | JPG |
| Hero Landing | 1920x1080px | 500KB | JPG |

### Documentos

| Tipo | Max Size | Formato |
|------|----------|---------|
| Currículo PDF | 5MB | PDF |
| Carta Recomendação | 5MB | PDF |
| Material Gestão | 10MB | PDF |

---

## 🎯 Model com Arquivos

### Exemplo: Model Talento

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Talento extends Model
{
    protected $table = 'talentos';
    
    protected $fillable = [
        'nome', 'email', 'telefone', 'whatsapp',
        'cargo', 'cargo_outro',
        'mini_curriculo', 'pretensao_salarial',
        'dias_disponiveis', 'horarios_disponiveis',
        'foto_path', 'curriculo_pdf_path', 'carta_recomendacao_path',
        'ativo'
    ];
    
    protected $casts = [
        'dias_disponiveis' => 'array',
        'pretensao_salarial' => 'decimal:2',
        'ativo' => 'boolean',
    ];
    
    // Accessor para URL pública da foto
    public function getFotoUrlAttribute()
    {
        return $this->foto_path 
            ? Storage::url($this->foto_path) 
            : '/images/placeholder-talento.png';
    }
    
    // Accessor para URL do currículo
    public function getCurriculoUrlAttribute()
    {
        return $this->curriculo_pdf_path 
            ? Storage::url($this->curriculo_pdf_path) 
            : null;
    }
    
    // Deleta arquivos ao deletar registro
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($talento) {
            if ($talento->foto_path) {
                Storage::disk('public')->delete($talento->foto_path);
            }
            if ($talento->curriculo_pdf_path) {
                Storage::disk('public')->delete($talento->curriculo_pdf_path);
            }
            if ($talento->carta_recomendacao_path) {
                Storage::disk('public')->delete($talento->carta_recomendacao_path);
            }
        });
    }
    
    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
    
    public function scopeCargo($query, $cargo)
    {
        return $query->where('cargo', $cargo);
    }
}
```

### Uso no Blade

```blade
{{-- Usando accessor --}}
<img src="{{ $talento->foto_url }}" alt="{{ $talento->nome }}">

{{-- Link PDF --}}
@if($talento->curriculo_url)
    <a href="{{ $talento->curriculo_url }}" target="_blank">
        Ver Currículo
    </a>
@endif
```

---

## 🧹 Limpeza de Arquivos Órfãos

### Comando Artisan (criar)

```php
// app/Console/Commands/LimparArquivosOrfaos.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Talento;

class LimparArquivosOrfaos extends Command
{
    protected $signature = 'storage:limpar-orfaos';
    protected $description = 'Remove arquivos sem registro no banco';
    
    public function handle()
    {
        $disk = Storage::disk('public');
        
        // Lista todos os arquivos de fotos
        $arquivos = $disk->files('talentos/fotos');
        
        // Pega todos os paths do banco
        $pathsValidos = Talento::whereNotNull('foto_path')
            ->pluck('foto_path')
            ->toArray();
        
        $deletados = 0;
        foreach ($arquivos as $arquivo) {
            if (!in_array($arquivo, $pathsValidos)) {
                $disk->delete($arquivo);
                $deletados++;
            }
        }
        
        $this->info("Arquivos órfãos deletados: {$deletados}");
    }
}
```

**Executar:**
```bash
php artisan storage:limpar-orfaos
```

---

## 🔐 Segurança e Boas Práticas

### ✅ FAZER

1. **Validar tipo de arquivo**
   ```php
   'foto' => 'image|mimes:jpeg,png,jpg,webp'
   'pdf' => 'mimes:pdf'
   ```

2. **Limitar tamanho**
   ```php
   'foto' => 'max:2048'  // 2MB
   ```

3. **Sanitizar nomes**
   ```php
   $fileName = Str::slug($nome) . '.' . $extension;
   ```

4. **Usar Storage facade**
   ```php
   Storage::disk('public')->put($path, $content);
   ```

5. **Deletar ao remover registro**
   ```php
   Storage::disk('public')->delete($oldPath);
   ```

6. **Verificar se arquivo existe**
   ```php
   Storage::disk('public')->exists($path);
   ```

### ❌ NÃO FAZER

1. **Usar nome original do arquivo**
   ```php
   // ❌ PERIGOSO
   $file->getClientOriginalName();
   
   // ✅ SEGURO
   Str::slug($nome) . '.' . $file->extension();
   ```

2. **Salvar sem validação**
   ```php
   // ❌ PERIGOSO
   $file->move($destination, $fileName);
   
   // ✅ SEGURO
   $validated = $request->validate([...]);
   $file->store('path', 'public');
   ```

3. **Permitir qualquer tipo**
   ```php
   // ❌ PERIGOSO
   'arquivo' => 'file'
   
   // ✅ SEGURO
   'arquivo' => 'mimes:jpeg,png,pdf|max:5120'
   ```

4. **Expor paths reais**
   ```php
   // ❌ NÃO fazer
   <img src="/storage/app/public/talentos/fotos/1.jpg">
   
   // ✅ CORRETO
   <img src="{{ Storage::url($talento->foto_path) }}">
   ```

---

## 🖼️ Placeholders

### Quando usar

- Usuário sem foto
- Arquivo deletado/corrompido
- Erro no carregamento

### Criar placeholders

```
public/images/
├── placeholder-talento.png      → Avatar genérico profissional
├── placeholder-restaurante.png  → Fachada genérica
├── placeholder-fornecedor.png   → Logo genérico
├── placeholder-equipamento.png  → Equipamento genérico
└── placeholder-user.png         → Avatar padrão
```

### No código

```blade
<img src="{{ $talento->foto_path ? Storage::url($talento->foto_path) : '/images/placeholder-talento.png' }}" 
     alt="{{ $talento->nome }}"
     onerror="this.src='/images/placeholder-talento.png'">
```

---

## 📦 Backup de Arquivos

### Estratégia

1. **Backup regular do storage/**
   - Junto com backup do banco de dados
   - Pode usar S3, Dropbox, Google Drive

2. **Laravel Backup Package** (recomendado)
   ```bash
   composer require spatie/laravel-backup
   ```

3. **Cron job**
   ```php
   // app/Console/Kernel.php
   $schedule->command('backup:run')->daily()->at('03:00');
   ```

---

## 🚀 Performance

### Otimizações

1. **Intervention Image**
   - Redimensionar no upload
   - Comprimir qualidade (85%)
   - Converter para formato otimizado

2. **Lazy Loading**
   ```blade
   <img src="..." loading="lazy">
   ```

3. **WebP quando possível**
   ```php
   $image->encode('webp', 85);
   ```

4. **CDN (v2)**
   - Servir arquivos via CDN
   - Reduz latência
   - Economiza banda do servidor

---

## 📋 Checklist de Implementação

### Setup Inicial

- [ ] Criar estrutura de diretórios em `storage/app/public/`
- [ ] Executar `php artisan storage:link`
- [ ] Verificar permissões (775 ou 755)
- [ ] Testar upload básico

### Por Módulo

**Talentos:**
- [ ] Upload de foto
- [ ] Upload de currículo PDF
- [ ] Upload de carta de recomendação
- [ ] Placeholders

**Restaurantes/Fornecedores:**
- [ ] Upload de logo
- [ ] Placeholders

**Classificados:**
- [ ] Upload de foto de equipamento
- [ ] Múltiplas fotos (opcional v2)
- [ ] Placeholders

**Material de Gestão:**
- [ ] Upload de PDFs
- [ ] Vídeos (YouTube embed - sem upload)

---

## 🛠️ Comandos Úteis

```bash
# Criar link simbólico
php artisan storage:link

# Ver tamanho do storage
du -sh storage/app/public

# Limpar cache de arquivos
php artisan cache:clear

# Permissões corretas
chmod -R 775 storage
chown -R www-data:www-data storage  # Linux

# Listar arquivos
php artisan tinker
Storage::disk('public')->files('talentos/fotos');
```

---

## 📝 Exemplo Completo - Formulário de Upload

### Blade (Admin)

```blade
<form action="{{ route('admin.talentos.store') }}" 
      method="POST" 
      enctype="multipart/form-data">
    @csrf
    
    <div class="mb-4">
        <label for="nome">Nome Completo</label>
        <input type="text" name="nome" id="nome" required>
    </div>
    
    <div class="mb-4">
        <label for="foto">Foto (opcional)</label>
        <input type="file" 
               name="foto" 
               id="foto" 
               accept="image/jpeg,image/png,image/jpg,image/webp">
        <p class="text-xs text-[var(--cor-texto-muted)]">
            Máximo 2MB. Formatos: JPG, PNG, WEBP
        </p>
        
        {{-- Preview --}}
        <img id="foto-preview" class="hidden mt-2 w-32 h-32 rounded-xl object-cover">
    </div>
    
    <div class="mb-4">
        <label for="curriculo_pdf">Currículo PDF (opcional)</label>
        <input type="file" 
               name="curriculo_pdf" 
               id="curriculo_pdf" 
               accept="application/pdf">
        <p class="text-xs text-[var(--cor-texto-muted)]">
            Máximo 5MB
        </p>
    </div>
    
    <button type="submit" class="btn-primary">
        Cadastrar Talento
    </button>
</form>

<script>
// Preview da foto antes de enviar
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('foto-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
```

---

## 🎯 Resumo

### Todos os arquivos do projeto são armazenados em:
```
storage/app/public/ (privado)
    ↓ (link simbólico)
public/storage/ (acesso público)
```

### Acesso via:
```php
Storage::url($path)  // Retorna: /storage/talentos/fotos/1-joao.jpg
```

### Operações principais:
```php
// Upload
$path = $file->store('pasta', 'public');

// Upload com nome customizado
$path = $file->storeAs('pasta', $nome, 'public');

// Deletar
Storage::disk('public')->delete($path);

// Verificar existência
Storage::disk('public')->exists($path);

// Baixar
return Storage::disk('public')->download($path);
```

---

**Sistema de upload completo e seguro!** 📁✨

Todos os arquivos ficam no servidor, otimizados e protegidos.
