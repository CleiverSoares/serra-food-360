<?php

namespace App\Repositories;

use App\Models\FornecedorModel;

class FornecedorRepository
{
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
