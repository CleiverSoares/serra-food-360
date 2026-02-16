<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModel;
use App\Models\CompradorModel;
use App\Models\FornecedorModel;
use App\Models\TalentoModel;
use App\Models\SegmentoModel;
use App\Models\AssinaturaModel;
use App\Models\EnderecoModel;
use App\Models\ContatoModel;
use Illuminate\Support\Facades\Hash;

class DadosTesteSeeder extends Seeder
{
    private $cidades = [
        'Domingos Martins',
        'Venda Nova do Imigrante',
        'Marechal Floriano',
        'Santa Maria de Jetibá',
        'Santa Leopoldina',
        'Alfredo Chaves',
    ];

    private $segmentos;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('⚠️  ATENÇÃO: Este seeder vai LIMPAR dados de teste existentes!');
        
        // Limpar dados de teste (exceto admin)
        $this->limparDadosTeste();

        $this->segmentos = [
            'alimentacao' => SegmentoModel::where('slug', 'alimentacao')->first(),
            'pet-shop' => SegmentoModel::where('slug', 'pet-shop')->first(),
            'construcao' => SegmentoModel::where('slug', 'construcao')->first(),
        ];

        $this->command->info('🌱 Criando dados de teste...');
        $this->command->newLine();

        // Criar compradores
        $this->criarCompradores();
        
        // Criar fornecedores
        $this->criarFornecedores();
        
        // Criar talentos
        $this->criarTalentos();

