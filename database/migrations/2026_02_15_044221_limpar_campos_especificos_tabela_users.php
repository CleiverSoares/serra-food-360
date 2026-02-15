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
        Schema::table('users', function (Blueprint $table) {
            // Remove campos que agora estão em tabelas específicas
            $table->dropColumn([
                'nome_estabelecimento',
                'tipo_negocio',
                'categorias',
                'descricao',
                'logo_path',
                'site_url',
                'colaboradores'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restaura campos se necessário
            $table->string('nome_estabelecimento')->nullable();
            $table->string('tipo_negocio')->nullable();
            $table->json('categorias')->nullable();
            $table->text('descricao')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('site_url')->nullable();
            $table->integer('colaboradores')->nullable();
        });
    }
};
