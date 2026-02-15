<?php

namespace App\Services;

use App\Repositories\UserRepository;

/**
 * Service de Compradores
 * REGRAS DE NEGÓCIO específicas de compradores
 */
class CompradorService
{
    public function __construct(
        private UserRepository $userRepository,
        private FilterService $filterService
    ) {}

    /**
     * Buscar compradores com filtros (PÚBLICO - apenas aprovados)
     */
    public function buscarCompradoresComFiltros(array $parametros)
    {
        $filtros = $this->filterService->prepararFiltros($parametros, [
            'busca', 'plano', 'segmento', 'cidade'
        ]);
        
        return $this->userRepository->buscarCompradoresComFiltros($filtros, 12);
    }

    /**
     * Buscar compradores ADMIN (todos os status)
     */
    public function buscarCompradoresAdmin(array $parametros)
    {
        $filtros = $this->filterService->prepararFiltros($parametros, [
            'busca', 'status', 'plano', 'segmento', 'cidade'
        ]);
        
        // Admin vê todos (999999 = sem paginação)
        return $this->userRepository->buscarCompradoresComFiltros($filtros, 999999)->items();
    }

    /**
     * Obter dados de filtros para view
     */
    public function obterDadosFiltros(): array
    {
        return $this->filterService->obterDadosFiltrosParaView('comprador');
    }

    /**
     * Buscar comprador por ID (PÚBLICO - apenas aprovados)
     */
    public function buscarComprador(int $id)
    {
        return $this->userRepository->buscarPorIdERole($id, 'comprador', 'aprovado');
    }

    /**
     * Buscar comprador ADMIN (qualquer status)
     */
    public function buscarCompradorAdmin(int $id)
    {
        $comprador = $this->userRepository->buscarPorId($id);
        
        if (!$comprador || $comprador->role !== 'comprador') {
            return null;
        }
        
        $comprador->load(['comprador', 'segmentos', 'aprovador', 'enderecoPrincipal', 'contatos', 'assinaturaAtiva']);
        return $comprador;
    }
}
