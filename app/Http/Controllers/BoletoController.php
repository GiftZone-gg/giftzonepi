<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produto;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class BoletoController extends Controller
{
    public function show()
    {
        $carrinho = session()->get('cart', []);

        if (empty($carrinho)) {
            return redirect()->route('carrinho.index')->with('error', 'Carrinho vazio.');
        }

        $total = 0;
        foreach ($carrinho as $id => $quantidade) {
            $produto = Produto::find($id);
            if ($produto) {
                $edicoes = is_array($produto->edicoes)
                    ? $produto->edicoes
                    : json_decode($produto->edicoes, true);
                $total += ($edicoes[0]['preco'] ?? 0) * $quantidade;
            }
        }

        // Código de barras fictício
        $codigo = vsprintf('%05d.%05d %05d.%06d %05d.%06d %d %014d', [
            rand(10000, 99999), rand(10000, 99999),
            rand(10000, 99999), rand(100000, 999999),
            rand(10000, 99999), rand(100000, 999999),
            rand(1, 9),
            rand(10000000000000, 99999999999999),
        ]);

        $vencimento = now()->addWeekdays(3)->format('d/m/Y');

        session()->put('boleto_pending', [
            'cart'   => $carrinho,
            'total'  => $total,
            'codigo' => $codigo,
        ]);

        return view('pagamento.boleto', compact('total', 'codigo', 'vencimento'));
    }

    public function confirmar()
    {
        $pending = session()->get('boleto_pending');

        if (!$pending) {
            return redirect()->route('carrinho.index')->with('error', 'Sessão expirada.');
        }

        $user  = auth()->user();
        $order = Order::create([
            'user_id'         => $user->id,
            'order_number'    => '#GZ-' . strtoupper(Str::random(8)),
            'total_amount'    => $pending['total'],
            'discount_amount' => 0,
            'final_amount'    => $pending['total'],
            'payment_status'  => 'pending', // boleto demora para compensar
            'order_status'    => 'pending',
        ]);

        foreach ($pending['cart'] as $id => $quantidade) {
            $produto = Produto::find($id);
            if ($produto) {
                $edicoes = is_array($produto->edicoes)
                    ? $produto->edicoes
                    : json_decode($produto->edicoes, true);
                $preco = $edicoes[0]['preco'] ?? 0;
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $quantidade,
                    'price'      => $preco,
                    'total'      => $preco * $quantidade,
                ]);
            }
        }

        session()->forget(['cart', 'boleto_pending']);

        return redirect()->route('usuario.pedidos')
            ->with('success', "Pedido {$order->order_number} registrado! Aguardando compensação do boleto.");
    }
}