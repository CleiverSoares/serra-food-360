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

    /**
     * Buscar fornecedores visíveis para um comprador
     * (apenas fornecedores que compartilham segmentos)
     */
    public function buscarFornecedoresVisiveis(UserModel $comprador): Collection
    {
        $segmentosComprador = $comprador->segmentos->pluck('id');

        if ($segmentosComprador->isEmpty()) {
            return collect();
        }

        return UserModel::where('role', 'fornecedor')
            ->where('status', 'aprovado')
            ->whereHas('segmentos', function ($query) use ($segmentosComprador) {
                $query->whereIn('segmentos.id', $segmentosComprador);
            })
            ->with(['fornecedor', 'segmentos'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Buscar compradores visíveis para um fornecedor
     * (apenas compradores que compartilham segmentos)
     */
    public function buscarCompradoresVisiveis(UserModel $fornecedor): Collection
    {
        $segmentosFornecedor = $fornecedor->segmentos->pluck('id');

        if ($segmentosFornecedor->isEmpty()) {
            return collect();
        }

        return UserModel::where('role', 'comprador')
            ->where('status', 'aprovado')
            ->whereHas('segmentos', function ($query) use ($segmentosFornecedor) {
                $query->whereIn('segmentos.id', $segmentosFornecedor);
            })
            ->with(['comprador', 'segmentos'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Buscar usuários de um segmento específico
     */
    public function buscarPorSegmento(string $slug, ?string $role = null): Collection
    {
        $query = UserModel::where('status', 'aprovado')
            ->whereHas('segmentos', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->with(['segmentos']);

        if ($role) {
            $query->where('role', $role);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Listar usuários pendentes com eager loading
     */
    public function listarPendentes(): Collection
    {
        return UserModel::where('status', 'pendente')
            ->with(['comprador', 'fornecedor', 'segmentos'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Listar usuários aprovados com eager loading
     */
    public function listarAprovados(): Collection
    {
        return UserModel::where('status', 'aprovado')
            ->with(['comprador', 'fornecedor', 'segmentos', 'aprovador'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Listar compradores com eager loading
     */
    public function listarCompradores(): Collection
    {
        return UserModel::where('role', 'comprador')
            ->where('status', 'aprovado')
            ->with(['comprador', 'segmentos'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Listar fornecedores com eager loading
     */
    public function listarFornecedores(): Collection
    {
        return UserModel::where('role', 'fornecedor')
            ->where('status', 'aprovado')
            ->with(['fornecedor', 'segmentos'])
            ->orderBy('name')
            ->get();
    }
}
