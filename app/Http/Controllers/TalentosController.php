<?php

namespace App\Http\Controllers;

use App\Models\TalentoModel;

/**
 * Controller PÚBLICO de Talentos
 * Compradores podem VER, apenas admin pode EDITAR
 */
class TalentosController extends Controller
{
    /**
     * Lista todos os talentos disponíveis
     */
    public function index()
    {
        // Fornecedores NÃO veem talentos
        if (auth()->user()->role === 'fornecedor') {
            abort(403, 'Fornecedores não têm acesso ao banco de talentos.');
        }

        $busca = request()->get('busca', '');
        $cargo = request()->get('cargo', '');
        $disponibilidade = request()->get('disponibilidade', '');
        $tipoCobranca = request()->get('tipo_cobranca', '');
        $valorMin = request()->get('valor_min', '');
        $valorMax = request()->get('valor_max', '');

        $query = TalentoModel::where('ativo', true);

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

        $talentos = $query->orderBy('nome')->paginate(12);

        // Obter lista única de cargos para o filtro
        $cargos = TalentoModel::where('ativo', true)
            ->select('cargo')
            ->distinct()
            ->orderBy('cargo')
            ->pluck('cargo');

        // Obter lista única de disponibilidades para o filtro
        $disponibilidades = TalentoModel::where('ativo', true)
            ->whereNotNull('disponibilidade')
            ->select('disponibilidade')
            ->distinct()
            ->orderBy('disponibilidade')
            ->pluck('disponibilidade');

        return view('admin.talentos.index', compact(
            'talentos',
            'busca',
            'cargo',
            'disponibilidade',
            'cargos',
            'disponibilidades',
            'tipoCobranca',
            'valorMin',
            'valorMax'
        ));
    }

    /**
     * Exibe detalhes de um talento específico
     */
    public function show($id)
    {
        // Fornecedores NÃO veem talentos
        if (auth()->user()->role === 'fornecedor') {
            abort(403, 'Fornecedores não têm acesso ao banco de talentos.');
        }

        $talento = TalentoModel::findOrFail($id);

        if (!$talento->ativo) {
            abort(404, 'Talento não encontrado.');
        }

        return view('admin.talentos.show', compact('talento'));
    }
}
