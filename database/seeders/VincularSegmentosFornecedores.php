<?php

namespace Database\Seeders;

use App\Models\UserModel;
use App\Models\SegmentoModel;
use Illuminate\Database\Seeder;

class VincularSegmentosFornecedores extends Seeder
{
    public function run(): void
    {
        echo "🔗 Vinculando segmentos aos fornecedores...\n\n";

        $fornecedores = UserModel::where('role', 'fornecedor')->get();
        $segmentos = SegmentoModel::all();

        if ($fornecedores->isEmpty()) {
            echo "❌ Nenhum fornecedor encontrado!\n";
            return;
        }

        if ($segmentos->isEmpty()) {
            echo "❌ Nenhum segmento encontrado!\n";
            return;
        }

        foreach ($fornecedores as $fornecedor) {
            // Pegar 2-4 segmentos aleatórios
            $segmentosAleatorios = $segmentos->random(rand(2, 4))->pluck('id')->toArray();
            
            $fornecedor->segmentos()->sync($segmentosAleatorios);
            
            $nomes = $segmentos->whereIn('id', $segmentosAleatorios)->pluck('nome')->implode(', ');
            echo "✅ {$fornecedor->fornecedor->nome_empresa}: {$nomes}\n";
        }

        echo "\n✨ Fornecedores vinculados aos segmentos!\n";
    }
}
