<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ConfiguracaoService;
use App\Repositories\HistoricoPrecosPlanoRepository;
use Illuminate\Http\Request;

class AdminConfiguracoesController extends Controller
{
    public function __construct(
        private ConfiguracaoService $configuracaoService,
        private HistoricoPrecosPlanoRepository $historicoRepository
    ) {}

    /**
     * Exibir configurações
     */
    public function index()
    {
        $configuracoes = $this->configuracaoService->listarTodas();
        
        // Agrupar configurações
        $grupos = $configuracoes->groupBy('grupo');

        return view('admin.configuracoes.index', compact('grupos'));
    }

    /**
     * Salvar configurações
     */
    public function salvar(Request $request)
    {
        $dados = $request->except(['_token', '_method']);
        
        $this->configuracaoService->atualizarConfiguracoes($dados, auth()->id());

        return redirect()
            ->route('admin.configuracoes.index')
            ->with('sucesso', 'Configurações atualizadas com sucesso!');
    }

    /**
     * Ver histórico de alterações de preços
     */
    public function historico()
    {
        $historico = $this->historicoRepository->buscarTodos(50);

        return view('admin.configuracoes.historico', compact('historico'));
    }
}
