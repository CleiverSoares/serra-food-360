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
        // Verificar se usuário existe
        $usuario = $this->userRepository->buscarPorEmail($email);
        
        if (!$usuario) {
            return false;
        }

        // Criar token
        $token = $this->passwordResetRepository->criarToken($email);

        // Enviar email
        try {
            Mail::to($email)->send(new RedefinirSenha($token));
            return true;
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de reset: ' . $e->getMessage());
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
        // Validar token
        if (!$this->validarToken($email, $token)) {
            return false;
        }

        // Buscar usuário
        $usuario = $this->userRepository->buscarPorEmail($email);
        
        if (!$usuario) {
            return false;
        }

        // Atualizar senha
        $usuario->password = Hash::make($novaSenha);
        $usuario->save();

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
