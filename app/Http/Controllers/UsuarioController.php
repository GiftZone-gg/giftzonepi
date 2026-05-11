<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function perfil()
    {
        return view('usuario.perfil');
    }

    public function pedidos()
    {
        return view('usuario.pedidos');
    }

    public function pagamentos()
    {
        return view('usuario.pagamentos');
    }

    public function favoritos()
    {
        return view('usuario.favoritos');
    }

    public function editar()
    {
        return view('usuario.editar');
    }

    public function editarSalvar(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'email'    => 'required|email|max:255',
            'password' => 'nullable|min:8|confirmed',
        ]);

        return redirect()->route('usuario.editar')->with('success', 'Perfil atualizado com sucesso!');
    }
}