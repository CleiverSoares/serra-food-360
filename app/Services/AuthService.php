<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\UserRepository;
use App\Repositories\RestauranteRepository;
use App\Repositories\FornecedorRepository;
use App\Repositories\EnderecoRepository;
use App\Repositories\ContatoRepository;
use App\Mail\NovoCadastroParaAprovacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private RestauranteRepository $restauranteRepository,
        private FornecedorRepository $fornecedorRepository,
        private EnderecoRepository $enderecoRepository,
        private ContatoRepository $contatoRepository,
        private ConfiguracaoService $configuracaoService
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
        ];
        
        // Upload do logo se fornecido
        $logoPath = null;
        if (isset($dados['logo']) && $dados['logo']) {
            $logoPath = $this->salvarLogo($dados['logo'], $role);
        }
        
        // Dados do usuário (apenas campos da tabela users - SEM telefone/whatsapp/cidade)
        $dadosUser = [
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
            'role' => $role,
            'status' => $dados['status'] ?? 'pendente', // Respeita status passado
        ];
        
        // Criar usuário
        $usuario = $this->userRepository->criar($dadosUser);
        
        // Criar endereço principal (se fornecido)
        if (isset($dados['cidade']) && $dados['cidade']) {
            $this->enderecoRepository->criarPrincipal(
                $usuario->id,
                $dados['cidade'],
                $dados['estado'] ?? 'ES'
            );
        }
        
        // Criar contato telefone (se fornecido)
        if (isset($dados['telefone']) && $dados['telefone']) {
            $this->contatoRepository->criarPrincipal(
                $usuario->id,
                'telefone',
                $dados['telefone']
            );
        }
        
        // Criar contato WhatsApp (se fornecido)
        if (isset($dados['whatsapp']) && $dados['whatsapp']) {
            $this->contatoRepository->criarPrincipal(
                $usuario->id,
                'whatsapp',
                $dados['whatsapp']
            );
        }
        
        // Associar segmentos ao usuário (usando Repository)
        if (isset($dados['segmentos']) && is_array($dados['segmentos'])) {
            $this->userRepository->associarSegmentos($usuario, $dados['segmentos']);
        }
        
        // Criar perfil específico
        if ($role === 'comprador') {
            $this->restauranteRepository->criar([
                'user_id' => $usuario->id,
                'cnpj' => $dadosPerfil['cnpj'],
                'nome_negocio' => $dadosPerfil['nome_estabelecimento'], // nome_negocio para compradores
                'descricao' => $dadosPerfil['descricao'],
                'logo_path' => $logoPath,
            ]);
        } elseif ($role === 'fornecedor') {
            $this->fornecedorRepository->criar([
                'user_id' => $usuario->id,
                'cnpj' => $dadosPerfil['cnpj'],
                'nome_empresa' => $dadosPerfil['nome_estabelecimento'], // nome_empresa para fornecedores
                'descricao' => $dadosPerfil['descricao'],
                'logo_path' => $logoPath,
            ]);
        }
        
        // Enviar email para admin se cadastro for de novo usuário externo (status pendente)
        if ($usuario->status === 'pendente') {
            try {
                $emailAdmin = $this->configuracaoService->emailAdminAprovacoes();
                Mail::to($emailAdmin)->send(new NovoCadastroParaAprovacao($usuario));
            } catch (\Exception $e) {
                // Log erro mas não impede o cadastro
                \Log::error('Erro ao enviar email de novo cadastro: ' . $e->getMessage());
            }
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
