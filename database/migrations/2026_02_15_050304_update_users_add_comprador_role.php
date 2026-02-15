<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro: adicionar 'comprador' ao enum (mantendo 'restaurante' temporariamente)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'restaurante', 'comprador', 'fornecedor') NOT NULL");
        
        // Segundo: atualizar todos os usuários com role 'restaurante' para 'comprador'
        DB::table('users')
            ->where('role', 'restaurante')
            ->update(['role' => 'comprador']);
        
        // Terceiro: remover 'restaurante' do enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'comprador', 'fornecedor') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: adicionar 'restaurante' de volta ao enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'restaurante', 'comprador', 'fornecedor') NOT NULL");
        
        // Atualizar todos os 'comprador' de volta para 'restaurante'
        DB::table('users')
            ->where('role', 'comprador')
            ->update(['role' => 'restaurante']);
    }
};
