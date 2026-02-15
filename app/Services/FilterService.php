<?php

namespace App\Services;

use App\Repositories\EnderecoRepository;
use App\Repositories\SegmentoRepository;

/**
 * Service GENÉRICO de Filtros
 * Reutilizável para qualquer entidade (Comprador, Fornecedor, Talento, etc)
 */
class FilterService
{
    public function __construct(
        private EnderecoRepository $enderecoRepository,
        private SegmentoRepository $segmentoRepository
    ) {}

    /**
     * Preparar filtros de request (GENÉRICO)
     */
    public function prepararFiltros(array $parametros, array $camposPermitidos = []): array
    {
        $filtros = [];
        
        foreach ($camposPermitidos as $campo) {
            $filtros[$campo] = $parametros[$campo] ?? '';
        }
        
        return $filtros;
    }

    /**
     * Obter dados de filtros para view (DINÂMICO)
     */
    public function obterDadosFiltrosParaView(string $role = null): array
    {
        $dados = [
            'filtrosStatus' => $this->obterOpcoesStatus(),
            'filtrosPlano' => $this->obterOpcoesPlano(),
            'segmentos' => $this->segmentoRepository->buscarAtivos(),
        ];

        // Adicionar cidades se role for especificado
        if ($role) {
            $dados['filtrosCidade'] = $this->enderecoRepository->buscarCidadesUnicasPorRole($role);
        }

        return $dados;
    }

    /**
     * Opções de filtro de status
     */
    public function obterOpcoesStatus(): array
    {
        return [
            '' => 'Todos',
            'aprovado' => 'Aprovados',
            'pendente' => 'Pendentes',
            'rejeitado' => 'Rejeitados',
            'inativo' => 'Inativos',
        ];
    }

    /**
     * Opções de filtro de plano
     */
    public function obterOpcoesPlano(): array
    {
        return [
            '' => 'Todos',
            'gratuito' => 'Gratuito',
            'basico' => 'Básico',
            'premium' => 'Premium',
            'comum' => 'Comum',
            'vip' => 'VIP',
        ];
    }

    /**
     * Extrair filtros aplicados para passar para view
     */
    public function extrairFiltrosAplicados(array $parametros): array
    {
        return [
            'busca' => $parametros['busca'] ?? '',
            'status' => $parametros['status'] ?? '',
            'plano' => $parametros['plano'] ?? '',
            'cidade' => $parametros['cidade'] ?? '',
            'segmentoId' => $parametros['segmento'] ?? '',
            'cargo' => $parametros['cargo'] ?? '',
            'disponibilidade' => $parametros['disponibilidade'] ?? '',
            'tipoCobranca' => $parametros['tipo_cobranca'] ?? '',
            'valorMin' => $parametros['valor_min'] ?? '',
            'valorMax' => $parametros['valor_max'] ?? '',
        ];
    }
}
