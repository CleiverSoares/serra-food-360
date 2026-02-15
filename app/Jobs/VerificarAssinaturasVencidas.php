<?php

namespace App\Jobs;

use App\Services\AssinaturaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerificarAssinaturasVencidas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(AssinaturaService $assinaturaService): void
    {
        Log::info('Iniciando verificação de assinaturas vencidas');

        // 1. Processar assinaturas vencidas (inativar usuários)
        $usuariosInativados = $assinaturaService->processarAssinaturasVencidas();
        
        if (count($usuariosInativados) > 0) {
            Log::info('Usuários inativados por assinatura vencida', [
                'quantidade' => count($usuariosInativados),
                'usuarios' => $usuariosInativados,
            ]);
        }

        // 2. Enviar avisos de vencimento (7, 3 e 1 dia)
        $avisosEnviados = $assinaturaService->enviarAvisosVencimento();
        
        if (count($avisosEnviados) > 0) {
            Log::info('Avisos de vencimento enviados', [
                'quantidade' => count($avisosEnviados),
                'avisos' => $avisosEnviados,
            ]);
        }

        Log::info('Verificação de assinaturas concluída', [
            'usuarios_inativados' => count($usuariosInativados),
            'avisos_enviados' => count($avisosEnviados),
        ]);
    }
}
