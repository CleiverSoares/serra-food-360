<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ConfiguracaoService;
use Illuminate\Http\Request;

class AdminConfiguracoesController extends Controller
{
    public function __construct(
        private ConfiguracaoService $configuracaoService
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
        
        $this->configuracaoService->atualizarConfiguracoes($dados);

        return redirect()
            ->route('admin.configuracoes.index')
            ->with('sucesso', 'Configurações atualizadas com sucesso!');
    }
}
