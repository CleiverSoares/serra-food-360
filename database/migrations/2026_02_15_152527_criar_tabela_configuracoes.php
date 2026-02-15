<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Criar tabela de configurações do sistema
     */
    public function up(): void
    {
        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->text('valor')->nullable();
            $table->string('tipo')->default('text'); // text, email, tel, url, textarea
            $table->string('grupo')->default('geral'); // geral, contato, email, sistema
            $table->string('label');
            $table->text('descricao')->nullable();
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });

        // Inserir configurações padrão
        DB::table('configuracoes')->insert([
            [
                'chave' => 'nome_sistema',
                'valor' => 'Serra Food 360',
                'tipo' => 'text',
                'grupo' => 'geral',
                'label' => 'Nome do Sistema',
                'descricao' => 'Nome exibido no sistema',
                'ordem' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'email_contato',
                'valor' => 'contato@serrafood360.com.br',
                'tipo' => 'email',
                'grupo' => 'contato',
                'label' => 'Email de Contato',
                'descricao' => 'Email principal para contato com administradores',
                'ordem' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'telefone_contato',
                'valor' => '(27) 99999-9999',
                'tipo' => 'tel',
                'grupo' => 'contato',
                'label' => 'Telefone de Contato',
                'descricao' => 'Telefone principal para contato',
                'ordem' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'whatsapp_contato',
                'valor' => '5527999999999',
                'tipo' => 'tel',
                'grupo' => 'contato',
                'label' => 'WhatsApp de Contato',
                'descricao' => 'Número WhatsApp com código do país (ex: 5527999999999)',
                'ordem' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'mensagem_whatsapp_padrao',
                'valor' => 'Olá! Estou entrando em contato através do Serra Food 360.',
                'tipo' => 'textarea',
                'grupo' => 'contato',
                'label' => 'Mensagem Padrão WhatsApp',
                'descricao' => 'Mensagem que abre no WhatsApp ao clicar nos botões',
                'ordem' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'email_admin_aprovacoes',
                'valor' => 'admin@serrafood360.com.br',
                'tipo' => 'email',
                'grupo' => 'email',
                'label' => 'Email Admin (Aprovações)',
                'descricao' => 'Email que recebe notificações de novos cadastros',
                'ordem' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverter migration
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes');
    }
};
