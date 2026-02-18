<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotacoes', function (Blueprint $table) {
            $table->string('imagem_produto_url')->nullable()->after('descricao');
        });
    }

    public function down(): void
    {
        Schema::table('cotacoes', function (Blueprint $table) {
            $table->dropColumn('imagem_produto_url');
        });
    }
};
