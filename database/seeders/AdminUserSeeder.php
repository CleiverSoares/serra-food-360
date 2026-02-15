<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@serrafood360.com'],
            [
                'name' => 'Administrador Serra Food 360',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'aprovado',
                'telefone' => '(27) 99999-9999',
                'aprovado_em' => now(),
            ]
        );

        $this->command->info('Usuário Admin criado com sucesso!');
        $this->command->info('Email: admin@serrafood360.com');
        $this->command->info('Senha: admin123');
    }
}
