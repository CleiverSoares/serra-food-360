<?php

namespace App\Services;

use App\Models\AssinaturaModel;
use App\Repositories\AssinaturaRepository;
use App\Repositories\UserRepository;
use App\Services\ConfiguracaoService;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Collection;

class AssinaturaService
{
    public function __construct(
        private AssinaturaRepository $assinaturaRepository,
        private UserRepository $userRepository,
        private EmailService $emailService,
        private ConfiguracaoService $configuracaoService
    ) {}

    /**
     * Criar nova assinatura para um usuário
     */
    public function criarAssinatura(int $userId, string $plano, string $tipoPagamento): AssinaturaModel
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        // IMPORTANTE: Usar dias fixos para garantir período correto
        // Mensal = 30 dias, Anual = 365 dias (não depende do mês)
        $dias = $tipoPagamento === 'anual' ? 365 : 30;
        
        $dados = [
            'user_id' => $userId,
            'plano' => $plano,
            'tipo_pagamento' => $tipoPagamento,
            'data_inicio' => now(),
            'data_fim' => now()->addDays($dias),
            'status' => 'ativo',
        ];

        $assinatura = $this->assinaturaRepository->criar($dados);

        \Log::info('[ASSINATURA] Nova assinatura criada', [
            'assinatura_id' => $assinatura->id,
            'user_id' => $userId,
            'plano' => $plano,
            'tipo_pagamento' => $tipoPagamento,
            'data_fim' => $assinatura->data_fim->format('d/m/Y')
        ]);

