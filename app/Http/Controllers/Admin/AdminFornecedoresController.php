<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\AuthService;
use App\Services\FornecedorService;
use App\Services\FilterService;
use App\Repositories\SegmentoRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class AdminFornecedoresController extends Controller
{
    public function __construct(
        private UserService $userService,
        private AuthService $authService,
        private FornecedorService $fornecedorService,
        private FilterService $filterService,
        private SegmentoRepository $segmentoRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Listar todos os fornecedores
     */
    public function index(Request $request)
    {
        // Filtros (SEM queries diretas - tudo via repository/service)
        $filtros = [
            'status' => $request->get('status'),
            'plano' => $request->get('plano'),
            'cidade' => $request->get('cidade'),
            'segmento' => $request->get('segmento'),
            'busca' => $request->get('busca'),
        ];

        // Buscar fornecedores (sem paginação para admin)
        $fornecedores = $this->userRepository->buscarFornecedoresComFiltros($filtros, 999999)->items();

        // Dados para filtros
        $filtrosStatus = $this->filterService->obterFiltrosStatus();
        $filtrosPlano = $this->filterService->obterFiltrosPlano();
        $filtrosCidade = $this->enderecoRepository->buscarCidadesUnicasPorRole('fornecedor');
        $segmentos = $this->segmentoRepository->buscarAtivos();

        return view('admin.fornecedores.index', compact(
            'fornecedores',
            'filtrosStatus',
            'filtrosPlano',
            'filtrosCidade',
            'segmentos'
        ) + $filtros + ['status' => $filtros['status'], 'segmentoId' => $filtros['segmento']]);
    }

    /**
     * Exibir formulário de criar fornecedor
     */
    public function create()
    {
        $segmentos = $this->segmentoRepository->buscarAtivos();
        
        return view('admin.fornecedores.create', compact('segmentos'));
    }

    /**
     * Salvar novo fornecedor (já aprovado)
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'cnpj' => 'nullable|string|max:18',
            'nome_estabelecimento' => 'required|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'estado' => 'nullable|string|size:2',
            'descricao' => 'nullable|string|max:500',
            'segmentos' => 'required|array|min:1',
            'segmentos.*' => 'exists:segmentos,id',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Forçar role e status
        $dados['role'] = 'fornecedor';
        $dados['status'] = 'aprovado'; // Já cria aprovado!

        $usuario = $this->authService->cadastrar($dados);

        return redirect()
            ->route('admin.fornecedores.index')
            ->with('sucesso', 'Fornecedor criado e aprovado com sucesso!');
    }

    /**
     * Exibir detalhes do fornecedor (via Service)
     */
    public function show(int $id)
    {
        $fornecedor = $this->fornecedorService->buscarFornecedorAdmin($id);

        if (!$fornecedor) {
            return redirect()->route('admin.fornecedores.index')
                ->with('erro', 'Fornecedor não encontrado.');
        }

        return view('admin.fornecedores.show', compact('fornecedor'));
    }

    /**
     * Exibir formulário de edição (via Service)
     */
    public function edit(int $id)
    {
        $fornecedor = $this->fornecedorService->buscarFornecedorAdmin($id);

        if (!$fornecedor) {
            return redirect()->route('admin.fornecedores.index')
                ->with('erro', 'Fornecedor não encontrado.');
        }

        $segmentos = $this->segmentoRepository->buscarAtivos();

        return view('admin.fornecedores.edit', compact('fornecedor', 'segmentos'));
    }

    /**
     * Atualizar fornecedor
     */
    public function update(Request $request, int $id)
    {
        $fornecedor = $this->userService->buscarPorId($id);

        if (!$fornecedor || $fornecedor->role !== 'fornecedor') {
            return redirect()->route('admin.fornecedores.index')
                ->with('erro', 'Fornecedor não encontrado.');
        }

        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:pendente,aprovado,rejeitado,inativo',
            'segmentos' => 'required|array|min:1',
            'segmentos.*' => 'exists:segmentos,id',
        ]);

        // Atualizar user
        $this->userService->atualizarPerfil($fornecedor, [
            'name' => $dados['name'],
            'email' => $dados['email'],
            'status' => $dados['status'],
        ]);

        // Atualizar segmentos (usando Repository ao invés de query direta)
        $this->userRepository->sincronizarSegmentos($fornecedor, $dados['segmentos']);

        return redirect()->route('admin.fornecedores.index')
            ->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }

    /**
     * Inativar fornecedor
     */
    public function inativar(int $id)
    {
        $fornecedor = $this->userService->buscarPorId($id);

        if (!$fornecedor || $fornecedor->role !== 'fornecedor') {
            return redirect()->route('admin.fornecedores.index')
                ->with('erro', 'Fornecedor não encontrado.');
        }

        $this->userService->atualizarPerfil($fornecedor, ['status' => 'inativo']);

        return redirect()->back()
            ->with('sucesso', 'Fornecedor inativado com sucesso!');
    }

    /**
     * Ativar fornecedor
     */
    public function ativar(int $id)
    {
        $fornecedor = $this->userService->buscarPorId($id);

        if (!$fornecedor || $fornecedor->role !== 'fornecedor') {
            return redirect()->route('admin.fornecedores.index')
                ->with('erro', 'Fornecedor não encontrado.');
        }

        $this->userService->atualizarPerfil($fornecedor, ['status' => 'aprovado']);

        return redirect()->back()
            ->with('sucesso', 'Fornecedor ativado com sucesso!');
    }
}
