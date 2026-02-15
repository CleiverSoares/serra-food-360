<?php

namespace App\Services;

use App\Repositories\UserRepository;

/**
 * Service de Fornecedores
 * REGRAS DE NEGÓCIO específicas de fornecedores
 */
class FornecedorService
{
    public function __construct(
        private UserRepository $userRepository,
        private FilterService $filterService
    ) {}

    /**
     * Buscar fornecedores com filtros (PÚBLICO - apenas aprovados)
     */
    public function buscarFornecedoresComFiltros(array $parametros)
    {
        $filtros = $this->filterService->prepararFiltros($parametros, [
            'busca', 'segmento', 'cidade'
        ]);
        
        return $this->userRepository->buscarFornecedoresComFiltros($filtros, 12);
    }

    /**
     * Buscar fornecedores ADMIN (todos os status)
     */
    public function buscarFornecedoresAdmin(array $parametros)
    {
        $filtros = $this->filterService->prepararFiltros($parametros, [
            'busca', 'status', 'segmento', 'cidade'
        ]);
        
        // Admin vê todos
        return $this->userRepository->buscarFornecedoresComFiltros($filtros, 999999);
    }

    /**
     * Obter dados de filtros para view
     */
    public function obterDadosFiltros(): array
    {
        return $this->filterService->obterDadosFiltrosParaView('fornecedor');
    }

    /**
     * Buscar fornecedor por ID (PÚBLICO - apenas aprovados)
     */
    public function buscarFornecedor(int $id)
    {
        return $this->userRepository->buscarPorIdERole($id, 'fornecedor', 'aprovado');
    }

    /**
     * Buscar fornecedor ADMIN (qualquer status)
     */
    public function buscarFornecedorAdmin(int $id)
    {
        $fornecedor = $this->userRepository->buscarPorId($id);
        
        if (!$fornecedor || $fornecedor->role !== 'fornecedor') {
            return null;
        }
        
        $fornecedor->load(['fornecedor', 'segmentos', 'aprovador', 'enderecoPrincipal', 'contatos', 'assinaturaAtiva']);
        return $fornecedor;
    }
}
