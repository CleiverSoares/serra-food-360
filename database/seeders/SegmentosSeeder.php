<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SegmentoModel;

class SegmentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $segmentos = [
            [
                'nome' => 'Alimentação',
                'slug' => 'alimentacao',
                'descricao' => 'Restaurantes, bares, lanchonetes e food service',
                'icone' => '🍽️',
                'cor' => '#16A34A',
                'ativo' => true,
            ],
            [
                'nome' => 'Pet Shop',
                'slug' => 'pet-shop',
                'descricao' => 'Pet shops, clínicas veterinárias e serviços pet',
                'icone' => '🐾',
                'cor' => '#EA580C',
                'ativo' => true,
            ],
            [
                'nome' => 'Construção',
                'slug' => 'construcao',
                'descricao' => 'Construtoras, materiais de construção e reformas',
                'icone' => '🔨',
                'cor' => '#0284C7',
                'ativo' => true,
            ],
            [
                'nome' => 'Varejo',
                'slug' => 'varejo',
                'descricao' => 'Lojas, comércio e varejo em geral',
                'icone' => '🛒',
                'cor' => '#7C3AED',
                'ativo' => true,
            ],
            [
                'nome' => 'Serviços',
                'slug' => 'servicos',
                'descricao' => 'Prestadores de serviços diversos',
                'icone' => '💼',
                'cor' => '#059669',
                'ativo' => true,
            ],
        ];

        foreach ($segmentos as $segmento) {
            SegmentoModel::updateOrCreate(
                ['slug' => $segmento['slug']],
                $segmento
            );
        }
    }
}
