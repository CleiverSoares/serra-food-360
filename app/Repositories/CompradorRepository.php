<?php

namespace App\Repositories;

use App\Models\CompradorModel;
use Illuminate\Database\Eloquent\Builder;

class CompradorRepository
{
    /**
     * Buscar todos os compradores (query builder)
     */
    public function buscarTodos(): Builder
    {
        return CompradorModel::query()->with('usuario');
    }

    /**
     * Buscar comprador por ID
     */
    public function buscarPorId(int $id): ?CompradorModel
    {
        return CompradorModel::with('usuario')->find($id);
    }

    /**
     * Criar novo comprador
     */
    public function criar(array $dados): CompradorModel
    {
        return CompradorModel::create($dados);
    }

    /**
     * Buscar comprador por user_id
     */
    public function buscarPorUserId(int $userId): ?CompradorModel
    {
        return CompradorModel::where('user_id', $userId)->first();
    }

    /**
     * Atualizar comprador
     */
    public function atualizar(CompradorModel $comprador, array $dados): bool
    {
        return $comprador->update($dados);
    }

    /**
     * Deletar comprador
     */
    public function deletar(CompradorModel $comprador): bool
    {
        return $comprador->delete();
    }
}
