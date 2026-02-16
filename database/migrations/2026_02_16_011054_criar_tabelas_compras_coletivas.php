<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Criar tabelas para sistema de Compras Coletivas
     */
    public function up(): void
    {
        // 1. Catálogo de Produtos (anti-duplicata)
        Schema::create('produtos_catalogo', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('unidade_medida'); // kg, un, cx, sc, litro, etc
            $table->foreignId('categoria_id')->nullable()->constrained('segmentos')->onDelete('set null');
            $table->string('imagem_url')->nullable();
            $table->boolean('ativo')->default(true);
            $table->integer('propostas_count')->default(0); // Contador de vezes que foi proposto
            $table->timestamps();
            
            $table->index('nome');
            $table->index(['ativo', 'categoria_id']);
        });

        // 2. Propostas de Produtos (feitas por compradores)
        Schema::create('compras_coletivas_propostas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_catalogo_id')->constrained('produtos_catalogo')->onDelete('cascade');
            $table->foreignId('proposto_por')->constrained('users')->onDelete('cascade'); // Comprador que propôs
            $table->text('justificativa')->nullable();
            $table->enum('status', ['pendente', 'em_votacao', 'aprovada', 'rejeitada'])->default('pendente');
            $table->date('data_votacao_inicio')->nullable();
            $table->date('data_votacao_fim')->nullable();
            $table->integer('votos_count')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'data_votacao_fim']);
        });

        // 3. Votos nas Propostas
        Schema::create('compras_coletivas_votos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposta_id')->constrained('compras_coletivas_propostas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('votado_em');
            
            $table->unique(['proposta_id', 'user_id']); // 1 voto por usuário por proposta
            $table->index('proposta_id');
        });

        // 4. Compras Coletivas Ativas (propostas aprovadas)
        Schema::create('compras_coletivas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_catalogo_id')->constrained('produtos_catalogo')->onDelete('cascade');
            $table->foreignId('proposta_id')->nullable()->constrained('compras_coletivas_propostas')->onDelete('set null');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->decimal('quantidade_minima', 10, 2); // Quantidade mínima para efetivar
            $table->decimal('quantidade_atual', 10, 2)->default(0);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->enum('status', ['aberta', 'em_negociacao', 'efetivada', 'cancelada'])->default('aberta');
            $table->foreignId('criado_por')->constrained('users')->onDelete('cascade');
            $table->integer('participantes_count')->default(0);
            $table->timestamps();
            
            $table->index(['status', 'data_fim']);
            $table->index('produto_catalogo_id');
        });

        // 5. Adesões dos Compradores
        Schema::create('compras_coletivas_adesoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_coletiva_id')->constrained('compras_coletivas')->onDelete('cascade');
            $table->foreignId('comprador_id')->constrained('users')->onDelete('cascade');
            $table->decimal('quantidade', 10, 2);
            $table->text('observacoes')->nullable();
            $table->enum('status', ['ativa', 'confirmada', 'cancelada'])->default('ativa');
            $table->timestamps();
            
            $table->unique(['compra_coletiva_id', 'comprador_id']);
            $table->index('compra_coletiva_id');
        });

        // 6. Ofertas dos Fornecedores
        Schema::create('compras_coletivas_ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_coletiva_id')->constrained('compras_coletivas')->onDelete('cascade');
            $table->foreignId('fornecedor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('quantidade_minima', 10, 2)->nullable();
            $table->text('condicoes')->nullable(); // Prazo de entrega, forma de pagamento, etc
            $table->enum('status', ['pendente', 'aceita', 'rejeitada'])->default('pendente');
            $table->timestamp('ofertado_em');
            
            $table->index('compra_coletiva_id');
            $table->index(['compra_coletiva_id', 'status']);
        });
    }

    /**
     * Reverter migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('compras_coletivas_ofertas');
        Schema::dropIfExists('compras_coletivas_adesoes');
        Schema::dropIfExists('compras_coletivas');
        Schema::dropIfExists('compras_coletivas_votos');
        Schema::dropIfExists('compras_coletivas_propostas');
        Schema::dropIfExists('produtos_catalogo');
    }
};
