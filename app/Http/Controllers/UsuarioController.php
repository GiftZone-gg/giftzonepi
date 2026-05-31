<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Produto;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Ajuste para o seu modelo correto de usuário

class UsuarioController extends Controller
{




    public function perfil()
    {
        $usuario = auth()->user();
        return view('usuario.perfil', compact('usuario'));
    }

   public function pedidos()
{
    $pedidos = \App\Models\Order::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
    return view('usuario.pedidos', compact('pedidos'));
}

    public function pagamentos()
    {
        $metodos = PaymentMethod::where('user_id', auth()->id())->get();
        return view('usuario.pagamentos', compact('metodos'));
    }

    public function favoritos()
    {
        $favoritos = Wishlist::with('produto')->where('user_id', auth()->id())->get();
        return view('usuario.favoritos', compact('favoritos'));
    }

    public function editar()
    {
        $usuario = auth()->user();
        return view('usuario.editar', compact('usuario'));
    }

//    public function editarSalvar(Request $request)
//     {
//         $usuario = auth()->user();

//         $request->validate([
//             'name'     => 'required|string|max:255',
//             'email'    => 'required|email|max:255|unique:users,email,' . $usuario->id,
//             'password' => 'nullable|min:8|confirmed',
//         ]);

//         $usuario->name = $request->name;
//         $usuario->email = $request->email;
        
//         if ($request->filled('password')) {
//             $usuario->password = Hash::make($request->password);
//         }

//         // --- INÍCIO DA LÓGICA DO AVATAR ---
//         if ($request->has('avatar_base64')) {
//             $avatarData = $request->input('avatar_base64');

//             if ($avatarData === 'default') {
//                 $usuario->avatar = 'icone1.svg';
//             } elseif (preg_match('/^data:image\/(\w+);base64,/', $avatarData)) {
//                 $imageParts = explode(";base64,", $avatarData);
//                 $imageType = explode("image/", $imageParts[0])[1];
//                 $imageBase64 = base64_decode($imageParts[1]);

//                 $fileName = 'avatars/' . uniqid() . '.' . $imageType;
//                 \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);

//                 $usuario->avatar = $fileName;
//             }
//         }
//         // --- FIM DA LÓGICA DO AVATAR ---

//         $usuario->save();

//         return redirect()->route('usuario.editar')->with('success', 'Perfil atualizado com sucesso!');
//     }

public function atualizarAvatar(Request $request)
{
    $usuario = auth()->user();
    $avatarData = $request->input('avatar_base64');
    
    if (preg_match('/^data:image\/(\w+);base64,/', $avatarData, $type)) {
        $imageData = substr($avatarData, strpos($avatarData, ',') + 1);
        $imageType = strtolower($type[1]);
        $imageBase64 = base64_decode($imageData);
        $fileName = 'avatars/' . uniqid() . '.' . $imageType;
        \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);
        $usuario->avatar = $fileName;
        $usuario->save();
        
        return response()->json([
            'success' => true,
            'avatar_url' => asset('storage/' . $fileName)
        ]);
    }
    
    return response()->json(['success' => false]);
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

    $usuario->name = $request->name;
    $usuario->email = $request->email;
    
    // Nickname
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
    
    // Senha
    if ($request->filled('password')) {
        $usuario->password = Hash::make($request->password);
    }

    // AVATAR - CORRIGIDO
    if ($request->has('avatar_base64') && $request->avatar_base64 !== 'default') {
        $avatarData = $request->input('avatar_base64');
        
        if (preg_match('/^data:image\/(\w+);base64,/', $avatarData, $type)) {
            $imageData = substr($avatarData, strpos($avatarData, ',') + 1);
            $imageType = strtolower($type[1]);
            
            if (in_array($imageType, ['jpg', 'jpeg', 'png'])) {
                $imageBase64 = base64_decode($imageData);
                $fileName = 'avatars/' . uniqid() . '.' . $imageType;
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageBase64);
                $usuario->avatar = $fileName;
            }
        }
    } elseif ($request->avatar_base64 === 'default') {
        $usuario->avatar = 'icone1.svg';
    }

    $usuario->save();

    return redirect()->route('usuario.perfil')->with('success', 'Perfil atualizado com sucesso!');
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
                'user_id' => $userId,
                'product_id' => $produtoId
            ]);
            return back()->with('success', 'Produto adicionado aos favoritos!');
        }

        return back()->with('info', 'Este produto já está nos seus favoritos.');
    }

    public function removerFavorito($id)
    {
        $favorito = Wishlist::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        $favorito->delete();
        return redirect()->route('usuario.favoritos')->with('success', 'Produto removido dos favoritos.');
    }

    public function excluirConta(Request $request)
    {
        $user = auth()->user();

        // A senha atual é enviada via campo do formulário (que será preenchido pelo prompt)
        $request->validate([
            'current_password' => 'required|string',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        auth()->logout();
        $user->delete();

        return redirect()->route('home')->with('success', 'Sua conta foi excluída permanentemente.');
    }

    
}