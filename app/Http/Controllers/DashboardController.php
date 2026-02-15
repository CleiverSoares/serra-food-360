<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Dashboard principal - redireciona baseado no role
     */
    public function index()
    {
        $usuario = $this->authService->obterUsuarioAutenticado();

        // Redireciona para dashboard específico baseado no role
        return match ($usuario->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'comprador' => $this->dashboardComprador(),
            'fornecedor' => $this->dashboardFornecedor(),
            default => abort(403, 'Tipo de usuário inválido'),
        };
    }

    /**
     * Dashboard do Comprador
     */
    private function dashboardComprador()
    {
        return view('dashboard.comprador');
    }

    /**
     * Dashboard do Restaurante (alias para retrocompatibilidade)
     * @deprecated
     */
    private function dashboardRestaurante()
    {
        return $this->dashboardComprador();
    }

    /**
     * Dashboard do Fornecedor
     */
    private function dashboardFornecedor()
    {
        return view('dashboard.fornecedor');
    }
}
