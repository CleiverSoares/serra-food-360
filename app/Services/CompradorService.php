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
            'busca', 'segmento', 'cidade'
        ]);
        
        return $this->userRepository->buscarCompradoresComFiltros($filtros, 12);
    }

    /**
     * Buscar compradores ADMIN (todos os status)
     */
    public function buscarCompradoresAdmin(array $parametros)
    {
        $filtros = $this->filterService->prepararFiltros($parametros, [
            'busca', 'status', 'segmento', 'cidade'
        ]);
        
        // Admin vê todos (false = não filtrar apenas aprovados)
        return $this->userRepository->buscarCompradoresComFiltros($filtros, 999999, false);
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

    /**
     * Preparar dados para formulário de edição
     */
    public function prepararDadosEdicao(int $id): array
    {
        $comprador = $this->buscarCompradorAdmin($id);
        
        if (!$comprador) {
            return [];
        }
        
        $dadosContato = $this->userRepository->buscarDadosContatoEEndereco($comprador);
        $segmentosIds = $this->userRepository->obterSegmentosIds($comprador);
        
        return [
            'comprador' => $comprador,
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
        
        if (!$usuario || $usuario->role !== 'comprador' || !$usuario->comprador) {
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

        $usuario->comprador->update($dadosAtualizar);
    }
}
