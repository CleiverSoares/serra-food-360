<?php

use Illuminate\Support\Facades\Schedule;

/**
 * Agendamento de tarefas
 */

// Verificar assinaturas vencidas e enviar avisos (diariamente às 9h)
Schedule::command('assinaturas:verificar')
    ->dailyAt('09:00')
    ->timezone('America/Sao_Paulo');
