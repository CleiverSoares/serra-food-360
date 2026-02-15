<?php

namespace App\Services;

use App\Repositories\ConfiguracaoRepository;
use Illuminate\Support\Collection;

class ConfiguracaoService
{
    public function __construct(
        private ConfiguracaoRepository $configuracaoRepository
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
     * Atualizar configurações
     */
    public function atualizarConfiguracoes(array $configuracoes): void
    {
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
}
