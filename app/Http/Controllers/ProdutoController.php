<?php
 
namespace App\Http\Controllers;
 
use App\Models\Produto;
use Illuminate\Http\Request;
 
class ProdutoController extends Controller
{
    /**
     * Exibe a página de um produto pelo slug.
     */
    public function show(string $slug)
{
    $produto = Produto::where('slug', $slug)->where('ativo', true)->firstOrFail();
    
    // Verifica se o usuário está logado e se este produto está nos favoritos dele
    $isFavorited = false;
    if (auth()->check()) {
        $isFavorited = \App\Models\Wishlist::where('user_id', auth()->id())
                          ->where('product_id', $produto->id)
                          ->exists();
    }
    
    return view('produto', compact('produto', 'isFavorited'));
}
}
 