<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Produto;
use Illuminate\Support\Str;

class PixController extends Controller
{
    // Monta o payload PIX e exibe a tela com QR Code
    public function show(Request $request)
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

        $chavePix   = 'c3a545af-36fc-42fa-a8e3-2644bda768db';
        $nomeBenef  = 'GiftZone';
        $cidade     = 'SAO PAULO';
        $valor      = number_format($total, 2, '.', '');
        $txid       = strtoupper(Str::random(10));

        $payload = $this->gerarPayloadPix($chavePix, $nomeBenef, $cidade, $valor, $txid);

        // URL da API pública para gerar QR Code (sem instalação)
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($payload);

        // Salva o pedido como pendente na sessão para confirmar depois
        session()->put('pix_pending', [
            'cart'    => $carrinho,
            'total'   => $total,
            'payload' => $payload,
            'txid'    => $txid,
        ]);

        return view('pagamento.pix', compact('total', 'qrCodeUrl', 'payload'));
    }

    // Confirma o pedido após pagamento (simulação)
    public function confirmar(Request $request)
    {
        $pending = session()->get('pix_pending');

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
            'payment_status'  => 'paid',
            'order_status'    => 'completed',
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

        session()->forget(['cart', 'pix_pending']);

        return redirect()->route('usuario.pedidos')
            ->with('success', "Pedido {$order->order_number} confirmado via PIX!");
    }

    // Gera o payload PIX padrão Banco Central
    private function gerarPayloadPix($chave, $nome, $cidade, $valor, $txid)
    {
        $nome   = substr($this->removerAcentos($nome), 0, 25);
        $cidade = substr($this->removerAcentos($cidade), 0, 15);

        $merchantAccountInfo = $this->tlv('00', 'BR.GOV.BCB.PIX') . $this->tlv('01', $chave);
        $merchantAccountInfo = $this->tlv('26', $merchantAccountInfo);

        $payload =
            $this->tlv('00', '01') .
            $merchantAccountInfo .
            $this->tlv('52', '0000') .
            $this->tlv('53', '986') .
            $this->tlv('54', $valor) .
            $this->tlv('58', 'BR') .
            $this->tlv('59', $nome) .
            $this->tlv('60', $cidade) .
            $this->tlv('62', $this->tlv('05', $txid));

        $payload .= $this->tlv('63', $this->crc16($payload . '6304'));

        return $payload;
    }

    private function tlv($id, $value)
    {
        return $id . str_pad(strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    private function crc16($data)
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
            }
        }
        return strtoupper(dechex($crc & 0xFFFF));
    }

    private function removerAcentos($str)
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
    }
}