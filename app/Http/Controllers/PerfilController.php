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
            // Admin não usa essa tela
            return redirect()->route('home')->with('erro', 'Acesso negado.');
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

        // Atualizar dados básicos
        $this->userService->atualizarPerfil($usuario, [
            'name' => $dados['name'],
            'email' => $dados['email'],
        ]);

        // Atualizar segmentos se fornecido
        if (isset($dados['segmentos'])) {
            $this->userRepository->sincronizarSegmentos($usuario, $dados['segmentos']);
        }

        // TODO: Usar Service para atualizar contatos, endereço e dados específicos
        // Por ora, mantém a lógica aqui mas idealmente deveria estar no Service
        
        // Atualizar contatos e endereço via UserRepository
        $this->userRepository->atualizarContatoEEndereco($usuario->id, $dados);

        // Atualizar dados específicos do comprador/fornecedor
        if ($usuario->role === 'comprador') {
            $this->compradorService->atualizarDadosNegocio($usuario->id, $dados);
        } elseif ($usuario->role === 'fornecedor') {
            $this->fornecedorService->atualizarDadosNegocio($usuario->id, $dados);
        }

        return redirect()->route('perfil.editar')
            ->with('sucesso', 'Perfil atualizado com sucesso!');
    }
}
