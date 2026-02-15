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
        Schema::table('talentos', function (Blueprint $table) {
            $table->boolean('ativo')->default(true)->after('carta_recomendacao_path');
            $table->string('disponibilidade', 100)->nullable()->after('ativo')->comment('Ex: Finais de semana, Durante a semana, Noites, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talentos', function (Blueprint $table) {
            $table->dropColumn(['ativo', 'disponibilidade']);
        });
    }
};
