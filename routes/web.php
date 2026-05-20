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
    $jogos = App\Models\Produto::where('ativo', true)->get();
    return view('catalogo', compact('jogos'));
})->name('catalogo');

Route::post('/login-action', [AuthController::class, 'login'])->name('login.auth');
Route::post('/register-action', [AuthController::class, 'register'])->name('register.auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/favoritos/adicionar/{produtoId}', [UsuarioController::class, 'adicionarFavorito'])->name('favoritos.adicionar');



// Carrinho
Route::get('/carrinho', [App\Http\Controllers\CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/carrinho/adicionar/{id}', [App\Http\Controllers\CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::patch('/carrinho/atualizar/{id}', [App\Http\Controllers\CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
Route::delete('/carrinho/remover/{id}', [App\Http\Controllers\CarrinhoController::class, 'remover'])->name('carrinho.remover');
Route::post('/carrinho/finalizar', [App\Http\Controllers\CarrinhoController::class, 'finalizar'])->name('carrinho.finalizar');

// Checkout (pagamento) – exige login
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [App\Http\Controllers\PagamentoController::class, 'checkout'])->name('checkout');
    Route::post('/pagamento/processar', [App\Http\Controllers\PagamentoController::class, 'processar'])->name('pagamento.processar');
});

Route::prefix('usuario')->name('usuario.')->group(function () {
    Route::get('/perfil',     [UsuarioController::class, 'perfil'])       ->name('perfil');
    Route::get('/pedidos',    [UsuarioController::class, 'pedidos'])      ->name('pedidos');
    Route::get('/pagamentos', [UsuarioController::class, 'pagamentos'])   ->name('pagamentos');
    Route::get('/favoritos',  [UsuarioController::class, 'favoritos'])    ->name('favoritos');
    Route::delete('/favoritos/{id}', [UsuarioController::class, 'removerFavorito'])->name('favoritos.remover');
    Route::get('/editar',     [UsuarioController::class, 'editar'])       ->name('editar');
    Route::put('/editar',     [UsuarioController::class, 'editarSalvar']) ->name('editar.salvar');
    Route::delete('/excluir-conta', [UsuarioController::class, 'excluirConta'])->name('excluir.conta');


// Pagamento (após login)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [App\Http\Controllers\PagamentoController::class, 'checkout'])->name('checkout');
    Route::post('/pagamento/processar', [App\Http\Controllers\PagamentoController::class, 'processar'])->name('pagamento.processar');
});
});