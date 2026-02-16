<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialModel;
use App\Models\UserModel;

class MateriaisSeeder extends Seeder
{
    /**
     * Seed de materiais educacionais
     */
    public function run(): void
    {
        // Buscar um admin para ser o criador
        $admin = UserModel::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->warn('⚠️  Admin não encontrado. Criando materiais sem criador.');
            return;
        }

        $materiais = [
            // FINANCEIRO
            [
                'titulo' => 'Como Calcular o Preço de Venda de Alimentos',
                'descricao' => 'Aprenda a calcular corretamente o preço de venda dos seus produtos, considerando custos fixos, variáveis, margem de lucro e impostos.',
                'categoria' => 'financeiro',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => false,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Gestão de Fluxo de Caixa para Restaurantes',
                'descricao' => 'Controle financeiro eficiente: como organizar entradas, saídas e manter o fluxo de caixa saudável no seu negócio de alimentação.',
                'categoria' => 'financeiro',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Planilha de Controle Financeiro para Food Service',
                'descricao' => 'Planilha completa em Excel para controlar receitas, despesas, DRE simplificado e indicadores de performance.',
                'categoria' => 'financeiro',
                'tipo' => 'link',
                'link_externo' => 'https://docs.google.com/spreadsheets/d/exemplo',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],

            // GESTÃO DE PESSOAS
            [
                'titulo' => 'Recrutamento e Seleção para Cozinha e Atendimento',
                'descricao' => 'Técnicas eficazes para contratar os melhores profissionais para seu restaurante: perfil ideal, entrevistas e integração.',
                'categoria' => 'pessoas',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => false,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Treinamento de Equipe: Boas Práticas',
                'descricao' => 'Como capacitar sua equipe para oferecer excelência no atendimento e qualidade na produção.',
                'categoria' => 'pessoas',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],

            // CARDÁPIO
            [
                'titulo' => 'Engenharia de Cardápio: Maximize Seus Lucros',
                'descricao' => 'Aprenda a estruturar seu cardápio para vender mais dos pratos mais lucrativos e reduzir desperdícios.',
                'categoria' => 'cardapio',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Precificação de Pratos: Guia Completo',
                'descricao' => 'Método passo a passo para calcular o custo de cada prato e definir preços competitivos e lucrativos.',
                'categoria' => 'cardapio',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => false,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],

            // SEGURANÇA ALIMENTAR
            [
                'titulo' => 'Boas Práticas de Manipulação de Alimentos',
                'descricao' => 'Normas de higiene, armazenamento correto, controle de temperatura e prevenção de contaminação.',
                'categoria' => 'seguranca-alimentar',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => false,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Checklist de Segurança Alimentar RDC 216',
                'descricao' => 'Checklist completo para adequação à RDC 216 da ANVISA: documentos, procedimentos e controles obrigatórios.',
                'categoria' => 'seguranca-alimentar',
                'tipo' => 'link',
                'link_externo' => 'https://exemplo.com/checklist-rdc216',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],

            // MARKETING
            [
                'titulo' => 'Marketing Digital para Restaurantes',
                'descricao' => 'Estratégias práticas de Instagram, Google Meu Negócio, delivery e fidelização de clientes.',
                'categoria' => 'marketing',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => false,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Como Fotografar Pratos para Instagram',
                'descricao' => 'Técnicas de fotografia gastronômica com celular: luz, ângulos, composição e edição.',
                'categoria' => 'marketing',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],

            // LEGISLAÇÃO
            [
                'titulo' => 'Abertura de Restaurante: Documentos Necessários',
                'descricao' => 'Passo a passo completo: alvarás, licenças sanitárias, registro de funcionários e obrigações fiscais.',
                'categoria' => 'legislacao',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => false,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Guia de Obrigações Trabalhistas para Food Service',
                'descricao' => 'CLT, MEI, PJ: direitos dos trabalhadores, encargos e como manter a regularidade trabalhista.',
                'categoria' => 'legislacao',
                'tipo' => 'link',
                'link_externo' => 'https://exemplo.com/guia-trabalhista',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],

            // LOGÍSTICA
            [
                'titulo' => 'Gestão de Estoque: Reduzindo Desperdícios',
                'descricao' => 'PEPS, PVPS, controle de validade, inventário rotativo e como reduzir perdas no estoque.',
                'categoria' => 'logistica',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => false,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Logística de Delivery: Otimize Suas Entregas',
                'descricao' => 'Roteirização, embalagens adequadas, controle de tempo e qualidade na entrega.',
                'categoria' => 'logistica',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],

            // INOVAÇÃO
            [
                'titulo' => 'Tecnologias para Restaurantes: PDV, Delivery e Automação',
                'descricao' => 'Sistemas de gestão, integração com apps de delivery, QR code para cardápio digital e mais.',
                'categoria' => 'inovacao',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => true,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
            [
                'titulo' => 'Tendências Gastronômicas 2026',
                'descricao' => 'As principais tendências do mercado food: sustentabilidade, plant-based, ghost kitchens e experiência do cliente.',
                'categoria' => 'inovacao',
                'tipo' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'apenas_vip' => false,
                'ativo' => true,
                'criado_por' => $admin->id,
            ],
        ];

        foreach ($materiais as $material) {
            MaterialModel::create($material);
        }

        $this->command->info('✅ ' . count($materiais) . ' materiais criados com sucesso!');
    }
}
