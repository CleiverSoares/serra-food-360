<?php

namespace App\Repositories;

use App\Models\RestauranteModel;
use App\Models\CompradorModel;

/**
 * Alias para CompradorRepository (retrocompatibilidade)
 * @deprecated Use CompradorRepository instead
 */
class RestauranteRepository
{
    private CompradorRepository $compradorRepository;

    public function __construct(CompradorRepository $compradorRepository)
    {
        $this->compradorRepository = $compradorRepository;
    }

    /**
     * Criar novo restaurante
     */
    public function criar(array $dados): RestauranteModel
    {
        return $this->compradorRepository->criar($dados);
    }

    /**
     * Buscar restaurante por user_id
     */
    public function buscarPorUserId(int $userId): ?RestauranteModel
    {
        return $this->compradorRepository->buscarPorUserId($userId);
    }

    /**
     * Atualizar restaurante
     */
    public function atualizar($restaurante, array $dados): bool
    {
        return $this->compradorRepository->atualizar($restaurante, $dados);
    }
}
