<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.checkout_title') }} - GiftZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Gasoek+One&family=Inria+Sans:ital,wght@1,700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #002830; color: white; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        .container { max-width: 1400px; margin: 0 auto; padding: 96px 60px 60px 60px; }
        .sidebar { position: fixed; top: 0; left: -280px; width: 280px; height: 100%; background-color: #001A20; z-index: 1000; transition: all 0.3s ease; padding: 32px 24px; display: flex; flex-direction: column; box-shadow: 5px 0 15px rgba(0,0,0,0.3); }
        .sidebar.active { left: 0; }
        .sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .close-menu { cursor: pointer; font-size: 24px; background: none; border: none; color: white; }
        .menu-group { border-bottom: 1px solid rgba(255,255,255,0.1); padding: 20px 0; }
        .menu-item { display: block; color: white; text-decoration: none; font-family: 'Gasoek One', sans-serif; font-size: 16px; margin-bottom: 20px; font-weight: 400; transition: color 0.3s; text-transform: uppercase; cursor: pointer; border: none; background: none; width: 100%; text-align: left; }
        .menu-item:hover { color: #FFDC74; }
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: none; z-index: 999; }
        .sidebar-overlay.active { display: block; }
        header { position: fixed; top: 0; left: 0; width: 100%; display: flex; justify-content: space-between; align-items: center; padding: 20px 60px; background: rgba(0,40,48,0.75); backdrop-filter: blur(8px); z-index: 998; opacity: 0.85; transition: opacity 0.3s ease, background-color 0.3s ease; }
        header:hover { opacity: 1; background: rgba(0,40,48,0.95); }
        .logo-area { display: flex; align-items: center; gap: 20px; }
        .hamburger { cursor: pointer; display: flex; flex-direction: column; gap: 6px; background: none; border: none; }
        .hamburger span { display: block; width: 24px; height: 2px; background-color: white; }
        .logo-box { height: 44px; display: flex; align-items: center; }
        .logo-box img { height: 100%; width: 160px; }
        .nav-avatar-container { display: inline-flex; align-items: center; justify-content: center; transition: transform 0.2s; }
        .nav-avatar-container:hover { transform: scale(1.08); }
        .nav-avatar-mini { width: 44px; height: 44px; border-radius: 50%; border: 2px solid #FFDC74; object-fit: cover; background: #1F6D7E; box-shadow: 0 0 12px rgba(245,200,66,0.3); }
        .btn-entrar { border: 1px solid rgba(255,255,255,0.3); padding: 12px 36px; border-radius: 8px; color: white; text-decoration: none; font-weight: 500; font-size: 18px; transition: all 0.3s ease; background: transparent; cursor: pointer; }
        .btn-entrar:hover { background: rgba(255,255,255,0.1); }
        .checkout-page-title { font-family: 'Gasoek One', sans-serif; font-size: 36px; font-weight: 400; color: #FDE9A2; margin-bottom: 28px; margin-top: 12px; }
        .btn-voltar { display: inline-flex; align-items: center; gap: 8px; color: #6a9a94; text-decoration: none; font-size: 13px; font-weight: 500; margin-bottom: 20px; transition: color 0.2s; }
        .btn-voltar:hover { color: #FFDC74; }
        .checkout-wrapper { display: grid; grid-template-columns: 1fr 380px; gap: 28px; align-items: start; }
        .checkout-panel { background: #001A20; border-radius: 20px; padding: 28px; }
        .panel-title { font-family: 'Gasoek One', sans-serif; font-size: 18px; font-weight: 400; color: #FDE9A2; text-transform: uppercase; margin-bottom: 24px; }
        .item-list { display: flex; align-items: center; gap: 20px; padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,0.07); }
        .item-list:last-of-type { border-bottom: none; }
        .item-img { width: 130px; min-width: 130px; height: 80px; border-radius: 10px; object-fit: cover; background: #1F6D7E; }
        .item-details { flex: 1; }
        .item-name { font-family: 'Gasoek One', sans-serif; font-size: 15px; font-weight: 400; color: white; margin-bottom: 4px; }
        .item-platform { font-size: 12px; color: #6a9a94; }
        .checkout-total { display: flex; justify-content: space-between; align-items: center; padding: 18px 0 0; border-top: 1px solid rgba(255,255,255,0.12); margin-top: 16px; font-family: 'Gasoek One', sans-serif; font-size: 22px; font-weight: 400; }
        .checkout-total span:last-child { color: #FFDC74; }
        .payment-panel { background: #001A20; border-radius: 20px; padding: 28px; position: sticky; top: 110px; }
        .metodo-opcao { display: flex; align-items: center; gap: 14px; padding: 14px 16px; background: #002830; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s; }
        .metodo-opcao:hover { border-color: #FFDC74; background: rgba(255,220,116,0.05); }
        .metodo-opcao input[type="radio"] { accent-color: #FFDC74; width: 16px; height: 16px; cursor: pointer; }
        .metodo-opcao span { flex: 1; font-size: 14px; font-weight: 500; }
        .payment-icon { display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .payment-icon img { height: 20px; width: auto; filter: brightness(0) invert(1); }

        /* Cartões salvos no checkout */
        .saved-cards-section { display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.06); }
        .saved-cards-section.visible { display: block; }
        .saved-cards-title { font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 10px; }
        .saved-card-option { display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: rgba(0,40,48,0.6); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; margin-bottom: 8px; cursor: pointer; transition: all 0.2s; }
        .saved-card-option:hover { border-color: #FFDC74; background: rgba(255,220,116,0.04); }
        .saved-card-option input[type="radio"] { accent-color: #FFDC74; width: 14px; height: 14px; cursor: pointer; }
        .saved-card-label { font-size: 13px; color: rgba(255,255,255,0.7); }
        .saved-card-badge { font-size: 10px; font-weight: 700; background: rgba(245,200,66,0.15); color: #F5C842; padding: 2px 8px; border-radius: 10px; }

        .btn-finalizar { display: block; width: 100%; background: #FFDC74; color: #001A20; border: none; border-radius: 12px; padding: 16px; font-family: 'Gasoek One', sans-serif; font-size: 18px; font-weight: 400; text-align: center; cursor: pointer; transition: background 0.2s, transform 0.15s; margin-top: 20px; }
        .btn-finalizar:hover { background: #ffe99e; transform: translateY(-2px); }

        footer { background: #001e25; border-top: 1px solid rgba(253,233,162,.1); padding: 28px 32px; margin-top: 60px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
        .footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .footer-logo img { height: 30px; }
        .footer-bottom { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .footer-copy { font-size: .78rem; color: #6a9a94; }
        .social-links { display: flex; gap: 14px; }
        .social-links a { color: #6a9a94; text-decoration: none; transition: color .2s; }
        .social-links a:hover { color: #FFDC74; }

        @media (max-width: 900px) { .container { padding: 96px 20px 40px; } .checkout-wrapper { grid-template-columns: 1fr; } .payment-panel { position: static; } header { padding: 20px 20px; } }
        @media (max-width: 600px) { .item-img { width: 80px; min-width: 80px; height: 56px; } .item-name { font-size: 13px; } }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <button class="close-menu" id="closeBtn">✕</button>
    </div>
    <nav class="menu-group">
        <a href="{{ route('home') }}" class="menu-item">{{ __('messages.home') }}</a>
        <a href="{{ route('catalogo') }}" class="menu-item">{{ __('messages.catalog') }}</a>
        <a href="#" class="menu-item">{{ __('messages.offers') }}</a>
    </nav>
    <nav class="menu-group">
        <a href="{{ route('catalogo', ['plataforma' => 'PlayStation 5']) }}" class="menu-item">{{ __('messages.playstation') }}</a>
        <a href="{{ route('catalogo', ['plataforma' => 'Xbox']) }}" class="menu-item">{{ __('messages.xbox') }}</a>
        <a href="{{ route('catalogo', ['plataforma' => 'Nintendo Switch']) }}" class="menu-item">{{ __('messages.nintendo') }}</a>
        <a href="{{ route('catalogo', ['plataforma' => 'PC']) }}" class="menu-item">{{ __('messages.steam') }}</a>
    </nav>
    <nav class="menu-group">
        @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item">{{ __('messages.logout') }} ({{ Auth::user()->name }})</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="menu-item">{{ __('messages.enter') }}</a>
        @endauth
    </nav>
</aside>

<header>
    <div class="logo-area">
        <button class="hamburger" id="menuBtn"><span></span><span></span><span></span></button>
        <div class="logo-box">
            <a href="{{ route('home') }}"><img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone Logo"></a>
        </div>
    </div>
    <div class="user-auth-area">
        @auth
            <a href="{{ route('usuario.perfil') }}" class="nav-avatar-container" title="{{ __('messages.my_profile') }}">
                <img class="nav-avatar-mini"
                     src="{{ Auth::user()->avatar === 'icone1.svg' || empty(Auth::user()->avatar) ? asset('images/icone1.svg') : asset('storage/' . Auth::user()->avatar) }}"
                     alt="Avatar" onerror="this.src='{{ asset('images/icone1.svg') }}'">
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-entrar">{{ __('messages.enter') }}</a>
        @endauth
    </div>
</header>

<div class="container">
    <a href="{{ route('carrinho.index') }}" class="btn-voltar">{{ __('messages.back_to_cart') }}</a>
    <h1 class="checkout-page-title">{{ __('messages.checkout_title') }}</h1>

    <form action="{{ route('pagamento.processar') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="checkout-wrapper">

            {{-- Coluna esquerda: itens --}}
            <div class="checkout-panel">
                <div class="panel-title">{{ __('messages.my_items') }}</div>
                @foreach($itens as $item)
                @php
                    $plataformas = is_array($item['produto']->plataformas) ? $item['produto']->plataformas : json_decode($item['produto']->plataformas, true) ?? [];
                @endphp
                <div class="item-list">
                    <img src="{{ asset('images/' . $item['produto']->imagem_principal) }}"
                         class="item-img" alt="{{ $item['produto']->nome }}" onerror="this.style.display='none'">
                    <div class="item-details">
                        <div class="item-name">{{ $item['produto']->nome }}</div>
                        <div class="item-platform">{{ $plataformas[0] ?? 'N/A' }}</div>
                    </div>
                </div>
                @endforeach
                <div class="checkout-total">
                    <span>{{ __('messages.total') }}</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>
            </div>

            {{-- Coluna direita: pagamento --}}
            <div class="payment-panel">
                <div class="panel-title">{{ __('messages.payment') }}</div>

                <label class="metodo-opcao">
                    <input type="radio" name="metodo" value="credito" required onchange="toggleSavedCards()">
                    <span>{{ __('messages.credit_card') }}</span>
                    <div class="payment-icon">💳</div>
                </label>
                <label class="metodo-opcao">
                    <input type="radio" name="metodo" value="debito" onchange="toggleSavedCards()">
                    <span>{{ __('messages.debit_card') }}</span>
                    <div class="payment-icon">💳</div>
                </label>
                <label class="metodo-opcao">
                    <input type="radio" name="metodo" value="boleto" onchange="toggleSavedCards()">
                    <span>{{ __('messages.express_boleto') }}</span>
                    <div class="payment-icon">📄</div>
                </label>
                <label class="metodo-opcao">
                    <input type="radio" name="metodo" value="pix" onchange="toggleSavedCards()">
                    <span>PIX</span>
                    <div class="payment-icon">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/50/Pix_%28Brazil%29_logo.svg" alt="PIX">
                    </div>
                </label>

                {{-- Cartões salvos (aparece ao selecionar crédito/débito) --}}
                <div class="saved-cards-section" id="savedCardsSection">
                    @if($cartoesSalvos->count() > 0)
                        <p class="saved-cards-title">{{ __('messages.select_saved_card') }}</p>
                        @foreach($cartoesSalvos as $card)
                        <label class="saved-card-option">
                            <input type="radio" name="card_id" value="{{ $card->id }}" {{ $card->is_primary ? 'checked' : '' }}>
                            <span class="saved-card-label">
                                {{ \App\Models\PaymentMethod::brandIcon($card->brand) }} •••• {{ $card->last_four }}
                                · {{ $card->holder_name }}
                            </span>
                            @if($card->is_primary)
                                <span class="saved-card-badge">{{ __('messages.primary') }}</span>
                            @endif
                        </label>
                        @endforeach
                    @else
                        <p style="font-size: 12px; color: rgba(255,255,255,0.3); text-align: center; padding: 12px 0;">
                            {{ __('messages.no_cards') }}
                        </p>
                    @endif
                </div>

                <button type="submit" class="btn-finalizar">{{ __('messages.finalize_purchase') }}</button>
            </div>

        </div>
    </form>
</div>

<footer>
    <div class="footer-inner">
        <a href="/" class="footer-logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone" onerror="this.style.display='none'">
        </a>
        <div class="footer-bottom">
            <span class="footer-copy">© {{ date('Y') }} GiftZone {{ __('messages.all_rights') }}</span>
            <div class="social-links">
                <a href="#" aria-label="Instagram"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.336 3.608 1.311.975.975 1.249 2.242 1.311 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.336 2.633-1.311 3.608-.975.975-2.242 1.249-3.608 1.311-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.336-3.608-1.311-.975-.975-1.249-2.242-1.311-3.608C2.175 15.584 2.163 15.204 2.163 12s.012-3.584.07-4.85c.062-1.366.336-2.633 1.311-3.608C4.519 2.567 5.786 2.293 7.152 2.231 8.418 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.014 7.052.072 5.197.157 3.355.673 2.014 2.014.673 3.355.157 5.197.072 7.052.014 8.332 0 8.741 0 12c0 3.259.014 3.668.072 4.948.085 1.855.601 3.697 1.942 5.038 1.341 1.341 3.183 1.857 5.038 1.942C8.332 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 1.855-.085 3.697-.601 5.038-1.942 1.341-1.341 1.857-3.183 1.942-5.038C23.986 15.668 24 15.259 24 12c0-3.259-.014-3.668-.072-4.948-.085-1.855-.601-3.697-1.942-5.038C20.645.673 18.803.157 16.948.072 15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg></a>
                <a href="#" aria-label="Twitter"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                <a href="#" aria-label="Facebook"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073C24 5.404 18.627 0 12 0S0 5.404 0 12.073c0 6.031 4.388 11.031 10.125 11.927v-8.437H7.078v-3.49h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796v8.437C19.612 23.104 24 18.104 24 12.073z"/></svg></a>
                <a href="#" aria-label="Discord"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg></a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Menu lateral
    const menuBtn = document.getElementById('menuBtn');
    const closeBtn = document.getElementById('closeBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    function toggleMenu() { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); }
    menuBtn.addEventListener('click', toggleMenu);
    closeBtn.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    // Mostra/esconde cartões salvos conforme o método selecionado
    function toggleSavedCards() {
        const selected = document.querySelector('input[name="metodo"]:checked');
        const section = document.getElementById('savedCardsSection');

        if (selected && (selected.value === 'credito' || selected.value === 'debito')) {
            section.classList.add('visible');
        } else {
            section.classList.remove('visible');
            // Desmarca cartão selecionado se mudar para pix/boleto
            document.querySelectorAll('input[name="card_id"]').forEach(r => r.checked = false);
        }
    }
</script>

</body>
</html>