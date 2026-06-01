<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function home()
    {
        $todos           = Produto::where('ativo', true)->get();
        $maisprocurados  = $todos->take(3);
        $playstation     = $todos->filter(function($p) {
                            $plats = is_array($p->plataformas) ? $p->plataformas : [];
                            return in_array('PlayStation 5', $plats) || in_array('PlayStation 4', $plats);
                        })->take(3)->values();
        $steam           = $todos->filter(function($p) {
                            $plats = is_array($p->plataformas) ? $p->plataformas : [];
                            return in_array('PC', $plats);
                        })->take(3)->values();
        $nintendo        = $todos->filter(function($p) {
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
            $isFavorited = \App\Models\Wishlist::where('user_id', auth()->id())
                              ->where('product_id', $produto->id)
                              ->exists();
        }

        return view('produto', compact('produto', 'isFavorited'));
    }
}