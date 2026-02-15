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
        // Tabela de Restaurantes
        Schema::create('restaurantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nome_estabelecimento');
            $table->string('tipo_cozinha')->nullable();
            $table->integer('capacidade')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('site_url')->nullable();
            $table->integer('colaboradores')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });

        // Tabela de Fornecedores
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nome_empresa');
            $table->json('categorias')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('site_url')->nullable();
            $table->text('descricao')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornecedores');
        Schema::dropIfExists('restaurantes');
    }
};
