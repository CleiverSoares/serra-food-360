<?php

namespace App\Repositories;

use App\Models\MaterialModel;
use App\Models\MaterialFavoritoModel;
use App\Models\MaterialAcessoModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MaterialRepository
{
    /**
     * Buscar materiais com filtros e paginação
     */
    public function buscarComFiltros(array $filtros = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = MaterialModel::query()->where('ativo', true);

        // Filtro por categoria
        if (!empty($filtros['categoria'])) {
            $query->where('categoria', $filtros['categoria']);
        }

        // Filtro por tipo
        if (!empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        // Filtro por busca textual
        if (!empty($filtros['busca'])) {
            $query->where(function ($q) use ($filtros) {
                $q->where('titulo', 'like', "%{$filtros['busca']}%")
                  ->orWhere('descricao', 'like', "%{$filtros['busca']}%");
            });
        }

        // Filtro VIP
        if (isset($filtros['apenas_vip'])) {
            $query->where('apenas_vip', $filtros['apenas_vip']);
        }

        return $query->with('criador')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Buscar materiais acessíveis para o usuário (VIP ou não)
     */
    public function buscarParaUsuario(int $userId, bool $isVip, array $filtros = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = MaterialModel::query()->where('ativo', true);

        // Se não é VIP, só mostra conteúdo free
        if (!$isVip) {
            $query->where('apenas_vip', false);
        }

        // Aplicar outros filtros
        if (!empty($filtros['categoria'])) {
            $query->where('categoria', $filtros['categoria']);
        }

        if (!empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        if (!empty($filtros['busca'])) {
            $query->where(function ($q) use ($filtros) {
                $q->where('titulo', 'like', "%{$filtros['busca']}%")
                  ->orWhere('descricao', 'like', "%{$filtros['busca']}%");
            });
        }

        return $query->with('criador')
            ->withCount([
                'usuariosFavoritos as is_favorito' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Buscar material por ID
     */
    public function buscarPorId(int $id): ?MaterialModel
    {
        return MaterialModel::with('criador')->find($id);
    }

    /**
     * Criar material
     */
    public function criar(array $dados): MaterialModel
    {
        return MaterialModel::create($dados);
    }

    /**
     * Atualizar material
     */
    public function atualizar(int $id, array $dados): bool
    {
        return MaterialModel::where('id', $id)->update($dados);
    }

    /**
     * Deletar material
     */
    public function deletar(int $id): bool
    {
        return MaterialModel::destroy($id) > 0;
    }

    /**
     * Incrementar contador de visualizações
     */
    public function incrementarViews(int $materialId): bool
    {
        return MaterialModel::where('id', $materialId)->increment('views_count');
    }

    /**
     * Incrementar contador de downloads
     */
    public function incrementarDownloads(int $materialId): bool
    {
        return MaterialModel::where('id', $materialId)->increment('downloads_count');
    }

    /**
     * Adicionar aos favoritos
     */
    public function adicionarFavorito(int $userId, int $materialId): MaterialFavoritoModel
    {
        return MaterialFavoritoModel::firstOrCreate([
            'user_id' => $userId,
            'material_id' => $materialId,
        ]);
    }

    /**
     * Remover dos favoritos
     */
    public function removerFavorito(int $userId, int $materialId): bool
    {
        return MaterialFavoritoModel::where('user_id', $userId)
            ->where('material_id', $materialId)
            ->delete() > 0;
    }

    /**
     * Buscar favoritos do usuário
     */
    public function buscarFavoritosUsuario(int $userId): Collection
    {
        return MaterialModel::whereHas('usuariosFavoritos', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('criador')->get();
    }

    /**
     * Verificar se está nos favoritos
     */
    public function isFavorito(int $userId, int $materialId): bool
    {
        return MaterialFavoritoModel::where('user_id', $userId)
            ->where('material_id', $materialId)
            ->exists();
    }

    /**
     * Registrar acesso ao material
     */
    public function registrarAcesso(int $userId, int $materialId, string $tipoAcesso): MaterialAcessoModel
    {
        return MaterialAcessoModel::create([
            'user_id' => $userId,
            'material_id' => $materialId,
            'tipo_acesso' => $tipoAcesso,
            'acessado_em' => now(),
        ]);
    }

    /**
     * Buscar materiais mais acessados (analytics admin)
     */
    public function buscarMaisAcessados(int $limite = 10): Collection
    {
        return MaterialModel::where('ativo', true)
            ->orderBy('views_count', 'desc')
            ->limit($limite)
            ->get();
    }

    /**
     * Buscar materiais mais baixados (analytics admin)
     */
    public function buscarMaisBaixados(int $limite = 10): Collection
    {
        return MaterialModel::where('ativo', true)
            ->orderBy('downloads_count', 'desc')
            ->limit($limite)
            ->get();
    }
}
