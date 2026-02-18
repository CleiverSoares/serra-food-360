<?php

namespace App\Services;

use App\Repositories\MaterialRepository;
use App\Models\MaterialModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class MaterialService
{
    public function __construct(
        private MaterialRepository $materialRepository
    ) {}

    /**
     * Listar materiais para admin (todos)
     */
    public function listarParaAdmin(array $filtros = [], int $perPage = 12): LengthAwarePaginator
    {
        return $this->materialRepository->buscarComFiltros($filtros, $perPage);
    }

    /**
     * Listar materiais para usuário (respeitando VIP)
     */
    public function listarParaUsuario(int $userId, bool $isVip, array $filtros = [], int $perPage = 12): LengthAwarePaginator
    {
        return $this->materialRepository->buscarParaUsuario($userId, $isVip, $filtros, $perPage);
    }

    /**
     * Obter material por ID
     */
    public function obterPorId(int $id): ?MaterialModel
    {
        return $this->materialRepository->buscarPorId($id);
    }

    /**
     * Criar material com upload de arquivo/thumbnail
     */
    public function criar(array $dados): MaterialModel
    {
        // Upload de arquivo (se tipo=arquivo)
        if (isset($dados['arquivo']) && $dados['tipo'] === 'arquivo') {
            $arquivo = $dados['arquivo'];
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $dados['arquivo_path'] = $arquivo->storeAs('materiais/arquivos', $nomeArquivo, 'public');
            unset($dados['arquivo']);
        }

        // Upload de thumbnail
        if (isset($dados['thumbnail'])) {
            $thumbnail = $dados['thumbnail'];
            $nomeThumbnail = time() . '_thumb_' . $thumbnail->getClientOriginalName();
            $dados['thumbnail_url'] = $thumbnail->storeAs('materiais/thumbnails', $nomeThumbnail, 'public');
            unset($dados['thumbnail']);
        }

        return $this->materialRepository->criar($dados);
    }

    /**
     * Atualizar material
     */
    public function atualizar(int $id, array $dados): bool
    {
        $material = $this->materialRepository->buscarPorId($id);

        if (!$material) {
            return false;
        }

        // Upload de novo arquivo
        if (isset($dados['arquivo']) && $dados['tipo'] === 'arquivo') {
            // Deletar arquivo antigo
            if ($material->arquivo_path) {
                Storage::disk('public')->delete($material->arquivo_path);
            }

            $arquivo = $dados['arquivo'];
            $nomeArquivo = time() . '_' . $arquivo->getClientOriginalName();
            $dados['arquivo_path'] = $arquivo->storeAs('materiais/arquivos', $nomeArquivo, 'public');
            unset($dados['arquivo']);
        }

        // Upload de nova thumbnail
        if (isset($dados['thumbnail'])) {
            // Deletar thumbnail antiga
            if ($material->thumbnail_url) {
                Storage::disk('public')->delete($material->thumbnail_url);
            }

            $thumbnail = $dados['thumbnail'];
            $nomeThumbnail = time() . '_thumb_' . $thumbnail->getClientOriginalName();
            $dados['thumbnail_url'] = $thumbnail->storeAs('materiais/thumbnails', $nomeThumbnail, 'public');
            unset($dados['thumbnail']);
        }

        return $this->materialRepository->atualizar($id, $dados);
    }

    /**
     * Deletar material e seus arquivos
     */
    public function deletar(int $id): bool
    {
        $material = $this->materialRepository->buscarPorId($id);

        if (!$material) {
            return false;
        }

        // Deletar arquivos físicos
        if ($material->arquivo_path) {
            Storage::disk('public')->delete($material->arquivo_path);
        }

        if ($material->thumbnail_url) {
            Storage::disk('public')->delete($material->thumbnail_url);
        }

        return $this->materialRepository->deletar($id);
    }

    /**
     * Visualizar material (incrementa contador e registra acesso)
     */
    public function visualizar(int $materialId, int $userId): void
    {
        $this->materialRepository->incrementarViews($materialId);
        $this->materialRepository->registrarAcesso($userId, $materialId, 'visualizacao');
    }

    /**
     * Baixar material (incrementa contador e registra acesso)
     */
    public function baixar(int $materialId, int $userId): void
    {
        $this->materialRepository->incrementarDownloads($materialId);
        $this->materialRepository->registrarAcesso($userId, $materialId, 'download');
    }

    /**
     * Verificar se material está favoritado
     */
    public function isFavorito(int $userId, int $materialId): bool
    {
        return $this->materialRepository->isFavorito($userId, $materialId);
    }

    /**
     * Alternar favorito (adiciona ou remove)
     */
    public function alternarFavorito(int $userId, int $materialId): bool
    {
        $isFavorito = $this->materialRepository->isFavorito($userId, $materialId);

        if ($isFavorito) {
            $this->materialRepository->removerFavorito($userId, $materialId);
            return false; // Removeu
        }

        $this->materialRepository->adicionarFavorito($userId, $materialId);
        return true; // Adicionou
    }

    /**
     * Listar favoritos do usuário
     */
    public function listarFavoritos(int $userId): Collection
    {
        return $this->materialRepository->buscarFavoritosUsuario($userId);
    }

    /**
     * Obter analytics (mais acessados, mais baixados)
     */
    public function obterAnalytics(): array
    {
        return [
            'mais_acessados' => $this->materialRepository->buscarMaisAcessados(10),
            'mais_baixados' => $this->materialRepository->buscarMaisBaixados(10),
        ];
    }
}
