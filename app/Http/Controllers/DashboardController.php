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
            'admin' => $this->dashboardAdmin(),
            'restaurante' => $this->dashboardRestaurante(),
            'fornecedor' => $this->dashboardFornecedor(),
            default => abort(403, 'Tipo de usuário inválido'),
        };
    }

    /**
     * Dashboard do Admin
     */
    private function dashboardAdmin()
    {
        return view('dashboard.admin');
    }

    /**
     * Dashboard do Restaurante
     */
    private function dashboardRestaurante()
    {
        return view('dashboard.restaurante');
    }

    /**
     * Dashboard do Fornecedor
     */
    private function dashboardFornecedor()
    {
        return view('dashboard.fornecedor');
    }
}
