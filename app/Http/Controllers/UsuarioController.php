<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Produto;
use App\Models\PaymentMethod;
use App\Models\Order;

class UsuarioController extends Controller
{
    public function perfil()
    {
        $usuario = auth()->user();
        return view('usuario.perfil', compact('usuario'));
    }

    public function pedidos()
    {
        $pedidos = Order::with('items.produto')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('usuario.pedidos', compact('pedidos'));
    }

    public function pagamentos()
    {
        $metodos = PaymentMethod::where('user_id', auth()->id())
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalGasto = Order::where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->sum('final_amount');

        $ultimoPagamento = Order::where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->first();

        return view('usuario.pagamentos', compact('metodos', 'totalGasto', 'ultimoPagamento'));
    }

    // ─── Cartões ───

    public function adicionarCartao(Request $request)
    {
        $request->validate([
            'card_number'  => 'required|string|min:13|max:19',
            'holder_name'  => 'required|string|max:255',
            'expiry'       => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{4}$/'],
            'card_type'    => 'required|in:credito,debito',
        ]);

        $userId = auth()->id();

        // Máximo 4 cartões
        $count = PaymentMethod::where('user_id', $userId)->count();
        if ($count >= 4) {
            return back()->with('error', __('messages.max_cards_reached'));
        }

        $number   = preg_replace('/\D/', '', $request->card_number);
        $brand    = PaymentMethod::detectBrand($number);
        $lastFour = substr($number, -4);

        // Se é o primeiro cartão, marca como principal
        $isPrimary = ($count === 0);

        PaymentMethod::create([
            'user_id'     => $userId,
            'label'       => ucfirst($brand) . ' •••• ' . $lastFour,
            'brand'       => $brand,
            'type'        => $request->card_type,
            'last_four'   => $lastFour,
            'holder_name' => strtoupper($request->holder_name),
            'expiry'      => $request->expiry,
            'is_primary'  => $isPrimary,
        ]);

        return back()->with('success', __('messages.card_added'));
    }

    public function removerCartao($id)
    {
        $card = PaymentMethod::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $wasPrimary = $card->is_primary;
        $card->delete();

        // Se removeu o principal, promove outro
        if ($wasPrimary) {
            $next = PaymentMethod::where('user_id', auth()->id())->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return back()->with('success', __('messages.card_removed'));
    }

    public function definirCartaoPrincipal($id)
    {
        $userId = auth()->id();

        // Remove primary de todos
        PaymentMethod::where('user_id', $userId)->update(['is_primary' => false]);

        // Define o novo
        PaymentMethod::where('user_id', $userId)
            ->where('id', $id)
            ->update(['is_primary' => true]);

        return back()->with('success', __('messages.card_set_primary'));
    }

    // ─── Favoritos ───

    public function favoritos()
    {
        $favoritos = Wishlist::with('produto')
            ->where('user_id', auth()->id())
            ->get();
        return view('usuario.favoritos', compact('favoritos'));
    }

    public function adicionarFavorito($produtoId)
    {
        $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para favoritar.');
        }

        $produto = Produto::find($produtoId);
        if (!$produto) {
            return back()->with('error', 'Produto não encontrado.');
        }

        $existe = Wishlist::where('user_id', $userId)
            ->where('product_id', $produtoId)
            ->exists();

        if (!$existe) {
            Wishlist::create([
                'user_id'    => $userId,
                'product_id' => $produtoId,
            ]);
            return back()->with('success', 'Produto adicionado aos favoritos!');
        }

        return back()->with('info', 'Este produto já está nos seus favoritos.');
    }

    public function removerFavorito($id)
    {
        $favorito = Wishlist::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();
        $favorito->delete();
        return redirect()->route('usuario.favoritos')->with('success', 'Produto removido dos favoritos.');
    }

    // ─── Editar Perfil ───

    public function editar()
    {
        $usuario = auth()->user();
        return view('usuario.editar', compact('usuario'));
    }

