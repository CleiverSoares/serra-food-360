<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\TalentoService;

class AdminDashboardController extends Controller
{
    public function __construct(
        private UserService $userService,
        private TalentoService $talentoService
    ) {}

    /**
     * Dashboard do Admin
     */
    public function index()
    {
        $estatisticas = $this->userService->obterEstatisticas();
        $estatisticas['talentos'] = $this->talentoService->contar();

        return view('admin.dashboard', compact('estatisticas'));
    }
}
