<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Criar tabela de materiais de gestão
     */
    public function up(): void
    {
        Schema::create('materiais', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->enum('categoria', [
                'financeiro',
                'pessoas',
                'cardapio',
                'seguranca-alimentar',
                'marketing',
                'legislacao',
                'logistica',
                'inovacao'
            ]);
            $table->enum('tipo', ['arquivo', 'video', 'link']);
            $table->string('arquivo_path')->nullable(); // Se tipo=arquivo
            $table->string('video_url')->nullable(); // Se tipo=video (YouTube)
            $table->string('link_externo')->nullable(); // Se tipo=link
            $table->string('thumbnail_url')->nullable();
            $table->boolean('apenas_vip')->default(false);
            $table->boolean('ativo')->default(true);
            $table->integer('views_count')->default(0);
            $table->integer('downloads_count')->default(0);
            $table->foreignId('criado_por')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['categoria', 'ativo']);
            $table->index('apenas_vip');
        });
    }

    /**
     * Reverter migration
     */
    public function down(): void
    {
        Schema::dropIfExists('materiais');
    }
};
