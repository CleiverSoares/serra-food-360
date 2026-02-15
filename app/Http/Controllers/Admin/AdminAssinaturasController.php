<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AssinaturaService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class AdminAssinaturasController extends Controller
{
    public function __construct(
        private AssinaturaService $assinaturaService
    ) {}

    /**
     * Criar nova assinatura (POST apenas)
     */
    public function armazenar(Request $request, int $userId): RedirectResponse
    {
        $validated = $request->validate([
            'plano' => 'required|in:basico,profissional,empresarial',
            'tipo_pagamento' => 'required|in:mensal,anual',
        ]);

        $this->assinaturaService->criarAssinatura(
            $userId,
            $validated['plano'],
            $validated['tipo_pagamento']
        );

        return redirect()
            ->back()
            ->with('sucesso', 'Assinatura criada com sucesso!');
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
            ->with('sucesso', 'Assinatura renovada com sucesso!');
    }

    /**
     * Cancelar assinatura
     */
    public function cancelar(int $id): RedirectResponse
    {
        $this->assinaturaService->cancelarAssinatura($id);

        return redirect()
            ->back()
            ->with('sucesso', 'Assinatura cancelada com sucesso!');
    }
}
