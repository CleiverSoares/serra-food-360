<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\AuthService;
use App\Services\FornecedorService;
use App\Services\AssinaturaService;
use App\Services\ConfiguracaoService;
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
        private AssinaturaService $assinaturaService,
        private ConfiguracaoService $configuracaoService,
        private FilterService $filterService,
        private SegmentoRepository $segmentoRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Listar todos os fornecedores (Controller → Service → Repository)
     */
    public function index(Request $request)
    {
        // Controller apenas orquestra
        $fornecedores = $this->fornecedorService->buscarFornecedoresAdmin($request->all());
        $dadosFiltros = $this->fornecedorService->obterDadosFiltros();
        $filtrosAplicados = $this->filterService->extrairFiltrosAplicados($request->all());

        return view('admin.fornecedores.index', array_merge(
            ['fornecedores' => $fornecedores],
            $dadosFiltros,
            $filtrosAplicados
        ));
    }

    /**
     * Exibir formulário de criar fornecedor
     */
    public function create()
    {
        $segmentos = $this->segmentoRepository->buscarAtivos();
        $precosPlanos = $this->configuracaoService->obterTodosPrecosPlanos();
        
        return view('admin.fornecedores.create', compact('segmentos', 'precosPlanos'));
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
            'plano' => 'required|in:comum,vip',
            'tipo_pagamento' => 'required|in:mensal,anual',
        ]);

        // Forçar role e status
        $dados['role'] = 'fornecedor';
        $dados['status'] = 'aprovado'; // Já cria aprovado!

        $usuario = $this->authService->cadastrar($dados);

        // Criar assinatura automaticamente
        $this->assinaturaService->criarAssinatura(
            $usuario->id,
            $dados['plano'],
            $dados['tipo_pagamento']
        );

        return redirect()
            ->route('admin.fornecedores.index')
            ->with('sucesso', 'Fornecedor criado com assinatura ativa!');
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
        $dadosEdicao = $this->fornecedorService->prepararDadosEdicao($id);

        if (empty($dadosEdicao)) {
            return redirect()->route('admin.fornecedores.index')
                ->with('erro', 'Fornecedor não encontrado.');
        }

        $segmentos = $this->segmentoRepository->buscarAtivos();

        return view('admin.fornecedores.edit', [
            'fornecedor' => $dadosEdicao['fornecedor'],
            'dados' => $dadosEdicao['dadosContato'],
            'segmentosIds' => $dadosEdicao['segmentosIds'],
            'segmentos' => $segmentos,
        ]);
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
