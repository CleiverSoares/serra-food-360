<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompradoresController;
use App\Http\Controllers\FornecedoresController;
use App\Http\Controllers\TalentosController;
use App\Http\Controllers\CotacoesController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsuariosController;
use App\Http\Controllers\Admin\AdminCompradoresController;
use App\Http\Controllers\Admin\AdminFornecedoresController;
use App\Http\Controllers\Admin\AdminTalentosController;
use App\Http\Controllers\Admin\AdminSegmentosController;
use App\Http\Controllers\Admin\AdminAssinaturasController;
use App\Http\Controllers\Admin\AdminConfiguracoesController;
use App\Http\Controllers\Admin\AdminCotacoesController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\MateriaisController;
use App\Http\Controllers\Admin\AdminMateriaisController;
use App\Http\Controllers\ComprasColetivasController;
use App\Http\Controllers\Admin\AdminComprasColetivasController;
use Illuminate\Support\Facades\Route;

// Rota pública
Route::get('/', fn () => view('landing'))->name('home');

// Rotas de autenticação (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'exibirLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/cadastro', [AuthController::class, 'exibirCadastro'])->name('cadastro');
    Route::post('/cadastro', [AuthController::class, 'cadastrar']);

    // Recuperação de Senha
    Route::get('/esqueci-senha', [PasswordResetController::class, 'exibirFormularioEmail'])->name('password.request');
    Route::post('/esqueci-senha', [PasswordResetController::class, 'enviarLink'])->name('password.email');
    Route::get('/redefinir-senha', [PasswordResetController::class, 'exibirFormularioRedefinicao'])->name('password.reset');
    Route::post('/redefinir-senha', [PasswordResetController::class, 'redefinir'])->name('password.update');
});

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Página de aguardando aprovação (qualquer usuário autenticado)
    Route::get('/aguardando', [AuthController::class, 'aguardando'])->name('aguardando');
    
    // Perfil do usuário logado
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.editar');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.atualizar');
    
    // Material de Gestão (público - usuários logados)
    Route::get('/materiais', [MateriaisController::class, 'index'])->name('materiais.index');
    Route::get('/materiais/{id}', [MateriaisController::class, 'show'])->name('materiais.show');
    Route::get('/materiais/{id}/download', [MateriaisController::class, 'download'])->name('materiais.download');
    Route::post('/materiais/{id}/favoritar', [MateriaisController::class, 'toggleFavorito'])->name('materiais.favoritar');
    Route::get('/materiais-favoritos', [MateriaisController::class, 'favoritos'])->name('materiais.favoritos');
    
    // Compras Coletivas (público - usuários logados)
    Route::get('/compras-coletivas', [ComprasColetivasController::class, 'index'])->name('compras-coletivas.index');
    Route::get('/compras-coletivas/{id}', [ComprasColetivasController::class, 'show'])->name('compras-coletivas.show');
    Route::post('/compras-coletivas/{id}/participar', [ComprasColetivasController::class, 'participar'])->name('compras-coletivas.participar');
    Route::put('/compras-coletivas/adesao/{id}', [ComprasColetivasController::class, 'atualizarParticipacao'])->name('compras-coletivas.adesao.atualizar');
    Route::delete('/compras-coletivas/adesao/{id}', [ComprasColetivasController::class, 'cancelarParticipacao'])->name('compras-coletivas.adesao.cancelar');
    
    Route::get('/compras-coletivas/propostas', [ComprasColetivasController::class, 'indexPropostas'])->name('compras-coletivas.propostas.index');
    Route::get('/compras-coletivas/propostas/criar', [ComprasColetivasController::class, 'createProposta'])->name('compras-coletivas.propostas.create');
    Route::post('/compras-coletivas/propostas', [ComprasColetivasController::class, 'storeProposta'])->name('compras-coletivas.propostas.store');
    Route::post('/compras-coletivas/propostas/{id}/votar', [ComprasColetivasController::class, 'votar'])->name('compras-coletivas.propostas.votar');
    
    Route::get('/compras-coletivas/fornecedor/ofertas', [ComprasColetivasController::class, 'minhasOfertas'])->name('compras-coletivas.fornecedor.ofertas');
    Route::post('/compras-coletivas/{id}/ofertar', [ComprasColetivasController::class, 'ofertar'])->name('compras-coletivas.ofertar');
    
    Route::get('/api/produtos/autocomplete', [ComprasColetivasController::class, 'autocompleteProdutos'])->name('produtos.autocomplete');
    
    // Rotas para usuários aprovados (TODOS veem)
    Route::middleware('approved')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Diretórios públicos (todos podem ver)
        Route::get('/compradores', [CompradoresController::class, 'index'])->name('compradores.index');
        Route::get('/compradores/{id}', [CompradoresController::class, 'show'])->name('compradores.show');
        
        Route::get('/fornecedores', [FornecedoresController::class, 'index'])->name('fornecedores.index');
        Route::get('/fornecedores/{id}', [FornecedoresController::class, 'show'])->name('fornecedores.show');
        
        // Talentos (fornecedor não vê - controle no controller)
        Route::get('/talentos', [TalentosController::class, 'index'])->name('talentos.index');
        Route::get('/talentos/{id}', [TalentosController::class, 'show'])->name('talentos.show');
        
        // Cotações (público - compradores visualizam, fornecedores adicionam ofertas)
        Route::get('/cotacoes', [CotacoesController::class, 'index'])->name('cotacoes.index');
        Route::get('/cotacoes/{id}', [CotacoesController::class, 'show'])->name('cotacoes.show');
        Route::post('/cotacoes/{cotacaoId}/oferta', [CotacoesController::class, 'salvarOferta'])->name('cotacoes.salvar-oferta');
        Route::delete('/cotacoes/{cotacaoId}/oferta', [CotacoesController::class, 'deletarOferta'])->name('cotacoes.deletar-oferta');
    });
    
    // Área Admin (apenas admin - CRUD completo)
    Route::middleware(['approved', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Gestão de usuários (manter para aprovações pendentes)
        Route::get('/usuarios', [AdminUsuariosController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/criar', [AdminUsuariosController::class, 'criar'])->name('usuarios.create');
        Route::post('/usuarios', [AdminUsuariosController::class, 'salvar'])->name('usuarios.store');
        Route::post('/usuarios/{id}/aprovar', [AdminUsuariosController::class, 'aprovar'])->name('usuarios.aprovar');
        Route::post('/usuarios/{id}/rejeitar', [AdminUsuariosController::class, 'rejeitar'])->name('usuarios.rejeitar');
        Route::delete('/usuarios/{id}', [AdminUsuariosController::class, 'deletar'])->name('usuarios.deletar');
        
        // Gestão de Compradores
        Route::get('/compradores', [AdminCompradoresController::class, 'index'])->name('compradores.index');
        Route::get('/compradores/criar', [AdminCompradoresController::class, 'create'])->name('compradores.create');
        Route::post('/compradores', [AdminCompradoresController::class, 'store'])->name('compradores.store');
        Route::get('/compradores/{id}', [AdminCompradoresController::class, 'show'])->name('compradores.show');
        Route::get('/compradores/{id}/editar', [AdminCompradoresController::class, 'edit'])->name('compradores.edit');
        Route::put('/compradores/{id}', [AdminCompradoresController::class, 'update'])->name('compradores.update');
        Route::post('/compradores/{id}/inativar', [AdminCompradoresController::class, 'inativar'])->name('compradores.inativar');
        Route::post('/compradores/{id}/ativar', [AdminCompradoresController::class, 'ativar'])->name('compradores.ativar');
        
        // Gestão de Fornecedores
        Route::get('/fornecedores', [AdminFornecedoresController::class, 'index'])->name('fornecedores.index');
        Route::get('/fornecedores/criar', [AdminFornecedoresController::class, 'create'])->name('fornecedores.create');
        Route::post('/fornecedores', [AdminFornecedoresController::class, 'store'])->name('fornecedores.store');
        Route::get('/fornecedores/{id}', [AdminFornecedoresController::class, 'show'])->name('fornecedores.show');
        Route::get('/fornecedores/{id}/editar', [AdminFornecedoresController::class, 'edit'])->name('fornecedores.edit');
        Route::put('/fornecedores/{id}', [AdminFornecedoresController::class, 'update'])->name('fornecedores.update');
        Route::post('/fornecedores/{id}/inativar', [AdminFornecedoresController::class, 'inativar'])->name('fornecedores.inativar');
        Route::post('/fornecedores/{id}/ativar', [AdminFornecedoresController::class, 'ativar'])->name('fornecedores.ativar');

        // Talentos
        Route::get('/talentos', [AdminTalentosController::class, 'index'])->name('talentos.index');
        Route::get('/talentos/criar', [AdminTalentosController::class, 'create'])->name('talentos.create');
        Route::post('/talentos', [AdminTalentosController::class, 'store'])->name('talentos.store');
        Route::get('/talentos/{id}', [AdminTalentosController::class, 'show'])->name('talentos.show');
        Route::get('/talentos/{id}/editar', [AdminTalentosController::class, 'edit'])->name('talentos.edit');
        Route::put('/talentos/{id}', [AdminTalentosController::class, 'update'])->name('talentos.update');
        Route::post('/talentos/{id}/inativar', [AdminTalentosController::class, 'inativar'])->name('talentos.inativar');
        Route::post('/talentos/{id}/ativar', [AdminTalentosController::class, 'ativar'])->name('talentos.ativar');
        Route::delete('/talentos/{id}', [AdminTalentosController::class, 'destroy'])->name('talentos.destroy');

        // Segmentos
        Route::get('/segmentos', [AdminSegmentosController::class, 'index'])->name('segmentos.index');
        Route::get('/segmentos/criar', [AdminSegmentosController::class, 'create'])->name('segmentos.create');
        Route::post('/segmentos', [AdminSegmentosController::class, 'store'])->name('segmentos.store');
        Route::get('/segmentos/{id}/editar', [AdminSegmentosController::class, 'edit'])->name('segmentos.edit');
        Route::put('/segmentos/{id}', [AdminSegmentosController::class, 'update'])->name('segmentos.update');
        Route::post('/segmentos/{id}/inativar', [AdminSegmentosController::class, 'inativar'])->name('segmentos.inativar');
        Route::post('/segmentos/{id}/ativar', [AdminSegmentosController::class, 'ativar'])->name('segmentos.ativar');
        Route::delete('/segmentos/{id}', [AdminSegmentosController::class, 'destroy'])->name('segmentos.destroy');

        // Assinaturas (apenas actions, sem views)
        Route::post('/assinaturas/usuario/{userId}', [AdminAssinaturasController::class, 'armazenar'])->name('assinaturas.armazenar');
        Route::post('/assinaturas/{id}/renovar', [AdminAssinaturasController::class, 'renovar'])->name('assinaturas.renovar');
        Route::post('/assinaturas/{id}/cancelar', [AdminAssinaturasController::class, 'cancelar'])->name('assinaturas.cancelar');

        // Cotações (admin)
        Route::get('/cotacoes', [AdminCotacoesController::class, 'index'])->name('cotacoes.index');
        Route::get('/cotacoes/criar', [AdminCotacoesController::class, 'create'])->name('cotacoes.create');
        Route::post('/cotacoes', [AdminCotacoesController::class, 'store'])->name('cotacoes.store');
        Route::get('/cotacoes/{id}/editar', [AdminCotacoesController::class, 'edit'])->name('cotacoes.edit');
        Route::put('/cotacoes/{id}', [AdminCotacoesController::class, 'update'])->name('cotacoes.update');
        Route::post('/cotacoes/{id}/encerrar', [AdminCotacoesController::class, 'encerrar'])->name('cotacoes.encerrar');
        Route::delete('/cotacoes/{id}', [AdminCotacoesController::class, 'destroy'])->name('cotacoes.destroy');
        Route::post('/cotacoes/{cotacaoId}/ofertas', [AdminCotacoesController::class, 'adicionarOferta'])->name('cotacoes.ofertas.adicionar');
        Route::delete('/cotacoes/ofertas/{id}', [AdminCotacoesController::class, 'deletarOferta'])->name('cotacoes.ofertas.deletar');

        // Configurações
        Route::get('/configuracoes', [AdminConfiguracoesController::class, 'index'])->name('configuracoes.index');
        Route::post('/configuracoes', [AdminConfiguracoesController::class, 'salvar'])->name('configuracoes.salvar');
        Route::get('/configuracoes/historico', [AdminConfiguracoesController::class, 'historico'])->name('configuracoes.historico');

        // Material de Gestão (admin)
        Route::get('/materiais', [AdminMateriaisController::class, 'index'])->name('materiais.index');
        Route::get('/materiais/criar', [AdminMateriaisController::class, 'create'])->name('materiais.create');
        Route::post('/materiais', [AdminMateriaisController::class, 'store'])->name('materiais.store');
        Route::get('/materiais/{id}/editar', [AdminMateriaisController::class, 'edit'])->name('materiais.edit');
        Route::put('/materiais/{id}', [AdminMateriaisController::class, 'update'])->name('materiais.update');
        Route::delete('/materiais/{id}', [AdminMateriaisController::class, 'destroy'])->name('materiais.destroy');
        Route::get('/materiais/analytics', [AdminMateriaisController::class, 'analytics'])->name('materiais.analytics');

        // Compras Coletivas - Produtos Catálogo (admin)
        Route::get('/compras-coletivas/produtos', [AdminComprasColetivasController::class, 'indexProdutos'])->name('compras-coletivas.produtos.index');
        Route::get('/compras-coletivas/produtos/criar', [AdminComprasColetivasController::class, 'createProduto'])->name('compras-coletivas.produtos.create');
        Route::post('/compras-coletivas/produtos', [AdminComprasColetivasController::class, 'storeProduto'])->name('compras-coletivas.produtos.store');
        Route::get('/compras-coletivas/produtos/{id}/editar', [AdminComprasColetivasController::class, 'editProduto'])->name('compras-coletivas.produtos.edit');
        Route::put('/compras-coletivas/produtos/{id}', [AdminComprasColetivasController::class, 'updateProduto'])->name('compras-coletivas.produtos.update');
        Route::delete('/compras-coletivas/produtos/{id}', [AdminComprasColetivasController::class, 'destroyProduto'])->name('compras-coletivas.produtos.destroy');

        // Compras Coletivas - Propostas (admin)
        Route::get('/compras-coletivas/propostas', [AdminComprasColetivasController::class, 'indexPropostas'])->name('compras-coletivas.propostas.index');
        Route::get('/compras-coletivas/propostas/{id}', [AdminComprasColetivasController::class, 'showProposta'])->name('compras-coletivas.propostas.show');
        Route::post('/compras-coletivas/propostas/{id}/iniciar-votacao', [AdminComprasColetivasController::class, 'iniciarVotacao'])->name('compras-coletivas.propostas.iniciar-votacao');
        Route::post('/compras-coletivas/propostas/{id}/aprovar', [AdminComprasColetivasController::class, 'aprovarProposta'])->name('compras-coletivas.propostas.aprovar');
        Route::post('/compras-coletivas/propostas/{id}/rejeitar', [AdminComprasColetivasController::class, 'rejeitarProposta'])->name('compras-coletivas.propostas.rejeitar');

        // Compras Coletivas - Compras (admin)
        Route::get('/compras-coletivas', [AdminComprasColetivasController::class, 'indexCompras'])->name('compras-coletivas.index');
        Route::get('/compras-coletivas/criar', [AdminComprasColetivasController::class, 'createCompra'])->name('compras-coletivas.create');
        Route::post('/compras-coletivas', [AdminComprasColetivasController::class, 'storeCompra'])->name('compras-coletivas.store');
        Route::get('/compras-coletivas/{id}/editar', [AdminComprasColetivasController::class, 'editCompra'])->name('compras-coletivas.edit');
        Route::put('/compras-coletivas/{id}', [AdminComprasColetivasController::class, 'updateCompra'])->name('compras-coletivas.update');
        Route::delete('/compras-coletivas/{id}', [AdminComprasColetivasController::class, 'destroyCompra'])->name('compras-coletivas.destroy');
    });
});

// Rota de assinatura vencida (autenticado, mas bloqueado)
Route::middleware(['auth'])->get('/assinatura/vencida', function () {
    return view('assinatura-vencida');
})->name('assinatura.vencida');
