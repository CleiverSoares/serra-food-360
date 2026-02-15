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
}