    public function editarSalvar(Request $request)
    {
        $usuario = auth()->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $usuario->name  = $request->name;
        $usuario->email = $request->email;

        if ($request->filled('nickname')) {
            $nickname = strtolower($request->nickname);
            if (!str_starts_with($nickname, '@')) {
                $nickname = '@' . ltrim($nickname, '@');
            }
            $baseNickname = $nickname;
            $contador = 1;
            while (User::where('nickname', $nickname)->where('id', '!=', $usuario->id)->exists()) {
                $nickname = $baseNickname . $contador;
                $contador++;
            }
            $usuario->nickname = $nickname;
        }

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $avatarData = $request->input('avatar_base64', '');

        if ($avatarData === 'reset_to_default') {
            if ($usuario->avatar && $usuario->avatar !== 'icone1.svg') {
                Storage::disk('public')->delete($usuario->avatar);
            }
            $usuario->avatar = 'icone1.svg';
        } elseif (!empty($avatarData) && preg_match('/^data:image\/(\w+);base64,/', $avatarData, $type)) {
            $imageData = substr($avatarData, strpos($avatarData, ',') + 1);
            $imageType = strtolower($type[1]);
            if (in_array($imageType, ['jpg', 'jpeg', 'png'])) {
                if ($usuario->avatar && $usuario->avatar !== 'icone1.svg') {
                    Storage::disk('public')->delete($usuario->avatar);
                }
                $imageBase64 = base64_decode($imageData);
                $fileName = 'avatars/' . uniqid() . '.' . $imageType;
                Storage::disk('public')->put($fileName, $imageBase64);
                $usuario->avatar = $fileName;
            }
        }

        $usuario->save();

        return redirect()->route('usuario.perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    public function atualizarAvatar(Request $request)
    {
        $usuario = auth()->user();
        $avatarData = $request->input('avatar_base64');

        if (preg_match('/^data:image\/(\w+);base64,/', $avatarData, $type)) {
            $imageData   = substr($avatarData, strpos($avatarData, ',') + 1);
            $imageType   = strtolower($type[1]);
            $imageBase64 = base64_decode($imageData);
            $fileName    = 'avatars/' . uniqid() . '.' . $imageType;

            if ($usuario->avatar && $usuario->avatar !== 'icone1.svg') {
                Storage::disk('public')->delete($usuario->avatar);
            }

            Storage::disk('public')->put($fileName, $imageBase64);
            $usuario->avatar = $fileName;
            $usuario->save();

            return response()->json([
                'success'    => true,
                'avatar_url' => asset('storage/' . $fileName),
            ]);
        }

        return response()->json(['success' => false]);
    }

    // ─── Excluir Conta ───

    public function excluirConta(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        if ($user->avatar && $user->avatar !== 'icone1.svg') {
            Storage::disk('public')->delete($user->avatar);
        }

        auth()->logout();
        $user->delete();

        return redirect()->route('home')->with('success', 'Sua conta foi excluída permanentemente.');
    }

    // ─── Pedidos ───

    public function cancelarPedido($id)
    {
        $order = Order::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        if (!$order->canCancel()) {
            return back()->with('error', 'Este pedido não pode ser cancelado.');
        }

        $order->update([

        
            'order_status'   => 'cancelled',
            'payment_status' => 'refunded',
        ]);

        \App\Models\UserNotification::criarPedidoCancelado(auth()->id(), $order->order_number);

        return back()->with('success', "Pedido {$order->order_number} cancelado com sucesso.");
    }

    public function revelarChave($itemId)
    {
        $item = \App\Models\OrderItem::whereHas('order', function ($q) {
            $q->where('user_id', auth()->id());
        })->where('id', $itemId)->firstOrFail();

        $item->update(['key_revealed' => true]);

        return response()->json([
            'success' => true,
            'key'     => $item->digital_key,
        ]);
    }

    public function notificacoes()
{
    $notificacoes = \App\Models\UserNotification::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->get();

    // Marca todas como lidas
    \App\Models\UserNotification::where('user_id', auth()->id())
        ->where('read', false)
        ->update(['read' => true]);

    return view('usuario.notificacoes', compact('notificacoes'));
}

public function notificacaoExcluir($id)
{
    \App\Models\UserNotification::where('user_id', auth()->id())
        ->where('id', $id)
        ->delete();

    return back()->with('success', 'Notificação removida.');
}
}