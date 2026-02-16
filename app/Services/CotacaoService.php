<?php

namespace App\Services;

use App\Models\CotacaoModel;
use App\Models\CotacaoFornecedorModel;
use App\Repositories\CotacaoRepository;
use App\Repositories\CotacaoFornecedorRepository;
use App\Repositories\UserRepository;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Mail\NovaCotacaoDisponivel;
use App\Mail\NovaCotacaoParaFornecedor;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CotacaoService
{
    public function __construct(
        private CotacaoRepository $cotacaoRepository,
        private CotacaoFornecedorRepository $cotacaoFornecedorRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * Listar cotações ativas para um comprador (baseado nos segmentos e filtros)
     */
    public function buscarCotacoesParaComprador(int $userId, array $filtros = []): EloquentCollection
    {
        $usuario = $this->userRepository->buscarPorId($userId);
        
        if (!$usuario || $usuario->role !== 'comprador') {
            return new EloquentCollection([]);
        }

        // Buscar pelos segmentos do comprador
        $segmentosIds = $usuario->segmentos->pluck('id')->toArray();
        
        if (empty($segmentosIds)) {
            return new EloquentCollection([]);
        }

        return $this->cotacaoRepository->buscarComFiltros($segmentosIds, $filtros);
    }

    /**
     * Buscar cotação por ID (com validação de acesso)
     */
    public function buscarCotacao(int $id, ?int $userId = null): ?CotacaoModel
    {
        $cotacao = $this->cotacaoRepository->buscarPorId($id);
        
        if (!$cotacao) {
            return null;
        }

        // Se userId for passado, validar acesso por segmento
        if ($userId) {
            $usuario = $this->userRepository->buscarPorId($userId);
            
            if ($usuario && $usuario->role === 'comprador') {
                $segmentosUsuario = $usuario->segmentos->pluck('id')->toArray();
                
                if (!in_array($cotacao->segmento_id, $segmentosUsuario)) {
                    return null; // Comprador não tem acesso
                }
            }
        }

        return $cotacao;
    }

    /**
     * Criar nova cotação (admin)
     */
    public function criarCotacao(array $dados): CotacaoModel
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        $dados['status'] = 'ativo';
        
        $cotacao = $this->cotacaoRepository->criar($dados);
        
        // Enviar emails para compradores E fornecedores do segmento
        $this->notificarCompradoresSegmento($cotacao);
        $this->notificarFornecedoresSegmento($cotacao);
        
        Log::info('Nova cotação criada', [
            'cotacao_id' => $cotacao->id,
            'produto' => $cotacao->produto,
            'segmento_id' => $cotacao->segmento_id,
        ]);
        
        return $cotacao;
    }

    /**
     * Notificar compradores do segmento sobre nova cotação
     */
    private function notificarCompradoresSegmento(CotacaoModel $cotacao): void
    {
        // Buscar compradores aprovados do segmento
        $compradores = $this->userRepository->buscarPorRole('comprador')
            ->whereHas('segmentos', function ($query) use ($cotacao) {
                $query->where('segmentos.id', $cotacao->segmento_id);
            })
            ->where('status', 'aprovado')
            ->get();

        Log::info('Enviando emails para compradores do segmento', [
            'cotacao_id' => $cotacao->id,
            'segmento' => $cotacao->segmento->nome,
            'total_compradores' => $compradores->count(),
        ]);

        foreach ($compradores as $comprador) {
            try {
                $nomeComprador = $comprador->comprador?->nome_negocio ?? $comprador->name;
                
                Mail::to($comprador->email)->send(
                    new NovaCotacaoDisponivel($cotacao, $nomeComprador)
                );

                Log::info('Email de nova cotação enviado', [
                    'cotacao_id' => $cotacao->id,
                    'comprador_email' => $comprador->email,
                    'comprador_id' => $comprador->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de nova cotação', [
                    'cotacao_id' => $cotacao->id,
                    'comprador_email' => $comprador->email,
                    'erro' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    /**
     * Notificar fornecedores do segmento sobre nova cotação
     */
    private function notificarFornecedoresSegmento(CotacaoModel $cotacao): void
    {
        // Buscar fornecedores aprovados do segmento
        $fornecedores = $this->userRepository->buscarPorRole('fornecedor')
            ->whereHas('segmentos', function ($query) use ($cotacao) {
                $query->where('segmentos.id', $cotacao->segmento_id);
            })
            ->where('status', 'aprovado')
            ->get();

        Log::info('Enviando emails para fornecedores do segmento', [
            'cotacao_id' => $cotacao->id,
            'segmento' => $cotacao->segmento->nome,
            'total_fornecedores' => $fornecedores->count(),
        ]);

        foreach ($fornecedores as $fornecedor) {
            try {
                $nomeFornecedor = $fornecedor->fornecedor?->nome_empresa ?? $fornecedor->name;
                
                Mail::to($fornecedor->email)->send(
                    new NovaCotacaoParaFornecedor($cotacao, $nomeFornecedor)
                );

                Log::info('Email de oportunidade enviado para fornecedor', [
                    'cotacao_id' => $cotacao->id,
                    'fornecedor_email' => $fornecedor->email,
                    'fornecedor_id' => $fornecedor->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email para fornecedor', [
                    'cotacao_id' => $cotacao->id,
                    'fornecedor_email' => $fornecedor->email,
                    'erro' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    /**
     * Atualizar cotação (admin)
     */
    public function atualizarCotacao(int $id, array $dados): bool
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        $cotacao = $this->cotacaoRepository->buscarPorId($id);
        
        if (!$cotacao) {
            return false;
        }

        return $this->cotacaoRepository->atualizar($cotacao, $dados);
    }

    /**
     * Adicionar oferta de fornecedor (admin)
     */
    public function adicionarOferta(array $dados): CotacaoFornecedorModel
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        $oferta = $this->cotacaoFornecedorRepository->criar($dados);
        
        // Verificar se é a melhor oferta e marcar destaque automaticamente
        $cotacao = $this->cotacaoRepository->buscarPorId($dados['cotacao_id']);
        $melhorOferta = $cotacao->melhorOferta();
        
        if ($melhorOferta && $melhorOferta->id === $oferta->id) {
            $this->cotacaoFornecedorRepository->marcarDestaque($dados['cotacao_id'], $oferta->id);
        }
        
        return $oferta;
    }

    /**
     * Atualizar oferta de fornecedor (admin)
     */
    public function atualizarOferta(int $id, array $dados): bool
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        $oferta = $this->cotacaoFornecedorRepository->buscarPorId($id);
        
        if (!$oferta) {
            return false;
        }

        $resultado = $this->cotacaoFornecedorRepository->atualizar($oferta, $dados);
        
        // Recalcular destaque
        $cotacao = $this->cotacaoRepository->buscarPorId($oferta->cotacao_id);
        $melhorOferta = $cotacao->melhorOferta();
        
        if ($melhorOferta) {
            $this->cotacaoFornecedorRepository->marcarDestaque($oferta->cotacao_id, $melhorOferta->id);
        }
        
        return $resultado;
    }

    /**
     * Deletar oferta (admin)
     */
    public function deletarOferta(int $id): bool
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        $oferta = $this->cotacaoFornecedorRepository->buscarPorId($id);
        
        if (!$oferta) {
            return false;
        }

        return $this->cotacaoFornecedorRepository->deletar($oferta);
    }

    /**
     * Encerrar cotação (admin)
     */
    public function encerrarCotacao(int $id): bool
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        return $this->cotacaoRepository->encerrar($id);
    }

    /**
     * Deletar cotação (admin)
     */
    public function deletarCotacao(int $id): bool
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        $cotacao = $this->cotacaoRepository->buscarPorId($id);
        
        if (!$cotacao) {
            return false;
        }

        return $this->cotacaoRepository->deletar($cotacao);
    }

    /**
     * Listar todas cotações (admin)
     */
    public function listarTodasAdmin(): EloquentCollection
    {
        return $this->cotacaoRepository->buscarTodas();
    }

    /**
     * Listar cotações ativas para um fornecedor (baseado nos segmentos)
     */
    public function buscarCotacoesParaFornecedor(int $userId): EloquentCollection
    {
        $usuario = $this->userRepository->buscarPorId($userId);
        
        if (!$usuario || $usuario->role !== 'fornecedor') {
            return new EloquentCollection([]);
        }

        // Buscar pelos segmentos do fornecedor
        $segmentosIds = $usuario->segmentos->pluck('id')->toArray();
        
        if (empty($segmentosIds)) {
            return new EloquentCollection([]);
        }

        return $this->cotacaoRepository->buscarPorSegmentos($segmentosIds, true);
    }

    /**
     * Verificar se fornecedor já tem oferta nesta cotação
     */
    public function fornecedorTemOferta(int $cotacaoId, int $fornecedorId): bool
    {
        return $this->cotacaoFornecedorRepository->fornecedorTemOferta($cotacaoId, $fornecedorId);
    }

    /**
     * Adicionar/atualizar oferta do próprio fornecedor
     */
    public function salvarOfertaFornecedor(int $cotacaoId, int $fornecedorId, array $dados): void
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        // Verificar se já tem oferta
        $ofertaExistente = $this->cotacaoFornecedorRepository
            ->buscarPorCotacaoEFornecedor($cotacaoId, $fornecedorId);
        
        if ($ofertaExistente) {
            // Atualizar oferta existente
            $this->cotacaoFornecedorRepository->atualizar($ofertaExistente, $dados);
            
            Log::info('Oferta de fornecedor atualizada', [
                'cotacao_id' => $cotacaoId,
                'fornecedor_id' => $fornecedorId,
                'preco' => $dados['preco_unitario'],
            ]);
        } else {
            // Criar nova oferta
            $dados['cotacao_id'] = $cotacaoId;
            $dados['fornecedor_id'] = $fornecedorId;
            $oferta = $this->cotacaoFornecedorRepository->criar($dados);
            
            Log::info('Nova oferta de fornecedor criada', [
                'cotacao_id' => $cotacaoId,
                'fornecedor_id' => $fornecedorId,
                'preco' => $dados['preco_unitario'],
            ]);
        }
        
        // Recalcular destaque (melhor oferta)
        $cotacao = $this->cotacaoRepository->buscarPorId($cotacaoId);
        $melhorOferta = $cotacao->melhorOferta();
        
        if ($melhorOferta) {
            $this->cotacaoFornecedorRepository->marcarDestaque($cotacaoId, $melhorOferta->id);
        }
    }

    /**
     * Deletar oferta do próprio fornecedor
     */
    public function deletarOfertaFornecedor(int $cotacaoId, int $fornecedorId): bool
    {
        // Limpar cache da dashboard
        AdminDashboardController::limparCache();
        
        $oferta = $this->cotacaoFornecedorRepository
            ->buscarPorCotacaoEFornecedor($cotacaoId, $fornecedorId);
        
        if (!$oferta) {
            return false;
        }

        $resultado = $this->cotacaoFornecedorRepository->deletar($oferta);
        
        if ($resultado) {
            Log::info('Oferta de fornecedor deletada', [
                'cotacao_id' => $cotacaoId,
                'fornecedor_id' => $fornecedorId,
            ]);
            
            // Recalcular destaque
            $cotacao = $this->cotacaoRepository->buscarPorId($cotacaoId);
            $melhorOferta = $cotacao->melhorOferta();
            
            if ($melhorOferta) {
                $this->cotacaoFornecedorRepository->marcarDestaque($cotacaoId, $melhorOferta->id);
            }
        }
        
        return $resultado;
    }
}
