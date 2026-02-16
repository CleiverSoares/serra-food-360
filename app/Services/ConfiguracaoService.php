<?php

namespace App\Services;

use App\Repositories\ConfiguracaoRepository;
use App\Repositories\HistoricoPrecosPlanoRepository;
use Illuminate\Support\Collection;

class ConfiguracaoService
{
    public function __construct(
        private ConfiguracaoRepository $configuracaoRepository,
        private HistoricoPrecosPlanoRepository $historicoRepository
    ) {}

    /**
     * Listar todas configurações
     */
    public function listarTodas(): Collection
    {
        return $this->configuracaoRepository->buscarTodas();
    }

    /**
     * Listar configurações por grupo
     */
    public function listarPorGrupo(string $grupo): Collection
    {
        return $this->configuracaoRepository->buscarPorGrupo($grupo);
    }

    /**
     * Obter valor de configuração
     */
    public function obterValor(string $chave, $padrao = null)
    {
        return $this->configuracaoRepository->obterValor($chave, $padrao);
    }

    /**
     * Atualizar configurações (com registro de histórico para preços de planos)
     */
    public function atualizarConfiguracoes(array $configuracoes, int $userId): void
    {
        $chavesPlanos = ['plano_comum_mensal', 'plano_comum_anual', 'plano_vip_mensal', 'plano_vip_anual'];
        
        // Verificar se algum preço de plano está sendo alterado
        foreach ($configuracoes as $chave => $valorNovo) {
            if (in_array($chave, $chavesPlanos)) {
                // Buscar valor antigo
                $valorAntigo = $this->configuracaoRepository->obterValor($chave);
                
                // Se valor mudou, registrar no histórico
                if ($valorAntigo !== null && $valorAntigo != $valorNovo) {
                    $this->historicoRepository->registrar(
                        $chave,
                        (float) $valorAntigo,
                        (float) $valorNovo,
                        $userId
                    );
                }
            }
        }
        
        // Atualizar todas as configurações
        $this->configuracaoRepository->atualizarVarias($configuracoes);
    }

    /**
     * Obter link WhatsApp configurado
     */
    public function linkWhatsApp(?string $mensagem = null): string
    {
        $numero = $this->obterValor('whatsapp_contato', '5527999999999');
        $mensagemPadrao = $this->obterValor('mensagem_whatsapp_padrao', 'Olá!');
        $texto = urlencode($mensagem ?? $mensagemPadrao);
        
        return "https://wa.me/{$numero}?text={$texto}";
    }

    /**
     * Obter email admin para aprovações
     */
    public function emailAdminAprovacoes(): string
    {
        return $this->obterValor('email_admin_aprovacoes', 'admin@serrafood360.com.br');
    }

    /**
     * Obter preço de um plano
     */
    public function obterPrecoPlano(string $plano, string $tipoPagamento): float
    {
        $chave = "plano_{$plano}_{$tipoPagamento}";
        return (float) $this->obterValor($chave, 0);
    }

    /**
     * Obter todos os preços dos planos formatados
     */
    public function obterTodosPrecosPlanos(): array
    {
        return [
            'comum' => [
                'mensal' => $this->obterPrecoPlano('comum', 'mensal'),
                'anual' => $this->obterPrecoPlano('comum', 'anual'),
            ],
            'vip' => [
                'mensal' => $this->obterPrecoPlano('vip', 'mensal'),
                'anual' => $this->obterPrecoPlano('vip', 'anual'),
            ],
        ];
    }
}
