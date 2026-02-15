<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TalentoService;
use App\Services\FilterService;
use Illuminate\Http\Request;

/**
 * Controller Admin de Talentos
 * Controller → Service → Repository → Model
 */
class AdminTalentosController extends Controller
{
    public function __construct(
        private TalentoService $talentoService,
        private FilterService $filterService
    ) {}

    /**
     * Listar todos os talentos
     */
    public function index(Request $request)
    {
        // Controller apenas orquestra
        $talentos = $this->talentoService->buscarTalentosAdmin($request->all());
        $dadosFiltros = $this->talentoService->obterDadosFiltros(false);
        $filtrosAplicados = $this->filterService->extrairFiltrosAplicados($request->all());

        return view('admin.talentos.index', array_merge(
            ['talentos' => $talentos],
            $dadosFiltros,
            $filtrosAplicados
        ));
    }

    /**
     * Exibir formulário de criação
     */
    public function create()
    {
        return view('admin.talentos.create');
    }

    /**
     * Salvar novo talento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'cargo' => 'required|string|max:100',
            'mini_curriculo' => 'required|string',
            'pretensao' => 'nullable|numeric|min:0',
            'tipo_cobranca' => 'required|in:hora,dia',
            'disponibilidade' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'curriculo_pdf' => 'nullable|mimes:pdf|max:5120',
            'carta_recomendacao' => 'nullable|mimes:pdf|max:5120',
        ]);

        $talento = $this->talentoService->criar($validated);

        return redirect()
            ->route('admin.talentos.show', $talento->id)
            ->with('sucesso', 'Talento cadastrado com sucesso!');
    }

    /**
     * Exibir detalhes de um talento
     */
    public function show(int $id)
    {
        $talento = $this->talentoService->buscarPorIdOuFalhar($id);
        return view('admin.talentos.show', compact('talento'));
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(int $id)
    {
        $talento = $this->talentoService->buscarPorIdOuFalhar($id);
        return view('admin.talentos.edit', compact('talento'));
    }

    /**
     * Atualizar talento
     */
    public function update(Request $request, int $id)
    {
        $talento = $this->talentoService->buscarPorIdOuFalhar($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'cargo' => 'required|string|max:100',
            'mini_curriculo' => 'required|string',
            'pretensao' => 'nullable|numeric|min:0',
            'tipo_cobranca' => 'required|in:hora,dia',
            'disponibilidade' => 'nullable|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'curriculo_pdf' => 'nullable|mimes:pdf|max:5120',
            'carta_recomendacao' => 'nullable|mimes:pdf|max:5120',
        ]);

        $this->talentoService->atualizar($talento, $validated);

        return redirect()
            ->route('admin.talentos.show', $talento->id)
            ->with('sucesso', 'Talento atualizado com sucesso!');
    }

    /**
     * Inativar talento
     */
    public function inativar(int $id)
    {
        $this->talentoService->inativar($id);
        return back()->with('sucesso', 'Talento inativado com sucesso!');
    }

    /**
     * Ativar talento
     */
    public function ativar(int $id)
    {
        $this->talentoService->ativar($id);
        return back()->with('sucesso', 'Talento ativado com sucesso!');
    }

    /**
     * Deletar talento
     */
    public function destroy(int $id)
    {
        $talento = $this->talentoService->buscarPorIdOuFalhar($id);
        $this->talentoService->deletar($talento);

        return redirect()
            ->route('admin.talentos.index')
            ->with('sucesso', 'Talento excluído com sucesso!');
    }
}
