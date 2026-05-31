<?php

use App\Http\Controllers\PixController;
use App\Http\Controllers\BoletoController;
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

Route::post('/usuario/atualizar-avatar', [App\Http\Controllers\UsuarioController::class, 'atualizarAvatar'])->name('usuario.atualizar.avatar');

Route::post('/favoritos/adicionar/{produtoId}', [UsuarioController::class, 'adicionarFavorito'])->name('favoritos.adicionar');

// No seu routes/web.php
Route::post('/comprar-direto/{produto}', [App\Http\Controllers\CarrinhoController::class, 'comprarDireto'])->name('comprar.direto');

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

    Route::middleware(['auth'])->group(function () {

    // ... suas rotas existentes ...

    // ADICIONE AQUI:
    Route::get('/pix', [PixController::class, 'show'])->name('pagamento.pix');
    Route::post('/pix/confirmar', [PixController::class, 'confirmar'])->name('pagamento.pix.confirmar');

});



Route::get('/boleto', [BoletoController::class, 'show'])->name('pagamento.boleto');
Route::post('/boleto/confirmar', [BoletoController::class, 'confirmar'])->name('pagamento.boleto.confirmar');
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