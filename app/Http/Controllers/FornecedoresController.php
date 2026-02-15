<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\SegmentoModel;

/**
 * Controller PÚBLICO de Fornecedores
 * Todos podem VER, apenas admin pode EDITAR
 */
class FornecedoresController extends Controller
{

    /**
     * Lista todos os fornecedores (apenas aprovados)
     */
    public function index()
    {
        $query = UserModel::where('role', 'fornecedor')
            ->where('status', 'aprovado')
            ->with(['fornecedor', 'segmentos']);
        
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
        
        $fornecedores = $query->paginate(12);
        
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
        $filtrosCidade = UserModel::where('role', 'fornecedor')
            ->where('status', 'aprovado')
            ->whereNotNull('cidade')
            ->distinct()
            ->pluck('cidade')
            ->toArray();
        
        $segmentos = SegmentoModel::where('ativo', true)->orderBy('nome')->get();

        return view('admin.fornecedores.index', compact(
            'fornecedores',
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
     * Exibe detalhes de um fornecedor específico
     */
    public function show($id)
    {
        $usuario = UserModel::where('id', $id)
            ->where('role', 'fornecedor')
            ->where('status', 'aprovado')
            ->with(['fornecedor', 'segmentos'])
            ->first();

        if (!$usuario) {
            abort(404, 'Fornecedor não encontrado.');
        }

        return view('admin.fornecedores.show', ['fornecedor' => $usuario]);
    }
}
