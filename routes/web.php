<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompradoresController;
use App\Http\Controllers\FornecedoresController;
use App\Http\Controllers\TalentosController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsuariosController;
use App\Http\Controllers\Admin\AdminCompradoresController;
use App\Http\Controllers\Admin\AdminFornecedoresController;
use App\Http\Controllers\Admin\AdminTalentosController;
use App\Http\Controllers\Admin\AdminSegmentosController;
use Illuminate\Support\Facades\Route;

// Rota pública
Route::get('/', fn () => view('landing'))->name('home');

// Rotas de autenticação (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'exibirLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/cadastro', [AuthController::class, 'exibirCadastro'])->name('cadastro');
    Route::post('/cadastro', [AuthController::class, 'cadastrar']);
});

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Página de aguardando aprovação (qualquer usuário autenticado)
    Route::get('/aguardando', [AuthController::class, 'aguardando'])->name('aguardando');
    
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
    });
});
