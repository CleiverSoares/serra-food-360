<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remover colunas antigas após normalização
     * Nota: Execute apenas após confirmar que os dados foram migrados corretamente
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cidade', 'telefone', 'whatsapp']);
        });
    }

    /**
     * Reverter remoção das colunas (recria vazias)
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cidade', 100)->nullable()->after('plano');
            $table->string('telefone', 20)->nullable()->after('cidade');
            $table->string('whatsapp', 20)->nullable()->after('telefone');
        });
    }
};
