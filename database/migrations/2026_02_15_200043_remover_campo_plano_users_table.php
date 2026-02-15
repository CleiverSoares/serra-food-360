<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remover campo plano da tabela users
     * Agora usamos apenas a tabela assinaturas
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('plano');
        });
    }

    /**
     * Reverter migration
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plano')->nullable()->after('status');
        });
    }
};
