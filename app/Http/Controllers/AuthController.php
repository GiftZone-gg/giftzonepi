<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ─── Registro ───

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'cpf'      => 'required|string|unique:users',
            'password' => 'required|string|min:8',
        ]);

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

        event(new Registered($user));

        \App\Models\UserNotification::criarBoasVindas($user->id);

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    // ─── Login ───

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            // Admin pula verificação
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            // Usuário normal precisa verificar
            if (!Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'login_error' => 'E-mail ou senha incorretos.',
        ])->withInput();
    }

    // ─── Logout ───

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // ─── Verificação de E-mail ───

    public function verificationNotice()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('usuario.perfil');
        }
        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('usuario.perfil')->with('success', 'E-mail verificado com sucesso!');
    }

    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('usuario.perfil');
        }
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Link de verificação reenviado!');
    }

    // ─── Esqueci Minha Senha ───

    public function forgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function forgotPasswordSend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link de redefinição enviado! Confira seu e-mail.');
        }

        return back()->withErrors(['email' => 'Não encontramos um usuário com esse e-mail.']);
    }

    public function resetPasswordForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPasswordUpdate(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Senha redefinida com sucesso! Faça login.');
        }

        return back()->withErrors(['email' => 'Token inválido ou expirado. Solicite novamente.']);
    }
}