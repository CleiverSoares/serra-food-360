<?php

namespace App\Http\Controllers;

use App\Services\MaterialService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MateriaisController extends Controller
{
    public function __construct(
        private MaterialService $materialService
    ) {
        $this->middleware('auth');
    }

    /**
     * Listar materiais para o usuário logado
     */
    public function index(Request $request): View
    {
        $usuario = auth()->user();
        $isVip = $usuario->comprador?->plano === 'vip' || $usuario->fornecedor?->plano === 'vip';
        
        $filtros = $request->only(['categoria', 'tipo', 'busca']);
        
        $materiais = $this->materialService->listarParaUsuario(
            $usuario->id,
            $isVip,
            $filtros,
            12
        );
        
        $categorias = [
            'financeiro' => 'Financeiro',
            'pessoas' => 'Gestão de Pessoas',
            'cardapio' => 'Cardápio',
            'seguranca-alimentar' => 'Segurança Alimentar',
            'marketing' => 'Marketing',
            'legislacao' => 'Legislação',
            'logistica' => 'Logística',
            'inovacao' => 'Inovação',
        ];

        return view('materiais.index', compact('materiais', 'categorias', 'isVip'));
    }

    /**
     * Exibir detalhes do material
     */
    public function show(int $id): View
    {
        $material = $this->materialService->obterPorId($id);

        if (!$material) {
            abort(404);
        }

        $usuario = auth()->user();
        $isVip = $usuario->comprador?->plano === 'vip' || $usuario->fornecedor?->plano === 'vip';

        // Se é VIP only e usuário não é VIP, bloquear
        if ($material->apenas_vip && !$isVip) {
            abort(403, 'Este conteúdo é exclusivo para assinantes VIP.');
        }

        // Registrar visualização
        $this->materialService->visualizar($material->id, $usuario->id);

        return view('materiais.show', compact('material', 'isVip'));
    }

    /**
     * Baixar arquivo do material
     */
    public function download(int $id): BinaryFileResponse
    {
        $material = $this->materialService->obterPorId($id);

        if (!$material || $material->tipo !== 'arquivo' || !$material->arquivo_path) {
            abort(404);
        }

        $usuario = auth()->user();
        $isVip = $usuario->comprador?->plano === 'vip' || $usuario->fornecedor?->plano === 'vip';

        // Se é VIP only e usuário não é VIP, bloquear
        if ($material->apenas_vip && !$isVip) {
            abort(403, 'Este conteúdo é exclusivo para assinantes VIP.');
        }

        // Registrar download
        $this->materialService->baixar($material->id, $usuario->id);

        return Storage::disk('public')->download($material->arquivo_path, $material->titulo);
    }

    /**
     * Alternar favorito (AJAX)
     */
    public function toggleFavorito(int $id): JsonResponse
    {
        $usuario = auth()->user();
        
        $adicionou = $this->materialService->alternarFavorito($usuario->id, $id);

        return response()->json([
            'success' => true,
            'favorito' => $adicionou,
            'mensagem' => $adicionou ? 'Adicionado aos favoritos!' : 'Removido dos favoritos!',
        ]);
    }

    /**
     * Listar favoritos do usuário
     */
    public function favoritos(): View
    {
        $usuario = auth()->user();
        $materiais = $this->materialService->listarFavoritos($usuario->id);

        return view('materiais.favoritos', compact('materiais'));
    }
}
