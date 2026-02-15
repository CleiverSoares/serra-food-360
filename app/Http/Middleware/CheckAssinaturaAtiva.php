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
            // Verificar se tem assinatura ativa
            if (!$this->assinaturaService->temAssinaturaAtiva($user->id)) {
                // Buscar assinatura (pode estar vencida ou não existir)
                $assinatura = $this->assinaturaService->buscarAssinaturaAtiva($user->id);
                
                if ($assinatura && $assinatura->estaVencida()) {
                    // Assinatura vencida
                    return redirect()->route('assinatura.vencida')
                        ->with('error', 'Sua assinatura venceu. Renove para continuar acessando a plataforma.');
                }
                
                // Sem assinatura
                return redirect()->route('assinatura.criar')
                    ->with('warning', 'Você precisa de uma assinatura ativa para acessar esta área.');
            }
        }

        return $next($request);
    }
}
