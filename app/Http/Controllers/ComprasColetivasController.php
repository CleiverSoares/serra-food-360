<?php

namespace App\Http\Controllers;

use App\Services\CompraColetivaService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ComprasColetivasController extends Controller
{
    public function __construct(
        private CompraColetivaService $compraColetivaService
    ) {
        $this->middleware('auth');
    }

    // ========== COMPRAS ATIVAS (Compradores) ==========

    public function index(): View
    {
        $compras = $this->compraColetivaService->listarComprasAtivas();
        $usuario = auth()->user();

        return view('compras-coletivas.index', compact('compras', 'usuario'));
    }

    public function show(int $id): View
    {
        $compra = $this->compraColetivaService->obterCompraPorId($id);

        if (!$compra) {
            abort(404);
        }

        $usuario = auth()->user();
        $minhaAdesao = $this->compraColetivaService->obterAdesaoUsuario($id, $usuario->id);

        return view('compras-coletivas.show', compact('compra', 'usuario', 'minhaAdesao'));
    }

    public function participar(Request $request, int $id): RedirectResponse
    {
        $dados = $request->validate([
            'quantidade' => 'required|numeric|min:0.01',
            'observacoes' => 'nullable|string|max:500',
        ]);

        $resultado = $this->compraColetivaService->participar(
            $id,
            auth()->id(),
            $dados['quantidade'],
            $dados['observacoes'] ?? null
        );

        if ($resultado) {
            return redirect()
                ->back()
                ->with('success', 'Você participou da compra coletiva!');
        }

        return redirect()
            ->back()
            ->with('error', 'Não foi possível participar. Você já está participando ou a compra está fechada.');
    }

    public function atualizarParticipacao(Request $request, int $adesaoId): RedirectResponse
    {
        $dados = $request->validate([
            'quantidade' => 'required|numeric|min:0.01',
            'observacoes' => 'nullable|string|max:500',
        ]);

        $resultado = $this->compraColetivaService->atualizarParticipacao(
            $adesaoId,
            $dados['quantidade'],
            $dados['observacoes'] ?? null
        );

        if ($resultado) {
            return redirect()
                ->back()
                ->with('success', 'Participação atualizada!');
        }

        return redirect()
            ->back()
            ->with('error', 'Não foi possível atualizar.');
    }

    public function cancelarParticipacao(int $adesaoId): RedirectResponse
    {
        $this->compraColetivaService->cancelarParticipacao($adesaoId);

        return redirect()
            ->back()
            ->with('success', 'Participação cancelada.');
    }

    // ========== PROPOSTAS ==========

    public function indexPropostas(): View
    {
        $propostas = $this->compraColetivaService->listarPropostasEmVotacao();
        $minhasPropostas = $this->compraColetivaService->listarPropostas([
            'proposto_por' => auth()->id()
        ]);

        return view('compras-coletivas.propostas.index', compact('propostas', 'minhasPropostas'));
    }

    public function createProposta(): View
    {
        return view('compras-coletivas.propostas.create');
    }

    public function storeProposta(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'produto_nome' => 'required|string|max:255',
            'produto_descricao' => 'nullable|string',
            'unidade_medida' => 'required|string|max:50',
            'categoria_id' => 'nullable|exists:segmentos,id',
            'justificativa' => 'required|string|max:1000',
        ]);

        // Criar produto no catálogo (ou usar existente se similar)
        $produto = $this->compraColetivaService->criarProduto([
            'nome' => $dados['produto_nome'],
            'descricao' => $dados['produto_descricao'],
            'unidade_medida' => $dados['unidade_medida'],
            'categoria_id' => $dados['categoria_id'],
            'ativo' => true,
        ]);

        // Criar proposta
        $this->compraColetivaService->criarProposta([
            'produto_catalogo_id' => $produto->id,
            'proposto_por' => auth()->id(),
            'justificativa' => $dados['justificativa'],
            'status' => 'pendente',
        ]);

        return redirect()
            ->route('compras-coletivas.propostas.index')
            ->with('success', 'Proposta enviada! Aguarde análise do admin.');
    }

    public function votar(int $propostaId): JsonResponse
    {
        $resultado = $this->compraColetivaService->votar($propostaId, auth()->id());

        if ($resultado) {
            return response()->json([
                'success' => true,
                'message' => 'Voto computado!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Você já votou nesta proposta.',
        ], 400);
    }

    // ========== AUTOCOMPLETE PRODUTOS ==========

    public function autocompleteProdutos(Request $request): JsonResponse
    {
        $termo = $request->get('termo', '');
        $produtos = $this->compraColetivaService->autocompleteProdutos($termo);

        return response()->json($produtos);
    }

    // ========== OFERTAS (Fornecedores) ==========

    public function minhasOfertas(): View
    {
        $ofertas = $this->compraColetivaService->listarOfertasFornecedor(auth()->id());

        return view('compras-coletivas.fornecedor.ofertas', compact('ofertas'));
    }

    public function ofertar(Request $request, int $compraId): RedirectResponse
    {
        $dados = $request->validate([
            'preco_unitario' => 'required|numeric|min:0.01',
            'quantidade_minima' => 'nullable|numeric|min:0.01',
            'condicoes' => 'nullable|string|max:1000',
        ]);

        $resultado = $this->compraColetivaService->ofertar(
            $compraId,
            auth()->id(),
            $dados['preco_unitario'],
            $dados['quantidade_minima'] ?? null,
            $dados['condicoes'] ?? null
        );

        if ($resultado) {
            return redirect()
                ->back()
                ->with('success', 'Oferta enviada!');
        }

        return redirect()
            ->back()
            ->with('error', 'Não foi possível enviar a oferta.');
    }
}
