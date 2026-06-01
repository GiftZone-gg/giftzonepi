<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone | {{ __('messages.login_tab') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Gasoek+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #002830; color: white; min-height: 100vh; display: flex; flex-direction: column; }
        header { padding: 32px 60px; }
        .logo-box { display: flex; align-items: center; text-decoration: none; gap: 5px; }
        .logo-box span:first-child { font-family: 'Gasoek One', sans-serif; font-size: 32px; color: #FFDC74; }
        .logo-box span:last-child { font-family: 'Gasoek One', sans-serif; font-size: 32px; color: #FFF1EA; }
        .auth-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .auth-card { background-color: #001A20; width: 100%; max-width: 420px; border-radius: 20px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.5); border: 1px solid rgba(255, 220, 116, 0.1); }
        .auth-tabs { display: flex; background: #002830; padding: 6px; border-radius: 12px; margin-bottom: 35px; }
        .tab-btn { flex: 1; padding: 12px; border: none; border-radius: 8px; background: transparent; color: rgba(255,255,255,0.4); font-family: 'Inter', sans-serif; font-weight: 700; cursor: pointer; transition: all 0.3s; }
        .tab-btn.active { background: #FFDC74; color: #001A20; }
        .input-group { position: relative; margin-bottom: 15px; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #FFDC74; font-size: 14px; }
        .input-group input { width: 100%; padding: 15px 15px 15px 45px; background: #002830; border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; font-size: 14px; outline: none; transition: border-color 0.3s; }
        .input-group input:focus { border-color: #FFDC74; }
        .error-msg { color: #ff6b6b; font-size: 12px; margin-bottom: 10px; display: block; }
        .options { display: flex; justify-content: space-between; align-items: center; font-size: 13px; margin: 15px 0 30px; color: rgba(255,255,255,0.7); }
        .options a { color: #FFDC74; text-decoration: none; }
        .btn-main { width: 100%; padding: 16px; background: #FFDC74; color: #001A20; border: none; border-radius: 10px; font-family: 'Gasoek One', sans-serif; font-size: 18px; cursor: pointer; transition: transform 0.2s; text-transform: uppercase; }
        .btn-main:hover { transform: translateY(-2px); background: #fde9a2; }
        .divider { margin: 30px 0; display: flex; align-items: center; font-size: 11px; color: rgba(255,255,255,0.3); text-transform: uppercase; }
        .divider::before, .divider::after { content: ''; flex: 1; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .divider span { padding: 0 10px; }
        .social-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .social-item { background: #002830; border: 1px solid rgba(255,255,255,0.05); padding: 12px; border-radius: 10px; color: white; font-size: 20px; cursor: pointer; display: flex; justify-content: center; align-items: center; }
        @media (max-width: 600px) { header { padding: 20px; justify-content: center; } .auth-card { padding: 30px 20px; } }
    </style>
</head>
<body>

<script>
    // Máscara de CPF
    const cpfInput = document.querySelector('input[name="cpf"]');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.slice(0, 11);
            if (v.length > 9) {
                v = v.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
            } else if (v.length > 6) {
                v = v.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
            } else if (v.length > 3) {
                v = v.replace(/(\d{3})(\d{1,3})/, '$1.$2');
            }
            e.target.value = v;
        });
        cpfInput.setAttribute('maxlength', '14');
        cpfInput.setAttribute('placeholder', '000.000.000-00');
    }
</script>

    <header>
        <a href="/" class="logo-box">
            <span>Gift</span><span>Zone</span>
        </a>
    </header>

    <main class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-tabs">
                <button class="tab-btn {{ $errors->has('name') || $errors->has('cpf') ? '' : 'active' }}" id="tabLogin" onclick="switchTab('login')">{{ __('messages.login_tab') }}</button>
                <button class="tab-btn {{ $errors->has('name') || $errors->has('cpf') ? 'active' : '' }}" id="tabRegister" onclick="switchTab('register')">{{ __('messages.register_tab') }}</button>
            </div>

            <form action="{{ route('login.auth') }}" method="POST" id="loginForm" style="{{ $errors->has('name') || $errors->has('cpf') ? 'display: none;' : '' }}">
                @csrf
                @if($errors->has('login_error'))
                    <span class="error-msg">{{ $errors->first('login_error') }}</span>
                @endif
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="{{ __('messages.email') }}" value="{{ old('email') }}" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="{{ __('messages.password') }}" required>
                </div>
                <div class="options">
                    <label style="cursor: pointer;"><input type="checkbox" name="remember" style="margin-right: 5px;"> {{ __('messages.remember') }}</label>
                    <a href="{{ route('password.request') }}">{{ __('messages.forgot_password') }}</a>
                </div>
                <button type="submit" class="btn-main">{{ __('messages.access') }}</button>
            </form>

            <form action="{{ route('register.auth') }}" method="POST" id="registerForm" style="{{ $errors->has('name') || $errors->has('cpf') ? '' : 'display: none;' }}">
                @csrf
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="name" placeholder="{{ __('messages.full_name') }}" value="{{ old('name') }}" required>
                    @error('name') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="{{ __('messages.email') }}" value="{{ old('email') }}" required>
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-address-card"></i>
                    <input type="text" name="cpf" placeholder="{{ __('messages.cpf_placeholder') }}" value="{{ old('cpf') }}" required>
                    @error('cpf') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="{{ __('messages.create_password') }}" required>
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-main" style="margin-top: 20px;">{{ __('messages.create_account') }}</button>
            </form>

            <div class="divider"><span>{{ __('messages.or_use_social') }}</span></div>
            <div class="social-row">
                <button type="button" class="social-item"><i class="fa-brands fa-google"></i></button>
                <button type="button" class="social-item"><i class="fa-brands fa-steam"></i></button>
                <button type="button" class="social-item"><i class="fa-brands fa-discord"></i></button>
            </div>
        </div>
    </main>

    <script>
        function switchTab(type) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const tabLogin = document.getElementById('tabLogin');
            const tabRegister = document.getElementById('tabRegister');
            if (type === 'register') {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                tabRegister.classList.add('active');
                tabLogin.classList.remove('active');
            } else {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                tabLogin.classList.add('active');
                tabRegister.classList.remove('active');
            }
        }
    </script>
</body>
</html>