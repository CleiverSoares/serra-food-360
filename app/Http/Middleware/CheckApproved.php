<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('erro', 'Você precisa estar logado.');
        }

        $user = auth()->user();

        // Admin sempre pode acessar
        if ($user->ehAdmin()) {
            return $next($request);
        }

        // Verifica se está aprovado
        if ($user->status === 'pendente') {
            return redirect()->route('aguardando')->with('info', 'Seu cadastro está aguardando aprovação.');
        }

        if ($user->status === 'rejeitado') {
            auth()->logout();
            return redirect()->route('login')->with('erro', 'Seu cadastro foi rejeitado. Motivo: ' . $user->motivo_rejeicao);
        }

        return $next($request);
    }
}
