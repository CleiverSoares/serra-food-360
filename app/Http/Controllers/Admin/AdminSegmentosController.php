<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SegmentoModel;
use Illuminate\Http\Request;

class AdminSegmentosController extends Controller
{
    /**
     * Listar todos os segmentos
     */
    public function index()
    {
        $segmentos = SegmentoModel::withCount('users')
            ->orderBy('nome')
            ->get();

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
            'cor' => 'required|string|max:7', // hex color
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

        SegmentoModel::create($validated);

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento criado com sucesso!');
    }

    /**
     * Exibir formulário de editar segmento
     */
    public function edit($id)
    {
        $segmento = SegmentoModel::withCount('users')->findOrFail($id);

        return view('admin.segmentos.edit', compact('segmento'));
    }

    /**
     * Atualizar segmento
     */
    public function update(Request $request, $id)
    {
        $segmento = SegmentoModel::findOrFail($id);

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

        $segmento->update($validated);

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento atualizado com sucesso!');
    }

    /**
     * Deletar segmento
     */
    public function destroy($id)
    {
        $segmento = SegmentoModel::withCount('users')->findOrFail($id);

        if ($segmento->users_count > 0) {
            return redirect()
                ->route('admin.segmentos.index')
                ->with('erro', 'Não é possível deletar este segmento pois existem ' . $segmento->users_count . ' usuários associados a ele.');
        }

        $segmento->delete();

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento deletado com sucesso!');
    }

    /**
     * Ativar segmento
     */
    public function ativar($id)
    {
        $segmento = SegmentoModel::findOrFail($id);
        $segmento->update(['ativo' => true]);

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento ativado com sucesso!');
    }

    /**
     * Inativar segmento
     */
    public function inativar($id)
    {
        $segmento = SegmentoModel::findOrFail($id);
        $segmento->update(['ativo' => false]);

        return redirect()
            ->route('admin.segmentos.index')
            ->with('sucesso', 'Segmento inativado com sucesso!');
    }
}
