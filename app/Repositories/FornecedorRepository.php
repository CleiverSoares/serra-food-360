<?php

namespace App\Repositories;

use App\Models\FornecedorModel;
use Illuminate\Database\Eloquent\Builder;

class FornecedorRepository
{
    /**
     * Buscar todos os fornecedores (query builder)
     */
    public function buscarTodos(): Builder
    {
        return FornecedorModel::query()->with('usuario');
    }

    /**
     * Buscar fornecedor por ID
     */
    public function buscarPorId(int $id): ?FornecedorModel
    {
        return FornecedorModel::with('usuario')->find($id);
    }

    /**
     * Criar novo fornecedor
     */
    public function criar(array $dados): FornecedorModel
    {
        return FornecedorModel::create($dados);
    }

    /**
     * Buscar fornecedor por user_id
     */
    public function buscarPorUserId(int $userId): ?FornecedorModel
    {
        return FornecedorModel::where('user_id', $userId)->first();
    }

    /**
     * Atualizar fornecedor
     */
    public function atualizar(FornecedorModel $fornecedor, array $dados): bool
    {
        return $fornecedor->update($dados);
    }
}
