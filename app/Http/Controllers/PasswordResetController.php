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

        \Log::info('[CONTROLLER] Requisição de recuperação de senha recebida', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Por segurança, sempre mostramos mensagem de sucesso
        // (para não revelar quais emails existem no sistema)
        $this->passwordResetService->enviarLinkRedefinicao($request->email);

        return redirect()
            ->back()
            ->with('sucesso', 'Se o email existir em nossa base, você receberá um link de redefinição.');
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

        \Log::info('[CONTROLLER] Tentativa de redefinição de senha', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        $redefinido = $this->passwordResetService->redefinirSenha(
            $request->email,
            $request->token,
            $request->password
        );

        if ($redefinido) {
            \Log::info('[CONTROLLER] Redefinição concluída com sucesso', ['email' => $request->email]);
            return redirect()
                ->route('login')
                ->with('sucesso', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
        }

        \Log::warning('[CONTROLLER] Falha na redefinição de senha', ['email' => $request->email]);
        return redirect()
            ->back()
            ->with('erro', 'Não foi possível redefinir a senha. Tente novamente.')
            ->withInput();
    }
}
