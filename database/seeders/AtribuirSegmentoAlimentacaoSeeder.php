<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserModel;
use App\Models\SegmentoModel;
use Illuminate\Support\Facades\DB;

class AtribuirSegmentoAlimentacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar segmento de alimentação
        $segmentoAlimentacao = SegmentoModel::where('slug', 'alimentacao')->first();

        if (!$segmentoAlimentacao) {
            $this->command->error('Segmento "alimentacao" não encontrado. Execute primeiro o SegmentosSeeder.');
            return;
        }

        // Buscar todos os usuários que não são admin e não têm segmentos atribuídos
        $usuarios = UserModel::whereIn('role', ['comprador', 'fornecedor'])
            ->whereDoesntHave('segmentos')
            ->get();

        if ($usuarios->isEmpty()) {
            $this->command->info('Nenhum usuário sem segmento encontrado.');
            return;
        }

        // Atribuir segmento "alimentacao" a cada usuário
        foreach ($usuarios as $usuario) {
            $usuario->segmentos()->attach($segmentoAlimentacao->id);
        }

        $this->command->info("Segmento 'alimentacao' atribuído a {$usuarios->count()} usuário(s).");
    }
}
