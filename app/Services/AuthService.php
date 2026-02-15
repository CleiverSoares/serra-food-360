<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Autenticar usuário
     */
    public function autenticar(array $credenciais, bool $lembrar = false): bool
    {
        return Auth::attempt($credenciais, $lembrar);
    }

    /**
     * Obter usuário autenticado
     */
    public function obterUsuarioAutenticado(): ?UserModel
    {
        return Auth::user();
    }

    /**
     * Cadastrar novo usuário
     */
    public function cadastrar(array $dados): UserModel
    {
        // Hash da senha
        $dados['password'] = Hash::make($dados['password']);
        
        // Status padrão: pendente
        $dados['status'] = 'pendente';
        
        // Upload do logo se fornecido
        if (isset($dados['logo']) && $dados['logo']) {
            $dados['logo_path'] = $this->salvarLogo($dados['logo'], $dados['role']);
            unset($dados['logo']);
        }
        
        // Criar usuário
        return $this->userRepository->criar($dados);
    }

    /**
     * Fazer logout
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Verificar se usuário pode acessar sistema
     */
    public function podeAcessar(UserModel $usuario): array
    {
        // Admin sempre pode
        if ($usuario->ehAdmin()) {
            return ['pode' => true, 'rota' => 'admin.dashboard'];
        }

        // Verifica status
        if ($usuario->status === 'pendente') {
            return ['pode' => false, 'motivo' => 'pendente', 'rota' => 'aguardando'];
        }

        if ($usuario->status === 'rejeitado') {
            return ['pode' => false, 'motivo' => 'rejeitado', 'mensagem' => $usuario->motivo_rejeicao];
        }

        if ($usuario->status === 'inativo') {
            return ['pode' => false, 'motivo' => 'inativo', 'mensagem' => 'Sua assinatura está suspensa.'];
        }

        // Aprovado
        return ['pode' => true, 'rota' => 'dashboard'];
    }

    /**
     * Aprovar usuário
     */
    public function aprovar(int $usuarioId, int $adminId): bool
    {
        $usuario = $this->userRepository->buscarPorId($usuarioId);
        
        if (!$usuario) {
            return false;
        }

        return $this->userRepository->atualizar($usuario, [
            'status' => 'aprovado',
            'aprovado_por' => $adminId,
            'aprovado_em' => now(),
        ]);
    }

    /**
     * Rejeitar usuário
     */
    public function rejeitar(int $usuarioId, int $adminId, ?string $motivo = null): bool
    {
        $usuario = $this->userRepository->buscarPorId($usuarioId);
        
        if (!$usuario) {
            return false;
        }

        return $this->userRepository->atualizar($usuario, [
            'status' => 'rejeitado',
            'aprovado_por' => $adminId,
            'aprovado_em' => now(),
            'motivo_rejeicao' => $motivo,
        ]);
    }

    /**
     * Salvar logo do usuário
     */
    private function salvarLogo($arquivo, string $role): string
    {
        $pasta = $role === 'restaurante' ? 'restaurantes/logos' : 'fornecedores/logos';
        return $arquivo->store($pasta, 'public');
    }
}
