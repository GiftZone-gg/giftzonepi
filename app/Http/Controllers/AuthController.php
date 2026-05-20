<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'cpf'      => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Gera nickname único (ex: joaosilva123)
        $baseNick = Str::slug($request->name, '');
        $nickname = $baseNick . rand(100, 999);
        while (User::where('nickname', $nickname)->exists()) {
            $nickname = $baseNick . rand(100, 999);
        }

        $user = User::create([
            'name'     => $request->name,
            'nickname' => $nickname,
            'email'    => $request->email,
            'cpf'      => $request->cpf,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('usuario.perfil');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'login_error' => 'E-mail ou senha incorretos.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}