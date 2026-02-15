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

        $query = TalentoModel::query()
            ->where('ativo', true)
            ->where('disponivel', true);

        // Filtros
        if (request('nome')) {
            $query->where('nome_completo', 'like', '%' . request('nome') . '%');
        }

        if (request('funcao')) {
            $query->where('funcao', 'like', '%' . request('funcao') . '%');
        }

        if (request('tipo_cobranca')) {
            $query->where('tipo_cobranca', request('tipo_cobranca'));
        }

        if (request('valor_min')) {
            $query->where('valor_hora', '>=', request('valor_min'));
        }

        if (request('valor_max')) {
            $query->where('valor_hora', '<=', request('valor_max'));
        }

        $talentos = $query->orderBy('nome_completo')->paginate(12);

        // Preservar filtros
        $nome = request('nome');
        $funcao = request('funcao');
        $tipoCobranca = request('tipo_cobranca');
        $valorMin = request('valor_min', 0);
        $valorMax = request('valor_max', 500);

        return view('talentos.index', compact(
            'talentos',
            'nome',
            'funcao',
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

        return view('talentos.show', compact('talento'));
    }
}
