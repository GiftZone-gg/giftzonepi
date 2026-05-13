<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProdutoController;

Route::get('/produto/{slug}', [ProdutoController::class, 'show'])->name('produto.show');

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/catalogo', function () {
return view('catalogo');
})->name('catalogo');

Route::post('/login-action', [AuthController::class, 'login'])->name('login.auth');
Route::post('/register-action', [AuthController::class, 'register'])->name('register.auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('usuario')->name('usuario.')->group(function () {
    Route::get('/perfil',     [UsuarioController::class, 'perfil'])       ->name('perfil');
    Route::get('/pedidos',    [UsuarioController::class, 'pedidos'])      ->name('pedidos');
    Route::get('/pagamentos', [UsuarioController::class, 'pagamentos'])   ->name('pagamentos');
    Route::get('/favoritos',  [UsuarioController::class, 'favoritos'])    ->name('favoritos');
    Route::get('/editar',     [UsuarioController::class, 'editar'])       ->name('editar');
    Route::put('/editar',     [UsuarioController::class, 'editarSalvar']) ->name('editar.salvar');
});