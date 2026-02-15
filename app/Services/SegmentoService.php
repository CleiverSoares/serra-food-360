<?php

namespace App\Services;

use App\Repositories\SegmentoRepository;
use App\Models\SegmentoModel;

/**
 * Service de Segmentos
 * REGRAS DE NEGÓCIO específicas de segmentos
 */
class SegmentoService
{
    public function __construct(
        private SegmentoRepository $segmentoRepository
    ) {}

    /**
     * Listar todos os segmentos com contagem de usuários
     */
    public function listarTodosComContagem()
    {
        return $this->segmentoRepository->buscarTodosComContagem();
    }

    /**
     * Buscar segmento por ID
     */
    public function buscarPorId(int $id): ?SegmentoModel
    {
        return $this->segmentoRepository->buscarPorId($id);
    }

    /**
     * Buscar segmento por ID com contagem ou falhar
     */
    public function buscarPorIdComContagemOuFalhar(int $id): SegmentoModel
    {
        return $this->segmentoRepository->buscarPorIdComContagemOuFalhar($id);
    }

    /**
     * Criar segmento (REGRA: validar dados)
     */
    public function criar(array $dados): SegmentoModel
    {
        // Garantir campo ativo como boolean
        $dados['ativo'] = isset($dados['ativo']) && $dados['ativo'] ? true : false;
        
        return $this->segmentoRepository->criar($dados);
    }

    /**
     * Atualizar segmento (REGRA: validar dados)
     */
    public function atualizar(SegmentoModel $segmento, array $dados): bool
    {
        // Garantir campo ativo como boolean
        $dados['ativo'] = isset($dados['ativo']) && $dados['ativo'] ? true : false;
        
        return $this->segmentoRepository->atualizar($segmento, $dados);
    }

    /**
     * Deletar segmento (REGRA: verificar se tem usuários)
     */
    public function deletar(int $id): array
    {
        $segmento = $this->segmentoRepository->buscarPorIdComContagemOuFalhar($id);

        // REGRA DE NEGÓCIO: não pode deletar se tiver usuários
        if ($segmento->users_count > 0) {
            return [
                'sucesso' => false,
                'mensagem' => "Não é possível deletar este segmento pois existem {$segmento->users_count} usuários associados a ele."
            ];
        }

        $this->segmentoRepository->deletar($segmento);

        return [
            'sucesso' => true,
            'mensagem' => 'Segmento deletado com sucesso!'
        ];
    }

    /**
     * Ativar segmento
     */
    public function ativar(int $id): bool
    {
        return $this->segmentoRepository->ativar($id);
    }

    /**
     * Inativar segmento
     */
    public function inativar(int $id): bool
    {
        return $this->segmentoRepository->inativar($id);
    }

    /**
     * Buscar ativos
     */
    public function buscarAtivos()
    {
        return $this->segmentoRepository->buscarAtivos();
    }
}
