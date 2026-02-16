<?php

namespace App\Http\Controllers;

use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PasswordResetController extends Controller
{
    public function __construct(
        private PasswordResetService $passwordResetService
    ) {}

    /**
     * Exibir formulário "Esqueci minha senha"
     */
    public function exibirFormularioEmail(): View
    {
        return view('auth.esqueci-senha');
    }

    /**
     * Enviar link de redefinição
     */
    public function enviarLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Por favor, informe seu email',
            'email.email' => 'Por favor, informe um email válido',
        ]);

        $enviado = $this->passwordResetService->enviarLinkRedefinicao($request->email);

        if ($enviado) {
            return redirect()
                ->back()
                ->with('sucesso', 'Link de redefinição enviado! Verifique seu email.');
        }

        return redirect()
            ->back()
            ->with('erro', 'Email não encontrado em nossa base de dados.')
            ->withInput();
    }

    /**
     * Exibir formulário de redefinir senha
     */
    public function exibirFormularioRedefinicao(Request $request): View|RedirectResponse
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return redirect()->route('login')->with('erro', 'Link de redefinição inválido');
        }

        // Validar token
        if (!$this->passwordResetService->validarToken($email, $token)) {
            return redirect()->route('login')->with('erro', 'Link de redefinição expirado ou inválido');
        }

        return view('auth.redefinir-senha', compact('token', 'email'));
    }

    /**
     * Processar redefinição de senha
     */
    public function redefinir(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Por favor, informe a nova senha',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres',
            'password.confirmed' => 'As senhas não conferem',
        ]);

        $redefinido = $this->passwordResetService->redefinirSenha(
            $request->email,
            $request->token,
            $request->password
        );

        if ($redefinido) {
            return redirect()
                ->route('login')
                ->with('sucesso', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
        }

        return redirect()
            ->back()
            ->with('erro', 'Não foi possível redefinir a senha. Tente novamente.')
            ->withInput();
    }
}
