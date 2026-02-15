<?php

namespace App\Http\Controllers;

use App\Services\FornecedorService;
use App\Services\FilterService;
use Illuminate\Http\Request;

/**
 * Controller PÚBLICO de Fornecedores
 * Controller → Service → Repository → Model
 */
class FornecedoresController extends Controller
{
    public function __construct(
        private FornecedorService $fornecedorService,
        private FilterService $filterService
    ) {}

    /**
     * Lista todos os fornecedores (apenas aprovados)
     */
    public function index(Request $request)
    {
        // Controller apenas orquestra
        $fornecedores = $this->fornecedorService->buscarFornecedoresComFiltros($request->all());
        $dadosFiltros = $this->fornecedorService->obterDadosFiltros();
        $filtrosAplicados = $this->filterService->extrairFiltrosAplicados($request->all());

        return view('admin.fornecedores.index', array_merge(
            ['fornecedores' => $fornecedores],
            $dadosFiltros,
            $filtrosAplicados
        ));
    }

    /**
     * Exibe detalhes de um fornecedor específico
     */
    public function show($id)
    {
        $usuario = $this->fornecedorService->buscarFornecedor($id);

        if (!$usuario) {
            abort(404, 'Fornecedor não encontrado.');
        }

        return view('admin.fornecedores.show', ['fornecedor' => $usuario]);
    }
}
