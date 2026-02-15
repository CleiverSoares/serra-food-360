<?php

namespace App\Http\Controllers;

use App\Services\FilterService;
use App\Repositories\CompradorRepository;
use App\Models\SegmentoModel;

/**
 * Controller PÚBLICO de Compradores
 * Todos podem VER, apenas admin pode EDITAR
 */
class CompradoresController extends Controller
{
    private CompradorRepository $compradorRepository;
    private FilterService $filterService;

    public function __construct(
        CompradorRepository $compradorRepository,
        FilterService $filterService
    ) {
        $this->compradorRepository = $compradorRepository;
        $this->filterService = $filterService;
    }

    /**
     * Lista todos os compradores (apenas aprovados)
     */
    public function index()
    {
        $query = $this->compradorRepository->buscarTodos()
            ->with(['usuario', 'usuario.segmentos'])
            ->where('users.status', 'aprovado');

        // Aplicar filtros
        $query = $this->filterService->aplicarFiltros($query, request()->all(), 'comprador');

        $compradores = $query->paginate(12);
        $segmentos = SegmentoModel::where('ativo', true)->orderBy('nome')->get();

        return view('compradores.index', compact('compradores', 'segmentos'));
    }

    /**
     * Exibe detalhes de um comprador específico
     */
    public function show($id)
    {
        $comprador = $this->compradorRepository->buscarPorId($id);

        if (!$comprador || $comprador->usuario->status !== 'aprovado') {
            abort(404, 'Comprador não encontrado.');
        }

        return view('compradores.show', compact('comprador'));
    }
}
