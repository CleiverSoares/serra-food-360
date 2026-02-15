<?php

namespace App\Http\Controllers;

use App\Services\TalentoService;
use App\Services\FilterService;
use Illuminate\Http\Request;

/**
 * Controller PÚBLICO de Talentos
 * Controller → Service → Repository → Model
 */
class TalentosController extends Controller
{
    public function __construct(
        private TalentoService $talentoService,
        private FilterService $filterService
    ) {}

    /**
     * Lista todos os talentos disponíveis
     */
    public function index(Request $request)
    {
        // REGRA: Fornecedores NÃO veem talentos
        if (auth()->user()->role === 'fornecedor') {
            abort(403, 'Fornecedores não têm acesso ao banco de talentos.');
        }

        // Controller apenas orquestra
        $talentos = $this->talentoService->buscarTalentosComFiltros($request->all());
        $dadosFiltros = $this->talentoService->obterDadosFiltros(true);
        $filtrosAplicados = $this->filterService->extrairFiltrosAplicados($request->all());

        return view('admin.talentos.index', array_merge(
            ['talentos' => $talentos],
            $dadosFiltros,
            $filtrosAplicados
        ));
    }

    /**
     * Exibe detalhes de um talento específico
     */
    public function show($id)
    {
        // REGRA: Fornecedores NÃO veem talentos
        if (auth()->user()->role === 'fornecedor') {
            abort(403, 'Fornecedores não têm acesso ao banco de talentos.');
        }

        $talento = $this->talentoService->buscarPorIdOuFalhar($id);

        if (!$talento->ativo) {
            abort(404, 'Talento não encontrado.');
        }

        return view('admin.talentos.show', compact('talento'));
    }
}
