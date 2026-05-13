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
 
        return view('produto', compact('produto'));
    }
}
 