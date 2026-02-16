<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Criar tabela de histórico de alterações de preços dos planos
     */
    public function up(): void
    {
        Schema::create('historico_precos_planos', function (Blueprint $table) {
            $table->id();
            
            // Configuração alterada
            $table->string('chave_configuracao');
            
            // Valores
            $table->decimal('valor_antigo', 10, 2)->nullable();
            $table->decimal('valor_novo', 10, 2);
            
            // Auditoria
            $table->foreignId('alterado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->timestamp('created_at');
            
            // Índices
            $table->index('chave_configuracao');
            $table->index('alterado_por');
            $table->index('created_at');
        });
    }

    /**
     * Reverter migration
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_precos_planos');
    }
};
