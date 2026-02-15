<?php

namespace App\Repositories;

use App\Models\TalentoModel;
use Illuminate\Database\Eloquent\Collection;

class TalentoRepository
{
    /**
     * Buscar todos os talentos
     */
    public function buscarTodos(): Collection
    {
        return TalentoModel::orderBy('nome')->get();
    }

    /**
     * Buscar talento por ID
     */
    public function buscarPorId(int $id): ?TalentoModel
    {
        return TalentoModel::find($id);
    }

    /**
     * Criar novo talento
     */
    public function criar(array $dados): TalentoModel
    {
        return TalentoModel::create($dados);
    }

    /**
     * Atualizar talento
     */
    public function atualizar(TalentoModel $talento, array $dados): bool
    {
        return $talento->update($dados);
    }

    /**
     * Deletar talento
     */
    public function deletar(TalentoModel $talento): bool
    {
        return $talento->delete();
    }

    /**
     * Buscar talentos por cargo
     */
    public function buscarPorCargo(string $cargo): Collection
    {
        return TalentoModel::where('cargo', $cargo)
            ->orderBy('nome')
            ->get();
    }

    /**
     * Buscar talentos com pretensão até valor
     */
    public function buscarPorPretensaoMaxima(float $valorMaximo): Collection
    {
        return TalentoModel::where('pretensao', '<=', $valorMaximo)
            ->orWhereNull('pretensao')
            ->orderBy('pretensao')
            ->get();
    }

    /**
     * Contar total de talentos
     */
    public function contar(): int
    {
        return TalentoModel::count();
    }
}
