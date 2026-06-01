<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone | Redefinir Senha</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif; background-color: #002830; color: white;
            min-height: 100vh; display: flex; flex-direction: column;
            align-items: center; justify-content: center; padding: 40px 20px;
        }
        .logo-box {
            display: flex; align-items: center; text-decoration: none; gap: 5px;
            margin-bottom: 40px;
        }
        .logo-box span:first-child { font-family: 'Gasoek One', sans-serif; font-size: 36px; color: #FFDC74; }
        .logo-box span:last-child { font-family: 'Gasoek One', sans-serif; font-size: 36px; color: #FFF1EA; }
        .card {
            background-color: #001A20; width: 100%; max-width: 440px;
            border-radius: 20px; padding: 44px 36px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            border: 1px solid rgba(255, 220, 116, 0.1); text-align: center;
        }
        .icon-wrap {
            width: 70px; height: 70px; background: rgba(107,255,181,0.1);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px; font-size: 32px;
        }
        .card-title {
            font-family: 'Gasoek One', sans-serif; font-size: 20px;
            color: #FFDC74; margin-bottom: 12px;
        }
        .card-text {
            font-size: 14px; color: rgba(255,255,255,0.5); line-height: 1.6; margin-bottom: 24px;
        }
        .input-group { position: relative; margin-bottom: 16px; text-align: left; }
        .input-label {
            font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.4);
            text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; display: block;
        }
        .input-group input {
            width: 100%; padding: 14px 16px; background: #002830;
            border: 1px solid rgba(255,255,255,0.1); border-radius: 10px;
            color: white; font-size: 14px; outline: none; transition: border-color 0.3s;
        }
        .input-group input:focus { border-color: #FFDC74; }
        .input-group input::placeholder { color: rgba(255,255,255,0.25); }
        .error-msg { color: #ff6b6b; font-size: 12px; margin-top: 6px; display: block; }
        .alert-success {
            background: rgba(107,255,181,0.1); border: 1px solid rgba(107,255,181,0.3);
            color: #6bffb5; border-radius: 10px; padding: 12px 18px;
            font-size: 14px; margin-bottom: 20px;
        }
        .btn-main {
            width: 100%; padding: 16px; background: #FFDC74; color: #001A20;
            border: none; border-radius: 10px; font-family: 'Gasoek One', sans-serif;
            font-size: 16px; cursor: pointer; transition: transform 0.2s, background 0.2s;
            text-transform: uppercase; margin-top: 8px;
        }
        .btn-main:hover { transform: translateY(-2px); background: #fde9a2; }
        .back-link {
            display: block; margin-top: 20px; color: rgba(255,255,255,0.3);
            text-decoration: none; font-size: 13px; transition: color 0.2s;
        }
        .back-link:hover { color: rgba(255,255,255,0.6); }
        @media (max-width: 500px) { .card { padding: 32px 24px; } }
    </style>
</head>
<body>

    <a href="/" class="logo-box">
        <span>Gift</span><span>Zone</span>
    </a>

    <div class="card">
        <div class="icon-wrap">🔑</div>
        <h1 class="card-title">Nova Senha</h1>
        <p class="card-text">
            Digite sua nova senha abaixo.
        </p>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <label class="input-label">E-mail</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" required placeholder="Seu e-mail">
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="input-group">
                <label class="input-label">Nova Senha</label>
                <input type="password" name="password" required placeholder="Mínimo 8 caracteres">
                @error('password') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="input-group">
                <label class="input-label">Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" required placeholder="Repita a senha">
            </div>

            <button type="submit" class="btn-main">Redefinir Senha</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">← Voltar para o login</a>
    </div>

</body>
</html>