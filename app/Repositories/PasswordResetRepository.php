<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetRepository
{
    /**
     * Criar token de reset
     */
    public function criarToken(string $email): string
    {
        // Deletar tokens antigos
        $this->deletarPorEmail($email);
        
        // Gerar novo token
        $token = Str::random(64);
        
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);
        
        return $token;
    }

    /**
     * Buscar token válido
     */
    public function buscarTokenValido(string $email, string $token): ?object
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->where('created_at', '>=', Carbon::now()->subHours(1)) // Válido por 1 hora
            ->first();
    }

    /**
     * Deletar token por email
     */
    public function deletarPorEmail(string $email): void
    {
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }

    /**
     * Deletar token específico
     */
    public function deletarToken(string $token): void
    {
        DB::table('password_reset_tokens')->where('token', $token)->delete();
    }

    /**
     * Verificar se email existe e tem token
     */
    public function temTokenPendente(string $email): bool
    {
        return DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('created_at', '>=', Carbon::now()->subHours(1))
            ->exists();
    }
}
