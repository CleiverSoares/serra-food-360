<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adicionar preços dos planos na tabela configuracoes
     */
    public function up(): void
    {
        DB::table('configuracoes')->insert([
            [
                'chave' => 'plano_comum_mensal',
                'valor' => '99.00',
                'tipo' => 'number',
                'grupo' => 'planos',
                'label' => 'Plano Comum - Mensal (R$)',
                'descricao' => 'Valor mensal do Plano Comum (X)',
                'ordem' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'plano_comum_anual',
                'valor' => '990.00',
                'tipo' => 'number',
                'grupo' => 'planos',
                'label' => 'Plano Comum - Anual (R$)',
                'descricao' => 'Valor anual do Plano Comum (X) - 12 meses',
                'ordem' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'plano_vip_mensal',
                'valor' => '199.00',
                'tipo' => 'number',
                'grupo' => 'planos',
                'label' => 'Plano VIP - Mensal (R$)',
                'descricao' => 'Valor mensal do Plano VIP (2X)',
                'ordem' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'chave' => 'plano_vip_anual',
                'valor' => '1990.00',
                'tipo' => 'number',
                'grupo' => 'planos',
                'label' => 'Plano VIP - Anual (R$)',
                'descricao' => 'Valor anual do Plano VIP (2X) - 12 meses',
                'ordem' => 13,
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
        DB::table('configuracoes')->whereIn('chave', [
            'plano_comum_mensal',
            'plano_comum_anual',
            'plano_vip_mensal',
            'plano_vip_anual',
        ])->delete();
    }
};
