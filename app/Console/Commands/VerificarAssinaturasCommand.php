<?php

namespace App\Console\Commands;

use App\Jobs\VerificarAssinaturasVencidas;
use Illuminate\Console\Command;

class VerificarAssinaturasCommand extends Command
{
    /**
     * Nome e assinatura do comando
     */
    protected $signature = 'assinaturas:verificar';

    /**
     * Descrição do comando
     */
    protected $description = 'Verifica assinaturas vencidas e envia avisos de vencimento';

    /**
     * Executar comando
     */
    public function handle(): int
    {
        $this->info('Iniciando verificação de assinaturas...');

        // Disparar job
        VerificarAssinaturasVencidas::dispatch();

        $this->info('Job de verificação de assinaturas foi enviado para a fila.');
        $this->info('Usuários com assinaturas vencidas serão inativados.');
        $this->info('Avisos de vencimento serão enviados (7, 3 e 1 dia).');

        return Command::SUCCESS;
    }
}
