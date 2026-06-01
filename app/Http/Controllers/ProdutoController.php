<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function home()
    {
        $maisprocurados = Produto::where('ativo', true)->inRandomOrder()->take(3)->get();

        $playstation = Produto::where('ativo', true)
            ->where(function ($q) {
                $q->whereJsonContains('plataformas', 'PlayStation 5')
                  ->orWhereJsonContains('plataformas', 'PlayStation 4');
            })->take(3)->get();

        $steam = Produto::where('ativo', true)
            ->whereJsonContains('plataformas', 'PC')
            ->take(3)->get();

        $nintendo = Produto::where('ativo', true)
            ->whereJsonContains('plataformas', 'Nintendo Switch')
            ->take(3)->get();

        return view('index', compact('maisprocurados', 'playstation', 'steam', 'nintendo'));
    }

    public function show(string $slug)
    {
        $produto = Produto::where('slug', $slug)->where('ativo', true)->firstOrFail();

        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = Wishlist::where('user_id', auth()->id())
                ->where('product_id', $produto->id)
                ->exists();
        }

        // ─── Produtos Relacionados ───
        $relacionados = Produto::where('ativo', true)
            ->where('id', '!=', $produto->id)
            ->where(function ($query) use ($produto) {
                if ($produto->genero) {
                    $query->where('genero', $produto->genero);
                }
                if (is_array($produto->plataformas)) {
                    foreach ($produto->plataformas as $plat) {
                        $query->orWhereJsonContains('plataformas', $plat);
                    }
                }
            })
            ->where('id', '!=', $produto->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($relacionados->count() < 4) {
            $idsExcluir = $relacionados->pluck('id')->push($produto->id)->toArray();
            $complemento = Produto::where('ativo', true)
                ->whereNotIn('id', $idsExcluir)
                ->inRandomOrder()
                ->take(4 - $relacionados->count())
                ->get();
            $relacionados = $relacionados->merge($complemento);
        }

        $cartCount = count(session()->get('cart', []));

        return view('produto', compact('produto', 'isFavorited', 'relacionados', 'cartCount'));
    }
}