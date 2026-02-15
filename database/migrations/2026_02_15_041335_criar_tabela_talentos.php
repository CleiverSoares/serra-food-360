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
        Schema::create('talentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('whatsapp', 20);
            $table->string('cargo');
            $table->text('mini_curriculo');
            $table->decimal('pretensao', 10, 2)->nullable();
            $table->string('foto_path')->nullable();
            $table->string('curriculo_pdf_path')->nullable();
            $table->string('carta_recomendacao_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talentos');
    }
};
