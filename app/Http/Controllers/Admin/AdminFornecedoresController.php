<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\FilterService;
use App\Repositories\SegmentoRepository;
use Illuminate\Http\Request;

class AdminFornecedoresController extends Controller
{
    public function __construct(
        private UserService $userService,
        private FilterService $filterService,
        private SegmentoRepository $segmentoRepository
    ) {}

    /**
     * Listar todos os fornecedores
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $plano = $request->get('plano');
        $cidade = $request->get('cidade');
        $segmentoId = $request->get('segmento');
        $busca = $request->get('busca');

        // Query base
        $query = \App\Models\UserModel::where('role', 'fornecedor')
            ->with(['fornecedor', 'segmentos', 'aprovador']);

        // Aplicar filtros
        $query = $this->filterService->aplicarFiltroStatus($query, $status);
        $query = $this->filterService->aplicarFiltroPlano($query, $plano);
        $query = $this->filterService->aplicarFiltroCidade($query, $cidade);
        $query = $this->filterService->aplicarFiltroSegmento($query, $segmentoId);
        $query = $this->filterService->aplicarFiltroBusca($query, $busca);

        $fornecedores = $query->orderBy('created_at', 'desc')->get();

        // Dados para filtros
        $filtrosStatus = $this->filterService->obterFiltrosStatus();
        $filtrosPlano = $this->filterService->obterFiltrosPlano();
        $filtrosCidade = $this->filterService->obterFiltrosCidade();
        $segmentos = $this->segmentoRepository->buscarAtivos();

        return view('admin.fornecedores.index', compact(
            'fornecedores',
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
     * Exibir detalhes do fornecedor
     */
    public function show(int $id)
    {
        $fornecedor = $this->userService->buscarPorId($id);

        if (!$fornecedor || $fornecedor->role !== 'fornecedor') {
            return redirect()->route('admin.fornecedores.index')
                ->with('erro', 'Fornecedor não encontrado.');
        }

        $fornecedor->load(['fornecedor', 'segmentos', 'aprovador']);

        return view('admin.fornecedores.show', compact('fornecedor'));
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(int $id)
    {
        $fornecedor = $this->userService->buscarPorId($id);

        if (!$fornecedor || $fornecedor->role !== 'fornecedor') {
            return redirect()->route('admin.fornecedores.index')
                ->with('erro', 'Fornecedor não encontrado.');
        }

        $fornecedor->load(['fornecedor', 'segmentos']);
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
            'telefone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'cidade' => 'required|string|max:255',
            'status' => 'required|in:pendente,aprovado,rejeitado,inativo',
            'plano' => 'nullable|in:comum,vip',
            'segmentos' => 'required|array|min:1',
            'segmentos.*' => 'exists:segmentos,id',
        ]);

        // Atualizar user
        $this->userService->atualizarPerfil($fornecedor, [
            'name' => $dados['name'],
            'email' => $dados['email'],
            'telefone' => $dados['telefone'],
            'whatsapp' => $dados['whatsapp'],
            'cidade' => $dados['cidade'],
            'status' => $dados['status'],
            'plano' => $dados['plano'],
        ]);

        // Atualizar segmentos
        $fornecedor->segmentos()->sync($dados['segmentos']);

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
