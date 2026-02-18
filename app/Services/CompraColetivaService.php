<?php

namespace App\Services;

use App\Repositories\ProdutoCatalogoRepository;
use App\Repositories\CompraColetivaPropostaRepository;
use App\Repositories\CompraColetivaRepository;
use App\Models\CompraColetivaModel;
use App\Models\CompraColetivaPropostaModel;
use App\Models\ProdutoCatalogoModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class CompraColetivaService
{
    public function __construct(
        private ProdutoCatalogoRepository $produtoRepository,
        private CompraColetivaPropostaRepository $propostaRepository,
        private CompraColetivaRepository $compraRepository
    ) {}

    // ========== PRODUTOS CATÁLOGO ==========

    public function listarProdutos(array $filtros = []): LengthAwarePaginator
    {
        return $this->produtoRepository->buscarComFiltros($filtros, 20);
    }

    public function obterProdutoPorId(int $id): ?ProdutoCatalogoModel
    {
        return $this->produtoRepository->buscarPorId($id);
    }

    public function criarProduto(array $dados): ProdutoCatalogoModel
    {
        // Upload de imagem
        if (isset($dados['imagem'])) {
            $imagem = $dados['imagem'];
            $nomeImagem = time() . '_' . $imagem->getClientOriginalName();
            $dados['imagem_url'] = $imagem->storeAs('produtos', $nomeImagem, 'public');
            unset($dados['imagem']);
        }

        return $this->produtoRepository->criar($dados);
    }

    public function atualizarProduto(int $id, array $dados): bool
    {
        $produto = $this->produtoRepository->buscarPorId($id);

        if (!$produto) {
            return false;
        }

        // Upload de nova imagem
        if (isset($dados['imagem'])) {
            // Deletar imagem antiga
            if ($produto->imagem_url) {
                Storage::disk('public')->delete($produto->imagem_url);
            }

            $imagem = $dados['imagem'];
            $nomeImagem = time() . '_' . $imagem->getClientOriginalName();
            $dados['imagem_url'] = $imagem->storeAs('produtos', $nomeImagem, 'public');
            unset($dados['imagem']);
        }

        return $this->produtoRepository->atualizar($id, $dados);
    }

    public function deletarProduto(int $id): bool
    {
        $produto = $this->produtoRepository->buscarPorId($id);

        if (!$produto) {
            return false;
        }

        // Deletar imagem
        if ($produto->imagem_url) {
            Storage::disk('public')->delete($produto->imagem_url);
        }

        return $this->produtoRepository->deletar($id);
    }

    public function buscarProdutosSimilares(string $nome): Collection
    {
        return $this->produtoRepository->buscarSimilares($nome);
    }

    public function autocompleteProdutos(string $termo): Collection
    {
        return $this->produtoRepository->buscarParaAutocomplete($termo);
    }

    // ========== PROPOSTAS ==========

    public function listarPropostas(array $filtros = []): LengthAwarePaginator
    {
        return $this->propostaRepository->buscarComFiltros($filtros, 20);
    }

    public function listarPropostasEmVotacao(): Collection
    {
        return $this->propostaRepository->buscarEmVotacao();
    }

    public function obterPropostaPorId(int $id): ?CompraColetivaPropostaModel
    {
        return $this->propostaRepository->buscarPorId($id);
    }

    public function criarProposta(array $dados): CompraColetivaPropostaModel
    {
        $proposta = $this->propostaRepository->criar($dados);

        // Incrementar contador do produto
        $this->produtoRepository->incrementarPropostas($dados['produto_catalogo_id']);

        return $proposta;
    }

    public function iniciarVotacao(int $propostaId, int $diasVotacao = 7): bool
    {
        return $this->propostaRepository->atualizar($propostaId, [
            'status' => 'em_votacao',
            'data_votacao_inicio' => now(),
            'data_votacao_fim' => now()->addDays($diasVotacao),
        ]);
    }

    public function votar(int $propostaId, int $userId): bool
    {
        // Verificar se já votou
        if ($this->propostaRepository->jaVotou($propostaId, $userId)) {
            return false;
        }

        $this->propostaRepository->adicionarVoto($propostaId, $userId);
        return true;
    }

    public function removerVoto(int $propostaId, int $userId): bool
    {
        return $this->propostaRepository->removerVoto($propostaId, $userId);
    }

    public function aprovarProposta(int $propostaId): bool
    {
        return $this->propostaRepository->atualizar($propostaId, ['status' => 'aprovada']);
    }

    public function rejeitarProposta(int $propostaId): bool
    {
        return $this->propostaRepository->atualizar($propostaId, ['status' => 'rejeitada']);
    }

    // ========== COMPRAS COLETIVAS ==========

    public function listarComprasAtivas(): Collection
    {
        return $this->compraRepository->buscarAtivas();
    }

    public function listarCompras(array $filtros = []): LengthAwarePaginator
    {
        return $this->compraRepository->buscarComFiltros($filtros, 12);
    }

    public function obterCompraPorId(int $id): ?CompraColetivaModel
    {
        return $this->compraRepository->buscarPorId($id);
    }

    public function criarCompra(array $dados): CompraColetivaModel
    {
        return $this->compraRepository->criar($dados);
    }

    public function atualizarCompra(int $id, array $dados): bool
    {
        return $this->compraRepository->atualizar($id, $dados);
    }

    public function deletarCompra(int $id): bool
    {
        return $this->compraRepository->deletar($id);
    }

    public function participar(int $compraId, int $compradorId, float $quantidade, ?string $observacoes = null): bool
    {
        try {
            $this->compraRepository->adicionarAdesao([
                'compra_coletiva_id' => $compraId,
                'comprador_id' => $compradorId,
                'quantidade' => $quantidade,
                'observacoes' => $observacoes,
                'status' => 'ativa',
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function atualizarParticipacao(int $adesaoId, float $quantidade, ?string $observacoes = null): bool
    {
        return $this->compraRepository->atualizarAdesao($adesaoId, [
            'quantidade' => $quantidade,
            'observacoes' => $observacoes,
        ]);
    }

    public function cancelarParticipacao(int $adesaoId): bool
    {
        return $this->compraRepository->cancelarAdesao($adesaoId);
    }

    public function obterAdesaoUsuario(int $compraId, int $userId)
    {
        return $this->compraRepository->buscarAdesaoUsuario($compraId, $userId);
    }

    public function ofertar(int $compraId, int $fornecedorId, float $precoUnitario, ?float $quantidadeMinima = null, ?string $condicoes = null): bool
    {
        try {
            $this->compraRepository->adicionarOferta([
                'compra_coletiva_id' => $compraId,
                'fornecedor_id' => $fornecedorId,
                'preco_unitario' => $precoUnitario,
                'quantidade_minima' => $quantidadeMinima,
                'condicoes' => $condicoes,
                'status' => 'pendente',
                'ofertado_em' => now(),
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function listarOfertasFornecedor(int $fornecedorId): Collection
    {
        return $this->compraRepository->buscarOfertasFornecedor($fornecedorId);
    }
}
