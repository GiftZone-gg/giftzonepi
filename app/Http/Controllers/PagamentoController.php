<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Support\Str;

class PagamentoController extends Controller
{
    public function checkout()
    {
        $carrinho = session()->get('cart', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', __('messages.empty_cart'));
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
                    'produto'    => $produto,
                    'quantidade' => $quantidade,
                    'preco'      => $preco,
                    'subtotal'   => $subtotal,
                ];
            }
        }

        // Busca cartões salvos do usuário
        $cartoesSalvos = PaymentMethod::where('user_id', auth()->id())
            ->orderBy('is_primary', 'desc')
            ->get();

        return view('pagamento.checkout', compact('itens', 'total', 'cartoesSalvos'));
    }

    public function processar(Request $request)
    {
        $request->validate([
            'metodo' => 'required|in:boleto,credito,debito,pix',
        ]);

        $carrinho = session()->get('cart', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', __('messages.empty_cart'));
        }

        if ($request->metodo === 'pix') {
            return redirect()->route('pagamento.pix');
        }

        if ($request->metodo === 'boleto') {
            return redirect()->route('pagamento.boleto');
        }

        // ─── Crédito / Débito ───
        // Valida se tem cartão selecionado ou dados de novo cartão
        $cardId = $request->input('card_id');

        if ($cardId) {
            // Usa cartão salvo
            $card = PaymentMethod::where('user_id', auth()->id())
                ->where('id', $cardId)
                ->first();

            if (!$card) {
                return back()->with('error', 'Cartão não encontrado.');
            }
        }
        // Se não tem card_id, aceita como simulação (sem validação real de gateway)

        $user  = auth()->user();
        $total = 0;

        foreach ($carrinho as $id => $quantidade) {
            $produto = Produto::find($id);
            if ($produto) {
                $total += $this->getPreco($produto) * $quantidade;
            }
        }

        $order = Order::create([
            'user_id'         => $user->id,
            'order_number'    => '#GZ-' . strtoupper(Str::random(8)),
            'total_amount'    => $total,
            'discount_amount' => 0,
            'final_amount'    => $total,
            'payment_status'  => 'paid',
            'order_status'    => 'completed',
        ]);

        foreach ($carrinho as $id => $quantidade) {
            $produto = Produto::find($id);
            if ($produto) {
                $preco = $this->getPreco($produto);

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $id,
                    'quantity'     => $quantidade,
                    'price'        => $preco,
                    'total'        => $preco * $quantidade,
                    'digital_key'  => OrderItem::gerarChaveDigital(),
                    'key_revealed' => false,
                ]);
            }
        }

        session()->forget('cart');

        \App\Models\UserNotification::criarPedidoRealizado($user->id, $order->order_number);

        $metodoLabel = $request->metodo === 'credito' ? __('messages.credit_card') : __('messages.debit_card');

        return redirect()->route('usuario.pedidos')
            ->with('success', "Pedido {$order->order_number} finalizado — {$metodoLabel}!");
    }

    private function getPreco($produto)
    {
        $edicoes = is_array($produto->edicoes) ? $produto->edicoes : json_decode($produto->edicoes, true);
        return $edicoes[0]['preco'] ?? 0;
    }
}