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

    /**
     * Buscar talentos com filtros (QUERY COMPLEXA)
     */
    public function buscarComFiltros(array $filtros, bool $apenasAtivos = false)
    {
        $query = TalentoModel::query();

        // Apenas ativos
        if ($apenasAtivos) {
            $query->where('ativo', true);
        }

        // Busca por nome, whatsapp ou cargo
        if (!empty($filtros['busca'])) {
            $busca = $filtros['busca'];
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('whatsapp', 'like', "%{$busca}%")
                  ->orWhere('cargo', 'like', "%{$busca}%");
            });
        }

        // Filtro por cargo
        if (!empty($filtros['cargo'])) {
            $query->where('cargo', 'like', "%{$filtros['cargo']}%");
        }

        // Filtro por disponibilidade
        if (!empty($filtros['disponibilidade'])) {
            $query->where('disponibilidade', 'like', "%{$filtros['disponibilidade']}%");
        }

        // Filtro por tipo de cobrança
        if (!empty($filtros['tipoCobranca'])) {
            $query->where('tipo_cobranca', $filtros['tipoCobranca']);
        }

        // Filtro por valor mínimo
        if (isset($filtros['valorMin']) && $filtros['valorMin'] !== '' && is_numeric($filtros['valorMin'])) {
            $query->where('pretensao', '>=', $filtros['valorMin']);
        }

        // Filtro por valor máximo
        if (isset($filtros['valorMax']) && $filtros['valorMax'] !== '' && is_numeric($filtros['valorMax'])) {
            $query->where('pretensao', '<=', $filtros['valorMax']);
        }

        return $query->orderBy('nome');
    }

    /**
     * Buscar cargos únicos (para filtros)
     */
    public function buscarCargosUnicos(bool $apenasAtivos = false): \Illuminate\Support\Collection
    {
        $query = TalentoModel::select('cargo')->distinct()->orderBy('cargo');
        
        if ($apenasAtivos) {
            $query->where('ativo', true);
        }
        
        return $query->pluck('cargo');
    }

    /**
     * Buscar disponibilidades únicas (para filtros)
     */
    public function buscarDisponibilidadesUnicas(bool $apenasAtivos = false): \Illuminate\Support\Collection
    {
        $query = TalentoModel::whereNotNull('disponibilidade')
            ->select('disponibilidade')
            ->distinct()
            ->orderBy('disponibilidade');
        
        if ($apenasAtivos) {
            $query->where('ativo', true);
        }
        
        return $query->pluck('disponibilidade');
    }

    /**
     * Buscar talento por ID com findOrFail
     */
    public function buscarPorIdOuFalhar(int $id): TalentoModel
    {
        return TalentoModel::findOrFail($id);
    }

    /**
     * Ativar talento
     */
    public function ativar(int $id): bool
    {
        return TalentoModel::where('id', $id)->update(['ativo' => true]);
    }

    /**
     * Inativar talento
     */
    public function inativar(int $id): bool
    {
        return TalentoModel::where('id', $id)->update(['ativo' => false]);
    }
}
