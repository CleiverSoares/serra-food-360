<?php

namespace App\Http\Controllers\Fornecedor;

use App\Http\Controllers\Controller;
use App\Services\FilterService;
use App\Repositories\FornecedorRepository;
use App\Models\SegmentoModel;

/**
 * Controller para Fornecedores visualizarem outros Fornecedores (parceiros/concorrentes)
 * APENAS LEITURA
 */
class FornecedorFornecedoresController extends Controller
{
    private FornecedorRepository $fornecedorRepository;
    private FilterService $filterService;

    public function __construct(
        FornecedorRepository $fornecedorRepository,
        FilterService $filterService
    ) {
        $this->fornecedorRepository = $fornecedorRepository;
        $this->filterService = $filterService;
    }

    /**
     * Lista todos os fornecedores (aprovados)
     */
    public function index()
    {
        $query = $this->fornecedorRepository->buscarTodos()
            ->with(['usuario', 'usuario.segmentos'])
            ->where('users.status', 'aprovado');

        // Aplicar filtros
        $query = $this->filterService->aplicarFiltros($query, request()->all(), 'fornecedor');

        $fornecedores = $query->paginate(12);
        $segmentos = SegmentoModel::where('ativo', true)->orderBy('nome')->get();

        return view('fornecedor.fornecedores.index', compact('fornecedores', 'segmentos'));
    }

    /**
     * Exibe detalhes de um fornecedor específico
     */
    public function show($id)
    {
        $fornecedor = $this->fornecedorRepository->buscarPorId($id);

        if (!$fornecedor || $fornecedor->usuario->status !== 'aprovado') {
            abort(404, 'Fornecedor não encontrado.');
        }

        return view('fornecedor.fornecedores.show', compact('fornecedor'));
    }
}
