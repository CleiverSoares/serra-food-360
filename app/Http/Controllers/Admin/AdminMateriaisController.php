<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MaterialService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminMateriaisController extends Controller
{
    public function __construct(
        private MaterialService $materialService
    ) {}

    /**
     * Listar todos os materiais (admin)
     */
    public function index(Request $request): View
    {
        $filtros = $request->only(['categoria', 'tipo', 'busca', 'apenas_vip']);
        
        $materiais = $this->materialService->listarParaAdmin($filtros, 15);
        
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

        return view('admin.materiais.index', compact('materiais', 'categorias'));
    }

    /**
     * Exibir formulário de criação
     */
    public function create(): View
    {
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

        return view('admin.materiais.create', compact('categorias'));
    }

    /**
     * Salvar novo material
     */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria' => 'required|in:financeiro,pessoas,cardapio,seguranca-alimentar,marketing,legislacao,logistica,inovacao',
            'tipo' => 'required|in:arquivo,video,link',
            'arquivo' => 'required_if:tipo,arquivo|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'video_url' => 'required_if:tipo,video|url',
            'link_externo' => 'required_if:tipo,link|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'apenas_vip' => 'boolean',
            'ativo' => 'boolean',
        ]);

        $dados['criado_por'] = auth()->id();
        $dados['apenas_vip'] = $request->has('apenas_vip');
        $dados['ativo'] = $request->has('ativo');

        // Passar os arquivos separadamente
        if ($request->hasFile('thumbnail')) {
            $dados['thumbnail'] = $request->file('thumbnail');
        }
        
        if ($request->hasFile('arquivo')) {
            $dados['arquivo'] = $request->file('arquivo');
        }

        $this->materialService->criar($dados);

        return redirect()
            ->route('admin.materiais.index')
            ->with('sucesso', 'Material criado com sucesso!');
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(int $id): View
    {
        $material = $this->materialService->obterPorId($id);

        if (!$material) {
            abort(404);
        }

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

        return view('admin.materiais.edit', compact('material', 'categorias'));
    }

    /**
     * Atualizar material
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'categoria' => 'required|in:financeiro,pessoas,cardapio,seguranca-alimentar,marketing,legislacao,logistica,inovacao',
            'tipo' => 'required|in:arquivo,video,link',
            'arquivo' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'video_url' => 'nullable|url',
            'link_externo' => 'nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'apenas_vip' => 'boolean',
            'ativo' => 'boolean',
        ]);

        $dados['apenas_vip'] = $request->has('apenas_vip');
        $dados['ativo'] = $request->has('ativo');

        // Passar os arquivos separadamente
        if ($request->hasFile('thumbnail')) {
            $dados['thumbnail'] = $request->file('thumbnail');
        }
        
        if ($request->hasFile('arquivo')) {
            $dados['arquivo'] = $request->file('arquivo');
        }

        $this->materialService->atualizar($id, $dados);

        return redirect()
            ->route('admin.materiais.index')
            ->with('sucesso', 'Material atualizado com sucesso!');
    }

    /**
     * Deletar material
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->materialService->deletar($id);

        return redirect()
            ->route('admin.materiais.index')
            ->with('sucesso', 'Material deletado com sucesso!');
    }

    /**
     * Exibir analytics
     */
    public function analytics(): View
    {
        $analytics = $this->materialService->obterAnalytics();

        return view('admin.materiais.analytics', $analytics);
    }
}
