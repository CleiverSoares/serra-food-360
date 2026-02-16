<?php

namespace App\Http\Controllers;

use App\Services\CotacaoService;
use App\Repositories\SegmentoRepository;
use Illuminate\Http\Request;

class CotacoesController extends Controller
{
    public function __construct(
        private CotacaoService $cotacaoService,
        private SegmentoRepository $segmentoRepository
    ) {}

    /**
     * Listar cotações (Kanban mobile-first)
     * Comprador: vê ofertas ordenadas
     * Fornecedor: pode adicionar sua oferta
     */
    public function index(Request $request)
    {
        $usuario = auth()->user();
        
        if ($usuario->role === 'fornecedor') {
            // Fornecedor: buscar cotações do seu segmento
            $cotacoes = $this->cotacaoService->buscarCotacoesParaFornecedor($usuario->id);
            return view('cotacoes.fornecedor-index', compact('cotacoes'));
        }
        
        // Comprador: buscar cotações do seu segmento com filtros
        $filtros = $request->only(['busca', 'segmento_id', 'status']);
        $cotacoes = $this->cotacaoService->buscarCotacoesParaComprador($usuario->id, $filtros);
        
        // Buscar apenas segmentos do comprador para o filtro
        $segmentos = $usuario->segmentos;
        
        return view('cotacoes.index', compact('cotacoes', 'segmentos', 'filtros'));
    }

    /**
     * Exibir detalhes de uma cotação
     */
    public function show(int $id)
    {
        $usuario = auth()->user();
        
        $cotacao = $this->cotacaoService->buscarCotacao($id, $usuario->id);
        
        if (!$cotacao) {
            abort(404, 'Cotação não encontrada ou você não tem acesso.');
        }
        
        return view('cotacoes.show', compact('cotacoes'));
    }

    /**
     * Salvar oferta do fornecedor
     */
    public function salvarOferta(Request $request, int $cotacaoId)
    {
        $dados = $request->validate([
            'preco_unitario' => 'required|numeric|min:0.01',
            'prazo_entrega' => 'nullable|string|max:50',
            'quantidade_disponivel' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string|max:500',
        ]);

        $usuario = auth()->user();

        if ($usuario->role !== 'fornecedor') {
            abort(403, 'Apenas fornecedores podem adicionar ofertas.');
        }

        $this->cotacaoService->salvarOfertaFornecedor($cotacaoId, $usuario->id, $dados);

        return redirect()
            ->route('cotacoes.index')
            ->with('sucesso', 'Sua oferta foi enviada com sucesso!');
    }

    /**
     * Deletar oferta do fornecedor
     */
    public function deletarOferta(int $cotacaoId)
    {
        $usuario = auth()->user();

        if ($usuario->role !== 'fornecedor') {
            abort(403);
        }

        $this->cotacaoService->deletarOfertaFornecedor($cotacaoId, $usuario->id);

        return redirect()
            ->route('cotacoes.index')
            ->with('sucesso', 'Sua oferta foi removida.');
    }
}
