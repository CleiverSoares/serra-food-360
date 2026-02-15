<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\UserRepository;
use App\Repositories\RestauranteRepository;
use App\Repositories\FornecedorRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private RestauranteRepository $restauranteRepository,
        private FornecedorRepository $fornecedorRepository
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
        $role = $dados['role'];
        
        // Separar dados específicos do perfil
        $dadosPerfil = [
            'cnpj' => $dados['cnpj'] ?? null,
            'nome_estabelecimento' => $dados['nome_estabelecimento'] ?? null,
            'descricao' => $dados['descricao'] ?? null,
            'categorias' => $dados['categorias'] ?? null,
        ];
        
        // Upload do logo se fornecido
        $logoPath = null;
        if (isset($dados['logo']) && $dados['logo']) {
            $logoPath = $this->salvarLogo($dados['logo'], $role);
        }
        
        // Dados do usuário (apenas campos da tabela users)
        $dadosUser = [
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
            'role' => $role,
            'status' => 'pendente',
            'telefone' => $dados['telefone'] ?? null,
            'whatsapp' => $dados['whatsapp'] ?? null,
            'cidade' => $dados['cidade'] ?? null,
        ];
        
        // Criar usuário
        $usuario = $this->userRepository->criar($dadosUser);
        
        // Criar perfil específico
        if ($role === 'comprador') {
            $this->restauranteRepository->criar([
                'user_id' => $usuario->id,
                'cnpj' => $dadosPerfil['cnpj'],
                'nome_estabelecimento' => $dadosPerfil['nome_estabelecimento'],
                'descricao' => $dadosPerfil['descricao'],
                'logo_path' => $logoPath,
            ]);
        } elseif ($role === 'fornecedor') {
            $this->fornecedorRepository->criar([
                'user_id' => $usuario->id,
                'cnpj' => $dadosPerfil['cnpj'],
                'nome_empresa' => $dadosPerfil['nome_estabelecimento'], // usa mesmo campo
                'descricao' => $dadosPerfil['descricao'],
                'categorias' => $dadosPerfil['categorias'],
                'logo_path' => $logoPath,
            ]);
        }
        
        return $usuario;
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
        $pasta = $role === 'comprador' ? 'compradores/logos' : 'fornecedores/logos';
        return $arquivo->store($pasta, 'public');
    }
}
