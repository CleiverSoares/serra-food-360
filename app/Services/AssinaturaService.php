<?php

namespace App\Services;

use App\Models\AssinaturaModel;
use App\Repositories\AssinaturaRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

class AssinaturaService
{
    public function __construct(
        private AssinaturaRepository $assinaturaRepository,
        private UserRepository $userRepository,
        private EmailService $emailService
    ) {}

    /**
     * Criar nova assinatura para um usuário
     */
    public function criarAssinatura(int $userId, string $plano, string $tipoPagamento): AssinaturaModel
    {
        $meses = $tipoPagamento === 'anual' ? 12 : 1;
        
        $dados = [
            'user_id' => $userId,
            'plano' => $plano,
            'tipo_pagamento' => $tipoPagamento,
            'data_inicio' => now(),
            'data_fim' => now()->addMonths($meses),
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
}
