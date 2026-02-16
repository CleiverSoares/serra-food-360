<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Criar tabelas para sistema de cotações
     */
    public function up(): void
    {
        // 1. Tabela principal de cotações
        Schema::create('cotacoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('produto'); // Ex: Filé Mignon, Óleo de Soja
            $table->string('unidade', 20); // kg, litro, unidade, cx
            $table->decimal('quantidade_minima', 10, 2)->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->enum('status', ['ativo', 'encerrado'])->default('ativo');
            $table->foreignId('segmento_id')->constrained('segmentos')->onDelete('cascade');
            $table->foreignId('criado_por')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['status', 'segmento_id']);
            $table->index(['data_inicio', 'data_fim']);
        });

        // 2. Tabela de ofertas dos fornecedores (pivot enriquecida)
        Schema::create('cotacao_fornecedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotacao_id')->constrained('cotacoes')->onDelete('cascade');
            $table->foreignId('fornecedor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('preco_unitario', 10, 2);
            $table->text('observacoes')->nullable();
            $table->string('prazo_entrega', 50)->nullable(); // Ex: 24h, 2 dias úteis
            $table->decimal('quantidade_disponivel', 10, 2)->nullable();
            $table->boolean('destaque')->default(false); // Melhor oferta
            $table->timestamps();
            
            $table->unique(['cotacao_id', 'fornecedor_id']);
            $table->index('preco_unitario');
        });
    }

    /**
     * Reverter a migration
     */
    public function down(): void
    {
        Schema::dropIfExists('cotacao_fornecedores');
        Schema::dropIfExists('cotacoes');
    }
};
