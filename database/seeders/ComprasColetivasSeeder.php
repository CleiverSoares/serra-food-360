<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProdutoCatalogoModel;
use App\Models\CompraColetivaPropostaModel;
use App\Models\CompraColetivaModel;
use App\Models\CompraColetivaAdesaoModel;
use App\Models\CompraColetivaVotoModel;
use App\Models\UserModel;
use App\Models\SegmentoModel;

class ComprasColetivasSeeder extends Seeder
{
    /**
     * Seed de Compras Coletivas
     */
    public function run(): void
    {
        $admin = UserModel::where('role', 'admin')->first();
        $compradores = UserModel::where('role', 'comprador')->limit(5)->get();
        $segmento = SegmentoModel::first();

        if (!$admin || $compradores->count() < 3) {
            $this->command->warn('⚠️  Necessário ter admin e pelo menos 3 compradores cadastrados.');
            return;
        }

        $this->command->info('🖼️  Criando produtos com imagens...');

        // 1. PRODUTOS CATÁLOGO (com placeholder de imagens via https://placehold.co)
        $produtos = [
            [
                'nome' => 'Arroz Branco Tipo 1',
                'descricao' => 'Arroz branco longo fino, tipo 1, pacote de 5kg',
                'unidade_medida' => 'sc',
                'categoria_id' => $segmento->id,
                'ativo' => true,
                'propostas_count' => 3,
                'imagem_url' => 'produtos/arroz.jpg', // Placeholder - admin pode fazer upload da imagem real
            ],
            [
                'nome' => 'Feijão Carioca',
                'descricao' => 'Feijão carioca tipo 1, saco de 1kg',
                'unidade_medida' => 'sc',
                'categoria_id' => $segmento->id,
                'ativo' => true,
                'propostas_count' => 2,
                'imagem_url' => 'produtos/feijao.jpg',
            ],
            [
                'nome' => 'Óleo de Soja',
                'descricao' => 'Óleo de soja refinado, garrafa PET 900ml',
                'unidade_medida' => 'un',
                'categoria_id' => $segmento->id,
                'ativo' => true,
                'propostas_count' => 5,
                'imagem_url' => 'produtos/oleo.jpg',
            ],
            [
                'nome' => 'Açúcar Cristal',
                'descricao' => 'Açúcar cristal refinado, pacote de 1kg',
                'unidade_medida' => 'sc',
                'categoria_id' => $segmento->id,
                'ativo' => true,
                'propostas_count' => 1,
                'imagem_url' => 'produtos/acucar.jpg',
            ],
            [
                'nome' => 'Farinha de Trigo',
                'descricao' => 'Farinha de trigo tipo 1, pacote de 1kg',
                'unidade_medida' => 'sc',
                'categoria_id' => $segmento->id,
                'ativo' => true,
                'propostas_count' => 2,
                'imagem_url' => 'produtos/farinha.jpg',
            ],
            [
                'nome' => 'Tomate',
                'descricao' => 'Tomate longa vida, caixa com 20kg',
                'unidade_medida' => 'cx',
                'categoria_id' => $segmento->id,
                'ativo' => true,
                'propostas_count' => 4,
                'imagem_url' => 'produtos/tomate.jpg',
            ],
        ];

        $produtosCriados = [];
        foreach ($produtos as $produto) {
            $produtosCriados[] = ProdutoCatalogoModel::create($produto);
        }

        $this->command->info('✅ ' . count($produtosCriados) . ' produtos adicionados ao catálogo');

        // 2. PROPOSTAS
        $propostas = [
            [
                'produto_catalogo_id' => $produtosCriados[0]->id,
                'proposto_por' => $compradores[0]->id,
                'justificativa' => 'Arroz é um item básico que todos precisam. Comprando em grande quantidade, podemos economizar muito!',
                'status' => 'aprovada',
            ],
            [
                'produto_catalogo_id' => $produtosCriados[1]->id,
                'proposto_por' => $compradores[1]->id,
                'justificativa' => 'Feijão está muito caro no varejo. Uma compra coletiva seria perfeita para reduzir custos.',
                'status' => 'em_votacao',
                'data_votacao_inicio' => now()->subDays(2),
                'data_votacao_fim' => now()->addDays(5),
                'votos_count' => 8,
            ],
            [
                'produto_catalogo_id' => $produtosCriados[2]->id,
                'proposto_por' => $compradores[2]->id,
                'justificativa' => 'Óleo de soja é essencial para todos os restaurantes. Vamos comprar juntos?',
                'status' => 'pendente',
            ],
            [
                'produto_catalogo_id' => $produtosCriados[5]->id,
                'proposto_por' => $compradores[0]->id,
                'justificativa' => 'Preciso de tomate em grande quantidade para meu negócio. Quem mais se interessa?',
                'status' => 'em_votacao',
                'data_votacao_inicio' => now()->subDays(1),
                'data_votacao_fim' => now()->addDays(6),
                'votos_count' => 12,
            ],
        ];

        $propostasCriadas = [];
        foreach ($propostas as $proposta) {
            $propostasCriadas[] = CompraColetivaPropostaModel::create($proposta);
        }

        $this->command->info('✅ ' . count($propostasCriadas) . ' propostas criadas');

        // 3. VOTOS (para as em votação)
        foreach ($propostasCriadas as $proposta) {
            if ($proposta->status === 'em_votacao') {
                foreach ($compradores->take(min($proposta->votos_count, $compradores->count())) as $comprador) {
                    CompraColetivaVotoModel::create([
                        'proposta_id' => $proposta->id,
                        'user_id' => $comprador->id,
                        'votado_em' => now()->subDays(rand(1, 3)),
                    ]);
                }
            }
        }

        $this->command->info('✅ Votos adicionados às propostas em votação');

        // 4. COMPRAS COLETIVAS ATIVAS
        $compras = [
            [
                'produto_catalogo_id' => $produtosCriados[0]->id,
                'proposta_id' => $propostasCriadas[0]->id,
                'titulo' => 'Compra Coletiva: Arroz Branco 5kg',
                'descricao' => 'Vamos comprar juntos arroz branco tipo 1 em sacos de 5kg. Quanto mais gente, melhor o preço!',
                'quantidade_minima' => 100.00,
                'quantidade_atual' => 75.00,
                'data_inicio' => now()->subDays(5),
                'data_fim' => now()->addDays(10),
                'status' => 'aberta',
                'criado_por' => $admin->id,
                'participantes_count' => 3,
            ],
            [
                'produto_catalogo_id' => $produtosCriados[3]->id,
                'proposta_id' => null,
                'titulo' => 'Compra Coletiva: Açúcar Cristal 1kg',
                'descricao' => 'Açúcar cristal refinado, excelente oportunidade para economizar!',
                'quantidade_minima' => 50.00,
                'quantidade_atual' => 62.00,
                'data_inicio' => now()->subDays(3),
                'data_fim' => now()->addDays(12),
                'status' => 'aberta',
                'criado_por' => $admin->id,
                'participantes_count' => 4,
            ],
            [
                'produto_catalogo_id' => $produtosCriados[4]->id,
                'proposta_id' => null,
                'titulo' => 'Compra Coletiva: Farinha de Trigo 1kg',
                'descricao' => 'Farinha de trigo tipo 1 para padarias, pizzarias e restaurantes.',
                'quantidade_minima' => 80.00,
                'quantidade_atual' => 45.00,
                'data_inicio' => now()->subDays(1),
                'data_fim' => now()->addDays(14),
                'status' => 'aberta',
                'criado_por' => $admin->id,
                'participantes_count' => 2,
            ],
        ];

        $comprasCriadas = [];
        foreach ($compras as $compra) {
            $comprasCriadas[] = CompraColetivaModel::create($compra);
        }

        $this->command->info('✅ ' . count($comprasCriadas) . ' compras coletivas ativas criadas');

        // 5. ADESÕES DOS COMPRADORES
        // Compra 1: 3 participantes
        CompraColetivaAdesaoModel::create([
            'compra_coletiva_id' => $comprasCriadas[0]->id,
            'comprador_id' => $compradores[0]->id,
            'quantidade' => 30.00,
            'observacoes' => 'Preciso de 30 sacos. Entrega urgente se possível!',
            'status' => 'ativa',
        ]);

        CompraColetivaAdesaoModel::create([
            'compra_coletiva_id' => $comprasCriadas[0]->id,
            'comprador_id' => $compradores[1]->id,
            'quantidade' => 25.00,
            'status' => 'ativa',
        ]);

        CompraColetivaAdesaoModel::create([
            'compra_coletiva_id' => $comprasCriadas[0]->id,
            'comprador_id' => $compradores[2]->id,
            'quantidade' => 20.00,
            'status' => 'ativa',
        ]);

        // Compra 2: 4 participantes
        foreach ($compradores->take(4) as $index => $comprador) {
            CompraColetivaAdesaoModel::create([
                'compra_coletiva_id' => $comprasCriadas[1]->id,
                'comprador_id' => $comprador->id,
                'quantidade' => 15.00 + ($index * 2),
                'status' => 'ativa',
            ]);
        }

        // Compra 3: 2 participantes
        foreach ($compradores->take(2) as $index => $comprador) {
            CompraColetivaAdesaoModel::create([
                'compra_coletiva_id' => $comprasCriadas[2]->id,
                'comprador_id' => $comprador->id,
                'quantidade' => 20.00 + ($index * 5),
                'status' => 'ativa',
            ]);
        }

        $this->command->info('✅ Adesões de compradores criadas');
        $this->command->info('🎉 Seed de Compras Coletivas concluído com sucesso!');
    }
}
