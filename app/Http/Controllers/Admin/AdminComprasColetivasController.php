<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CompraColetivaService;
use App\Repositories\SegmentoRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminComprasColetivasController extends Controller
{
    public function __construct(
        private CompraColetivaService $compraColetivaService,
        private SegmentoRepository $segmentoRepository
    ) {}

    // ========== PRODUTOS CATÁLOGO ==========

    public function indexProdutos(Request $request): View
    {
        $filtros = $request->only(['busca', 'categoria_id']);
        $produtos = $this->compraColetivaService->listarProdutos($filtros);
        $categorias = $this->segmentoRepository->buscarTodos();

        return view('admin.compras-coletivas.produtos.index', compact('produtos', 'categorias'));
    }

    public function createProduto(): View
    {
        $categorias = $this->segmentoRepository->buscarTodos();
        return view('admin.compras-coletivas.produtos.create', compact('categorias'));
    }

    public function storeProduto(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'unidade_medida' => 'required|string|max:50',
            'categoria_id' => 'nullable|exists:segmentos,id',
            'imagem' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'ativo' => 'boolean',
        ]);

        $dados['ativo'] = $request->has('ativo');

        $this->compraColetivaService->criarProduto($dados);

        return redirect()
            ->route('admin.compras-coletivas.produtos.index')
            ->with('success', 'Produto adicionado ao catálogo!');
    }

    public function editProduto(int $id): View
    {
        $produto = $this->compraColetivaService->obterProdutoPorId($id);

        if (!$produto) {
            abort(404);
        }

        $categorias = $this->segmentoRepository->buscarTodos();
        return view('admin.compras-coletivas.produtos.edit', compact('produto', 'categorias'));
    }

    public function updateProduto(Request $request, int $id): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'unidade_medida' => 'required|string|max:50',
            'categoria_id' => 'nullable|exists:segmentos,id',
            'imagem' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'ativo' => 'boolean',
        ]);

        $dados['ativo'] = $request->has('ativo');

        $this->compraColetivaService->atualizarProduto($id, $dados);

        return redirect()
            ->route('admin.compras-coletivas.produtos.index')
            ->with('success', 'Produto atualizado!');
    }

    public function destroyProduto(int $id): RedirectResponse
    {
        $this->compraColetivaService->deletarProduto($id);

        return redirect()
            ->route('admin.compras-coletivas.produtos.index')
            ->with('success', 'Produto deletado!');
    }

    // ========== PROPOSTAS ==========

    public function indexPropostas(Request $request): View
    {
        $filtros = $request->only(['status']);
        $propostas = $this->compraColetivaService->listarPropostas($filtros);

        return view('admin.compras-coletivas.propostas.index', compact('propostas'));
    }

    public function showProposta(int $id): View
    {
        $proposta = $this->compraColetivaService->obterPropostaPorId($id);

        if (!$proposta) {
            abort(404);
        }

        return view('admin.compras-coletivas.propostas.show', compact('proposta'));
    }

    public function iniciarVotacao(int $id): RedirectResponse
    {
        $this->compraColetivaService->iniciarVotacao($id, 7);

        return redirect()
            ->back()
            ->with('success', 'Votação iniciada! Encerramento em 7 dias.');
    }

    public function aprovarProposta(int $id): RedirectResponse
    {
        $this->compraColetivaService->aprovarProposta($id);

        return redirect()
            ->back()
            ->with('success', 'Proposta aprovada!');
    }

    public function rejeitarProposta(int $id): RedirectResponse
    {
        $this->compraColetivaService->rejeitarProposta($id);

        return redirect()
            ->back()
            ->with('success', 'Proposta rejeitada.');
    }

    // ========== COMPRAS COLETIVAS ==========

    public function indexCompras(Request $request): View
    {
        $filtros = $request->only(['status', 'busca']);
        $compras = $this->compraColetivaService->listarCompras($filtros);

        return view('admin.compras-coletivas.compras.index', compact('compras'));
    }

    public function createCompra(): View
    {
        $produtos = $this->compraColetivaService->listarProdutos([]);
        return view('admin.compras-coletivas.compras.create', compact('produtos'));
    }

    public function storeCompra(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'produto_catalogo_id' => 'required|exists:produtos_catalogo,id',
            'proposta_id' => 'nullable|exists:compras_coletivas_propostas,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'quantidade_minima' => 'required|numeric|min:0.01',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
        ]);

        $dados['criado_por'] = auth()->id();
        $dados['status'] = 'aberta';

        $this->compraColetivaService->criarCompra($dados);

        return redirect()
            ->route('admin.compras-coletivas.compras.index')
            ->with('success', 'Compra Coletiva criada!');
    }

    public function editCompra(int $id): View
    {
        $compra = $this->compraColetivaService->obterCompraPorId($id);

        if (!$compra) {
            abort(404);
        }

        $produtos = $this->compraColetivaService->listarProdutos([]);
        return view('admin.compras-coletivas.compras.edit', compact('compra', 'produtos'));
    }

    public function updateCompra(Request $request, int $id): RedirectResponse
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'quantidade_minima' => 'required|numeric|min:0.01',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'status' => 'required|in:aberta,em_negociacao,efetivada,cancelada',
        ]);

        $this->compraColetivaService->atualizarCompra($id, $dados);

        return redirect()
            ->route('admin.compras-coletivas.compras.index')
            ->with('success', 'Compra atualizada!');
    }

    public function destroyCompra(int $id): RedirectResponse
    {
        $this->compraColetivaService->deletarCompra($id);

        return redirect()
            ->route('admin.compras-coletivas.compras.index')
            ->with('success', 'Compra deletada!');
    }
}
