<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsuariosController;
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
    
    // Dashboard (apenas usuários aprovados)
    Route::middleware('approved')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
    
    // Área Admin (apenas admin)
    Route::middleware(['approved', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Gestão de usuários
        Route::get('/usuarios', [AdminUsuariosController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/criar', [AdminUsuariosController::class, 'criar'])->name('usuarios.create');
        Route::post('/usuarios', [AdminUsuariosController::class, 'salvar'])->name('usuarios.store');
        Route::post('/usuarios/{id}/aprovar', [AdminUsuariosController::class, 'aprovar'])->name('usuarios.aprovar');
        Route::post('/usuarios/{id}/rejeitar', [AdminUsuariosController::class, 'rejeitar'])->name('usuarios.rejeitar');
        Route::delete('/usuarios/{id}', [AdminUsuariosController::class, 'deletar'])->name('usuarios.deletar');
    });
});
