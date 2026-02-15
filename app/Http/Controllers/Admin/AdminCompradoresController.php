<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\AuthService;
use App\Services\FilterService;
use App\Repositories\SegmentoRepository;
use App\Models\SegmentoModel;
use Illuminate\Http\Request;

class AdminCompradoresController extends Controller
{
    public function __construct(
        private UserService $userService,
        private AuthService $authService,
        private FilterService $filterService,
        private SegmentoRepository $segmentoRepository
    ) {}

    /**
     * Listar todos os compradores
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $plano = $request->get('plano');
        $cidade = $request->get('cidade');
        $segmentoId = $request->get('segmento');
        $busca = $request->get('busca');

        // Query base
        $query = \App\Models\UserModel::where('role', 'comprador')
            ->with(['comprador', 'segmentos', 'aprovador']);

        // Aplicar filtros
        $query = $this->filterService->aplicarFiltroStatus($query, $status);
        $query = $this->filterService->aplicarFiltroPlano($query, $plano);
        $query = $this->filterService->aplicarFiltroCidade($query, $cidade);
        $query = $this->filterService->aplicarFiltroSegmento($query, $segmentoId);
        $query = $this->filterService->aplicarFiltroBusca($query, $busca);

        $compradores = $query->orderBy('created_at', 'desc')->get();

        // Dados para filtros
        $filtrosStatus = $this->filterService->obterFiltrosStatus();
        $filtrosPlano = $this->filterService->obterFiltrosPlano();
        $filtrosCidade = $this->filterService->obterFiltrosCidade();
        $segmentos = $this->segmentoRepository->buscarAtivos();

        return view('admin.compradores.index', compact(
            'compradores',
            'filtrosStatus',
            'filtrosPlano',
            'filtrosCidade',
            'segmentos',
            'status',
            'plano',
            'cidade',
            'segmentoId',
            'busca'
        ));
    }

    /**
     * Exibir formulário de criar comprador
     */
    public function create()
    {
        $segmentos = SegmentoModel::where('ativo', true)
            ->orderBy('nome')
            ->get();
        
        return view('admin.compradores.create', compact('segmentos'));
    }

    /**
     * Salvar novo comprador (já aprovado)
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'telefone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'cnpj' => 'nullable|string|max:18',
            'nome_estabelecimento' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'segmentos' => 'required|array|min:1',
            'segmentos.*' => 'exists:segmentos,id',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Forçar role e status
        $dados['role'] = 'comprador';
        $dados['status'] = 'aprovado'; // Já cria aprovado!

        $usuario = $this->authService->cadastrar($dados);

        return redirect()
            ->route('admin.compradores.index')
            ->with('sucesso', 'Comprador criado e aprovado com sucesso!');
    }

    /**
     * Exibir detalhes do comprador
     */
    public function show(int $id)
    {
        $comprador = $this->userService->buscarPorId($id);

        if (!$comprador || $comprador->role !== 'comprador') {
            return redirect()->route('admin.compradores.index')
                ->with('erro', 'Comprador não encontrado.');
        }

        $comprador->load(['comprador', 'segmentos', 'aprovador']);

        return view('admin.compradores.show', compact('comprador'));
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(int $id)
    {
        $comprador = $this->userService->buscarPorId($id);

        if (!$comprador || $comprador->role !== 'comprador') {
            return redirect()->route('admin.compradores.index')
                ->with('erro', 'Comprador não encontrado.');
        }

        $comprador->load(['comprador', 'segmentos']);
        $segmentos = $this->segmentoRepository->buscarAtivos();

        return view('admin.compradores.edit', compact('comprador', 'segmentos'));
    }

    /**
     * Atualizar comprador
     */
    public function update(Request $request, int $id)
    {
        $comprador = $this->userService->buscarPorId($id);

        if (!$comprador || $comprador->role !== 'comprador') {
            return redirect()->route('admin.compradores.index')
                ->with('erro', 'Comprador não encontrado.');
        }

        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telefone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'status' => 'required|in:pendente,aprovado,rejeitado,inativo',
            'plano' => 'nullable|in:comum,vip',
            'segmentos' => 'required|array|min:1',
            'segmentos.*' => 'exists:segmentos,id',
        ]);

        // Atualizar user
        $this->userService->atualizarPerfil($comprador, [
            'name' => $dados['name'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
            'whatsapp' => $dados['whatsapp'],
            'cidade' => $dados['cidade'],
            'status' => $dados['status'],
            'plano' => $dados['plano'],
        ]);

        // Atualizar segmentos
        $comprador->segmentos()->sync($dados['segmentos']);

        return redirect()->route('admin.compradores.index')
            ->with('sucesso', 'Comprador atualizado com sucesso!');
    }

    /**
     * Inativar comprador
     */
    public function inativar(int $id)
    {
        $comprador = $this->userService->buscarPorId($id);

        if (!$comprador || $comprador->role !== 'comprador') {
            return redirect()->route('admin.compradores.index')
                ->with('erro', 'Comprador não encontrado.');
        }

        $this->userService->atualizarPerfil($comprador, ['status' => 'inativo']);

        return redirect()->back()
            ->with('sucesso', 'Comprador inativado com sucesso!');
    }

    /**
     * Ativar comprador
     */
    public function ativar(int $id)
    {
        $comprador = $this->userService->buscarPorId($id);

        if (!$comprador || $comprador->role !== 'comprador') {
            return redirect()->route('admin.compradores.index')
                ->with('erro', 'Comprador não encontrado.');
        }

        $this->userService->atualizarPerfil($comprador, ['status' => 'aprovado']);

        return redirect()->back()
            ->with('sucesso', 'Comprador ativado com sucesso!');
    }
}
