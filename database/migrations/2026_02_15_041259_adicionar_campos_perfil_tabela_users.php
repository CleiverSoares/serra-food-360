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
            $table->enum('role', ['admin', 'restaurante', 'fornecedor'])->after('email');
            $table->enum('status', ['pendente', 'aprovado', 'rejeitado', 'inativo'])->default('pendente')->after('role');
            $table->enum('plano', ['comum', 'vip'])->nullable()->after('status');
            $table->string('nome_estabelecimento')->nullable()->after('plano');
            $table->string('telefone', 20)->nullable()->after('nome_estabelecimento');
            $table->string('whatsapp', 20)->nullable()->after('telefone');
            $table->string('cidade')->nullable()->after('whatsapp');
            $table->string('tipo_negocio')->nullable()->after('cidade');
            $table->json('categorias')->nullable()->after('tipo_negocio');
            $table->text('descricao')->nullable()->after('categorias');
            $table->string('logo_path')->nullable()->after('descricao');
            $table->string('site_url')->nullable()->after('logo_path');
            $table->integer('colaboradores')->nullable()->after('site_url');
            $table->foreignId('aprovado_por')->nullable()->constrained('users')->after('colaboradores');
            $table->timestamp('aprovado_em')->nullable()->after('aprovado_por');
            $table->text('motivo_rejeicao')->nullable()->after('aprovado_em');
            
            // Indexes
            $table->index('role');
            $table->index('status');
            $table->index('plano');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
            $table->dropIndex(['plano']);
            $table->dropForeign(['aprovado_por']);
            $table->dropColumn([
                'role',
                'status',
                'plano',
                'nome_estabelecimento',
                'telefone',
                'whatsapp',
                'cidade',
                'tipo_negocio',
                'categorias',
                'descricao',
                'logo_path',
                'site_url',
                'colaboradores',
                'aprovado_por',
                'aprovado_em',
                'motivo_rejeicao'
            ]);
        });
    }
};
