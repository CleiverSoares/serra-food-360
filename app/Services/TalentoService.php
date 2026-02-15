<?php

namespace App\Services;

use App\Models\TalentoModel;
use App\Repositories\TalentoRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class TalentoService
{
    public function __construct(
        private TalentoRepository $talentoRepository
    ) {}

    /**
     * Listar todos os talentos
     */
    public function listarTodos(): Collection
    {
        return $this->talentoRepository->buscarTodos();
    }

    /**
     * Buscar talento por ID
     */
    public function buscarPorId(int $id): ?TalentoModel
    {
        return $this->talentoRepository->buscarPorId($id);
    }

    /**
     * Criar novo talento
     */
    public function criar(array $dados): TalentoModel
    {
        // Upload de foto se fornecida
        if (isset($dados['foto']) && $dados['foto']) {
            $dados['foto_path'] = $dados['foto']->store('talentos/fotos', 'public');
            unset($dados['foto']);
        }

        // Upload de currículo se fornecido
        if (isset($dados['curriculo_pdf']) && $dados['curriculo_pdf']) {
            $dados['curriculo_pdf_path'] = $dados['curriculo_pdf']->store('talentos/curriculos', 'public');
            unset($dados['curriculo_pdf']);
        }

        // Upload de carta de recomendação se fornecida
        if (isset($dados['carta_recomendacao']) && $dados['carta_recomendacao']) {
            $dados['carta_recomendacao_path'] = $dados['carta_recomendacao']->store('talentos/cartas', 'public');
            unset($dados['carta_recomendacao']);
        }

        return $this->talentoRepository->criar($dados);
    }

    /**
     * Atualizar talento
     */
    public function atualizar(TalentoModel $talento, array $dados): bool
    {
        // Upload de nova foto se fornecida
        if (isset($dados['foto']) && $dados['foto']) {
            // Deletar foto antiga
            if ($talento->foto_path) {
                Storage::disk('public')->delete($talento->foto_path);
            }
            $dados['foto_path'] = $dados['foto']->store('talentos/fotos', 'public');
            unset($dados['foto']);
        }

        // Upload de novo currículo se fornecido
        if (isset($dados['curriculo_pdf']) && $dados['curriculo_pdf']) {
            // Deletar currículo antigo
            if ($talento->curriculo_pdf_path) {
                Storage::disk('public')->delete($talento->curriculo_pdf_path);
            }
            $dados['curriculo_pdf_path'] = $dados['curriculo_pdf']->store('talentos/curriculos', 'public');
            unset($dados['curriculo_pdf']);
        }

        // Upload de nova carta se fornecida
        if (isset($dados['carta_recomendacao']) && $dados['carta_recomendacao']) {
            // Deletar carta antiga
            if ($talento->carta_recomendacao_path) {
                Storage::disk('public')->delete($talento->carta_recomendacao_path);
            }
            $dados['carta_recomendacao_path'] = $dados['carta_recomendacao']->store('talentos/cartas', 'public');
            unset($dados['carta_recomendacao']);
        }

        return $this->talentoRepository->atualizar($talento, $dados);
    }

    /**
     * Deletar talento
     */
    public function deletar(TalentoModel $talento): bool
    {
        return $this->talentoRepository->deletar($talento);
    }

    /**
     * Buscar talentos por cargo
     */
    public function buscarPorCargo(string $cargo): Collection
    {
        return $this->talentoRepository->buscarPorCargo($cargo);
    }

    /**
     * Buscar talentos por pretensão máxima
     */
    public function buscarPorPretensaoMaxima(float $valorMaximo): Collection
    {
        return $this->talentoRepository->buscarPorPretensaoMaxima($valorMaximo);
    }

    /**
     * Contar talentos
     */
    public function contar(): int
    {
        return $this->talentoRepository->contar();
    }
}
