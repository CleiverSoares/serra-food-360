<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\TalentoService;
use App\Services\AssinaturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    private const CACHE_TTL = 1800; // 30 minutos

    public function __construct(
        private UserService $userService,
        private TalentoService $talentoService,
        private AssinaturaService $assinaturaService
    ) {}

    /**
     * Dashboard do Admin (com cache)
     */
    public function index(Request $request)
    {
        $dataInicio = $request->get('data_inicio');
        $dataFim = $request->get('data_fim');
        
        // Chave de cache única baseada nos filtros
        $cacheKey = 'dashboard_admin_' . md5($dataInicio . $dataFim);

        // Se usuário forçar refresh (Ctrl+F5), limpar cache
        if ($request->has('refresh')) {
            Cache::forget($cacheKey);
        }

        // Buscar ou criar cache
        $dados = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($dataInicio, $dataFim) {
            // Estatísticas gerais
            $estatisticas = $this->userService->obterEstatisticas();
            $estatisticas['talentos'] = $this->talentoService->contar();

            // Estatísticas financeiras
            $financeiro = $this->assinaturaService->obterEstatisticasFinanceiras($dataInicio, $dataFim);
            
            // Dados para gráficos
            $graficos = $this->assinaturaService->obterDadosGraficos();
            
            // Receita por tipo de usuário
            $receitaPorTipo = $this->assinaturaService->obterReceitaPorTipo();
            
            // Receita por segmento
            $receitaPorSegmento = $this->assinaturaService->obterReceitaPorSegmento();

            return compact('estatisticas', 'financeiro', 'graficos', 'receitaPorTipo', 'receitaPorSegmento');
        });
        
        // Passar filtros aplicados para a view
        $dados['filtros'] = [
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
        ];

        return view('admin.dashboard', $dados);
    }

    /**
     * Limpar cache da dashboard
     */
    public static function limparCache(): void
    {
        Cache::forget('dashboard_admin_' . md5(''));
        
        // Limpar variações com filtros de data (últimos 30 dias)
        for ($i = 0; $i < 30; $i++) {
            $data = now()->subDays($i)->format('Y-m-d');
            Cache::forget('dashboard_admin_' . md5($data . $data));
        }
    }
}
