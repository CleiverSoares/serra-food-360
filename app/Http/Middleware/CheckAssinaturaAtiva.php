<?php

namespace App\Http\Middleware;

use App\Services\AssinaturaService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAssinaturaAtiva
{
    public function __construct(
        private AssinaturaService $assinaturaService
    ) {}

    /**
     * Verifica se usuário tem assinatura ativa
     * 
     * Apenas compradores e fornecedores precisam de assinatura
     * Admin não precisa
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Admin não precisa de assinatura
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Verificar se é comprador ou fornecedor
        if (in_array($user->role, ['comprador', 'fornecedor'])) {
            
            // Buscar assinatura ativa
            $assinatura = $this->assinaturaService->buscarAssinaturaAtiva($user->id);
            
            // Se não tiver assinatura OU estiver vencida
            if (!$assinatura || $assinatura->estaVencida()) {
                // Já está na página de assinatura vencida? Permite
                if ($request->is('assinatura/vencida')) {
                    return $next($request);
                }
                
                // Redireciona para página de assinatura vencida
                return redirect()->route('assinatura.vencida');
            }
        }

        return $next($request);
    }
}
