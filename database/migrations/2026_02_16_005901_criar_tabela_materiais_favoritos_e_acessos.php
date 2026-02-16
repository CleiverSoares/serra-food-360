<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Criar tabelas auxiliares para materiais
     */
    public function up(): void
    {
        // Favoritos dos usuários
        Schema::create('materiais_favoritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materiais')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'material_id']);
            $table->index('user_id');
        });

        // Tracking de acessos (para analytics)
        Schema::create('materiais_acessos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materiais')->onDelete('cascade');
            $table->enum('tipo_acesso', ['visualizacao', 'download']);
            $table->timestamp('acessado_em');
            
            $table->index(['material_id', 'tipo_acesso']);
            $table->index(['user_id', 'acessado_em']);
        });
    }

    /**
     * Reverter migration
     */
    public function down(): void
    {
        Schema::dropIfExists('materiais_acessos');
        Schema::dropIfExists('materiais_favoritos');
    }
};
