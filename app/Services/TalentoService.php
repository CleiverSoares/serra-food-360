<?php

namespace App\Services;

use App\Models\TalentoModel;
use App\Repositories\TalentoRepository;
use App\Services\FilterService;
use Illuminate\Support\Facades\Storage;

class TalentoService
{
    public function __construct(
        private TalentoRepository $talentoRepository,
        private FilterService $filterService
    ) {}

    /**
     * Buscar talentos com filtros (PÚBLICO - apenas ativos)
     */
    public function buscarTalentosComFiltros(array $parametros)
    {
        $filtros = $this->prepararFiltros($parametros);
        return $this->talentoRepository->buscarComFiltros($filtros, true)->paginate(12);
    }

    /**
     * Buscar talentos ADMIN (todos os status)
     */
    public function buscarTalentosAdmin(array $parametros)
    {
        $filtros = $this->prepararFiltros($parametros);
        return $this->talentoRepository->buscarComFiltros($filtros, false)->get();
    }

    /**
     * Obter dados de filtros para view
     */
    public function obterDadosFiltros(bool $apenasAtivos = true): array
    {
        return [
            'cargos' => $this->talentoRepository->buscarCargosUnicos($apenasAtivos),
            'disponibilidades' => $this->talentoRepository->buscarDisponibilidadesUnicas($apenasAtivos),
        ];
    }

    /**
     * Buscar talento por ID
     */
    public function buscarPorId(int $id): ?TalentoModel
    {
        return $this->talentoRepository->buscarPorId($id);
    }

    /**
     * Buscar talento por ID ou falhar
     */
    public function buscarPorIdOuFalhar(int $id): TalentoModel
    {
        return $this->talentoRepository->buscarPorIdOuFalhar($id);
    }

    /**
     * Criar talento (REGRA: processar uploads)
     */
    public function criar(array $dados): TalentoModel
    {
        // REGRA DE NEGÓCIO: processar uploads
        $dados = $this->processarUploads($dados);
        
        return $this->talentoRepository->criar($dados);
    }

    /**
     * Atualizar talento (REGRA: processar uploads, deletar antigos)
     */
    public function atualizar(TalentoModel $talento, array $dados): bool
    {
        // REGRA DE NEGÓCIO: processar uploads e deletar antigos
        $dados = $this->processarUploadsAtualizacao($talento, $dados);
        
        return $this->talentoRepository->atualizar($talento, $dados);
    }

    /**
     * Deletar talento (REGRA: deletar arquivos antes)
     */
    public function deletar(TalentoModel $talento): bool
    {
        // REGRA DE NEGÓCIO: deletar arquivos primeiro
        $this->deletarArquivos($talento);
        
        return $this->talentoRepository->deletar($talento);
    }

    /**
     * Ativar talento
     */
    public function ativar(int $id): bool
    {
        return $this->talentoRepository->ativar($id);
    }

    /**
     * Inativar talento
     */
    public function inativar(int $id): bool
    {
        return $this->talentoRepository->inativar($id);
    }

    /**
     * Contar talentos
     */
    public function contar(): int
    {
        return $this->talentoRepository->contar();
    }

    /**
     * Preparar filtros (REGRA DE NEGÓCIO)
     */
    private function prepararFiltros(array $parametros): array
    {
        return [
            'busca' => $parametros['busca'] ?? '',
            'cargo' => $parametros['cargo'] ?? '',
            'disponibilidade' => $parametros['disponibilidade'] ?? '',
            'tipoCobranca' => $parametros['tipo_cobranca'] ?? '',
            'valorMin' => $parametros['valor_min'] ?? '',
            'valorMax' => $parametros['valor_max'] ?? '',
        ];
    }

    /**
     * Processar uploads (REGRA DE NEGÓCIO)
     */
    private function processarUploads(array $dados): array
    {
        if (isset($dados['foto']) && $dados['foto']) {
            $dados['foto_path'] = $dados['foto']->store('talentos/fotos', 'public');
            unset($dados['foto']);
        }

        if (isset($dados['curriculo_pdf']) && $dados['curriculo_pdf']) {
            $dados['curriculo_path'] = $dados['curriculo_pdf']->store('talentos/curriculos', 'public');
            unset($dados['curriculo_pdf']);
        }

        if (isset($dados['carta_recomendacao']) && $dados['carta_recomendacao']) {
            $dados['carta_recomendacao_path'] = $dados['carta_recomendacao']->store('talentos/cartas', 'public');
            unset($dados['carta_recomendacao']);
        }

        return $dados;
    }

    /**
     * Processar uploads em atualização (REGRA: deletar antigos)
     */
    private function processarUploadsAtualizacao(TalentoModel $talento, array $dados): array
    {
        if (isset($dados['foto']) && $dados['foto']) {
            if ($talento->foto_path) {
                Storage::disk('public')->delete($talento->foto_path);
            }
            $dados['foto_path'] = $dados['foto']->store('talentos/fotos', 'public');
            unset($dados['foto']);
        }

        if (isset($dados['curriculo_pdf']) && $dados['curriculo_pdf']) {
            if ($talento->curriculo_path) {
                Storage::disk('public')->delete($talento->curriculo_path);
            }
            $dados['curriculo_path'] = $dados['curriculo_pdf']->store('talentos/curriculos', 'public');
            unset($dados['curriculo_pdf']);
        }

        if (isset($dados['carta_recomendacao']) && $dados['carta_recomendacao']) {
            if ($talento->carta_recomendacao_path) {
                Storage::disk('public')->delete($talento->carta_recomendacao_path);
            }
            $dados['carta_recomendacao_path'] = $dados['carta_recomendacao']->store('talentos/cartas', 'public');
            unset($dados['carta_recomendacao']);
        }

        return $dados;
    }

    /**
     * Deletar arquivos (REGRA DE NEGÓCIO)
     */
    private function deletarArquivos(TalentoModel $talento): void
    {
        if ($talento->foto_path) {
            Storage::disk('public')->delete($talento->foto_path);
        }
        if ($talento->curriculo_path) {
            Storage::disk('public')->delete($talento->curriculo_path);
        }
        if ($talento->carta_recomendacao_path) {
            Storage::disk('public')->delete($talento->carta_recomendacao_path);
        }
    }
}
