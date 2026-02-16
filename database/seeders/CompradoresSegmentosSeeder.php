<?php

namespace Database\Seeders;

use App\Models\UserModel;
use App\Models\CompradorModel;
use App\Models\EnderecoModel;
use App\Models\ContatoModel;
use App\Models\SegmentoModel;
use App\Services\AssinaturaService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompradoresSegmentosSeeder extends Seeder
{
    public function run(): void
    {
        echo "👥 Criando compradores em diversos segmentos...\n\n";

        $assinaturaService = app(AssinaturaService::class);
        $segmentos = SegmentoModel::all();

        if ($segmentos->count() < 5) {
            echo "❌ Execute o seeder de segmentos extras primeiro!\n";
            return;
        }

        $compradores = [
            // Restaurantes
            [
                'email' => 'bistro@example.com',
                'nome' => 'Bistro Gourmet',
                'tipo' => 'Bistrô',
                'cidade' => 'Vitória',
                'segmentos' => ['Restaurante', 'Cafeteria'],
                'plano' => 'vip',
            ],
            [
                'email' => 'cozinha.italiana@example.com',
                'nome' => 'Cozinha Italiana',
                'tipo' => 'Restaurante',
                'cidade' => 'Serra',
                'segmentos' => ['Restaurante', 'Pizzaria'],
                'plano' => 'comum',
            ],

            // Padarias
            [
                'email' => 'padaria.artesanal@example.com',
                'nome' => 'Padaria Artesanal do Vale',
                'tipo' => 'Padaria',
                'cidade' => 'Domingos Martins',
                'segmentos' => ['Padaria'],
                'plano' => 'comum',
            ],
            [
                'email' => 'doce.sabor@example.com',
                'nome' => 'Doce Sabor Confeitaria',
                'tipo' => 'Confeitaria',
                'cidade' => 'Vila Velha',
                'segmentos' => ['Padaria', 'Cafeteria'],
                'plano' => 'vip',
            ],

            // Lanchonetes
            [
                'email' => 'burger.station@example.com',
                'nome' => 'Burger Station',
                'tipo' => 'Hamburgueria',
                'cidade' => 'Vitória',
                'segmentos' => ['Lanchonete'],
                'plano' => 'comum',
            ],
            [
                'email' => 'foodtruck.serra@example.com',
                'nome' => 'Food Truck da Serra',
                'tipo' => 'Food Truck',
                'cidade' => 'Serra',
                'segmentos' => ['Lanchonete', 'Churrascaria'],
                'plano' => 'comum',
            ],

            // Pizzarias
            [
                'email' => 'pizza.napolitana@example.com',
                'nome' => 'Pizza Napolitana',
                'tipo' => 'Pizzaria',
                'cidade' => 'Vila Velha',
                'segmentos' => ['Pizzaria'],
                'plano' => 'vip',
            ],

            // Hotéis
            [
                'email' => 'hotel.serra@example.com',
                'nome' => 'Hotel Serra Verde',
                'tipo' => 'Hotel',
                'cidade' => 'Domingos Martins',
                'segmentos' => ['Hotel', 'Restaurante', 'Cafeteria'],
                'plano' => 'vip',
            ],

            // Buffets
            [
                'email' => 'buffet.eventos@example.com',
                'nome' => 'Buffet Eventos & Festas',
                'tipo' => 'Buffet',
                'cidade' => 'Vitória',
                'segmentos' => ['Buffet', 'Restaurante'],
                'plano' => 'vip',
            ],

            // Churrascarias
            [
                'email' => 'churrasco.gaucho@example.com',
                'nome' => 'Churrascaria Gaúcho',
                'tipo' => 'Churrascaria',
                'cidade' => 'Serra',
                'segmentos' => ['Churrascaria', 'Restaurante'],
                'plano' => 'comum',
            ],

            // Cafeterias
            [
                'email' => 'cafe.especial@example.com',
                'nome' => 'Café Especial Capixaba',
                'tipo' => 'Cafeteria',
                'cidade' => 'Vitória',
                'segmentos' => ['Cafeteria'],
                'plano' => 'comum',
            ],
        ];

        $criados = 0;

        foreach ($compradores as $dados) {
            // Verificar se já existe
            if (UserModel::where('email', $dados['email'])->exists()) {
                echo "⏭️  Pulando: {$dados['email']} (já existe)\n";
                continue;
            }

            // Criar usuário
            $usuario = UserModel::create([
                'name' => $dados['nome'],
                'email' => $dados['email'],
                'password' => Hash::make('12345678'),
                'role' => 'comprador',
                'status' => 'aprovado',
            ]);

            // Criar perfil de comprador
            $comprador = CompradorModel::create([
                'user_id' => $usuario->id,
                'nome_negocio' => $dados['nome'],
                'tipo_negocio' => $dados['tipo'],
                'descricao' => "Negócio de {$dados['tipo']} localizado em {$dados['cidade']}",
            ]);

            // Criar endereço
            EnderecoModel::create([
                'user_id' => $usuario->id,
                'logradouro' => 'Rua Principal',
                'numero' => rand(100, 999),
                'bairro' => 'Centro',
                'cidade' => $dados['cidade'],
                'estado' => 'ES',
                'cep' => '29000-000',
            ]);

            // Criar contato
            ContatoModel::create([
                'user_id' => $usuario->id,
                'tipo' => 'whatsapp',
                'valor' => '27' . rand(90000000, 99999999),
                'is_principal' => true,
            ]);

            // Vincular segmentos
            $segmentosIds = [];
            foreach ($dados['segmentos'] as $nomeSegmento) {
                $segmento = $segmentos->firstWhere('nome', $nomeSegmento);
                if ($segmento) {
                    $segmentosIds[] = $segmento->id;
                }
            }
            $usuario->segmentos()->sync($segmentosIds);

            // Criar assinatura
            $assinaturaService->criarAssinatura(
                $usuario->id,
                $dados['plano'],
                'mensal'
            );

            $criados++;
            echo "✅ {$dados['nome']} ({$dados['tipo']}) - Segmentos: " . implode(', ', $dados['segmentos']) . "\n";
        }

        echo "\n✨ Total: {$criados} compradores criados!\n";
        echo "📧 Login: email do comprador | Senha: 12345678\n";
    }
}
