@extends('layouts.dashboard')

@section('titulo', 'Admin - Dashboard')
@section('page-title', 'Dashboard Administrativo')
@section('page-subtitle', 'Visão completa do sistema')

@section('conteudo')
<div class="p-4 lg:p-8">
    <div class="max-w-7xl mx-auto">

    <!-- Filtros de Período -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)] mb-6">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                <input type="date" name="data_inicio" value="{{ $filtros['data_inicio'] ?? '' }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                <input type="date" name="data_fim" value="{{ $filtros['data_fim'] ?? '' }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--cor-verde-serra)]">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-6 py-2 bg-[var(--cor-verde-serra)] text-white rounded-lg hover:bg-[var(--cor-verde-escuro)] transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Cards de Estatísticas Gerais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <span class="text-2xl font-bold text-[var(--cor-texto)]">{{ $estatisticas['pendentes'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-[var(--cor-texto-secundario)]">Usuários Pendentes</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
                </div>
                <span class="text-2xl font-bold text-[var(--cor-texto)]">{{ $estatisticas['aprovados'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-[var(--cor-texto-secundario)]">Usuários Aprovados</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="utensils" class="w-6 h-6 text-blue-600"></i>
                </div>
                <span class="text-2xl font-bold text-[var(--cor-texto)]">{{ $estatisticas['restaurantes'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-[var(--cor-texto-secundario)]">Compradores</h3>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6 text-purple-600"></i>
                </div>
                <span class="text-2xl font-bold text-[var(--cor-texto)]">{{ $estatisticas['fornecedores'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-[var(--cor-texto-secundario)]">Fornecedores</h3>
        </div>
    </div>

    <!-- Cards Financeiros -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-green-50 rounded-xl p-6 border-2 border-green-200">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="dollar-sign" class="w-8 h-8 text-green-600"></i>
                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded">RECEITA</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">
                R$ {{ number_format($financeiro['receita_total'], 2, ',', '.') }}
            </p>
            <p class="text-sm text-gray-600">Receita Total Ativa</p>
        </div>

        <div class="bg-blue-50 rounded-xl p-6 border-2 border-blue-200">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="trending-up" class="w-8 h-8 text-blue-600"></i>
                <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded">MRR</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">
                R$ {{ number_format($financeiro['mrr'], 2, ',', '.') }}
            </p>
            <p class="text-sm text-gray-600">Receita Mensal Recorrente</p>
        </div>

        <div class="bg-purple-50 rounded-xl p-6 border-2 border-purple-200">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="check-circle" class="w-8 h-8 text-purple-600"></i>
                <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-2 py-1 rounded">ATIVAS</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">
                {{ $financeiro['total_ativas'] }}
            </p>
            <p class="text-sm text-gray-600">Assinaturas Ativas</p>
        </div>

        <div class="bg-yellow-50 rounded-xl p-6 border-2 border-yellow-200">
            <div class="flex items-center justify-between mb-2">
                <i data-lucide="alert-circle" class="w-8 h-8 text-yellow-600"></i>
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-100 px-2 py-1 rounded">VENCIDAS</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mb-1">
                {{ $financeiro['total_vencidas'] }}
            </p>
            <p class="text-sm text-gray-600">Assinaturas Vencidas</p>
        </div>
    </div>

    <!-- Gráficos Principais -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Evolução de Receita -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="line-chart" class="w-5 h-5"></i>
                Evolução de Receita (6 meses)
            </h3>
            <div style="position: relative; height: 300px;">
                <canvas id="graficoReceita"></canvas>
            </div>
        </div>

        <!-- Distribuição de Receita por Plano -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="pie-chart" class="w-5 h-5"></i>
                Receita por Plano
            </h3>
            <div style="position: relative; height: 300px;">
                <canvas id="graficoPlanos"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráficos Secundários -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Receita por Tipo de Usuário -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5"></i>
                Receita por Tipo de Usuário
            </h3>
            <div style="position: relative; height: 300px;">
                <canvas id="graficoTipoUsuario"></canvas>
            </div>
        </div>

        <!-- Receita por Segmento -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="briefcase" class="w-5 h-5"></i>
                Receita por Segmento
            </h3>
            <div style="position: relative; height: 300px;">
                <canvas id="graficoSegmento"></canvas>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-[var(--cor-borda)]">
        <h2 class="text-xl font-bold text-[var(--cor-texto)] mb-6">Ações Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.usuarios.index', ['filtro' => 'pendentes']) }}" class="flex items-center gap-4 p-4 border-2 border-[var(--cor-borda)] rounded-lg hover:border-[var(--cor-verde-serra)] transition-all group">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="user-check" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-[var(--cor-texto)]">Aprovar Usuários</h3>
                    <p class="text-sm text-[var(--cor-texto-secundario)]">{{ $estatisticas['pendentes'] }} pendentes</p>
                </div>
            </a>

            <a href="{{ route('admin.compradores.index') }}" class="flex items-center gap-4 p-4 border-2 border-[var(--cor-borda)] rounded-lg hover:border-[var(--cor-verde-serra)] transition-all group">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-[var(--cor-texto)]">Gerenciar Compradores</h3>
                    <p class="text-sm text-[var(--cor-texto-secundario)]">{{ $estatisticas['restaurantes'] }} cadastrados</p>
                </div>
            </a>

            <a href="{{ route('admin.talentos.index') }}" class="flex items-center gap-4 p-4 border-2 border-[var(--cor-borda)] rounded-lg hover:border-[var(--cor-verde-serra)] transition-all group">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="briefcase" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-[var(--cor-texto)]">Gerenciar Talentos</h3>
                    <p class="text-sm text-[var(--cor-texto-secundario)]">{{ $estatisticas['talentos'] ?? 0 }} cadastrados</p>
                </div>
            </a>
        </div>
    </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
if (!window.__DASHBOARD_CHARTS_LOADED__) {
    window.__DASHBOARD_CHARTS_LOADED__ = true;
    
    const initDashboardCharts = () => {
        if (typeof Chart === 'undefined') {
            setTimeout(initDashboardCharts, 50);
            return;
        }

        const corComum = '#22C55E';
        const corVip = '#8B4512';
        const corComprador = '#3B82F6';
        const corFornecedor = '#8B5CF6';
        const coresSegmento = ['#22C55E', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899'];

        // 1. Gráfico de Evolução de Receita
        const canvasReceita = document.getElementById('graficoReceita');
        if (canvasReceita && !canvasReceita.__chart__) {
            canvasReceita.__chart__ = new Chart(canvasReceita.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($graficos['evolucao']['labels']),
                    datasets: [
                        {
                            label: 'Receita Comum (R$)',
                            data: @json($graficos['evolucao']['receita_comum']),
                            borderColor: corComum,
                            backgroundColor: corComum + '20',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Receita VIP (R$)',
                            data: @json($graficos['evolucao']['receita_vip']),
                            borderColor: corVip,
                            backgroundColor: corVip + '20',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 15, font: { size: 12, weight: '600' } } },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return ctx.dataset.label + ': R$ ' + ctx.parsed.y.toFixed(2).replace('.', ',');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toFixed(0);
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. Gráfico de Receita por Plano (Doughnut)
        const canvasPlanos = document.getElementById('graficoPlanos');
        if (canvasPlanos && !canvasPlanos.__chart__) {
            canvasPlanos.__chart__ = new Chart(canvasPlanos.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Comum (X)', 'VIP (2X)'],
                    datasets: [{
                        data: [{{ $financeiro['receita_comum'] }}, {{ $financeiro['receita_vip'] }}],
                        backgroundColor: [corComum, corVip],
                        borderColor: '#ffffff',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 15, font: { size: 12, weight: '600' } } },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const valor = 'R$ ' + ctx.parsed.toFixed(2).replace('.', ',');
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = ((ctx.parsed / total) * 100).toFixed(1);
                                    return ctx.label + ': ' + valor + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // 3. Gráfico de Receita por Tipo de Usuário
        const canvasTipo = document.getElementById('graficoTipoUsuario');
        if (canvasTipo && !canvasTipo.__chart__) {
            canvasTipo.__chart__ = new Chart(canvasTipo.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Compradores', 'Fornecedores'],
                    datasets: [{
                        label: 'Receita (R$)',
                        data: [{{ $receitaPorTipo['compradores'] }}, {{ $receitaPorTipo['fornecedores'] }}],
                        backgroundColor: [corComprador, corFornecedor],
                        borderColor: [corComprador, corFornecedor],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return 'Receita: R$ ' + ctx.parsed.y.toFixed(2).replace('.', ',');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toFixed(0);
                                }
                            }
                        }
                    }
                }
            });
        }

        // 4. Gráfico de Receita por Segmento
        const canvasSegmento = document.getElementById('graficoSegmento');
        if (canvasSegmento && !canvasSegmento.__chart__) {
            const segmentos = @json(array_keys($receitaPorSegmento));
            const valores = @json(array_values($receitaPorSegmento));
            
            canvasSegmento.__chart__ = new Chart(canvasSegmento.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: segmentos,
                    datasets: [{
                        label: 'Receita (R$)',
                        data: valores,
                        backgroundColor: coresSegmento,
                        borderColor: coresSegmento,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return 'Receita: R$ ' + ctx.parsed.x.toFixed(2).replace('.', ',');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toFixed(0);
                                }
                            }
                        }
                    }
                }
            });
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboardCharts, { once: true });
    } else {
        initDashboardCharts();
    }
}
</script>
@endpush
