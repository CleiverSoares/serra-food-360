<?php

namespace App\Repositories;

use App\Models\SegmentoModel;

class SegmentoRepository
{
    /**
     * Buscar todos os segmentos ativos
     */
    public function buscarAtivos()
    {
        return SegmentoModel::where('ativo', true)
            ->orderBy('nome')
            ->get();
    }

    /**
     * Buscar todos os segmentos
     */
    public function buscarTodos()
    {
        return SegmentoModel::orderBy('nome')->get();
    }

    /**
     * Buscar segmento por slug
     */
    public function buscarPorSlug(string $slug)
    {
        return SegmentoModel::where('slug', $slug)->first();
    }

    /**
     * Buscar segmento por ID
     */
    public function buscarPorId(int $id)
    {
        return SegmentoModel::find($id);
    }

    /**
     * Criar novo segmento
     */
    public function criar(array $dados)
    {
        return SegmentoModel::create($dados);
    }

    /**
     * Atualizar segmento
     */
    public function atualizar(SegmentoModel $segmento, array $dados): bool
    {
        return $segmento->update($dados);
    }

    /**
     * Deletar segmento
     */
    public function deletar(SegmentoModel $segmento): bool
    {
        return $segmento->delete();
    }

    /**
     * Buscar todos com contagem de users
     */
    public function buscarTodosComContagem()
    {
        return SegmentoModel::withCount('users')
            ->orderBy('nome')
            ->get();
    }

    /**
     * Buscar por ID com contagem de users
     */
    public function buscarPorIdComContagem(int $id): ?SegmentoModel
    {
        return SegmentoModel::withCount('users')->find($id);
    }

    /**
     * Buscar por ID ou falhar com contagem
     */
    public function buscarPorIdComContagemOuFalhar(int $id): SegmentoModel
    {
        return SegmentoModel::withCount('users')->findOrFail($id);
    }

    /**
     * Ativar segmento
     */
    public function ativar(int $id): bool
    {
        return SegmentoModel::where('id', $id)->update(['ativo' => true]);
    }

    /**
     * Inativar segmento
     */
    public function inativar(int $id): bool
    {
        return SegmentoModel::where('id', $id)->update(['ativo' => false]);
    }
}
