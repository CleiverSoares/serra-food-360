<?php

namespace App\Repositories;

use App\Models\ContatoModel;
use Illuminate\Database\Eloquent\Collection;

class ContatoRepository
{
    /**
     * Buscar contato principal por tipo
     */
    public function buscarPrincipalPorTipo(int $userId, string $tipo): ?ContatoModel
    {
        return ContatoModel::where('user_id', $userId)
            ->where('tipo', $tipo)
            ->where('is_principal', true)
            ->first();
    }

    /**
     * Buscar todos os contatos de um usuário
     */
    public function buscarPorUserId(int $userId): Collection
    {
        return ContatoModel::where('user_id', $userId)
            ->orderBy('is_principal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Buscar por tipo
     */
    public function buscarPorTipo(int $userId, string $tipo): Collection
    {
        return ContatoModel::where('user_id', $userId)
            ->where('tipo', $tipo)
            ->get();
    }

    /**
     * Criar contato
     */
    public function criar(array $dados): ContatoModel
    {
        return ContatoModel::create($dados);
    }

    /**
     * Criar contato principal para um usuário
     */
    public function criarPrincipal(int $userId, string $tipo, string $valor): ContatoModel
    {
        return ContatoModel::create([
            'user_id' => $userId,
            'tipo' => $tipo,
            'valor' => $valor,
            'is_principal' => true,
        ]);
    }

    /**
     * Atualizar contato
     */
    public function atualizar(int $id, array $dados): bool
    {
        return ContatoModel::where('id', $id)->update($dados);
    }

    /**
     * Deletar contato
     */
    public function deletar(int $id): bool
    {
        return ContatoModel::where('id', $id)->delete();
    }

    /**
     * Buscar por valor (telefone/whatsapp)
     */
    public function buscarPorValor(string $valor): Collection
    {
        $valorLimpo = preg_replace('/[^0-9]/', '', $valor);
        return ContatoModel::whereRaw("REPLACE(REPLACE(REPLACE(valor, '(', ''), ')', ''), '-', '') LIKE ?", ["%{$valorLimpo}%"])
            ->get();
    }
}