        return $assinatura;
    }

    /**
     * Buscar assinatura ativa de um usuário
     */
    public function buscarAssinaturaAtiva(int $userId): ?AssinaturaModel
    {
        return $this->assinaturaRepository->buscarAtivaPorUserId($userId);
    }

    /**
     * Verificar se usuário tem assinatura ativa
     */
    public function temAssinaturaAtiva(int $userId): bool
    {
        $assinatura = $this->buscarAssinaturaAtiva($userId);
        
        return $assinatura && $assinatura->estaAtiva();
    }

    /**
     * Renovar assinatura
     */
    public function renovarAssinatura(int $assinaturaId, string $tipoPagamento): bool
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        // IMPORTANTE: Usar dias fixos - Mensal = 30 dias, Anual = 365 dias
        $resultado = $this->assinaturaRepository->renovar($assinaturaId, $tipoPagamento);

        if ($resultado) {
            $assinatura = $this->assinaturaRepository->buscarPorId($assinaturaId);
            \Log::info('[ASSINATURA] Assinatura renovada', [
                'assinatura_id' => $assinaturaId,
                'user_id' => $assinatura->user_id,
                'plano' => $assinatura->plano,
                'tipo_pagamento' => $tipoPagamento,
                'nova_data_fim' => $assinatura->data_fim->format('d/m/Y')
            ]);
        }

        return $resultado;
    }

    /**
     * Cancelar assinatura
     */
    public function cancelarAssinatura(int $assinaturaId): bool
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        $assinatura = $this->assinaturaRepository->buscarPorId($assinaturaId);
        $resultado = $this->assinaturaRepository->cancelar($assinaturaId);

        if ($resultado && $assinatura) {
            \Log::info('[ASSINATURA] Assinatura cancelada', [
                'assinatura_id' => $assinaturaId,
                'user_id' => $assinatura->user_id,
                'plano' => $assinatura->plano
            ]);
        }

        return $resultado;
    }

    /**
     * Processar assinaturas vencidas
     * Inativa usuários com assinaturas vencidas
     */
    public function processarAssinaturasVencidas(): array
    {
        \Log::info('[ASSINATURA] Iniciando processamento de assinaturas vencidas');

        $assinaturasVencidas = $this->assinaturaRepository->buscarVencidas();
        $usuariosInativados = [];

        foreach ($assinaturasVencidas as $assinatura) {
            // Marcar assinatura como vencida
            $this->assinaturaRepository->marcarComoVencida($assinatura->id);
            
            // Inativar usuário
            $this->userRepository->atualizarPorId($assinatura->user_id, [
                'status' => 'inativo'
            ]);

            \Log::warning('[ASSINATURA] Usuário inativado por vencimento', [
                'user_id' => $assinatura->user_id,
                'nome' => $assinatura->usuario->name,
                'email' => $assinatura->usuario->email,
                'plano' => $assinatura->plano,
                'data_vencimento' => $assinatura->data_fim->format('d/m/Y')
            ]);
            
            $usuariosInativados[] = $assinatura->usuario->name;
        }

        \Log::info('[ASSINATURA] Processamento concluído', [
            'total_inativados' => count($usuariosInativados)
        ]);

        return $usuariosInativados;
    }

    /**
     * Enviar avisos de vencimento
     */
    public function enviarAvisosVencimento(): array
    {
        $assinaturas = $this->assinaturaRepository->buscarParaEnviarAviso();
        $avisosEnviados = [];

        foreach ($assinaturas as $assinatura) {
            $dias = $assinatura->diasRestantes();
            
            // Enviar email
            $this->emailService->enviarAvisoVencimento(
                $assinatura->usuario,
                $assinatura,
                $dias
            );
            
            // Marcar aviso como enviado
            $this->assinaturaRepository->marcarAvisoEnviado($assinatura->id);
            
            $avisosEnviados[] = [
                'usuario' => $assinatura->usuario->name,
                'dias_restantes' => $dias,
                'data_fim' => $assinatura->data_fim->format('d/m/Y'),
            ];
        }

        return $avisosEnviados;
    }

    /**
     * Listar histórico de assinaturas de um usuário
     */
    public function listarHistoricoAssinaturas(int $userId): Collection
    {
        return $this->assinaturaRepository->buscarPorUserId($userId);
    }

    /**
     * Obter estatísticas financeiras
     */
    public function obterEstatisticasFinanceiras(?string $dataInicio = null, ?string $dataFim = null): array
    {
        $assinaturas = $dataInicio || $dataFim 
            ? $this->assinaturaRepository->buscarPorPeriodo($dataInicio, $dataFim)
            : $this->assinaturaRepository->buscarAtivas();

        $receita = $this->calcularReceitaTotal($assinaturas);
        $contadores = $this->assinaturaRepository->contarPorStatus();
        $porPlano = $this->assinaturaRepository->contarPorPlano();
        $mrr = $this->calcularReceitaMensalRecorrente();

        return [
            'receita_total' => $receita['total'],
            'receita_comum' => $receita['comum'],
            'receita_vip' => $receita['vip'],
            'mrr' => $mrr,
            'total_ativas' => $contadores['ativo'],
            'total_vencidas' => $contadores['vencido'],
            'total_canceladas' => $contadores['cancelado'],
            'total_comum' => $porPlano['comum'],
            'total_vip' => $porPlano['vip'],
        ];
    }

    /**
     * Calcular receita total de assinaturas
     */
    private function calcularReceitaTotal(Collection $assinaturas): array
    {
        $total = 0;
        $comum = 0;
        $vip = 0;
        $precos = $this->configuracaoService->obterTodosPrecosPlanos();

        foreach ($assinaturas as $assinatura) {
            if ($assinatura->status !== 'ativo') continue;

            $valor = $precos[$assinatura->plano][$assinatura->tipo_pagamento];
            $total += $valor;

            if ($assinatura->plano === 'comum') {
                $comum += $valor;
            } else {
                $vip += $valor;
            }
        }

        return [
            'total' => $total,
            'comum' => $comum,
            'vip' => $vip,
        ];
    }

    /**
     * Calcular MRR (Monthly Recurring Revenue)
     */
    private function calcularReceitaMensalRecorrente(): float
    {
        $assinaturasAtivas = $this->assinaturaRepository->buscarAtivas();
        $mrr = 0;
        $precos = $this->configuracaoService->obterTodosPrecosPlanos();

        foreach ($assinaturasAtivas as $assinatura) {
            $valorMensal = $assinatura->tipo_pagamento === 'mensal'
                ? $precos[$assinatura->plano]['mensal']
                : $precos[$assinatura->plano]['anual'] / 12;

            $mrr += $valorMensal;
        }

        return $mrr;
    }

    /**
     * Obter dados para gráficos (com VALORES em R$)
     */
    public function obterDadosGraficos(): array
    {
        $evolucao = $this->assinaturaRepository->obterEvolucaoMensal(6);
        $precos = $this->configuracaoService->obterTodosPrecosPlanos();
        
        // Processar dados para o gráfico de evolução (VALORES)
        $meses = [];
        $receitaComum = [];
        $receitaVip = [];
        $quantidadeComum = [];
        $quantidadeVip = [];

        foreach ($evolucao->groupBy('mes') as $mes => $dados) {
            $meses[] = \Carbon\Carbon::createFromFormat('Y-m', $mes)->format('M/Y');
            
            // Quantidade
            $qtdComum = $dados->where('plano', 'comum')->sum('total');
            $qtdVip = $dados->where('plano', 'vip')->sum('total');
            
            $quantidadeComum[] = $qtdComum;
            $quantidadeVip[] = $qtdVip;
            
            // Valores estimados (média dos preços mensal e anual)
            $valorMedioComum = ($precos['comum']['mensal'] + ($precos['comum']['anual'] / 12)) / 2;
            $valorMedioVip = ($precos['vip']['mensal'] + ($precos['vip']['anual'] / 12)) / 2;
            
            $receitaComum[] = round($qtdComum * $valorMedioComum, 2);
            $receitaVip[] = round($qtdVip * $valorMedioVip, 2);
        }

        return [
            'evolucao' => [
                'labels' => $meses,
                'receita_comum' => $receitaComum,
                'receita_vip' => $receitaVip,
                'quantidade_comum' => $quantidadeComum,
                'quantidade_vip' => $quantidadeVip,
            ],
        ];
    }
    
    /**
     * Obter distribuição de receita por tipo de usuário
     */
    public function obterReceitaPorTipo(): array
    {
        $assinaturas = $this->assinaturaRepository->buscarAtivas();
        $precos = $this->configuracaoService->obterTodosPrecosPlanos();
        
        $receitaCompradores = 0;
        $receitaFornecedores = 0;
        
        foreach ($assinaturas as $assinatura) {
            $valor = $precos[$assinatura->plano][$assinatura->tipo_pagamento];
            
            if ($assinatura->usuario->role === 'comprador') {
                $receitaCompradores += $valor;
            } elseif ($assinatura->usuario->role === 'fornecedor') {
                $receitaFornecedores += $valor;
            }
        }
        
        return [
            'compradores' => $receitaCompradores,
            'fornecedores' => $receitaFornecedores,
        ];
    }
    
    /**
     * Obter receita por segmento
     */
    public function obterReceitaPorSegmento(): array
    {
        $assinaturas = $this->assinaturaRepository->buscarAtivas();
        $precos = $this->configuracaoService->obterTodosPrecosPlanos();
        
        $receitaPorSegmento = [];
        
        foreach ($assinaturas as $assinatura) {
            $valor = $precos[$assinatura->plano][$assinatura->tipo_pagamento];
            $segmentos = $assinatura->usuario->segmentos;
            
            foreach ($segmentos as $segmento) {
                $nomeSegmento = $segmento->nome;
                if (!isset($receitaPorSegmento[$nomeSegmento])) {
                    $receitaPorSegmento[$nomeSegmento] = 0;
                }
                $receitaPorSegmento[$nomeSegmento] += $valor;
            }
        }
        
        // Ordenar por receita (maior primeiro)
        arsort($receitaPorSegmento);
        
        return $receitaPorSegmento;
    }
}
