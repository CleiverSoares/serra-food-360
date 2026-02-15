<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TalentoModel;

class PopularTalentos extends Command
{
    protected $signature = 'talentos:popular';
    protected $description = 'Popular talentos com encoding correto';

    public function handle()
    {
        $this->info('Limpando talentos antigos...');
        TalentoModel::truncate();

        $talentos = [
            [
                'nome' => 'João Silva Santos',
                'whatsapp' => '(27) 99777-6655',
                'cargo' => 'Garçom',
                'mini_curriculo' => 'Experiência de 3 anos em restaurantes da serra. Pontual, educado, domínio de vinhos.',
                'pretensao' => 15.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Finais de semana',
                'ativo' => true,
            ],
            [
                'nome' => 'Maria Clara Souza',
                'whatsapp' => '(27) 99666-5544',
                'cargo' => 'Cozinheira',
                'mini_curriculo' => 'Chef de cozinha com 5 anos de experiência. Especialista em comida regional capixaba.',
                'pretensao' => 150.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Durante a semana',
                'ativo' => true,
            ],
            [
                'nome' => 'Pedro Costa Lima',
                'whatsapp' => '(27) 99555-4433',
                'cargo' => 'Auxiliar de Cozinha',
                'mini_curriculo' => 'Disponível para trabalhos extras aos finais de semana. Proativo e rápido no preparo.',
                'pretensao' => 60.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Finais de semana',
                'ativo' => true,
            ],
            [
                'nome' => 'Ana Paula Ferreira',
                'whatsapp' => '(27) 99444-3322',
                'cargo' => 'Recepcionista',
                'mini_curriculo' => 'Atendimento ao público, reservas e organização. Inglês intermediário.',
                'pretensao' => 12.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Noites e finais de semana',
                'ativo' => true,
            ],
            [
                'nome' => 'Lucas Henrique Alves',
                'whatsapp' => '(27) 99333-2211',
                'cargo' => 'Barman',
                'mini_curriculo' => 'Experiência em bares e eventos. Conhecimento em drinks clássicos e autorais.',
                'pretensao' => 20.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Noites',
                'ativo' => true,
            ],
            [
                'nome' => 'Fernanda Oliveira Costa',
                'whatsapp' => '(27) 99222-1100',
                'cargo' => 'Gerente de Salão',
                'mini_curriculo' => '7 anos de experiência em gerenciamento. Liderança de equipes, controle de estoque.',
                'pretensao' => 120.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Durante a semana',
                'ativo' => true,
            ],
            [
                'nome' => 'Carlos Eduardo Martins',
                'whatsapp' => '(27) 99111-0099',
                'cargo' => 'Sommelier',
                'mini_curriculo' => 'Certificado ABS. Conhecimento em vinhos nacionais e internacionais. Eventos corporativos.',
                'pretensao' => 35.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Finais de semana e eventos',
                'ativo' => true,
            ],
            [
                'nome' => 'Juliana Ribeiro Santos',
                'whatsapp' => '(27) 98999-8877',
                'cargo' => 'Confeiteira',
                'mini_curriculo' => 'Especialista em doces finos e bolos personalizados. Curso de confeitaria na França.',
                'pretensao' => 110.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Durante a semana',
                'ativo' => false,
            ],
            [
                'nome' => 'Roberto Mendes Silva',
                'whatsapp' => '(27) 98888-7766',
                'cargo' => 'Chapeiro',
                'mini_curriculo' => 'Especialista em hambúrgueres e lanches rápidos. Experiência em food trucks.',
                'pretensao' => 80.00,
                'tipo_cobranca' => 'dia',
                'disponibilidade' => 'Finais de semana',
                'ativo' => true,
            ],
            [
                'nome' => 'Camila Rodrigues Pinto',
                'whatsapp' => '(27) 98777-6655',
                'cargo' => 'Cumim',
                'mini_curriculo' => 'Ajudante geral de cozinha. Disponível para eventos e festivais.',
                'pretensao' => 10.00,
                'tipo_cobranca' => 'hora',
                'disponibilidade' => 'Finais de semana e eventos',
                'ativo' => true,
            ],
        ];

        foreach ($talentos as $talento) {
            TalentoModel::create($talento);
            $this->info("✓ {$talento['nome']} - {$talento['cargo']}");
        }

        $this->info('');
        $this->info('✅ ' . count($talentos) . ' talentos criados com sucesso!');
        
        return Command::SUCCESS;
    }
}
