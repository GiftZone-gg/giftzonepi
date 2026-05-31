<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;

class CarrinhoController extends Controller
{
    // Exibe o carrinho
    public function index()
    {
        $carrinho = session()->get('cart', []);
        $itens    = [];
        $total    = 0;

        foreach ($carrinho as $id => $quantidade) {
            $produto = Produto::find($id);
            if ($produto) {
                $preco    = $this->getPreco($produto);
                $subtotal = $preco * $quantidade;
                $total   += $subtotal;
                $itens[]  = [
                    'produto'    => $produto,
                    'quantidade' => $quantidade,
                    'preco'      => $preco,
                    'subtotal'   => $subtotal,
                ];
            }
        }

        return view('carrinho.index', compact('itens', 'total'));
    }

    // Compra direta: adiciona o produto ao carrinho e redireciona para checkout
    public function comprarDireto($produtoId)
    {
        $produto = Produto::find($produtoId);

        if (!$produto) {
            return back()->with('error', 'Produto não encontrado.');
        }

        $carrinho        = session()->get('cart', []);
        $quantidadeAtual = $carrinho[$produtoId] ?? 0;

        if ($quantidadeAtual >= 3) {
            return back()->with('error', 'Limite de 3 unidades por produto atingido.');
        }

        $totalItens = array_sum($carrinho);
        if ($totalItens >= 9) {
            return back()->with('error', 'Carrinho atingiu o limite máximo de 9 itens.');
        }

        $carrinho[$produtoId] = $quantidadeAtual + 1;
        session()->put('cart', $carrinho);

        // Redireciona direto para o checkout
        return redirect()->route('checkout');
    }

    // Adiciona um produto ao carrinho (via POST)
    public function adicionar($id)
    {
        $produto = Produto::find($id);

        if (!$produto) {
            return back()->with('error', 'Produto não encontrado.');
        }

        $carrinho        = session()->get('cart', []);
        $quantidadeAtual = $carrinho[$id] ?? 0;

        // Limite por produto: 3
        if ($quantidadeAtual >= 3) {
            return back()->with('error', 'Limite de 3 unidades por produto atingido.');
        }

        // Limite total de itens: 9
        $totalItens = array_sum($carrinho);
        if ($totalItens >= 9) {
            return back()->with('error', 'Carrinho atingiu o limite máximo de 9 itens.');
        }

        $carrinho[$id] = $quantidadeAtual + 1;
        session()->put('cart', $carrinho);

        return back()->with('success', 'Produto adicionado ao carrinho!');
    }

    // Atualiza a quantidade de um item
    public function atualizar(Request $request, $id)
    {
        $request->validate(['quantidade' => 'required|integer|min:1|max:3']);

        $carrinho = session()->get('cart', []);

        if (!isset($carrinho[$id])) {
            return back()->with('error', 'Item não encontrado.');
        }

        $totalSemItem = array_sum($carrinho) - $carrinho[$id];

        if ($totalSemItem + $request->quantidade > 9) {
            return back()->with('error', 'Limite total de 9 itens seria excedido.');
        }

        $carrinho[$id] = $request->quantidade;
        session()->put('cart', $carrinho);

        return redirect()->route('carrinho.index')->with('success', 'Carrinho atualizado.');
    }

    // Remove um item do carrinho
    public function remover($id)
    {
        $carrinho = session()->get('cart', []);
        unset($carrinho[$id]);
        session()->put('cart', $carrinho);

        return redirect()->route('carrinho.index')->with('success', 'Item removido.');
    }

    // Finaliza a compra (redireciona para checkout ou login)
    public function finalizar(Request $request)
    {
        if (empty(session()->get('cart', []))) {
            return redirect()->route('carrinho.index')->with('error', 'Carrinho vazio.');
        }

        if (!auth()->check()) {
            session()->put('url.intended', route('checkout'));
            return redirect()->route('login')->with('info', 'Faça login para finalizar a compra.');
        }

        return redirect()->route('checkout');
    }

    // Função auxiliar para obter o preço do produto (primeira edição)
    private function getPreco($produto)
    {
        $edicoes = is_array($produto->edicoes)
            ? $produto->edicoes
            : json_decode($produto->edicoes, true);

        return $edicoes[0]['preco'] ?? 0;
    }
}