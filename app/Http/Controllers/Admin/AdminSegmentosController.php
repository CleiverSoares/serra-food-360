<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SegmentoService;
use Illuminate\Http\Request;

/**
 * Controller Admin de Segmentos
 * Controller → Service → Repository → Model
 */
class AdminSegmentosController extends Controller
{
    public function __construct(
        private SegmentoService $segmentoService
    ) {}

    /**
     * Listar todos os segmentos
     */
    public function index()
    {
        $segmentos = $this->segmentoService->listarTodosComContagem();
        return view('admin.segmentos.index', compact('segmentos'));
    }

    /**
     * Exibir formulário de criar segmento
     */
    public function create()
    {
        return view('admin.segmentos.create');
    }

    /**
     * Salvar novo segmento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100|unique:segmentos,nome',
            'slug' => 'required|string|max:100|unique:segmentos,slug',
            'descricao' => 'nullable|string|max:500',
            'icone' => 'required|string|max:10',
            'cor' => 'required|string|max:7',
            'ativo' => 'nullable|boolean',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.unique' => 'Já existe um segmento com este nome.',
            'slug.required' => 'O slug é obrigatório.',
            'slug.unique' => 'Já existe um segmento com este slug.',
            'icone.required' => 'O ícone (emoji) é obrigatório.',
            'cor.required' => 'A cor é obrigatória.',
        ]);

        $validated['ativo'] = $request->has('ativo');
        $this->segmentoService->criar($validated);

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento criado com sucesso!');
    }

    /**
     * Exibir formulário de editar segmento
     */
    public function edit($id)
    {
        $segmento = $this->segmentoService->buscarPorIdComContagemOuFalhar($id);
        return view('admin.segmentos.edit', compact('segmento'));
    }

    /**
     * Atualizar segmento
     */
    public function update(Request $request, $id)
    {
        $segmento = $this->segmentoService->buscarPorIdComContagemOuFalhar($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:100|unique:segmentos,nome,' . $id,
            'slug' => 'required|string|max:100|unique:segmentos,slug,' . $id,
            'descricao' => 'nullable|string|max:500',
            'icone' => 'required|string|max:10',
            'cor' => 'required|string|max:7',
            'ativo' => 'nullable|boolean',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'nome.unique' => 'Já existe um segmento com este nome.',
            'slug.required' => 'O slug é obrigatório.',
            'slug.unique' => 'Já existe um segmento com este slug.',
            'icone.required' => 'O ícone (emoji) é obrigatório.',
            'cor.required' => 'A cor é obrigatória.',
        ]);

        $validated['ativo'] = $request->has('ativo');
        $this->segmentoService->atualizar($segmento, $validated);

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento atualizado com sucesso!');
    }

    /**
     * Deletar segmento
     */
    public function destroy($id)
    {
        $resultado = $this->segmentoService->deletar($id);

        if (!$resultado['sucesso']) {
            return redirect()
                ->route('admin.segmentos.index')
                ->with('erro', $resultado['mensagem']);
        }

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', $resultado['mensagem']);
    }

    /**
     * Ativar segmento
     */
    public function ativar($id)
    {
        $this->segmentoService->ativar($id);

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento ativado com sucesso!');
    }

    /**
     * Inativar segmento
     */
    public function inativar($id)
    {
        $this->segmentoService->inativar($id);

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento inativado com sucesso!');
    }
}
