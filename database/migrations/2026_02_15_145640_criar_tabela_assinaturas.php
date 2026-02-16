<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Criar tabela de assinaturas
     */
    public function up(): void
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com usuário
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            
            // Tipo de plano (conforme doc: Comum X e VIP 2X)
            $table->enum('plano', ['comum', 'vip']);
            
            // Tipo de pagamento
            $table->enum('tipo_pagamento', ['mensal', 'anual']);
            
            // Datas da assinatura
            $table->date('data_inicio');
            $table->date('data_fim');
            
            // Status
            $table->enum('status', ['ativo', 'pendente', 'vencido', 'cancelado'])
                ->default('ativo');
            
            // Controle de avisos
            $table->timestamp('ultimo_aviso_enviado')->nullable();
            
            // Auditoria
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'status']);
            $table->index(['data_fim', 'status']);
        });
    }

    /**
     * Reverter migration
     */
    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};
