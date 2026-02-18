<?php

namespace Database\Seeders;

use App\Models\CotacaoModel;
use App\Models\CotacaoFornecedorModel;
use App\Models\SegmentoModel;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

class CotacoesSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔍 Limpando cotações existentes...\n";
        CotacaoFornecedorModel::query()->delete();
        CotacaoModel::query()->delete();

        // Buscar segmentos
        $segmentos = SegmentoModel::all();
        if ($segmentos->isEmpty()) {
            echo "❌ Nenhum segmento encontrado! Execute o seeder de segmentos primeiro.\n";
            return;
        }

        // Buscar admin e fornecedores
        $admin = UserModel::where('role', 'admin')->first();
        $fornecedores = UserModel::where('role', 'fornecedor')
            ->where('status', 'aprovado')
            ->get();

        if (!$admin) {
            echo "❌ Admin não encontrado!\n";
            return;
        }

        if ($fornecedores->count() < 3) {
            echo "❌ Precisa de pelo menos 3 fornecedores cadastrados!\n";
            return;
        }

        echo "📋 Criando cotações com ofertas...\n\n";

        // Dados das cotações
        $cotacoes = [
            // Restaurantes
            [
                'titulo' => 'Cotação Semana 15/02 - Carnes Premium',
                'produto' => 'Filé Mignon',
                'descricao' => 'Filé de primeira qualidade, resfriado, para uso imediato',
                'unidade' => 'kg',
                'quantidade_minima' => 10,
                'segmento' => 'Restaurante',
                'dias_validade' => 7,
                'ofertas' => [
                    ['preco' => 89.90, 'prazo' => '24h', 'qtd' => 50, 'obs' => 'Carne certificada, rastreada'],
                    ['preco' => 95.00, 'prazo' => '48h', 'qtd' => 100, 'obs' => 'Frete grátis acima de 20kg'],
                    ['preco' => 92.50, 'prazo' => '24h', 'qtd' => 30, 'obs' => 'Entrega pela manhã'],
                ]
            ],
            [
                'titulo' => 'Cotação Semana 15/02 - Óleo',
                'produto' => 'Óleo de Soja',
                'descricao' => 'Óleo de soja refinado, garrafa PET 900ml',
                'unidade' => 'litro',
                'quantidade_minima' => 20,
                'segmento' => 'Restaurante',
                'dias_validade' => 10,
                'ofertas' => [
                    ['preco' => 7.50, 'prazo' => '3 dias', 'qtd' => 500, 'obs' => 'Marca premium'],
                    ['preco' => 6.90, 'prazo' => '5 dias', 'qtd' => 1000, 'obs' => 'Atacado com desconto progressivo'],
                    ['preco' => 7.20, 'prazo' => '2 dias', 'qtd' => 300, 'obs' => 'Entrega expressa'],
                ]
            ],
            
            // Padaria
            [
                'titulo' => 'Cotação Semana 15/02 - Farinha',
                'produto' => 'Farinha de Trigo Tipo 1',
                'descricao' => 'Farinha especial para panificação, rica em glúten',
                'unidade' => 'kg',
                'quantidade_minima' => 50,
                'segmento' => 'Padaria',
                'dias_validade' => 14,
                'ofertas' => [
                    ['preco' => 3.20, 'prazo' => '24h', 'qtd' => 200, 'obs' => 'Qualidade premium'],
                    ['preco' => 2.95, 'prazo' => '48h', 'qtd' => 500, 'obs' => 'Melhor preço do mercado'],
                    ['preco' => 3.10, 'prazo' => '24h', 'qtd' => 300, 'obs' => 'Certificado ANVISA'],
                ]
            ],
            [
                'titulo' => 'Cotação Semana 15/02 - Fermento',
                'produto' => 'Fermento Biológico Seco',
                'descricao' => 'Fermento instantâneo para pães e massas',
                'unidade' => 'kg',
                'quantidade_minima' => 5,
                'segmento' => 'Padaria',
                'dias_validade' => 10,
                'ofertas' => [
                    ['preco' => 18.50, 'prazo' => '2 dias', 'qtd' => 50, 'obs' => 'Alta atividade enzimática'],
                    ['preco' => 17.90, 'prazo' => '3 dias', 'qtd' => 100, 'obs' => 'Embalagem selada a vácuo'],
                    ['preco' => 19.20, 'prazo' => '1 dia', 'qtd' => 30, 'obs' => 'Entrega urgente'],
                ]
            ],
            
            // Lanchonete
            [
                'titulo' => 'Cotação Semana 15/02 - Batata Congelada',
                'produto' => 'Batata Pré-Frita Congelada',
                'descricao' => 'Batata palito pré-frita, congelada',
                'unidade' => 'kg',
                'quantidade_minima' => 20,
                'segmento' => 'Lanchonete',
                'dias_validade' => 7,
                'ofertas' => [
                    ['preco' => 12.90, 'prazo' => '24h', 'qtd' => 200, 'obs' => 'Batata argentina premium'],
                    ['preco' => 11.50, 'prazo' => '48h', 'qtd' => 500, 'obs' => 'Melhor custo-benefício'],
                    ['preco' => 13.20, 'prazo' => '24h', 'qtd' => 150, 'obs' => 'Corte especial 9mm'],
                ]
            ],
            [
                'titulo' => 'Cotação Semana 15/02 - Hambúrguer',
                'produto' => 'Hambúrguer Artesanal 180g',
                'descricao' => 'Hambúrguer bovino artesanal, 180g cada',
                'unidade' => 'unidade',
                'quantidade_minima' => 100,
                'segmento' => 'Lanchonete',
                'dias_validade' => 5,
                'ofertas' => [
                    ['preco' => 4.20, 'prazo' => '24h', 'qtd' => 500, 'obs' => '100% carne bovina, sem conservantes'],
                    ['preco' => 3.90, 'prazo' => '48h', 'qtd' => 1000, 'obs' => 'Produção própria'],
                    ['preco' => 4.50, 'prazo' => '12h', 'qtd' => 300, 'obs' => 'Blend especial wagyu 20%'],
                ]
            ],
            
            // Pizzaria
            [
                'titulo' => 'Cotação Semana 15/02 - Mussarela',
                'produto' => 'Queijo Mussarela Fatiada',
                'descricao' => 'Queijo mussarela fatiado, qualidade pizza',
                'unidade' => 'kg',
                'quantidade_minima' => 10,
                'segmento' => 'Pizzaria',
                'dias_validade' => 7,
                'ofertas' => [
                    ['preco' => 32.90, 'prazo' => '24h', 'qtd' => 100, 'obs' => 'Derretimento perfeito'],
                    ['preco' => 30.50, 'prazo' => '48h', 'qtd' => 200, 'obs' => 'Melhor preço atacado'],
                    ['preco' => 34.00, 'prazo' => '12h', 'qtd' => 50, 'obs' => 'Mussarela de búfala blend'],
                ]
            ],
            [
                'titulo' => 'Cotação Semana 15/02 - Molho de Tomate',
                'produto' => 'Molho de Tomate Pizza',
                'descricao' => 'Molho concentrado especial para pizza',
                'unidade' => 'kg',
                'quantidade_minima' => 20,
                'segmento' => 'Pizzaria',
                'dias_validade' => 10,
                'ofertas' => [
                    ['preco' => 12.90, 'prazo' => '2 dias', 'qtd' => 300, 'obs' => 'Tomates selecionados'],
                    ['preco' => 11.50, 'prazo' => '3 dias', 'qtd' => 500, 'obs' => 'Receita tradicional italiana'],
                    ['preco' => 13.50, 'prazo' => '24h', 'qtd' => 150, 'obs' => 'Molho gourmet premium'],
                ]
            ],
            
            // Hotel
            [
                'titulo' => 'Cotação Semana 15/02 - Café em Grão',
                'produto' => 'Café Gourmet em Grão',
                'descricao' => 'Café 100% arábica, torra média',
                'unidade' => 'kg',
                'quantidade_minima' => 10,
                'segmento' => 'Hotel',
                'dias_validade' => 10,
                'ofertas' => [
                    ['preco' => 42.00, 'prazo' => '24h', 'qtd' => 100, 'obs' => 'Certificado orgânico'],
                    ['preco' => 38.50, 'prazo' => '48h', 'qtd' => 200, 'obs' => 'Torra especial para café da manhã'],
                    ['preco' => 45.00, 'prazo' => '24h', 'qtd' => 50, 'obs' => 'Blend premium Serra'],
                ]
            ],
            
            // Cafeteria
            [
                'titulo' => 'Cotação Semana 15/02 - Leite Integral',
                'produto' => 'Leite Integral UHT',
                'descricao' => 'Leite integral longa vida, caixa 1L',
                'unidade' => 'litro',
                'quantidade_minima' => 50,
                'segmento' => 'Cafeteria',
                'dias_validade' => 7,
                'ofertas' => [
                    ['preco' => 4.50, 'prazo' => '24h', 'qtd' => 500, 'obs' => 'Marca líder'],
                    ['preco' => 4.20, 'prazo' => '48h', 'qtd' => 1000, 'obs' => 'Atacado com desconto'],
                    ['preco' => 4.80, 'prazo' => '12h', 'qtd' => 300, 'obs' => 'Leite integral A'],
                ]
            ],
            
            // Churrascaria
            [
                'titulo' => 'Cotação Semana 15/02 - Picanha',
                'produto' => 'Picanha Bovina Premium',
                'descricao' => 'Picanha de primeira, maturada',
                'unidade' => 'kg',
                'quantidade_minima' => 20,
                'segmento' => 'Churrascaria',
                'dias_validade' => 5,
                'ofertas' => [
                    ['preco' => 65.90, 'prazo' => '24h', 'qtd' => 100, 'obs' => 'Carne maturada 14 dias'],
                    ['preco' => 62.00, 'prazo' => '48h', 'qtd' => 200, 'obs' => 'Melhor custo-benefício'],
                    ['preco' => 68.50, 'prazo' => '12h', 'qtd' => 50, 'obs' => 'Angus premium'],
                ]
            ],
        ];

        $cotacoesCriadas = 0;
        $ofertasCriadas = 0;

        foreach ($cotacoes as $dadosCotacao) {
            // Buscar segmento
            $segmento = $segmentos->firstWhere('nome', $dadosCotacao['segmento']);
            
            if (!$segmento) {
                echo "⚠️  Segmento '{$dadosCotacao['segmento']}' não encontrado, pulando...\n";
                continue;
            }

            // Criar cotação
            $cotacao = CotacaoModel::create([
                'titulo' => $dadosCotacao['titulo'],
                'produto' => $dadosCotacao['produto'],
                'descricao' => $dadosCotacao['descricao'],
                'unidade' => $dadosCotacao['unidade'],
                'quantidade_minima' => $dadosCotacao['quantidade_minima'],
                'data_inicio' => now(),
                'data_fim' => now()->addDays($dadosCotacao['dias_validade']),
                'status' => 'ativo',
                'segmento_id' => $segmento->id,
                'criado_por' => $admin->id,
            ]);

            $cotacoesCriadas++;
            echo "✅ Cotação criada: {$dadosCotacao['produto']} ({$segmento->nome})\n";

            // Criar ofertas de fornecedores diferentes
            $fornecedoresDisponiveis = $fornecedores->shuffle();
            
            // Ordenar ofertas por preço (menor primeiro)
            $ofertasOrdenadas = collect($dadosCotacao['ofertas'])->sortBy('preco')->values()->toArray();
            
            foreach ($ofertasOrdenadas as $index => $dadosOferta) {
                if ($index >= $fornecedoresDisponiveis->count()) {
                    break;
                }

                $fornecedor = $fornecedoresDisponiveis[$index];

                CotacaoFornecedorModel::create([
                    'cotacao_id' => $cotacao->id,
                    'fornecedor_id' => $fornecedor->id,
                    'preco_unitario' => $dadosOferta['preco'],
                    'prazo_entrega' => $dadosOferta['prazo'],
                    'quantidade_disponivel' => $dadosOferta['qtd'],
                    'observacoes' => $dadosOferta['obs'],
                    'destaque' => $index === 0, // Menor preço é destaque
                ]);

                $ofertasCriadas++;
                $destaqueLabel = $index === 0 ? '👑 MELHOR OFERTA' : '';
                echo "   💰 Oferta: R$ {$dadosOferta['preco']} - {$fornecedor->fornecedor->nome_empresa} {$destaqueLabel}\n";
            }

            echo "\n";
        }

        echo "✨ Seeder concluído!\n";
        echo "📊 {$cotacoesCriadas} cotações criadas\n";
        echo "💵 {$ofertasCriadas} ofertas cadastradas\n";
    }
}
