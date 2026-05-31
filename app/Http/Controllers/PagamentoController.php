<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class PagamentoController extends Controller
{
    // Exibe a tela de escolha do método de pagamento
    public function checkout()
    {
        $carrinho = session()->get('cart', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'Carrinho vazio.');
        }

        $itens = [];
        $total = 0;

        foreach ($carrinho as $id => $quantidade) {
            $produto = Produto::find($id);
            if ($produto) {
                $preco    = $this->getPreco($produto);
                $subtotal = $preco * $quantidade;
                $total   += $subtotal;
                $itens[]  = [
                    'produto'   => $produto,
                    'quantidade' => $quantidade,
                    'preco'     => $preco,
                    'subtotal'  => $subtotal,
                ];
            }
        }

        return view('pagamento.checkout', compact('itens', 'total'));
    }

    // Processa o pagamento (simulação)
    public function processar(Request $request)
    {
        $request->validate([
            'metodo' => 'required|in:boleto,credito,debito,pix',
        ]);

        $carrinho = session()->get('cart', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'Carrinho vazio.');
        }

        $user  = auth()->user();
        $total = 0;

        foreach ($carrinho as $id => $quantidade) {
            $produto = Produto::find($id);
            if ($produto) {
                $total += $this->getPreco($produto) * $quantidade;
            }
        }

        // PIX: redireciona para tela de QR Code
        if ($request->metodo === 'pix') {
            return redirect()->route('pagamento.pix');
        }

        // Boleto: redireciona para tela de boleto
        if ($request->metodo === 'boleto') {
            return redirect()->route('pagamento.boleto');
        }

        // Cria o pedido
        $orderNumber = '#GZ-' . strtoupper(Str::random(8));

        $order = Order::create([
            'user_id'        => $user->id,
            'order_number'   => $orderNumber,
            'total_amount'   => $total,
            'discount_amount' => 0,
            'final_amount'   => $total,
            'payment_status' => 'paid',      // simulação
            'order_status'   => 'completed', // simulação
        ]);

        foreach ($carrinho as $id => $quantidade) {
            $produto = Produto::find($id);
            if ($produto) {
                $preco = $this->getPreco($produto);
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $quantidade,
                    'price'      => $preco,
                    'total'      => $preco * $quantidade,
                ]);
            }
        }

        session()->forget('cart');

        return redirect()->route('usuario.pedidos')
            ->with('success', "Pedido {$orderNumber} finalizado com sucesso! Pagamento via {$request->metodo}.");
    }

    private function getPreco($produto)
    {
        $edicoes = is_array($produto->edicoes)
            ? $produto->edicoes
            : json_decode($produto->edicoes, true);

        return $edicoes[0]['preco'] ?? 0;
    }
}