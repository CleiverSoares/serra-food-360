<?php

namespace App\Repositories;

use App\Models\ConfiguracaoModel;
use Illuminate\Support\Collection;

class ConfiguracaoRepository
{
    /**
     * Buscar todas configurações
     */
    public function buscarTodas(): Collection
    {
        return ConfiguracaoModel::orderBy('grupo')->orderBy('ordem')->get();
    }

    /**
     * Buscar configurações por grupo
     */
    public function buscarPorGrupo(string $grupo): Collection
    {
        return ConfiguracaoModel::where('grupo', $grupo)
            ->orderBy('ordem')
            ->get();
    }

    /**
     * Buscar por chave
     */
    public function buscarPorChave(string $chave): ?ConfiguracaoModel
    {
        return ConfiguracaoModel::where('chave', $chave)->first();
    }

    /**
     * Obter valor
     */
    public function obterValor(string $chave, $padrao = null)
    {
        return ConfiguracaoModel::obter($chave, $padrao);
    }

    /**
     * Atualizar configuração
     */
    public function atualizar(string $chave, $valor): bool
    {
        ConfiguracaoModel::definir($chave, $valor);
        return true;
    }

    /**
     * Atualizar múltiplas configurações
     */
    public function atualizarVarias(array $configuracoes): void
    {
        foreach ($configuracoes as $chave => $valor) {
            $this->atualizar($chave, $valor);
        }
    }

    /**
     * Criar configuração
     */
    public function criar(array $dados): ConfiguracaoModel
    {
        return ConfiguracaoModel::create($dados);
    }

    /**
     * Deletar configuração
     */
    public function deletar(int $id): bool
    {
        return ConfiguracaoModel::where('id', $id)->delete();
    }
}
