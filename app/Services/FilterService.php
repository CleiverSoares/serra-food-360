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
     * Remove valores vazios, nulos e strings só com espaços
     */
    public function prepararFiltros(array $parametros, array $camposPermitidos = []): array
    {
        $filtros = [];
        
        foreach ($camposPermitidos as $campo) {
            $valor = $parametros[$campo] ?? '';
            
            // Limpar espaços e verificar se não está vazio
            $valor = is_string($valor) ? trim($valor) : $valor;
            
            // Só adiciona ao filtro se tiver valor real
            if ($valor !== '' && $valor !== null) {
                $filtros[$campo] = $valor;
            }
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
     * Extrair filtros aplicados para passar para view
     */
    public function extrairFiltrosAplicados(array $parametros): array
    {
        // Limpa valores vazios e espaços em branco
        $limpar = function($valor) {
            return is_string($valor) ? trim($valor) : $valor;
        };
        
        return [
            'busca' => $limpar($parametros['busca'] ?? ''),
            'status' => $limpar($parametros['status'] ?? ''),
            'cidade' => $limpar($parametros['cidade'] ?? ''),
            'segmentoId' => $limpar($parametros['segmento'] ?? ''),
            'cargo' => $limpar($parametros['cargo'] ?? ''),
            'disponibilidade' => $limpar($parametros['disponibilidade'] ?? ''),
            'tipoCobranca' => $limpar($parametros['tipo_cobranca'] ?? ''),
            'valorMin' => $limpar($parametros['valor_min'] ?? ''),
            'valorMax' => $limpar($parametros['valor_max'] ?? ''),
        ];
    }
}
