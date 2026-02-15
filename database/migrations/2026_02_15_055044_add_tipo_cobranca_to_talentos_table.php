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
            $table->enum('tipo_cobranca', ['hora', 'dia'])->default('dia')->after('pretensao')->comment('Tipo de cobrança: por hora ou por dia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talentos', function (Blueprint $table) {
            $table->dropColumn('tipo_cobranca');
        });
    }
};
