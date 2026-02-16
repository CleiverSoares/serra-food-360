<?php

namespace App\Services;

use App\Repositories\PasswordResetRepository;
use App\Repositories\UserRepository;
use App\Mail\RedefinirSenha;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetService
{
    public function __construct(
        private PasswordResetRepository $passwordResetRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Enviar link de redefinição de senha
     */
    public function enviarLinkRedefinicao(string $email): bool
    {
        \Log::info('[RECUPERAÇÃO SENHA] Solicitação recebida', ['email' => $email]);

        // Verificar se usuário existe
        $usuario = $this->userRepository->buscarPorEmail($email);
        
        if (!$usuario) {
            \Log::warning('[RECUPERAÇÃO SENHA] Email não encontrado', ['email' => $email]);
            return false;
        }

        \Log::info('[RECUPERAÇÃO SENHA] Usuário encontrado', [
            'user_id' => $usuario->id,
            'email' => $email,
            'nome' => $usuario->name
        ]);

        // Criar token
        $token = $this->passwordResetRepository->criarToken($email);
        \Log::info('[RECUPERAÇÃO SENHA] Token gerado', ['email' => $email]);

        // Enviar email
        try {
            Mail::to($email)->send(new RedefinirSenha($token));
            \Log::info('[RECUPERAÇÃO SENHA] Email enviado com sucesso', ['email' => $email]);
            return true;
        } catch (\Exception $e) {
            \Log::error('[RECUPERAÇÃO SENHA] Erro ao enviar email', [
                'email' => $email,
                'erro' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Validar token de reset
     */
    public function validarToken(string $email, string $token): bool
    {
        $tokenData = $this->passwordResetRepository->buscarTokenValido($email, $token);
        return $tokenData !== null;
    }

    /**
     * Redefinir senha
     */
    public function redefinirSenha(string $email, string $token, string $novaSenha): bool
    {
        \Log::info('[REDEFINIR SENHA] Tentativa de redefinição', ['email' => $email]);

        // Validar token
        if (!$this->validarToken($email, $token)) {
            \Log::warning('[REDEFINIR SENHA] Token inválido ou expirado', ['email' => $email]);
            return false;
        }

        // Buscar usuário
        $usuario = $this->userRepository->buscarPorEmail($email);
        
        if (!$usuario) {
            \Log::warning('[REDEFINIR SENHA] Usuário não encontrado', ['email' => $email]);
            return false;
        }

        // Atualizar senha
        $usuario->password = Hash::make($novaSenha);
        $usuario->save();

        \Log::info('[REDEFINIR SENHA] Senha atualizada com sucesso', [
            'user_id' => $usuario->id,
            'email' => $email,
            'nome' => $usuario->name
        ]);

        // Deletar token usado
        $this->passwordResetRepository->deletarPorEmail($email);

        return true;
    }

    /**
     * Verificar se email tem token pendente
     */
    public function temTokenPendente(string $email): bool
    {
        return $this->passwordResetRepository->temTokenPendente($email);
    }
}
