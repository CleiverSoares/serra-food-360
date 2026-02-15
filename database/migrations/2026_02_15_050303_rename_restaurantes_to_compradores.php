<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renomear tabela
        Schema::rename('restaurantes', 'compradores');
        
        // Atualizar campos para serem mais genéricos
        Schema::table('compradores', function (Blueprint $table) {
            $table->renameColumn('nome_estabelecimento', 'nome_negocio');
            $table->renameColumn('tipo_cozinha', 'tipo_negocio');
            $table->dropColumn('capacidade'); // campo específico de restaurante
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter alterações de colunas
        Schema::table('compradores', function (Blueprint $table) {
            $table->renameColumn('nome_negocio', 'nome_estabelecimento');
            $table->renameColumn('tipo_negocio', 'tipo_cozinha');
            $table->integer('capacidade')->nullable()->after('tipo_cozinha');
        });
        
        // Reverter renomeação da tabela
        Schema::rename('compradores', 'restaurantes');
    }
};
