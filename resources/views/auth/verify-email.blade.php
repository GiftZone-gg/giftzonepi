<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone | Verifique seu E-mail</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #002830;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .logo-box {
            display: flex; align-items: center; text-decoration: none; gap: 5px;
            margin-bottom: 40px;
        }
        .logo-box span:first-child { font-family: 'Gasoek One', sans-serif; font-size: 36px; color: #FFDC74; }
        .logo-box span:last-child { font-family: 'Gasoek One', sans-serif; font-size: 36px; color: #FFF1EA; }
        .verify-card {
            background-color: #001A20;
            width: 100%; max-width: 480px;
            border-radius: 20px; padding: 48px 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            border: 1px solid rgba(255, 220, 116, 0.1);
            text-align: center;
        }
        .mail-icon {
            width: 80px; height: 80px;
            background: rgba(255,220,116,0.1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px; font-size: 36px;
        }
        .verify-title {
            font-family: 'Gasoek One', sans-serif;
            font-size: 22px; color: #FFDC74; margin-bottom: 16px;
        }
        .verify-text {
            font-size: 14px; color: rgba(255,255,255,0.6);
            line-height: 1.7; margin-bottom: 12px;
        }
        .verify-email-highlight { font-weight: 700; color: #FFDC74; }
        .alert-success {
            background: rgba(107,255,181,0.1);
            border: 1px solid rgba(107,255,181,0.3);
            color: #6bffb5; border-radius: 10px;
            padding: 12px 18px; font-size: 14px; margin-bottom: 20px;
        }
        .btn-resend {
            display: inline-block; width: 100%; padding: 16px;
            background: #FFDC74; color: #001A20; border: none;
            border-radius: 10px; font-family: 'Gasoek One', sans-serif;
            font-size: 16px; cursor: pointer;
            transition: transform 0.2s, background 0.2s;
            text-transform: uppercase; margin-top: 24px;
        }
        .btn-resend:hover { transform: translateY(-2px); background: #fde9a2; }
        .btn-logout-link {
            display: block; margin-top: 20px; background: none;
            border: none; color: rgba(255,255,255,0.3);
            font-size: 13px; cursor: pointer; transition: color 0.2s;
        }
        .btn-logout-link:hover { color: rgba(255,255,255,0.6); }
        .tips {
            margin-top: 24px; padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .tips p { font-size: 12px; color: rgba(255,255,255,0.3); line-height: 1.6; }
        @media (max-width: 500px) { .verify-card { padding: 32px 24px; } }
    </style>
</head>
<body>

    <a href="/" class="logo-box">
        <span>Gift</span><span>Zone</span>
    </a>

    <div class="verify-card">
        <div class="mail-icon">📧</div>

        <h1 class="verify-title">Verifique seu E-mail</h1>

        <p class="verify-text">
            Enviamos um link de verificação para o seu e-mail. Clique no link para ativar sua conta.
        </p>

        <p class="verify-text">
            E-mail enviado para:
            <span class="verify-email-highlight">{{ Auth::user()->email }}</span>
        </p>

        @if(session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <p class="verify-text" style="font-size: 13px; margin-top: 16px;">
            Não recebeu o e-mail? Clique abaixo para reenviar.
        </p>

        <form action="{{ route('verification.resend') }}" method="POST">
            @csrf
            <button type="submit" class="btn-resend">Reenviar E-mail de Verificação</button>
        </form>

        <div class="tips">
            <p>💡 Verifique também sua pasta de spam ou lixo eletrônico.</p>
        </div>

        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout-link">
                Sair e voltar à página inicial
            </button>
        </form>
    </div>

</body>
</html>