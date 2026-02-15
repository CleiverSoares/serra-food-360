<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Listar todos os usuários pendentes
     */
    public function listarPendentes(): Collection
    {
        return $this->userRepository->buscarPendentes();
    }

    /**
     * Listar todos os usuários aprovados
     */
    public function listarAprovados(): Collection
    {
        return $this->userRepository->buscarAprovados();
    }

    /**
     * Listar restaurantes
     */
    public function listarRestaurantes(): Collection
    {
        return $this->userRepository->buscarPorRole('restaurante');
    }

    /**
     * Listar fornecedores
     */
    public function listarFornecedores(): Collection
    {
        return $this->userRepository->buscarPorRole('fornecedor');
    }

    /**
     * Listar restaurantes VIP
     */
    public function listarRestaurantesVip(): Collection
    {
        return $this->userRepository->buscarRestaurantesVip();
    }

    /**
     * Buscar fornecedores por categorias
     */
    public function buscarFornecedoresPorCategorias(array $categorias): Collection
    {
        return $this->userRepository->buscarFornecedoresPorCategorias($categorias);
    }

    /**
     * Obter estatísticas de usuários
     */
    public function obterEstatisticas(): array
    {
        return [
            'pendentes' => $this->userRepository->contarPendentes(),
            'aprovados' => $this->userRepository->contarAprovados(),
            'restaurantes' => $this->userRepository->buscarPorRole('restaurante')->count(),
            'fornecedores' => $this->userRepository->buscarPorRole('fornecedor')->count(),
        ];
    }

    /**
     * Buscar usuário por ID
     */
    public function buscarPorId(int $id): ?UserModel
    {
        return $this->userRepository->buscarPorId($id);
    }

    /**
     * Atualizar perfil do usuário
     */
    public function atualizarPerfil(UserModel $usuario, array $dados): bool
    {
        // Se tiver logo novo, salvar
        if (isset($dados['logo']) && $dados['logo']) {
            // Deletar logo antigo se existir
            if ($usuario->logo_path) {
                Storage::disk('public')->delete($usuario->logo_path);
            }
            
            $pasta = $usuario->role === 'restaurante' ? 'restaurantes/logos' : 'fornecedores/logos';
            $dados['logo_path'] = $dados['logo']->store($pasta, 'public');
            unset($dados['logo']);
        }

        return $this->userRepository->atualizar($usuario, $dados);
    }

    /**
     * Deletar usuário
     */
    public function deletar(UserModel $usuario): bool
    {
        return $this->userRepository->deletar($usuario);
    }
}
