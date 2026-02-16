<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\CompradorService;
use App\Services\FornecedorService;
use App\Repositories\SegmentoRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function __construct(
        private UserService $userService,
        private CompradorService $compradorService,
        private FornecedorService $fornecedorService,
        private SegmentoRepository $segmentoRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Exibir formulário de edição do próprio perfil
     */
    public function edit()
    {
        $usuario = auth()->user();
        
        // Preparar dados conforme o role
        if ($usuario->role === 'comprador') {
            $dadosEdicao = $this->compradorService->prepararDadosEdicao($usuario->id);
            $perfil = $dadosEdicao['comprador'];
            $dados = $dadosEdicao['dadosContato'];
            $segmentosIds = $dadosEdicao['segmentosIds'];
        } elseif ($usuario->role === 'fornecedor') {
            $dadosEdicao = $this->fornecedorService->prepararDadosEdicao($usuario->id);
            $perfil = $dadosEdicao['fornecedor'];
            $dados = $dadosEdicao['dadosContato'];
            $segmentosIds = $dadosEdicao['segmentosIds'];
        } else {
            // Admin: dados simplificados
            $perfil = $usuario;
            $dados = [
                'telefone' => '',
                'whatsapp' => '',
                'cidade' => '',
                'estado' => '',
            ];
            $segmentosIds = [];
        }
        
        $segmentos = $this->segmentoRepository->buscarAtivos();
        
        return view('perfil.edit', compact('perfil', 'dados', 'segmentosIds', 'segmentos'));
    }

    /**
     * Atualizar o próprio perfil
     */
    public function update(Request $request)
    {
        $usuario = auth()->user();
        
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'telefone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'cidade' => 'nullable|string|max:255',
            'estado' => 'nullable|string|size:2',
            'nome_negocio' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:18',
            'tipo_negocio' => 'nullable|string|max:100',
            'colaboradores' => 'nullable|integer|min:1',
            'site_url' => 'nullable|url|max:255',
            'descricao' => 'nullable|string|max:500',
            'segmentos' => 'nullable|array',
            'segmentos.*' => 'exists:segmentos,id',
        ]);

        // Upload de logo/foto se fornecida
        if ($request->hasFile('logo')) {
            $foto = $request->file('logo');
            $nomeArquivo = time() . '_' . $usuario->id . '.' . $foto->extension();
            $caminhoFoto = $foto->storeAs('logos', $nomeArquivo, 'public');
            $dados['logo_path'] = $caminhoFoto;
        }

        // Atualizar dados básicos do usuário
        $dadosUsuario = [
            'name' => $dados['name'],
            'email' => $dados['email'],
        ];
        
        // Admin salva foto na tabela users
        if ($usuario->role === 'admin' && isset($dados['logo_path'])) {
            $dadosUsuario['logo_path'] = $dados['logo_path'];
        }
        
        $this->userService->atualizarPerfil($usuario, $dadosUsuario);

        // Atualizar segmentos se fornecido
        if (isset($dados['segmentos'])) {
            $this->userRepository->sincronizarSegmentos($usuario, $dados['segmentos']);
        }

        // Atualizar contatos e endereço via UserRepository (não admin)
        if ($usuario->role !== 'admin') {
            $this->userRepository->atualizarContatoEEndereco($usuario->id, $dados);
        }

        // Atualizar dados específicos do comprador/fornecedor
        if ($usuario->role === 'comprador') {
            $this->compradorService->atualizarDadosNegocio($usuario->id, $dados);
        } elseif ($usuario->role === 'fornecedor') {
            $this->fornecedorService->atualizarDadosNegocio($usuario->id, $dados);
        }

        // Recarregar dados do usuário para atualizar a sessão
        auth()->user()->refresh();

        return redirect()->route('perfil.editar')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }
}
