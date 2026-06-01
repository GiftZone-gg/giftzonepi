<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function home()
    {
<<<<<<< HEAD
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
=======
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
>>>>>>> 8f8792f46e75329bf093354c25e627c14b85d5d7

        return view('index', compact('maisprocurados', 'playstation', 'steam', 'nintendo'));
    }

    public function show(string $slug)
    {
        $produto = Produto::where('slug', $slug)->where('ativo', true)->firstOrFail();

        $isFavorited = false;
        if (auth()->check()) {
<<<<<<< HEAD
            $isFavorited = Wishlist::where('user_id', auth()->id())
                ->where('product_id', $produto->id)
                ->exists();
        }

        // ─── Produtos Relacionados ───
        $relacionados = Produto::where('ativo', true)
            ->where('id', '!=', $produto->id)
            ->where(function ($query) use ($produto) {
                // Mesmo gênero
                if ($produto->genero) {
                    $query->where('genero', $produto->genero);
                }
                // Ou mesma plataforma
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

        // Se não achou relacionados suficientes, completa com aleatórios
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
=======
            $isFavorited = \App\Models\Wishlist::where('user_id', auth()->id())
                              ->where('product_id', $produto->id)
                              ->exists();
        }

        return view('produto', compact('produto', 'isFavorited'));
>>>>>>> 8f8792f46e75329bf093354c25e627c14b85d5d7
    }
}