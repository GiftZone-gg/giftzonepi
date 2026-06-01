<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function home()
    {
        $todos = Produto::where('ativo', true)->get();

        $maisprocurados = $todos->shuffle()->take(3);

        $playstation = $todos->filter(function($p) {
            $plats = is_array($p->plataformas) ? $p->plataformas : [];
            return in_array('PlayStation 5', $plats) || in_array('PlayStation 4', $plats) || in_array('PS5', $plats) || in_array('PS4', $plats);
        })->take(3)->values();

        $steam = $todos->filter(function($p) {
            $plats = is_array($p->plataformas) ? $p->plataformas : [];
            return in_array('PC', $plats);
        })->take(3)->values();

        $nintendo = $todos->filter(function($p) {
            $plats = is_array($p->plataformas) ? $p->plataformas : [];
            return in_array('Nintendo Switch', $plats);
        })->take(3)->values();

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

        $todos = Produto::where('ativo', true)->where('id', '!=', $produto->id)->get();

        $relacionados = $todos->filter(function($p) use ($produto) {
            if ($produto->genero && $p->genero === $produto->genero) return true;
            $plats = is_array($produto->plataformas) ? $produto->plataformas : [];
            $pPlats = is_array($p->plataformas) ? $p->plataformas : [];
            return count(array_intersect($plats, $pPlats)) > 0;
        })->shuffle()->take(4);

        if ($relacionados->count() < 4) {
            $idsExcluir = $relacionados->pluck('id')->push($produto->id)->toArray();
            $complemento = $todos->whereNotIn('id', $idsExcluir)->shuffle()->take(4 - $relacionados->count());
            $relacionados = $relacionados->merge($complemento);
        }

        $cartCount = count(session()->get('cart', []));

        return view('produto', compact('produto', 'isFavorited', 'relacionados', 'cartCount'));
    }
}