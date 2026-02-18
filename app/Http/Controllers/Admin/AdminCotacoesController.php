<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CotacaoService;
use App\Repositories\SegmentoRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class AdminCotacoesController extends Controller
{
    public function __construct(
        private CotacaoService $cotacaoService,
        private SegmentoRepository $segmentoRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Listar todas as cotações (admin)
     */
    public function index()
    {
        $cotacoes = $this->cotacaoService->listarTodasAdmin();
        
        return view('admin.cotacoes.index', compact('cotacoes'));
    }

    /**
     * Formulário de criar cotação
     */
    public function create()
    {
        $segmentos = $this->segmentoRepository->buscarAtivos();
        
        return view('admin.cotacoes.create', compact('segmentos'));
    }

    /**
     * Salvar nova cotação
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'produto' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'imagem_produto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'unidade' => 'required|string|max:20',
            'quantidade_minima' => 'nullable|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'segmento_id' => 'required|exists:segmentos,id',
        ]);

        // Upload da imagem do produto
        if ($request->hasFile('imagem_produto')) {
            $imagem = $request->file('imagem_produto');
            $nomeImagem = time() . '_' . $imagem->getClientOriginalName();
            $dados['imagem_produto_url'] = $imagem->storeAs('cotacoes/produtos', $nomeImagem, 'public');
        }

        $dados['criado_por'] = auth()->id();
        $dados['status'] = 'ativo';

        $this->cotacaoService->criarCotacao($dados);

        return redirect()
            ->route('admin.cotacoes.index')
            ->with('sucesso', 'Cotação criada com sucesso!');
    }

    /**
     * Formulário de editar cotação
     */
    public function edit(int $id)
    {
        $cotacao = $this->cotacaoService->buscarCotacao($id);
        
        if (!$cotacao) {
            abort(404);
        }

        $segmentos = $this->segmentoRepository->buscarAtivos();
        $fornecedores = $this->userRepository->buscarPorRole('fornecedor');
        
        return view('admin.cotacoes.edit', compact('cotacao', 'segmentos', 'fornecedores'));
    }

    /**
     * Atualizar cotação
     */
    public function update(Request $request, int $id)
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'produto' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'imagem_produto' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'unidade' => 'required|string|max:20',
            'quantidade_minima' => 'nullable|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'segmento_id' => 'required|exists:segmentos,id',
        ]);

        // Upload da imagem do produto (se houver)
        if ($request->hasFile('imagem_produto')) {
            $cotacao = $this->cotacaoService->buscarCotacao($id);
            
            // Deletar imagem antiga
            if ($cotacao && $cotacao->imagem_produto_url) {
                \Storage::disk('public')->delete($cotacao->imagem_produto_url);
            }
            
            $imagem = $request->file('imagem_produto');
            $nomeImagem = time() . '_' . $imagem->getClientOriginalName();
            $dados['imagem_produto_url'] = $imagem->storeAs('cotacoes/produtos', $nomeImagem, 'public');
        }

        $this->cotacaoService->atualizarCotacao($id, $dados);

        return redirect()
            ->route('admin.cotacoes.edit', $id)
            ->with('sucesso', 'Cotação atualizada!');
    }

    /**
     * Adicionar oferta de fornecedor
     */
    public function adicionarOferta(Request $request, int $cotacaoId)
    {
        $dados = $request->validate([
            'fornecedor_id' => 'required|exists:users,id',
            'preco_unitario' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string',
            'prazo_entrega' => 'nullable|string|max:50',
            'quantidade_disponivel' => 'nullable|numeric|min:0',
        ]);

        $dados['cotacao_id'] = $cotacaoId;

        $this->cotacaoService->adicionarOferta($dados);

        return redirect()
            ->route('admin.cotacoes.edit', $cotacaoId)
            ->with('sucesso', 'Oferta adicionada!');
    }

    /**
     * Deletar oferta
     */
    public function deletarOferta(int $id)
    {
        $this->cotacaoService->deletarOferta($id);

        return back()->with('sucesso', 'Oferta removida!');
    }

    /**
     * Encerrar cotação
     */
    public function encerrar(int $id)
    {
        $this->cotacaoService->encerrarCotacao($id);

        return redirect()
            ->route('admin.cotacoes.index')
            ->with('sucesso', 'Cotação encerrada!');
    }

    /**
     * Deletar cotação
     */
    public function destroy(int $id)
    {
        $this->cotacaoService->deletarCotacao($id);

        return redirect()
            ->route('admin.cotacoes.index')
            ->with('sucesso', 'Cotação deletada!');
    }
}
