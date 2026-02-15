<?php

namespace App\Services;

class FilterService
{
    /**
     * Filtros disponíveis para status
     */
    public function obterFiltrosStatus(): array
    {
        return [
            'todos' => 'Todos',
            'aprovado' => 'Aprovados',
            'pendente' => 'Pendentes',
            'rejeitado' => 'Rejeitados',
            'inativo' => 'Inativos',
        ];
    }

    /**
     * Filtros disponíveis para plano
     */
    public function obterFiltrosPlano(): array
    {
        return [
            'todos' => 'Todos',
            'comum' => 'Comum',
            'vip' => 'VIP',
        ];
    }

    /**
     * Filtros disponíveis para cidade
     */
    public function obterFiltrosCidade(): array
    {
        return [
            'Todos',
            'Domingos Martins',
            'Santa Maria de Jetibá',
            'Venda Nova do Imigrante',
            'Marechal Floriano',
            'Viana',
            'Serra',
            'Cariacica',
        ];
    }

    /**
     * Aplicar filtro de status
     */
    public function aplicarFiltroStatus($query, ?string $status)
    {
        if (!$status || $status === 'todos') {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * Aplicar filtro de plano
     */
    public function aplicarFiltroPlano($query, ?string $plano)
    {
        if (!$plano || $plano === 'todos') {
            return $query;
        }

        return $query->where('plano', $plano);
    }

    /**
     * Aplicar filtro de cidade
     */
    public function aplicarFiltroCidade($query, ?string $cidade)
    {
        if (!$cidade || $cidade === 'Todos') {
            return $query;
        }

        return $query->where('cidade', $cidade);
    }

    /**
     * Aplicar filtro de segmento
     */
    public function aplicarFiltroSegmento($query, ?int $segmentoId)
    {
        if (!$segmentoId) {
            return $query;
        }

        return $query->whereHas('segmentos', function ($q) use ($segmentoId) {
            $q->where('segmentos.id', $segmentoId);
        });
    }

    /**
     * Aplicar filtro de busca por nome
     */
    public function aplicarFiltroBusca($query, ?string $busca)
    {
        if (!$busca) {
            return $query;
        }

        return $query->where(function ($q) use ($busca) {
            $q->where('name', 'like', "%{$busca}%")
              ->orWhere('email', 'like', "%{$busca}%");
        });
    }
}
