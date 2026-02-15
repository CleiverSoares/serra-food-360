<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TalentoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTalentosController extends Controller
{
    /**
     * Listar todos os talentos
     */
    public function index(Request $request)
    {
        $busca = $request->get('busca', '');
        $cargo = $request->get('cargo', '');
        $disponibilidade = $request->get('disponibilidade', '');
        $tipoCobranca = $request->get('tipo_cobranca', '');
        $valorMin = $request->get('valor_min', '');
        $valorMax = $request->get('valor_max', '');

        $query = TalentoModel::query();

        // Filtro de busca
        if ($busca) {
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('whatsapp', 'like', "%{$busca}%")
                  ->orWhere('cargo', 'like', "%{$busca}%");
            });
        }

        // Filtro por cargo
        if ($cargo) {
            $query->where('cargo', 'like', "%{$cargo}%");
        }

        // Filtro por disponibilidade
        if ($disponibilidade) {
            $query->where('disponibilidade', 'like', "%{$disponibilidade}%");
        }

        // Filtro por tipo de cobrança
        if ($tipoCobranca) {
            $query->where('tipo_cobranca', $tipoCobranca);
        }

        // Filtro por valor mínimo
        if ($valorMin !== '' && is_numeric($valorMin)) {
            $query->where('pretensao', '>=', $valorMin);
        }

        // Filtro por valor máximo
        if ($valorMax !== '' && is_numeric($valorMax)) {
            $query->where('pretensao', '<=', $valorMax);
        }

        $talentos = $query->orderBy('created_at', 'desc')->get();

        // Obter lista única de cargos para o filtro
        $cargos = TalentoModel::select('cargo')
            ->distinct()
            ->orderBy('cargo')
            ->pluck('cargo');

        // Obter lista única de disponibilidades para o filtro
        $disponibilidades = TalentoModel::whereNotNull('disponibilidade')
            ->select('disponibilidade')
            ->distinct()
            ->orderBy('disponibilidade')
            ->pluck('disponibilidade');

        return view('admin.talentos.index', compact(
            'talentos', 'busca', 'cargo', 'disponibilidade', 'cargos', 'disponibilidades',
            'tipoCobranca', 'valorMin', 'valorMax'
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

        $talento = new TalentoModel($validated);

        // Upload da foto
        if ($request->hasFile('foto')) {
            $talento->foto_path = $request->file('foto')->store('talentos/fotos', 'public');
        }

        // Upload do currículo
        if ($request->hasFile('curriculo_pdf')) {
            $talento->curriculo_path = $request->file('curriculo_pdf')->store('talentos/curriculos', 'public');
        }

        // Upload da carta de recomendação
        if ($request->hasFile('carta_recomendacao')) {
            $talento->carta_recomendacao_path = $request->file('carta_recomendacao')->store('talentos/cartas', 'public');
        }

        $talento->save();

        return redirect()
            ->route('admin.talentos.show', $talento->id)
            ->with('sucesso', 'Talento cadastrado com sucesso!');
    }

    /**
     * Exibir detalhes de um talento
     */
    public function show(int $id)
    {
        $talento = TalentoModel::findOrFail($id);
        return view('admin.talentos.show', compact('talento'));
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(int $id)
    {
        $talento = TalentoModel::findOrFail($id);
        return view('admin.talentos.edit', compact('talento'));
    }

    /**
     * Atualizar talento
     */
    public function update(Request $request, int $id)
    {
        $talento = TalentoModel::findOrFail($id);

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

        $talento->fill($validated);

        // Upload da foto (remove antiga se houver nova)
        if ($request->hasFile('foto')) {
            if ($talento->foto_path) {
                Storage::disk('public')->delete($talento->foto_path);
            }
            $talento->foto_path = $request->file('foto')->store('talentos/fotos', 'public');
        }

        // Upload do currículo (remove antigo se houver novo)
        if ($request->hasFile('curriculo_pdf')) {
            if ($talento->curriculo_path) {
                Storage::disk('public')->delete($talento->curriculo_path);
            }
            $talento->curriculo_path = $request->file('curriculo_pdf')->store('talentos/curriculos', 'public');
        }

        // Upload da carta de recomendação (remove antiga se houver nova)
        if ($request->hasFile('carta_recomendacao')) {
            if ($talento->carta_recomendacao_path) {
                Storage::disk('public')->delete($talento->carta_recomendacao_path);
            }
            $talento->carta_recomendacao_path = $request->file('carta_recomendacao')->store('talentos/cartas', 'public');
        }

        $talento->save();

        return redirect()
            ->route('admin.talentos.show', $talento->id)
            ->with('sucesso', 'Talento atualizado com sucesso!');
    }

    /**
     * Inativar talento
     */
    public function inativar(int $id)
    {
        $talento = TalentoModel::findOrFail($id);
        $talento->ativo = false;
        $talento->save();

        return back()->with('sucesso', 'Talento inativado com sucesso!');
    }

    /**
     * Ativar talento
     */
    public function ativar(int $id)
    {
        $talento = TalentoModel::findOrFail($id);
        $talento->ativo = true;
        $talento->save();

        return back()->with('sucesso', 'Talento ativado com sucesso!');
    }

    /**
     * Deletar talento
     */
    public function destroy(int $id)
    {
        $talento = TalentoModel::findOrFail($id);

        // Remove arquivos
        if ($talento->foto_path) {
            Storage::disk('public')->delete($talento->foto_path);
        }
        if ($talento->curriculo_path) {
            Storage::disk('public')->delete($talento->curriculo_path);
        }
        if ($talento->carta_recomendacao_path) {
            Storage::disk('public')->delete($talento->carta_recomendacao_path);
        }

        $talento->delete();

        return redirect()
            ->route('admin.talentos.index')
            ->with('sucesso', 'Talento excluído com sucesso!');
    }
}
