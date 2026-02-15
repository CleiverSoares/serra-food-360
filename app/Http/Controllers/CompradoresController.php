<?php

namespace App\Http\Controllers;

use App\Services\CompradorService;
use App\Services\FilterService;
use Illuminate\Http\Request;

/**
 * Controller PÚBLICO de Compradores
 * Controller → Service → Repository → Model
 */
class CompradoresController extends Controller
{
    public function __construct(
        private CompradorService $compradorService,
        private FilterService $filterService
    ) {}

    /**
     * Lista todos os compradores (apenas aprovados)
     */
    public function index(Request $request)
    {
        // Controller apenas orquestra
        $compradores = $this->compradorService->buscarCompradoresComFiltros($request->all());
        $dadosFiltros = $this->compradorService->obterDadosFiltros();
        $filtrosAplicados = $this->filterService->extrairFiltrosAplicados($request->all());

        return view('admin.compradores.index', array_merge(
            ['compradores' => $compradores],
            $dadosFiltros,
            $filtrosAplicados
        ));
    }

    /**
     * Exibe detalhes de um comprador específico
     */
    public function show($id)
    {
        $usuario = $this->compradorService->buscarComprador($id);

        if (!$usuario) {
            abort(404, 'Comprador não encontrado.');
        }

        return view('admin.compradores.show', ['comprador' => $usuario]);
    }
}
