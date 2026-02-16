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
        
        // Admin vê todos (false = não filtrar apenas aprovados)
        return $this->userRepository->buscarFornecedoresComFiltros($filtros, 999999, false);
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

    /**
     * Preparar dados para formulário de edição
     */
    public function prepararDadosEdicao(int $id): array
    {
        $fornecedor = $this->buscarFornecedorAdmin($id);
        
        if (!$fornecedor) {
            return [];
        }
        
        $dadosContato = $this->userRepository->buscarDadosContatoEEndereco($fornecedor);
        $segmentosIds = $this->userRepository->obterSegmentosIds($fornecedor);
        
        return [
            'fornecedor' => $fornecedor,
            'dadosContato' => $dadosContato,
            'segmentosIds' => $segmentosIds,
        ];
    }

    /**
     * Atualizar dados do negócio
     */
    public function atualizarDadosNegocio(int $userId, array $dados): void
    {
        $usuario = $this->userRepository->buscarPorId($userId);
        
        if (!$usuario || $usuario->role !== 'fornecedor' || !$usuario->fornecedor) {
            return;
        }

        $dadosAtualizar = [
            'nome_negocio' => $dados['nome_negocio'] ?? null,
            'cnpj' => $dados['cnpj'] ?? null,
            'tipo_negocio' => $dados['tipo_negocio'] ?? null,
            'colaboradores' => $dados['colaboradores'] ?? null,
            'site_url' => $dados['site_url'] ?? null,
            'descricao' => $dados['descricao'] ?? null,
        ];
        
        // Adicionar logo_path se fornecido
        if (isset($dados['logo_path'])) {
            $dadosAtualizar['logo_path'] = $dados['logo_path'];
        }

        $usuario->fornecedor->update($dadosAtualizar);
    }
}
