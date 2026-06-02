<?php

// ─── Troca de Idioma ───
Route::post('/idioma', function (Illuminate\Http\Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['pt', 'en'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('idioma.trocar');

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PixController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\AdminController;

// ─── Páginas Públicas ───
Route::get('/', [ProdutoController::class, 'home'])->name('home');
Route::get('/login', fn() => view('login'))->name('login');
Route::get('/catalogo', function () {
    $jogos = \App\Models\Produto::where('ativo', true)->get();
    $cartCount = count(session()->get('cart', []));
    return view('catalogo', compact('jogos', 'cartCount'));
})->name('catalogo');
Route::get('/produto/{slug}', [ProdutoController::class, 'show'])->name('produto.show');

// ─── Auth ───
Route::post('/login-action', [AuthController::class, 'login'])->name('login.auth');
Route::post('/register-action', [AuthController::class, 'register'])->name('register.auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Esqueci Minha Senha ───
Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPasswordSend'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPasswordUpdate'])->name('password.update');

// ─── Verificação de E-mail ───
/*
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'verificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/resend', [AuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');
    Route::get('/email/check-verified', [AuthController::class, 'checkVerified'])->name('verification.check');
});
*/



// ─── Carrinho ───
Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/carrinho/adicionar/{id}', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::patch('/carrinho/atualizar/{id}', [CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
Route::delete('/carrinho/remover/{id}', [CarrinhoController::class, 'remover'])->name('carrinho.remover');
Route::post('/carrinho/finalizar', [CarrinhoController::class, 'finalizar'])->name('carrinho.finalizar');

// ─── Favoritos ───
Route::post('/favoritos/adicionar/{produtoId}', [UsuarioController::class, 'adicionarFavorito'])->name('favoritos.adicionar');

// ─── ADMIN (precisa login, NÃO precisa verificação) ───
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Produtos
    Route::get('/produtos', [AdminController::class, 'produtos'])->name('produtos');
    Route::get('/produtos/criar', [AdminController::class, 'produtoCriar'])->name('produtos.criar');
    Route::post('/produtos', [AdminController::class, 'produtoSalvar'])->name('produtos.salvar');
    Route::get('/produtos/{id}/editar', [AdminController::class, 'produtoEditar'])->name('produtos.editar');
    Route::put('/produtos/{id}', [AdminController::class, 'produtoAtualizar'])->name('produtos.atualizar');
    Route::delete('/produtos/{id}', [AdminController::class, 'produtoRemover'])->name('produtos.remover');
    Route::post('/produtos/{id}/toggle', [AdminController::class, 'produtoToggle'])->name('produtos.toggle');

    // Pedidos
    Route::get('/pedidos', [AdminController::class, 'pedidos'])->name('pedidos');
    Route::put('/pedidos/{id}/status', [AdminController::class, 'pedidoStatus'])->name('pedidos.status');

    // Usuários
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
    Route::post('/usuarios/{id}/toggle-admin', [AdminController::class, 'usuarioToggleAdmin'])->name('usuarios.toggle-admin');
    Route::delete('/usuarios/{id}', [AdminController::class, 'usuarioExcluir'])->name('usuarios.excluir');
    Route::post('/usuarios/{id}/verificar', [AdminController::class, 'usuarioVerificar'])->name('usuarios.verificar');
});

// ─── Rotas que exigem login + e-mail verificado ───
Route::middleware(['auth'])->group(function () {

    // Compra direta
    Route::post('/comprar-direto/{produto}', [CarrinhoController::class, 'comprarDireto'])->name('comprar.direto');

    // Checkout e Pagamento
    Route::get('/checkout', [PagamentoController::class, 'checkout'])->name('checkout');
    Route::post('/pagamento/processar', [PagamentoController::class, 'processar'])->name('pagamento.processar');

    // PIX
    Route::get('/pix', [PixController::class, 'show'])->name('pagamento.pix');
    Route::post('/pix/confirmar', [PixController::class, 'confirmar'])->name('pagamento.pix.confirmar');

    // Boleto
    Route::get('/boleto', [BoletoController::class, 'show'])->name('pagamento.boleto');
    Route::post('/boleto/confirmar', [BoletoController::class, 'confirmar'])->name('pagamento.boleto.confirmar');

    // Avatar via AJAX
    Route::post('/usuario/atualizar-avatar', [UsuarioController::class, 'atualizarAvatar'])->name('usuario.atualizar.avatar');

    // ─── Painel do Usuário ───
    Route::prefix('usuario')->name('usuario.')->group(function () {
        Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('perfil');
        Route::get('/pedidos', [UsuarioController::class, 'pedidos'])->name('pedidos');
        Route::get('/pagamentos', [UsuarioController::class, 'pagamentos'])->name('pagamentos');
        Route::get('/favoritos', [UsuarioController::class, 'favoritos'])->name('favoritos');
        Route::delete('/favoritos/{id}', [UsuarioController::class, 'removerFavorito'])->name('favoritos.remover');
        Route::get('/notificacoes', [UsuarioController::class, 'notificacoes'])->name('notificacoes');
        Route::delete('/notificacoes/{id}', [UsuarioController::class, 'notificacaoExcluir'])->name('notificacoes.excluir');
        Route::get('/editar', [UsuarioController::class, 'editar'])->name('editar');
        Route::put('/editar', [UsuarioController::class, 'editarSalvar'])->name('editar.salvar');
        Route::delete('/excluir-conta', [UsuarioController::class, 'excluirConta'])->name('excluir.conta');

        // Pedidos
        Route::post('/pedidos/{id}/cancelar', [UsuarioController::class, 'cancelarPedido'])->name('pedidos.cancelar');
        Route::post('/pedidos/item/{id}/revelar', [UsuarioController::class, 'revelarChave'])->name('pedidos.revelar');

        // Cartões
        Route::post('/cartao/adicionar', [UsuarioController::class, 'adicionarCartao'])->name('cartao.adicionar');
        Route::delete('/cartao/{id}', [UsuarioController::class, 'removerCartao'])->name('cartao.remover');
        Route::post('/cartao/{id}/principal', [UsuarioController::class, 'definirCartaoPrincipal'])->name('cartao.principal');
    });
});