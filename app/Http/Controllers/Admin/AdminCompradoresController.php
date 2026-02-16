<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\AuthService;
use App\Services\CompradorService;
use App\Services\AssinaturaService;
use App\Services\ConfiguracaoService;
use App\Services\FilterService;
use App\Repositories\SegmentoRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class AdminCompradoresController extends Controller
{
    public function __construct(
        private UserService $userService,
        private AuthService $authService,
        private CompradorService $compradorService,
        private AssinaturaService $assinaturaService,
        private ConfiguracaoService $configuracaoService,
        private FilterService $filterService,
        private SegmentoRepository $segmentoRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Listar todos os compradores (Controller → Service → Repository)
     */
    public function index(Request $request)
    {
        // Controller apenas orquestra
        $compradores = $this->compradorService->buscarCompradoresAdmin($request->all());
        $dadosFiltros = $this->compradorService->obterDadosFiltros();
        $filtrosAplicados = $this->filterService->extrairFiltrosAplicados($request->all());

        return view('admin.compradores.index', array_merge(
            ['compradores' => $compradores],
            $dadosFiltros,
            $filtrosAplicados
        ));
    }

    /**
     * Exibir formulário de criar comprador
     */
    public function create()
    {
        $segmentos = $this->segmentoRepository->buscarAtivos();
        $precosPlanos = $this->configuracaoService->obterTodosPrecosPlanos();
        
        return view('admin.compradores.create', compact('segmentos', 'precosPlanos'));
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
        $dados['role'] = 'comprador';
        $dados['status'] = 'aprovado'; // Já cria aprovado!

        $usuario = $this->authService->cadastrar($dados);

        // Criar assinatura automaticamente
        $this->assinaturaService->criarAssinatura(
            $usuario->id,
            $dados['plano'],
            $dados['tipo_pagamento']
        );

        return redirect()
            ->route('admin.compradores.index')
            ->with('sucesso', 'Comprador criado com assinatura ativa!');
    }

    /**
     * Exibir detalhes do comprador (via Service)
     */
    public function show(int $id)
    {
        $comprador = $this->compradorService->buscarCompradorAdmin($id);

        if (!$comprador) {
            return redirect()->route('admin.compradores.index')
                ->with('erro', 'Comprador não encontrado.');
        }

        return view('admin.compradores.show', compact('comprador'));
    }

    /**
     * Exibir formulário de edição (via Service)
     */
    public function edit(int $id)
    {
        $comprador = $this->compradorService->buscarCompradorAdmin($id);

        if (!$comprador) {
            return redirect()->route('admin.compradores.index')
                ->with('erro', 'Comprador não encontrado.');
        }

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
            'status' => 'required|in:pendente,aprovado,rejeitado,inativo',
            'segmentos' => 'required|array|min:1',
            'segmentos.*' => 'exists:segmentos,id',
        ]);

        // Atualizar user
        $this->userService->atualizarPerfil($comprador, [
            'name' => $dados['name'],
            'email' => $dados['email'],
            'status' => $dados['status'],
        ]);

        // Atualizar segmentos (usando Repository ao invés de query direta)
        $this->userRepository->sincronizarSegmentos($comprador, $dados['segmentos']);

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