        $this->command->newLine();
        $this->command->info('✅ Dados de teste criados com sucesso!');
        $this->exibirResumo();
    }

    /**
     * Criar compradores com assinaturas variadas
     */
    private function criarCompradores(): void
    {
        $this->command->info('👥 Criando compradores...');

        $compradores = [
            [
                'name' => 'Carlos Silva',
                'email' => 'carlos@sabordaserra.com.br',
                'cnpj' => '12.345.678/0001-90',
                'nome_negocio' => 'Restaurante Sabor da Serra',
                'descricao' => 'Restaurante especializado em comida capixaba com vista para as montanhas',
                'logo' => 'https://ui-avatars.com/api/?name=Sabor+Serra&background=22C55E&color=fff&size=400',
                'cidade' => 'Domingos Martins',
                'telefone' => '(27) 3268-1122',
                'whatsapp' => '(27) 99876-5432',
                'segmentos' => ['alimentacao'],
                'plano' => 'vip',
                'tipo_pagamento' => 'anual',
                'dias_ativa' => 300,
            ],
            [
                'name' => 'Maria Oliveira',
                'email' => 'maria@burgerpoint.com.br',
                'cnpj' => '23.456.789/0001-01',
                'nome_negocio' => 'Burger Point Serra',
                'descricao' => 'Hamburgeria artesanal com ingredientes locais e orgânicos',
                'logo' => 'https://ui-avatars.com/api/?name=Burger+Point&background=EF4444&color=fff&size=400',
                'cidade' => 'Venda Nova do Imigrante',
                'telefone' => '(27) 3266-2233',
                'whatsapp' => '(27) 99765-4321',
                'segmentos' => ['alimentacao'],
                'plano' => 'comum',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => 15,
            ],
            [
                'name' => 'João Pedro Santos',
                'email' => 'joao@petmania.com.br',
                'cnpj' => '34.567.890/0001-12',
                'nome_negocio' => 'Pet Mania Serra',
                'descricao' => 'Pet shop completo com banho, tosa e clínica veterinária',
                'logo' => 'https://ui-avatars.com/api/?name=Pet+Mania&background=3B82F6&color=fff&size=400',
                'cidade' => 'Santa Maria de Jetibá',
                'telefone' => '(27) 3263-3344',
                'whatsapp' => '(27) 99654-3210',
                'segmentos' => ['pet-shop'],
                'plano' => 'vip',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => 45,
            ],
            [
                'name' => 'Ana Paula Costa',
                'email' => 'ana@pizzabella.com.br',
                'cnpj' => '45.678.901/0001-23',
                'nome_negocio' => 'Pizzaria Bella Vista',
                'descricao' => 'Pizzaria com forno a lenha e massa artesanal feita na hora',
                'logo' => 'https://ui-avatars.com/api/?name=Bella+Vista&background=F59E0B&color=fff&size=400',
                'cidade' => 'Marechal Floriano',
                'telefone' => '(27) 3288-4455',
                'whatsapp' => '(27) 99543-2109',
                'segmentos' => ['alimentacao'],
                'plano' => 'comum',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => 5, // Vencendo em breve
            ],
            [
                'name' => 'Roberto Almeida',
                'email' => 'roberto@barmontanha.com.br',
                'cnpj' => '56.789.012/0001-34',
                'nome_negocio' => 'Bar da Montanha',
                'descricao' => 'Bar com petiscos, cerveja artesanal e vista panorâmica',
                'logo' => 'https://ui-avatars.com/api/?name=Bar+Montanha&background=8B4512&color=fff&size=400',
                'cidade' => 'Domingos Martins',
                'telefone' => '(27) 3268-5566',
                'whatsapp' => '(27) 99432-1098',
                'segmentos' => ['alimentacao'],
                'plano' => 'vip',
                'tipo_pagamento' => 'anual',
                'dias_ativa' => 180,
            ],
            [
                'name' => 'Fernanda Souza',
                'email' => 'fernanda@cafeserra.com.br',
                'cnpj' => '67.890.123/0001-45',
                'nome_negocio' => 'Café Colonial da Serra',
                'descricao' => 'Café colonial tradicional com produtos artesanais da região',
                'logo' => 'https://ui-avatars.com/api/?name=Cafe+Serra&background=92400E&color=fff&size=400',
                'cidade' => 'Venda Nova do Imigrante',
                'telefone' => '(27) 3266-6677',
                'whatsapp' => '(27) 99321-0987',
                'segmentos' => ['alimentacao'],
                'plano' => 'comum',
                'tipo_pagamento' => 'anual',
                'dias_ativa' => 120,
            ],
            [
                'name' => 'Ricardo Santos',
                'email' => 'ricardo@padariapao.com.br',
                'cnpj' => '78.901.234/0001-56',
                'nome_negocio' => 'Padaria Pão Nosso',
                'descricao' => 'Padaria tradicional com pães artesanais e bolos caseiros',
                'logo' => 'https://ui-avatars.com/api/?name=Pao+Nosso&background=F97316&color=fff&size=400',
                'cidade' => 'Santa Leopoldina',
                'telefone' => '(27) 3266-7788',
                'whatsapp' => '(27) 99210-9876',
                'segmentos' => ['alimentacao'],
                'plano' => 'comum',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => -10, // Vencida
            ],
            [
                'name' => 'Patricia Lima',
                'email' => 'patricia@doceriamel.com.br',
                'cnpj' => '89.012.345/0001-67',
                'nome_negocio' => 'Doceria Mel da Serra',
                'descricao' => 'Doces finos, trufas e sobremesas para eventos',
                'logo' => 'https://ui-avatars.com/api/?name=Mel+Serra&background=EC4899&color=fff&size=400',
                'cidade' => 'Domingos Martins',
                'telefone' => '(27) 3268-8899',
                'whatsapp' => '(27) 99109-8765',
                'segmentos' => ['alimentacao'],
                'plano' => 'vip',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => 22,
            ],
        ];

        foreach ($compradores as $data) {
            $this->criarComprador($data);
        }

        $this->command->info('   ✓ ' . count($compradores) . ' compradores criados');
    }

    /**
     * Criar fornecedores com assinaturas variadas
     */
    private function criarFornecedores(): void
    {
        $this->command->info('📦 Criando fornecedores...');

        $fornecedores = [
            [
                'name' => 'Marcelo Ferreira',
                'email' => 'marcelo@distribebidas.com.br',
                'cnpj' => '11.222.333/0001-44',
                'nome_empresa' => 'Distribuidora Serra Bebidas',
                'descricao' => 'Distribuição de bebidas, refrigerantes e cervejas para toda região serrana',
                'logo' => 'https://ui-avatars.com/api/?name=Serra+Bebidas&background=06B6D4&color=fff&size=400',
                'cidade' => 'Domingos Martins',
                'telefone' => '(27) 3268-1000',
                'whatsapp' => '(27) 99900-1111',
                'segmentos' => ['alimentacao'],
                'plano' => 'vip',
                'tipo_pagamento' => 'anual',
                'dias_ativa' => 250,
            ],
            [
                'name' => 'Fernanda Lima',
                'email' => 'fernanda@verdefresh.com.br',
                'cnpj' => '22.333.444/0001-55',
                'nome_empresa' => 'Verde Fresh Hortifrúti',
                'descricao' => 'Frutas, verduras e legumes frescos direto do produtor',
                'logo' => 'https://ui-avatars.com/api/?name=Verde+Fresh&background=10B981&color=fff&size=400',
                'cidade' => 'Venda Nova do Imigrante',
                'telefone' => '(27) 3266-2000',
                'whatsapp' => '(27) 99800-2222',
                'segmentos' => ['alimentacao'],
                'plano' => 'comum',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => 20,
            ],
            [
                'name' => 'Pedro Henrique Souza',
                'email' => 'pedro@laticinioserra.com.br',
                'cnpj' => '33.444.555/0001-66',
                'nome_empresa' => 'Laticínios Serra Capixaba',
                'descricao' => 'Produção e distribuição de queijos artesanais, manteiga e derivados',
                'logo' => 'https://ui-avatars.com/api/?name=Laticinios+Serra&background=FBBF24&color=000&size=400',
                'cidade' => 'Santa Maria de Jetibá',
                'telefone' => '(27) 3263-3000',
                'whatsapp' => '(27) 99700-3333',
                'segmentos' => ['alimentacao'],
                'plano' => 'vip',
                'tipo_pagamento' => 'anual',
                'dias_ativa' => 360,
            ],
            [
                'name' => 'Juliana Martins',
                'email' => 'juliana@embalpro.com.br',
                'cnpj' => '44.555.666/0001-77',
                'nome_empresa' => 'EmbalPro Distribuidora',
                'descricao' => 'Embalagens descartáveis, sacos e utensílios para food service',
                'logo' => 'https://ui-avatars.com/api/?name=EmbalPro&background=8B5CF6&color=fff&size=400',
                'cidade' => 'Marechal Floriano',
                'telefone' => '(27) 3288-4000',
                'whatsapp' => '(27) 99600-4444',
                'segmentos' => ['alimentacao', 'pet-shop'],
                'plano' => 'vip',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => 60,
            ],
            [
                'name' => 'Lucas Rodrigues',
                'email' => 'lucas@petdistribuidora.com.br',
                'cnpj' => '55.666.777/0001-88',
                'nome_empresa' => 'Pet Distribuidora ES',
                'descricao' => 'Distribuição de ração, brinquedos e acessórios para pet shops',
                'logo' => 'https://ui-avatars.com/api/?name=Pet+ES&background=F43F5E&color=fff&size=400',
                'cidade' => 'Santa Leopoldina',
                'telefone' => '(27) 3266-5000',
                'whatsapp' => '(27) 99500-5555',
                'segmentos' => ['pet-shop'],
                'plano' => 'comum',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => 8,
            ],
            [
                'name' => 'Ricardo Oliveira',
                'email' => 'ricardo@carneserra.com.br',
                'cnpj' => '66.777.888/0001-99',
                'nome_empresa' => 'Açougue Serra Prime',
                'descricao' => 'Carnes nobres e cortes especiais para restaurantes',
                'logo' => 'https://ui-avatars.com/api/?name=Serra+Prime&background=DC2626&color=fff&size=400',
                'cidade' => 'Alfredo Chaves',
                'telefone' => '(27) 3277-6000',
                'whatsapp' => '(27) 99400-6666',
                'segmentos' => ['alimentacao'],
                'plano' => 'vip',
                'tipo_pagamento' => 'anual',
                'dias_ativa' => 200,
            ],
            [
                'name' => 'Amanda Costa',
                'email' => 'amanda@peixeserra.com.br',
                'cnpj' => '77.888.999/0001-00',
                'nome_empresa' => 'Peixaria Serra Mar',
                'descricao' => 'Peixes e frutos do mar frescos, entrega diária',
                'logo' => 'https://ui-avatars.com/api/?name=Serra+Mar&background=0EA5E9&color=fff&size=400',
                'cidade' => 'Domingos Martins',
                'telefone' => '(27) 3268-7000',
                'whatsapp' => '(27) 99300-7777',
                'segmentos' => ['alimentacao'],
                'plano' => 'comum',
                'tipo_pagamento' => 'mensal',
                'dias_ativa' => -5, // Vencida
            ],
            [
                'name' => 'Thiago Mendes',
                'email' => 'thiago@padocaserra.com.br',
                'cnpj' => '88.999.000/0001-11',
                'nome_empresa' => 'Padoca da Serra',
                'descricao' => 'Pães artesanais, broas e quitandas típicas capixabas',
                'logo' => 'https://ui-avatars.com/api/?name=Padoca+Serra&background=D97706&color=fff&size=400',
                'cidade' => 'Venda Nova do Imigrante',
                'telefone' => '(27) 3266-8000',
                'whatsapp' => '(27) 99200-8888',
                'segmentos' => ['alimentacao'],
                'plano' => 'comum',
                'tipo_pagamento' => 'anual',
                'dias_ativa' => 90,
            ],
        ];

        foreach ($fornecedores as $data) {
            $this->criarFornecedor($data);
        }

        $this->command->info('   ✓ ' . count($fornecedores) . ' fornecedores criados');
    }

    /**
     * Criar comprador com todos os relacionamentos
     */
    private function criarComprador(array $data): void
    {
        $user = UserModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('senha123'),
            'role' => 'comprador',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(rand(1, 30)),
        ]);

        // Endereço
        EnderecoModel::create([
            'user_id' => $user->id,
            'tipo' => 'principal',
            'cidade' => $data['cidade'],
            'estado' => 'ES',
        ]);

        // Contatos
        ContatoModel::create([
            'user_id' => $user->id,
            'tipo' => 'telefone',
            'valor' => $data['telefone'],
            'is_principal' => true,
        ]);

        ContatoModel::create([
            'user_id' => $user->id,
            'tipo' => 'whatsapp',
            'valor' => $data['whatsapp'],
            'is_principal' => true,
        ]);

        // Perfil comprador
        CompradorModel::create([
            'user_id' => $user->id,
            'cnpj' => $data['cnpj'],
            'nome_negocio' => $data['nome_negocio'],
            'tipo_negocio' => 'Estabelecimento Food Service',
            'descricao' => $data['descricao'],
            'colaboradores' => rand(5, 30),
            'logo_path' => $data['logo'],
        ]);

        // Segmentos
        foreach ($data['segmentos'] as $segSlug) {
            if (isset($this->segmentos[$segSlug])) {
                $user->segmentos()->attach($this->segmentos[$segSlug]->id);
            }
        }

        // Assinatura
        $this->criarAssinatura($user->id, $data['plano'], $data['tipo_pagamento'], $data['dias_ativa']);
    }

    /**
     * Criar fornecedor com todos os relacionamentos
     */
    private function criarFornecedor(array $data): void
    {
        $user = UserModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('senha123'),
            'role' => 'fornecedor',
            'status' => 'aprovado',
            'aprovado_por' => 1,
            'aprovado_em' => now()->subDays(rand(1, 30)),
        ]);

        // Endereço
        EnderecoModel::create([
            'user_id' => $user->id,
            'tipo' => 'principal',
            'cidade' => $data['cidade'],
            'estado' => 'ES',
        ]);

        // Contatos
        ContatoModel::create([
            'user_id' => $user->id,
            'tipo' => 'telefone',
            'valor' => $data['telefone'],
            'is_principal' => true,
        ]);

        ContatoModel::create([
            'user_id' => $user->id,
            'tipo' => 'whatsapp',
            'valor' => $data['whatsapp'],
            'is_principal' => true,
        ]);

        // Perfil fornecedor
        FornecedorModel::create([
            'user_id' => $user->id,
            'cnpj' => $data['cnpj'],
            'nome_empresa' => $data['nome_empresa'],
            'descricao' => $data['descricao'],
            'logo_path' => $data['logo'],
        ]);

        // Segmentos
        foreach ($data['segmentos'] as $segSlug) {
            if (isset($this->segmentos[$segSlug])) {
                $user->segmentos()->attach($this->segmentos[$segSlug]->id);
            }
        }

        // Assinatura
        $this->criarAssinatura($user->id, $data['plano'], $data['tipo_pagamento'], $data['dias_ativa']);
    }

    /**
     * Criar assinatura com status baseado em dias ativos
     */
    private function criarAssinatura(int $userId, string $plano, string $tipoPagamento, int $diasAtiva): void
    {
        $meses = $tipoPagamento === 'anual' ? 12 : 1;
        $dataInicio = now()->subDays(abs($diasAtiva));
        $dataFim = $dataInicio->copy()->addMonths($meses);

        $status = 'ativo';
        if ($diasAtiva < 0) {
            $status = 'vencido';
        }

        AssinaturaModel::create([
            'user_id' => $userId,
            'plano' => $plano,
            'tipo_pagamento' => $tipoPagamento,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'status' => $status,
        ]);
    }

    /**
     * Criar talentos
     */
    private function criarTalentos(): void
    {
        $this->command->info('💼 Criando talentos...');

        $talentos = [
            [
                'nome' => 'João Silva Santos',
                'whatsapp' => '(27) 99777-6655',
                'cargo' => 'Garçom',
                'mini_curriculo' => 'Experiência de 3 anos em restaurantes. Pontual, educado, domínio de vinhos.',
                'pretensao' => 15.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Finais de semana',
            ],
            [
                'nome' => 'Maria Clara Souza',
                'whatsapp' => '(27) 99666-5544',
                'cargo' => 'Cozinheira',
                'mini_curriculo' => 'Chef de cozinha com 5 anos de experiência. Especialista em comida regional capixaba.',
                'pretensao' => 150.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Durante a semana',
            ],
            [
                'nome' => 'Pedro Costa Lima',
                'whatsapp' => '(27) 99555-4433',
                'cargo' => 'Auxiliar de Cozinha',
                'mini_curriculo' => 'Disponível para trabalhos extras aos finais de semana. Proativo e rápido.',
                'pretensao' => 60.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Finais de semana',
            ],
            [
                'nome' => 'Ana Paula Ferreira',
                'whatsapp' => '(27) 99444-3322',
                'cargo' => 'Recepcionista',
                'mini_curriculo' => 'Atendimento ao público, reservas e organização. Inglês intermediário.',
                'pretensao' => 12.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Noites e finais de semana',
            ],
            [
                'nome' => 'Lucas Henrique Alves',
                'whatsapp' => '(27) 99333-2211',
                'cargo' => 'Barman',
                'mini_curriculo' => 'Experiência em bares e eventos. Conhecimento em drinks clássicos e autorais.',
                'pretensao' => 20.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Noites',
            ],
            [
                'nome' => 'Fernanda Oliveira Costa',
                'whatsapp' => '(27) 99222-1100',
                'cargo' => 'Gerente de Salão',
                'mini_curriculo' => '7 anos em gerenciamento. Liderança de equipes, controle de estoque.',
                'pretensao' => 120.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Durante a semana',
            ],
            [
                'nome' => 'Carlos Eduardo Martins',
                'whatsapp' => '(27) 99111-0099',
                'cargo' => 'Sommelier',
                'mini_curriculo' => 'Certificado ABS. Vinhos nacionais e internacionais. Eventos corporativos.',
                'pretensao' => 35.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Finais de semana e eventos',
            ],
            [
                'nome' => 'Juliana Ribeiro Santos',
                'whatsapp' => '(27) 98999-8877',
                'cargo' => 'Confeiteira',
                'mini_curriculo' => 'Especialista em doces finos e bolos personalizados. Curso na França.',
                'pretensao' => 110.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Durante a semana',
            ],
            [
                'nome' => 'Roberto Mendes Silva',
                'whatsapp' => '(27) 98888-7766',
                'cargo' => 'Chapeiro',
                'mini_curriculo' => 'Especialista em hambúrgueres e lanches rápidos. Experiência em food trucks.',
                'pretensao' => 80.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Finais de semana',
            ],
            [
                'nome' => 'Camila Rodrigues Pinto',
                'whatsapp' => '(27) 98777-6655',
                'cargo' => 'Cumim',
                'mini_curriculo' => 'Ajudante geral de cozinha. Disponível para eventos e festivais.',
                'pretensao' => 10.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Finais de semana e eventos',
            ],
            [
                'nome' => 'Rafael Santos Oliveira',
                'whatsapp' => '(27) 98666-5544',
                'cargo' => 'Pizzaiolo',
                'mini_curriculo' => 'Especialista em pizzas napolitanas. 4 anos de experiência em forno a lenha.',
                'pretensao' => 100.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Durante a semana',
            ],
            [
                'nome' => 'Beatriz Lima Souza',
                'whatsapp' => '(27) 98555-4433',
                'cargo' => 'Atendente',
                'mini_curriculo' => 'Experiência em fast food e atendimento rápido. Simpática e organizada.',
                'pretensao' => 11.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Noites',
            ],
        ];

        foreach ($talentos as $talento) {
            TalentoModel::create(array_merge($talento, ['ativo' => true]));
        }

        $this->command->info('   ✓ ' . count($talentos) . ' talentos criados');
    }

    /**
     * Limpar dados de teste antigos
     */
    private function limparDadosTeste(): void
    {
        $this->command->info('🧹 Limpando dados de teste antigos...');
        
        // Deletar usuários não-admin (cascade vai limpar o resto)
        UserModel::whereNotIn('role', ['admin'])->delete();
        
        // Deletar talentos
        TalentoModel::truncate();
        
        $this->command->info('   ✓ Dados antigos removidos');
        $this->command->newLine();
    }

    /**
     * Exibir resumo
     */
    private function exibirResumo(): void
    {
        $this->command->newLine();
        $this->command->info('📊 RESUMO:');
        $this->command->info('   • Compradores: ' . UserModel::where('role', 'comprador')->count());
        $this->command->info('   • Fornecedores: ' . UserModel::where('role', 'fornecedor')->count());
        $this->command->info('   • Talentos: ' . TalentoModel::count());
        $this->command->info('   • Assinaturas Ativas: ' . AssinaturaModel::where('status', 'ativo')->count());
        $this->command->info('   • Assinaturas Vencidas: ' . AssinaturaModel::where('status', 'vencido')->count());
        $this->command->newLine();
        $this->command->info('🔐 LOGINS DE TESTE:');
        $this->command->info('   Admin: admin@serrafood360.com / admin123');
        $this->command->info('   Comprador: carlos@sabordaserra.com.br / senha123');
        $this->command->info('   Fornecedor: marcelo@distribebidas.com.br / senha123');
    }
}
