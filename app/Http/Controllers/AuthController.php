<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Repositories\SegmentoRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private SegmentoRepository $segmentoRepository
    ) {}

    /**
     * Exibir formulário de login
     */
    public function exibirLogin()
    {
        return view('auth.login');
    }

    /**
     * Processar login
     */
    public function login(Request $request)
    {
        $credenciais = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        if (!$this->authService->autenticar($credenciais, $request->filled('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'As credenciais não correspondem aos nossos registros.',
            ]);
        }

        $request->session()->regenerate();

        $usuario = $this->authService->obterUsuarioAutenticado();
        $acesso = $this->authService->podeAcessar($usuario);

        if (!$acesso['pode']) {
            if ($acesso['motivo'] === 'rejeitado') {
                $this->authService->logout();
                throw ValidationException::withMessages([
                    'email' => 'Seu cadastro foi rejeitado. ' . ($acesso['mensagem'] ?? ''),
                ]);
            }
            return redirect()->route($acesso['rota']);
        }

        return redirect()->intended(route($acesso['rota']));
    }

    /**
     * Exibir formulário de cadastro
     */
    public function exibirCadastro()
    {
        $segmentos = $this->segmentoRepository->buscarAtivos();
        
        return view('auth.cadastro', compact('segmentos'));
    }

    /**
     * Processar cadastro
     */
    public function cadastrar(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'role' => 'required|in:comprador,fornecedor',
            'cnpj' => 'nullable|string|max:18',
            'nome_estabelecimento' => 'required|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'estado' => 'nullable|string|size:2',
            'descricao' => 'nullable|string|max:500',
            'segmentos' => 'required|array|min:1',
            'segmentos.*' => 'exists:segmentos,id',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'email.unique' => 'Este email já está cadastrado.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'password.confirmed' => 'As senhas não conferem.',
            'role.required' => 'Selecione o tipo de perfil.',
            'nome_estabelecimento.required' => 'O campo nome do estabelecimento é obrigatório.',
            'segmentos.required' => 'Selecione pelo menos um segmento de atuação.',
            'segmentos.min' => 'Selecione pelo menos um segmento de atuação.',
            'logo.image' => 'O arquivo deve ser uma imagem.',
            'logo.mimes' => 'A imagem deve ser JPG, JPEG, PNG ou WEBP.',
            'logo.max' => 'A imagem não pode ter mais de 2MB.',
        ]);

        $usuario = $this->authService->cadastrar($dados);
        
        auth()->login($usuario);

        return redirect()->route('aguardando');
    }

    /**
     * Exibir página aguardando aprovação
     */
    public function aguardando()
    {
        $usuario = $this->authService->obterUsuarioAutenticado();

        if ($usuario->status !== 'pendente') {
            return redirect()->route('dashboard');
        }

        return view('auth.aguardando');
    }

    /**
     * Processar logout
     */
    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
