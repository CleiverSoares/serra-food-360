<?php

namespace App\Http\Controllers\Comprador;

use App\Http\Controllers\Controller;
use App\Models\TalentoModel;

/**
 * Controller para Compradores visualizarem Talentos
 * APENAS LEITURA - para contratação
 */
class CompradorTalentosController extends Controller
{
    /**
     * Lista todos os talentos disponíveis
     */
    public function index()
    {
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

        return view('comprador.talentos.index', compact(
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
        $talento = TalentoModel::findOrFail($id);

        if (!$talento->ativo) {
            abort(404, 'Talento não encontrado.');
        }

        return view('comprador.talentos.show', compact('talento'));
    }
}
