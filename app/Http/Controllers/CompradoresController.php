<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\SegmentoModel;

/**
 * Controller PÚBLICO de Compradores
 * Todos podem VER, apenas admin pode EDITAR
 */
class CompradoresController extends Controller
{

    /**
     * Lista todos os compradores (apenas aprovados)
     */
    public function index()
    {
        $query = UserModel::where('role', 'comprador')
            ->where('status', 'aprovado')
            ->with(['comprador', 'segmentos']);
        
        // Obter filtros da requisição
        $status = request()->get('status', '');
        $plano = request()->get('plano', '');
        $busca = request()->get('busca', '');
        $segmentoId = request()->get('segmento', '');
        $cidade = request()->get('cidade', '');
        
        // Aplicar filtros
        if ($busca) {
            $query->where(function($q) use ($busca) {
                $q->where('name', 'like', "%{$busca}%")
                  ->orWhere('email', 'like', "%{$busca}%");
            });
        }
        
        if ($plano) {
            $query->where('plano', $plano);
        }
        
        if ($segmentoId) {
            $query->whereHas('segmentos', function($q) use ($segmentoId) {
                $q->where('segmentos.id', $segmentoId);
            });
        }
        
        if ($cidade) {
            $query->where('cidade', 'like', "%{$cidade}%");
        }
        
        $compradores = $query->paginate(12);
        
        // Dados para filtros
        $filtrosStatus = [
            '' => 'Todos',
            'ativo' => 'Ativo',
            'inativo' => 'Inativo'
        ];
        
        $filtrosPlano = [
            '' => 'Todos',
            'gratuito' => 'Gratuito',
            'basico' => 'Básico',
            'premium' => 'Premium'
        ];
        
        // Buscar cidades únicas
        $filtrosCidade = UserModel::where('role', 'comprador')
            ->where('status', 'aprovado')
            ->whereNotNull('cidade')
            ->distinct()
            ->pluck('cidade')
            ->toArray();
        
        $segmentos = SegmentoModel::where('ativo', true)->orderBy('nome')->get();

        return view('admin.compradores.index', compact(
            'compradores',
            'filtrosStatus',
            'filtrosPlano',
            'filtrosCidade',
            'segmentos',
            'status',
            'plano',
            'cidade',
            'segmentoId',
            'busca'
        ));
    }

    /**
     * Exibe detalhes de um comprador específico
     */
    public function show($id)
    {
        $usuario = UserModel::where('id', $id)
            ->where('role', 'comprador')
            ->where('status', 'aprovado')
            ->with(['comprador', 'segmentos'])
            ->first();

        if (!$usuario) {
            abort(404, 'Comprador não encontrado.');
        }

        return view('admin.compradores.show', ['comprador' => $usuario]);
    }
}
