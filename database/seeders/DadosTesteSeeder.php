<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModel;
use App\Models\CompradorModel;
use App\Models\FornecedorModel;
use App\Models\TalentoModel;
use App\Models\SegmentoModel;
use Illuminate\Support\Facades\Hash;

class DadosTesteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $segmentoAlimentacao = SegmentoModel::where('slug', 'alimentacao')->first();
        $segmentoPetShop = SegmentoModel::where('slug', 'pet-shop')->first();
        $segmentoConstrucao = SegmentoModel::where('slug', 'construcao')->first();

        // ========== COMPRADORES APROVADOS ==========
        
        // Comprador 1: Restaurante (aprovado)
        $user1 = UserModel::create([
            'name' => 'Carlos Silva',
            'email' => 'carlos@sabordaserra.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'comprador',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(10),
        ]);

        CompradorModel::create([
            'user_id' => $user1->id,
            'cnpj' => '12.345.678/0001-90',
            'nome_negocio' => 'Restaurante Sabor da Serra',
            'tipo_negocio' => 'Restaurante Regional',
            'descricao' => 'Restaurante especializado em comida capixaba com vista para as montanhas.',
            'colaboradores' => 15,
            'site_url' => 'https://sabordaserra.com.br',
        ]);

        $user1->segmentos()->attach($segmentoAlimentacao->id);

        // Comprador 2: Lanchonete (aprovado)
        $user2 = UserModel::create([
            'name' => 'Maria Oliveira',
            'email' => 'maria@burgerpoint.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'comprador',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(5),
        ]);

        CompradorModel::create([
            'user_id' => $user2->id,
            'cnpj' => '23.456.789/0001-01',
            'nome_negocio' => 'Burger Point Serra',
            'tipo_negocio' => 'Lanchonete',
            'descricao' => 'Hamburgeria artesanal com ingredientes locais.',
            'colaboradores' => 8,
        ]);

        $user2->segmentos()->attach($segmentoAlimentacao->id);

        // Comprador 3: Pet Shop (aprovado)
        $user3 = UserModel::create([
            'name' => 'João Pedro Santos',
            'email' => 'joao@petmania.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'comprador',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(3),
        ]);

        CompradorModel::create([
            'user_id' => $user3->id,
            'cnpj' => '34.567.890/0001-12',
            'nome_negocio' => 'Pet Mania Serra',
            'tipo_negocio' => 'Pet Shop',
            'descricao' => 'Pet shop completo com banho, tosa e clínica veterinária.',
            'colaboradores' => 6,
            'site_url' => 'https://petmania.com.br',
        ]);

        $user3->segmentos()->attach($segmentoPetShop->id);

        // ========== COMPRADORES PENDENTES ==========

        // Comprador 4: Pizzaria (pendente)
        $user4 = UserModel::create([
            'name' => 'Ana Paula Costa',
            'email' => 'ana@pizzabella.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'comprador',
            'status' => 'pendente',
            'telefone' => '(27) 3266-4433',
            'whatsapp' => '(27) 99555-4433',
            'cidade' => 'Marechal Floriano',
        ]);

        CompradorModel::create([
            'user_id' => $user4->id,
            'cnpj' => '45.678.901/0001-23',
            'nome_negocio' => 'Pizzaria Bella Vista',
            'tipo_negocio' => 'Pizzaria',
            'descricao' => 'Pizzaria com forno a lenha e massa artesanal.',
            'colaboradores' => 10,
        ]);

        $user4->segmentos()->attach($segmentoAlimentacao->id);

        // Comprador 5: Bar (pendente)
        $user5 = UserModel::create([
            'name' => 'Roberto Almeida',
            'email' => 'roberto@barmontanha.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'comprador',
            'status' => 'pendente',
            'telefone' => '(27) 3255-3322',
            'whatsapp' => '(27) 99444-3322',
            'cidade' => 'Domingos Martins',
        ]);

        CompradorModel::create([
            'user_id' => $user5->id,
            'nome_negocio' => 'Bar da Montanha',
            'tipo_negocio' => 'Bar e Petiscaria',
            'descricao' => 'Bar com petiscos e cerveja artesanal.',
            'colaboradores' => 5,
        ]);

        $user5->segmentos()->attach($segmentoAlimentacao->id);

        // ========== FORNECEDORES APROVADOS ==========

        // Fornecedor 1: Distribuidora de Bebidas (aprovado)
        $user6 = UserModel::create([
            'name' => 'Marcelo Ferreira',
            'email' => 'marcelo@distribebidas.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'fornecedor',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(15),
        ]);

        FornecedorModel::create([
            'user_id' => $user6->id,
            'cnpj' => '56.789.012/0001-34',
            'nome_empresa' => 'Distribuidora Serra Bebidas',
            'descricao' => 'Distribuição de bebidas, refrigerantes e cervejas para toda a região serrana.',
            'site_url' => 'https://serrabebidas.com.br',
        ]);

        $user6->segmentos()->attach($segmentoAlimentacao->id);

        // Fornecedor 2: Hortifrúti (aprovado)
        $user7 = UserModel::create([
            'name' => 'Fernanda Lima',
            'email' => 'fernanda@verdefresh.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'fornecedor',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(8),
        ]);

        FornecedorModel::create([
            'user_id' => $user7->id,
            'cnpj' => '67.890.123/0001-45',
            'nome_empresa' => 'Verde Fresh Hortifrúti',
            'descricao' => 'Fornecimento de frutas, verduras e legumes frescos direto do produtor.',
        ]);

        $user7->segmentos()->attach($segmentoAlimentacao->id);

        // Fornecedor 3: Laticínios (aprovado)
        $user8 = UserModel::create([
            'name' => 'Pedro Henrique Souza',
            'email' => 'pedro@laticinioserra.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'fornecedor',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(12),
        ]);

        FornecedorModel::create([
            'user_id' => $user8->id,
            'cnpj' => '78.901.234/0001-56',
            'nome_empresa' => 'Laticínios Serra Capixaba',
            'descricao' => 'Produção e distribuição de queijos, manteiga e derivados do leite.',
            'site_url' => 'https://laticinioserra.com.br',
        ]);

        $user8->segmentos()->attach($segmentoAlimentacao->id);

        // Fornecedor 4: Embalagens Multi-Segmento (aprovado)
        $user9 = UserModel::create([
            'name' => 'Juliana Martins',
            'email' => 'juliana@embalpro.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'fornecedor',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(7),
        ]);

        FornecedorModel::create([
            'user_id' => $user9->id,
            'cnpj' => '89.012.345/0001-67',
            'nome_empresa' => 'EmbalPro Distribuidora',
            'descricao' => 'Fornecimento de embalagens descartáveis, sacos e utensílios para diversos segmentos.',
            'site_url' => 'https://embalpro.com.br',
        ]);

        // Este fornecedor atende múltiplos segmentos!
        $user9->segmentos()->attach([
            $segmentoAlimentacao->id,
            $segmentoPetShop->id,
        ]);

        // Fornecedor 5: Pet Shop (aprovado)
        $user10 = UserModel::create([
            'name' => 'Lucas Rodrigues',
            'email' => 'lucas@petdistribuidora.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'fornecedor',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(4),
        ]);

        FornecedorModel::create([
            'user_id' => $user10->id,
            'cnpj' => '90.123.456/0001-78',
            'nome_empresa' => 'Pet Distribuidora ES',
            'descricao' => 'Distribuição de ração, brinquedos e acessórios para pet shops.',
        ]);

        $user10->segmentos()->attach($segmentoPetShop->id);

        // ========== FORNECEDORES PENDENTES ==========

        // Fornecedor 6: Carnes (pendente)
        $user11 = UserModel::create([
            'name' => 'Ricardo Oliveira',
            'email' => 'ricardo@carneserra.com.br',
            'password' => Hash::make('senha123'),
            'role' => 'fornecedor',
            'status' => 'pendente',
            'telefone' => '(27) 3266-8899',
            'whatsapp' => '(27) 98888-7766',
            'cidade' => 'Venda Nova do Imigrante',
        ]);

        FornecedorModel::create([
            'user_id' => $user11->id,
            'cnpj' => '01.234.567/0001-89',
            'nome_empresa' => 'Açougue Serra Prime',
            'descricao' => 'Fornecimento de carnes nobres e cortes especiais.',
        ]);

        $user11->segmentos()->attach($segmentoAlimentacao->id);

        // ========== TALENTOS ==========

        TalentoModel::create([
            'nome' => 'João Silva Santos',
            'whatsapp' => '(27) 99777-6655',
            'cargo' => 'Garçom',
            'mini_curriculo' => 'Experiência de 3 anos em restaurantes da serra. Pontual, educado, domínio de vinhos.',
            'pretensao' => 15.00,
            'tipo_cobranca' => 'hora',
            'disponibilidade' => 'Finais de semana',
            'ativo' => true,
        ]);

        TalentoModel::create([
            'nome' => 'Maria Clara Souza',
            'whatsapp' => '(27) 99666-5544',
            'cargo' => 'Cozinheira',
            'mini_curriculo' => 'Chef de cozinha com 5 anos de experiência. Especialista em comida regional capixaba.',
            'pretensao' => 150.00,
            'tipo_cobranca' => 'dia',
            'disponibilidade' => 'Durante a semana',
            'ativo' => true,
        ]);

        TalentoModel::create([
            'nome' => 'Pedro Costa Lima',
            'whatsapp' => '(27) 99555-4433',
            'cargo' => 'Auxiliar de Cozinha',
            'mini_curriculo' => 'Disponível para trabalhos extras aos finais de semana. Proativo e rápido no preparo.',
            'pretensao' => 60.00,
            'tipo_cobranca' => 'dia',
            'disponibilidade' => 'Finais de semana',
            'ativo' => true,
        ]);

        TalentoModel::create([
            'nome' => 'Ana Paula Ferreira',
            'whatsapp' => '(27) 99444-3322',
            'cargo' => 'Recepcionista',
            'mini_curriculo' => 'Atendimento ao público, reservas e organização. Inglês intermediário.',
            'pretensao' => 12.00,
            'tipo_cobranca' => 'hora',
            'disponibilidade' => 'Noites e finais de semana',
            'ativo' => true,
        ]);

        TalentoModel::create([
            'nome' => 'Lucas Henrique Alves',
            'whatsapp' => '(27) 99333-2211',
            'cargo' => 'Barman',
            'mini_curriculo' => 'Experiência em bares e eventos. Conhecimento em drinks clássicos e autorais.',
            'pretensao' => 20.00,
            'tipo_cobranca' => 'hora',
            'disponibilidade' => 'Noites',
            'ativo' => true,
        ]);

        TalentoModel::create([
            'nome' => 'Fernanda Oliveira Costa',
            'whatsapp' => '(27) 99222-1100',
            'cargo' => 'Gerente de Salão',
            'mini_curriculo' => '7 anos de experiência em gerenciamento. Liderança de equipes, controle de estoque.',
            'pretensao' => 120.00,
            'tipo_cobranca' => 'dia',
            'disponibilidade' => 'Durante a semana',
            'ativo' => true,
        ]);

        TalentoModel::create([
            'nome' => 'Carlos Eduardo Martins',
            'whatsapp' => '(27) 99111-0099',
            'cargo' => 'Sommelier',
            'mini_curriculo' => 'Certificado ABS. Conhecimento em vinhos nacionais e internacionais. Eventos corporativos.',
            'pretensao' => 35.00,
            'tipo_cobranca' => 'hora',
            'disponibilidade' => 'Finais de semana e eventos',
            'ativo' => true,
        ]);

        TalentoModel::create([
            'nome' => 'Juliana Ribeiro Santos',
            'whatsapp' => '(27) 98999-8877',
            'cargo' => 'Confeiteira',
            'mini_curriculo' => 'Especialista em doces finos e bolos personalizados. Curso de confeitaria na França.',
            'pretensao' => 110.00,
            'tipo_cobranca' => 'dia',
            'disponibilidade' => 'Durante a semana',
            'ativo' => false, // Inativo para testar filtro
        ]);

        TalentoModel::create([
            'nome' => 'Roberto Mendes Silva',
            'whatsapp' => '(27) 98888-7766',
            'cargo' => 'Chapeiro',
            'mini_curriculo' => 'Especialista em hambúrgueres e lanches rápidos. Experiência em food trucks.',
            'pretensao' => 80.00,
            'tipo_cobranca' => 'dia',
            'disponibilidade' => 'Finais de semana',
            'ativo' => true,
        ]);

        TalentoModel::create([
            'nome' => 'Camila Rodrigues Pinto',
            'whatsapp' => '(27) 98777-6655',
            'cargo' => 'Cumim',
            'mini_curriculo' => 'Ajudante geral de cozinha. Disponível para eventos e festivais.',
            'pretensao' => 10.00,
            'tipo_cobranca' => 'hora',
            'disponibilidade' => 'Finais de semana e eventos',
            'ativo' => true,
        ]);

        $this->command->info('✅ Dados de teste criados com sucesso!');
        $this->command->info('');
        $this->command->info('📊 Resumo:');
        $this->command->info('   - Compradores: 5 (3 aprovados, 2 pendentes)');
        $this->command->info('   - Fornecedores: 6 (5 aprovados, 1 pendente)');
        $this->command->info('   - Talentos: 5');
        $this->command->info('');
        $this->command->info('🔐 Login de teste:');
        $this->command->info('   - Comprador: carlos@sabordaserra.com.br / senha123');
        $this->command->info('   - Fornecedor: marcelo@distribebidas.com.br / senha123');
        $this->command->info('   - Admin: admin@serrafood360.com / admin123');
    }
}
