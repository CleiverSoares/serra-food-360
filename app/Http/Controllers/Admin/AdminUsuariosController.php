<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\UserService;
use App\Models\SegmentoModel;
use Illuminate\Http\Request;

class AdminUsuariosController extends Controller
{
    public function __construct(
        private UserService $userService,
        private AuthService $authService
    ) {}

    /**
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', 'pendentes');

        $usuarios = match ($filtro) {
            'aprovados' => $this->userService->listarAprovados(),
            'compradores' => $this->userService->listarCompradores(),
            'fornecedores' => $this->userService->listarFornecedores(),
            default => $this->userService->listarPendentes(),
        };

        return view('admin.usuarios.index', compact('usuarios', 'filtro'));
    }

    /**
     * Exibir formulário de criar usuário
     */
    public function criar()
    {
        $segmentos = SegmentoModel::where('ativo', true)
            ->orderBy('nome')
            ->get();
        
        return view('admin.usuarios.criar', compact('segmentos'));
    }

    /**
     * Salvar novo usuário criado pelo admin
     */
    public function salvar(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'telefone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'role' => 'required|in:comprador,fornecedor',
            'status' => 'required|in:pendente,aprovado,rejeitado,inativo',
            'cnpj' => 'nullable|string|max:18',
            'nome_estabelecimento' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'segmentos' => 'required|array|min:1',
            'segmentos.*' => 'exists:segmentos,id',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $usuario = $this->authService->cadastrar($dados);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('sucesso', 'Usuário criado com sucesso!');
    }

    /**
     * Aprovar usuário
     */
    public function aprovar(int $id)
    {
        $adminId = auth()->id();
        
        if ($this->authService->aprovar($id, $adminId)) {
            return redirect()
                ->back()
                ->with('sucesso', 'Usuário aprovado com sucesso!');
        }

        return redirect()
            ->back()
            ->with('erro', 'Erro ao aprovar usuário.');
    }

    /**
     * Rejeitar usuário
     */
    public function rejeitar(Request $request, int $id)
    {
        $adminId = auth()->id();
        $motivo = $request->input('motivo');
        
        if ($this->authService->rejeitar($id, $adminId, $motivo)) {
            return redirect()
                ->back()
                ->with('sucesso', 'Usuário rejeitado.');
        }

        return redirect()
            ->back()
            ->with('erro', 'Erro ao rejeitar usuário.');
    }

    /**
     * Deletar usuário
     */
    public function deletar(int $id)
    {
        $usuario = $this->userService->buscarPorId($id);

        if (!$usuario) {
            return redirect()
                ->back()
                ->with('erro', 'Usuário não encontrado.');
        }

        if ($this->userService->deletar($usuario)) {
            return redirect()
                ->back()
                ->with('sucesso', 'Usuário deletado com sucesso!');
        }

        return redirect()
            ->back()
            ->with('erro', 'Erro ao deletar usuário.');
    }
}
