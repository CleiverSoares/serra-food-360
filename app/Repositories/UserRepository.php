<?php

namespace App\Repositories;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Buscar usuário por email
     */
    public function buscarPorEmail(string $email): ?UserModel
    {
        return UserModel::where('email', $email)->first();
    }

    /**
     * Buscar usuário por ID
     */
    public function buscarPorId(int $id): ?UserModel
    {
        return UserModel::find($id);
    }

    /**
     * Criar novo usuário
     */
    public function criar(array $dados): UserModel
    {
        return UserModel::create($dados);
    }

    /**
     * Atualizar usuário
     */
    public function atualizar(UserModel $usuario, array $dados): bool
    {
        return $usuario->update($dados);
    }

    /**
     * Deletar usuário
     */
    public function deletar(UserModel $usuario): bool
    {
        return $usuario->delete();
    }

    /**
     * Buscar todos os usuários pendentes
     */
    public function buscarPendentes(): Collection
    {
        return UserModel::pendentes()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar todos os usuários aprovados
     */
    public function buscarAprovados(): Collection
    {
        return UserModel::aprovados()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar usuários por role
     */
    public function buscarPorRole(string $role): Collection
    {
        return UserModel::porRole($role)
            ->aprovados()
            ->orderBy('name')
            ->get();
    }

    /**
     * Buscar restaurantes VIP
     */
    public function buscarRestaurantesVip(): Collection
    {
        return UserModel::where('role', 'restaurante')
            ->where('plano', 'vip')
            ->aprovados()
            ->orderBy('name')
            ->get();
    }

    /**
     * Buscar fornecedores por categorias
     */
    public function buscarFornecedoresPorCategorias(array $categorias): Collection
    {
        return UserModel::where('role', 'fornecedor')
            ->aprovados()
            ->where(function ($query) use ($categorias) {
                foreach ($categorias as $categoria) {
                    $query->orWhereJsonContains('categorias', $categoria);
                }
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Contar usuários pendentes
     */
    public function contarPendentes(): int
    {
        return UserModel::pendentes()->count();
    }

    /**
     * Contar usuários aprovados
     */
    public function contarAprovados(): int
    {
        return UserModel::aprovados()->count();
    }

    /**
     * Verificar se email já existe
     */
    public function emailExiste(string $email): bool
    {
        return UserModel::where('email', $email)->exists();
    }
}
