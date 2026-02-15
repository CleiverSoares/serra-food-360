<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AssinaturaRepository;
use App\Repositories\UserRepository;
use App\Services\AssinaturaService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminAssinaturasController extends Controller
{
    public function __construct(
        private AssinaturaService $assinaturaService,
        private AssinaturaRepository $assinaturaRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Listar todas assinaturas
     */
    public function index(): View
    {
        // Buscar usuários compradores e fornecedores com assinatura ativa
        $usuarios = $this->userRepository->buscarUsuariosComAssinatura();

        return view('admin.assinaturas.index', compact('usuarios'));
    }

    /**
     * Exibir formulário de criação de assinatura
     */
    public function criar(int $userId): View
    {
        $usuario = $this->userRepository->buscarPorId($userId);

        if (!$usuario) {
            abort(404, 'Usuário não encontrado');
        }

        return view('admin.assinaturas.criar', compact('usuario'));
    }

    /**
     * Criar nova assinatura
     */
    public function armazenar(Request $request, int $userId): RedirectResponse
    {
        $validated = $request->validate([
            'plano' => 'required|in:basico,profissional,empresarial',
            'tipo_pagamento' => 'required|in:mensal,anual',
        ]);

        $assinatura = $this->assinaturaService->criarAssinatura(
            $userId,
            $validated['plano'],
            $validated['tipo_pagamento']
        );

        return redirect()
            ->route('admin.assinaturas.index')
            ->with('success', 'Assinatura criada com sucesso!');
    }

    /**
     * Exibir detalhes de assinatura
     */
    public function exibir(int $id): View
    {
        $assinatura = $this->assinaturaRepository->buscarPorId($id);

        if (!$assinatura) {
            abort(404, 'Assinatura não encontrada');
        }

        return view('admin.assinaturas.exibir', compact('assinatura'));
    }

    /**
     * Renovar assinatura
     */
    public function renovar(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_pagamento' => 'required|in:mensal,anual',
        ]);

        $this->assinaturaService->renovarAssinatura($id, $validated['tipo_pagamento']);

        return redirect()
            ->back()
            ->with('success', 'Assinatura renovada com sucesso!');
    }

    /**
     * Cancelar assinatura
     */
    public function cancelar(int $id): RedirectResponse
    {
        $this->assinaturaService->cancelarAssinatura($id);

        return redirect()
            ->back()
            ->with('success', 'Assinatura cancelada com sucesso!');
    }

    /**
     * Histórico de assinaturas de um usuário
     */
    public function historico(int $userId): View
    {
        $usuario = $this->userRepository->buscarPorId($userId);
        $assinaturas = $this->assinaturaService->listarHistoricoAssinaturas($userId);

        return view('admin.assinaturas.historico', compact('usuario', 'assinaturas'));
    }
}
