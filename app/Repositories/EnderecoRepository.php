<?php

namespace App\Repositories;

use App\Models\EnderecoModel;
use Illuminate\Database\Eloquent\Collection;

class EnderecoRepository
{
    /**
     * Buscar endereço principal de um usuário
     */
    public function buscarPrincipalPorUserId(int $userId): ?EnderecoModel
    {
        return EnderecoModel::where('user_id', $userId)
            ->where('is_padrao', true)
            ->first();
    }

    /**
     * Buscar todos os endereços de um usuário
     */
    public function buscarPorUserId(int $userId): Collection
    {
        return EnderecoModel::where('user_id', $userId)
            ->orderBy('is_padrao', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar cidades únicas por role de usuário
     */
    public function buscarCidadesUnicasPorRole(string $role, string $status = 'aprovado'): array
    {
        return EnderecoModel::whereHas('usuario', function($q) use ($role, $status) {
                $q->where('role', $role)
                  ->where('status', $status);
            })
            ->distinct()
            ->pluck('cidade')
            ->toArray();
    }

    /**
     * Criar endereço
     */
    public function criar(array $dados): EnderecoModel
    {
        return EnderecoModel::create($dados);
    }

    /**
     * Criar endereço principal para um usuário
     */
    public function criarPrincipal(int $userId, string $cidade, string $estado = 'ES'): EnderecoModel
    {
        return EnderecoModel::create([
            'user_id' => $userId,
            'tipo' => 'principal',
            'cidade' => $cidade,
            'estado' => $estado,
            'pais' => 'BR',
            'is_padrao' => true,
        ]);
    }

    /**
     * Atualizar endereço
     */
    public function atualizar(int $id, array $dados): bool
    {
        return EnderecoModel::where('id', $id)->update($dados);
    }

    /**
     * Deletar endereço
     */
    public function deletar(int $id): bool
    {
        return EnderecoModel::where('id', $id)->delete();
    }

    /**
     * Buscar por CEP
     */
    public function buscarPorCep(string $cep): Collection
    {
        return EnderecoModel::where('cep', $cep)->get();
    }

    /**
     * Buscar por cidade e estado
     */
    public function buscarPorCidadeEstado(string $cidade, string $estado): Collection
    {
        return EnderecoModel::where('cidade', $cidade)
            ->where('estado', $estado)
            ->get();
    }
}
