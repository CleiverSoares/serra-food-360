<?php

namespace Database\Seeders;

use App\Models\SegmentoModel;
use Illuminate\Database\Seeder;

class SegmentosExtrasSeeder extends Seeder
{
    public function run(): void
    {
        echo "📂 Criando segmentos extras...\n\n";

        $segmentos = [
            ['nome' => 'Restaurante', 'descricao' => 'Restaurantes, bares e similares'],
            ['nome' => 'Padaria', 'descricao' => 'Padarias e confeitarias'],
            ['nome' => 'Lanchonete', 'descricao' => 'Lanchonetes, fast food e food trucks'],
            ['nome' => 'Pizzaria', 'descricao' => 'Pizzarias e esfihaarias'],
            ['nome' => 'Hotel', 'descricao' => 'Hotéis, pousadas e resorts'],
            ['nome' => 'Buffet', 'descricao' => 'Buffets e serviços de catering'],
            ['nome' => 'Cafeteria', 'descricao' => 'Cafeterias e casas de chá'],
            ['nome' => 'Mercado', 'descricao' => 'Mercados, mini-mercados e mercearias'],
            ['nome' => 'Churrascaria', 'descricao' => 'Churrascarias e espetarias'],
            ['nome' => 'Sorveter ia', 'descricao' => 'Sorvet erias e açaiterias'],
        ];

        foreach ($segmentos as $dados) {
            $slug = \Illuminate\Support\Str::slug($dados['nome']);
            
            $segmento = SegmentoModel::firstOrCreate(
                ['nome' => $dados['nome']],
                [
                    'slug' => $slug,
                    'descricao' => $dados['descricao'], 
                    'ativo' => true
                ]
            );

            echo "✅ " . ($segmento->wasRecentlyCreated ? "Criado" : "Existente") . ": {$dados['nome']}\n";
        }

        echo "\n✨ Total de segmentos: " . SegmentoModel::count() . "\n";
    }
}
