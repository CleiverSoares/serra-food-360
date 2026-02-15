<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\UserService;
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
            'restaurantes' => $this->userService->listarRestaurantes(),
            'fornecedores' => $this->userService->listarFornecedores(),
            default => $this->userService->listarPendentes(),
        };

        return view('admin.usuarios.index', compact('usuarios', 'filtro'));
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
