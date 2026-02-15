# 🔄 Refatoração do Banco de Dados

## ⚠️ PROBLEMA ATUAL

Atualmente, campos como `cidade`, `telefone`, `whatsapp` estão **diretamente na tabela `users`**. Isso viola princípios de normalização e arquitetura sênior.

## ✅ SOLUÇÃO: Tabela `enderecos` Separada

### Estrutura Proposta

```sql
-- Tabela de Endereços
CREATE TABLE enderecos (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    tipo ENUM('principal', 'entrega', 'cobranca') DEFAULT 'principal',
    cep VARCHAR(9),
    logradouro VARCHAR(255),
    numero VARCHAR(20),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    pais VARCHAR(2) DEFAULT 'BR',
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    is_padrao BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_tipo (user_id, tipo),
    INDEX idx_cidade_estado (cidade, estado)
);

-- Tabela de Contatos
CREATE TABLE contatos (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    tipo ENUM('telefone', 'whatsapp', 'comercial', 'suporte') NOT NULL,
    valor VARCHAR(20) NOT NULL,
    is_principal BOOLEAN DEFAULT false,
    verificado_em TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_tipo (user_id, tipo)
);
```

## 🎯 Benefícios

### 1. **Múltiplos Endereços por Usuário**
- Endereço de cobrança
- Endereço de entrega
- Filiais (para fornecedores)

### 2. **Múltiplos Contatos**
- Vários telefones
- WhatsApp separado do telefone comercial
- Telefone de suporte

### 3. **Geolocalização**
- `latitude` e `longitude` para cálculo de distância
- Busca por proximidade
- Mapa de fornecedores/compradores

### 4. **Validação de Endereço**
- Integração com API de CEP (ViaCEP)
- Validação de cidade/estado
- Padronização

### 5. **Histórico**
- Manter endereços antigos
- Rastreabilidade de entregas

## 📋 Migration de Refatoração

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Criar tabela enderecos
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', ['principal', 'entrega', 'cobranca'])->default('principal');
            $table->string('cep', 9)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('complemento', 100)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('cidade', 100);
            $table->string('estado', 2);
            $table->string('pais', 2)->default('BR');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_padrao')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'tipo']);
            $table->index(['cidade', 'estado']);
        });

        // 2. Criar tabela contatos
        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', ['telefone', 'whatsapp', 'comercial', 'suporte']);
            $table->string('valor', 20);
            $table->boolean('is_principal')->default(false);
            $table->timestamp('verificado_em')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'tipo']);
        });

        // 3. Migrar dados existentes
        DB::transaction(function () {
            $users = DB::table('users')
                ->whereNotNull('cidade')
                ->orWhereNotNull('telefone')
                ->orWhereNotNull('whatsapp')
                ->get();

            foreach ($users as $user) {
                // Migrar endereço
                if ($user->cidade) {
                    DB::table('enderecos')->insert([
                        'user_id' => $user->id,
                        'tipo' => 'principal',
                        'cidade' => $user->cidade,
                        'estado' => 'ES', // Assumir Espírito Santo (Serra)
                        'is_padrao' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Migrar telefone
                if ($user->telefone) {
                    DB::table('contatos')->insert([
                        'user_id' => $user->id,
                        'tipo' => 'telefone',
                        'valor' => $user->telefone,
                        'is_principal' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Migrar WhatsApp
                if ($user->whatsapp) {
                    DB::table('contatos')->insert([
                        'user_id' => $user->id,
                        'tipo' => 'whatsapp',
                        'valor' => $user->whatsapp,
                        'is_principal' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        // 4. Remover colunas antigas da tabela users (DEPOIS de validar migração)
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn(['cidade', 'telefone', 'whatsapp']);
        // });
    }

    public function down(): void
    {
        Schema::dropIfExists('contatos');
        Schema::dropIfExists('enderecos');
        
        // Restaurar colunas antigas se necessário
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('cidade')->nullable();
        //     $table->string('telefone', 20)->nullable();
        //     $table->string('whatsapp', 20)->nullable();
        // });
    }
};
```

## 🔧 Models Necessários

### EnderecoModel.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnderecoModel extends Model
{
    protected $table = 'enderecos';
    
    protected $fillable = [
        'user_id',
        'tipo',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'pais',
        'latitude',
        'longitude',
        'is_padrao'
    ];

    protected $casts = [
        'is_padrao' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function usuario()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function enderecoCompleto(): string
    {
        $partes = array_filter([
            $this->logradouro,
            $this->numero,
            $this->complemento,
            $this->bairro,
            $this->cidade,
            $this->estado,
            $this->cep
        ]);

        return implode(', ', $partes);
    }

    public function calcularDistancia(float $lat, float $lng): ?float
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // Fórmula de Haversine
        $earthRadius = 6371; // km

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}
```

### ContatoModel.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContatoModel extends Model
{
    protected $table = 'contatos';
    
    protected $fillable = [
        'user_id',
        'tipo',
        'valor',
        'is_principal',
        'verificado_em'
    ];

    protected $casts = [
        'is_principal' => 'boolean',
        'verificado_em' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function formatado(): string
    {
        // Formatar telefone brasileiro
        $valor = preg_replace('/[^0-9]/', '', $this->valor);
        
        if (strlen($valor) === 11) {
            return '(' . substr($valor, 0, 2) . ') ' . substr($valor, 2, 5) . '-' . substr($valor, 7);
        }
        
        if (strlen($valor) === 10) {
            return '(' . substr($valor, 0, 2) . ') ' . substr($valor, 2, 4) . '-' . substr($valor, 6);
        }

        return $this->valor;
    }

    public function linkWhatsApp(): string
    {
        if ($this->tipo !== 'whatsapp') {
            return '#';
        }

        $numero = preg_replace('/[^0-9]/', '', $this->valor);
        return "https://wa.me/55{$numero}";
    }
}
```

## 📝 Atualizar UserModel

```php
// Adicionar relationships
public function enderecos()
{
    return $this->hasMany(EnderecoModel::class, 'user_id');
}

public function enderecoPrincipal()
{
    return $this->hasOne(EnderecoModel::class, 'user_id')
        ->where('is_padrao', true);
}

public function contatos()
{
    return $this->hasMany(ContatoModel::class, 'user_id');
}

public function whatsapp()
{
    return $this->hasOne(ContatoModel::class, 'user_id')
        ->where('tipo', 'whatsapp')
        ->where('is_principal', true);
}

public function telefone()
{
    return $this->hasOne(ContatoModel::class, 'user_id')
        ->where('tipo', 'telefone')
        ->where('is_principal', true);
}
```

## 🎯 Próximos Passos

1. ✅ Documentar refatoração (este arquivo)
2. ⏳ Criar migration `2026_XX_XX_criar_tabelas_enderecos_contatos.php`
3. ⏳ Criar models `EnderecoModel.php` e `ContatoModel.php`
4. ⏳ Criar repositories `EnderecoRepository.php` e `ContatoRepository.php`
5. ⏳ Atualizar controllers para usar novas tabelas
6. ⏳ Atualizar views para listar/editar múltiplos endereços
7. ⏳ Integrar com API ViaCEP para validação
8. ⏳ Implementar busca por proximidade geográfica
9. ⏳ Remover colunas antigas de `users` após validação

## 🚨 IMPORTANTE

**NÃO remover as colunas antigas até:**
- Migração completa e testada
- Validação em produção
- Backup do banco
- Confirmação de que tudo funciona

---

**Documentado em:** {{ date('Y-m-d H:i') }}  
**Status:** 📋 Planejado
