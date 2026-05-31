<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone - Carrinho</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Gasoek+One&family=Inria+Sans:ital,wght@1,700&display=swap" rel="stylesheet">
    <style>
        /* --- Reset e Base --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #002830;
            color: white;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 96px 60px 60px 60px;
        }

        /* --- Sidebar --- */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background-color: #001A20;
            z-index: 1000;
            transition: all 0.3s ease;
            padding: 32px 24px;
            display: flex;
            flex-direction: column;
            box-shadow: 5px 0 15px rgba(0,0,0,0.3);
        }

        .sidebar.active { left: 0; }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .close-menu {
            cursor: pointer;
            font-size: 24px;
            background: none;
            border: none;
            color: white;
        }

        .menu-group {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 0;
        }

        .menu-item {
            display: block;
            color: white;
            text-decoration: none;
            font-family: 'Gasoek One', sans-serif;
            font-size: 16px;
            margin-bottom: 20px;
            font-weight: 400;
            transition: color 0.3s;
            text-transform: uppercase;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .menu-item:hover { color: #FFDC74; }

        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            z-index: 999;
        }

        .sidebar-overlay.active { display: block; }

        /* --- Header --- */
        header {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: rgba(0, 40, 48, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 998;
            opacity: 0.85;
            transition: opacity 0.3s ease, background-color 0.3s ease;
        }

        header:hover {
            opacity: 1;
            background: rgba(0, 40, 48, 0.95);
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .hamburger {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: none;
            border: none;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background-color: white;
        }

        .logo-box {
            height: 44px;
            display: flex;
            align-items: center;
        }

        .logo-box img { height: 100%; width: 160px; }

        .nav-avatar-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .nav-avatar-container:hover { transform: scale(1.08); }

        .nav-avatar-mini {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid #FFDC74;
            object-fit: cover;
            background: #1F6D7E;
            box-shadow: 0 0 12px rgba(245, 200, 66, 0.3);
        }

        .btn-entrar {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px 36px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 18px;
            transition: all 0.3s ease;
            background: transparent;
            cursor: pointer;
        }

        .btn-entrar:hover { background: rgba(255, 255, 255, 0.1); }

        /* ===================== */
        /* --- CARRINHO ---      */
        /* ===================== */

        .cart-page-title {
            font-family: 'Gasoek One', sans-serif;
            font-size: 36px;
            font-weight: 400;
            color: #FDE9A2;
            margin-bottom: 28px;
            margin-top: 12px;
        }

        /* Alertas */
        .alert {
            border-radius: 10px;
            padding: 12px 18px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success { background: rgba(46, 125, 50, 0.3); border: 1px solid #2e7d32; color: #a5d6a7; }
        .alert-error   { background: rgba(198, 40, 40, 0.3); border: 1px solid #c62828; color: #ef9a9a; }

        /* Layout principal do carrinho */
        .cart-wrapper {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 28px;
            align-items: start;
        }

        /* Painel esquerdo - lista de itens */
        .cart-panel {
            background: #001A20;
            border-radius: 20px;
            padding: 28px;
        }

        .cart-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .cart-panel-title {
            font-family: 'Gasoek One', sans-serif;
            font-size: 18px;
            font-weight: 400;
            color: #FDE9A2;
            text-transform: uppercase;
        }

        /* Botão remover tudo */
        .btn-remove-all {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid rgba(231, 76, 60, 0.4);
            color: #e74c3c;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-remove-all:hover {
            background: rgba(231, 76, 60, 0.3);
            border-color: #e74c3c;
        }

        /* Item do carrinho */
        .cart-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 18px 0;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .cart-item:last-child { border-bottom: none; }

        /* Thumbnail da plataforma */
        .item-thumb {
            width: 130px;
            min-width: 130px;
            height: 80px;
            border-radius: 10px;
            background: #1F6D7E;
            overflow: hidden;
            position: relative;
        }

        .item-thumb-badge {
            position: absolute;
            top: 0; left: 0; right: 0;
            text-align: center;
            font-size: 9px;
            font-weight: 700;
            padding: 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .item-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Info do item */
        .item-info { flex: 1; }

        .item-name {
            font-family: 'Gasoek One', sans-serif;
            font-size: 15px;
            font-weight: 400;
            color: white;
            margin-bottom: 4px;
        }

        .item-platform {
            font-size: 12px;
            color: #6a9a94;
            margin-bottom: 12px;
        }

        /* Controle de quantidade inline */
        .item-qty-form {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .qty-input {
            width: 56px;
            background: #002830;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            color: white;
            font-size: 14px;
            text-align: center;
            padding: 6px 8px;
            outline: none;
            transition: border-color 0.2s;
        }

        .qty-input:focus { border-color: #FFDC74; }

        .btn-qty-update {
            background: rgba(52, 152, 219, 0.2);
            border: 1px solid rgba(52, 152, 219, 0.4);
            color: #3498db;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-qty-update:hover {
            background: rgba(52, 152, 219, 0.35);
        }

        /* Preço e remover */
        .item-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
        }

        .item-price {
            font-family: 'Gasoek One', sans-serif;
            font-size: 18px;
            color: #FFDC74;
        }

        .btn-remove {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.3);
            border-radius: 8px;
            cursor: pointer;
            color: #e74c3c;
            transition: all 0.2s;
        }

        .btn-remove:hover {
            background: rgba(231, 76, 60, 0.25);
            border-color: #e74c3c;
        }

        .btn-remove svg { width: 16px; height: 16px; }

        /* ---- Painel direito: resumo ---- */
        .cart-summary {
            background: #001A20;
            border-radius: 20px;
            padding: 28px;
            position: sticky;
            top: 110px;
        }

        .summary-title {
            font-family: 'Gasoek One', sans-serif;
            font-size: 18px;
            font-weight: 400;
            color: #FDE9A2;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        /* Campo de cupom */
        .coupon-area {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
        }

        .coupon-input {
            flex: 1;
            background: #002830;
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            color: white;
            font-size: 13px;
            padding: 10px 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .coupon-input::placeholder { color: #4a7a74; }
        .coupon-input:focus { border-color: #FFDC74; }

        .btn-coupon {
            background: #FFDC74;
            color: #001A20;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .btn-coupon:hover { background: #ffe99e; }

        /* Linha subtotal */
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            font-size: 14px;
            color: #a0c4bf;
        }

        .summary-row:last-of-type { border-bottom: none; }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0 20px;
            font-family: 'Gasoek One', sans-serif;
            font-size: 22px;
            font-weight: 400;
            color: white;
            border-top: 1px solid rgba(255,255,255,0.12);
            margin-top: 8px;
        }

        .summary-total span:last-child { color: #FFDC74; }

        /* Botão finalizar */
        .btn-finalizar {
            display: block;
            width: 100%;
            background: #FFDC74;
            color: #001A20;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-family: 'Gasoek One', sans-serif;
            font-size: 18px;
            font-weight: 400;
            text-align: center;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            margin-bottom: 12px;
        }

        .btn-finalizar:hover {
            background: #ffe99e;
            transform: translateY(-2px);
        }

        .btn-continuar {
            display: block;
            width: 100%;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.7);
            border-radius: 12px;
            padding: 14px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-continuar:hover {
            border-color: rgba(255,255,255,0.5);
            color: white;
        }

        /* Carrinho vazio */
        .empty-cart {
            text-align: center;
            padding: 80px 20px;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            opacity: 0.3;
        }

        .empty-cart h2 {
            font-family: 'Gasoek One', sans-serif;
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 12px;
            color: #FDE9A2;
        }

        .empty-cart p {
            color: #6a9a94;
            margin-bottom: 28px;
            font-size: 15px;
        }

        .btn-ir-catalogo {
            display: inline-block;
            background: #FFDC74;
            color: #001A20;
            border-radius: 12px;
            padding: 14px 36px;
            font-family: 'Gasoek One', sans-serif;
            font-size: 16px;
            font-weight: 400;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-ir-catalogo:hover {
            background: #ffe99e;
            transform: translateY(-2px);
        }

        /* FOOTER */
        footer { background: #001e25; border-top: 1px solid rgba(253,233,162,.1); padding: 28px 32px; margin-top: 60px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
        .footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .footer-logo img { height: 30px; }
        .footer-bottom { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .footer-copy { font-size: .78rem; color: #6a9a94; }
        .social-links { display: flex; gap: 14px; }
        .social-links a { color: #6a9a94; text-decoration: none; transition: color .2s; }
        .social-links a:hover { color: #FFDC74; }

        /* Responsividade */
        @media (max-width: 900px) {
            .container { padding: 96px 20px 40px; }
            .cart-wrapper { grid-template-columns: 1fr; }
            .cart-summary { position: static; }
            header { padding: 20px 20px; }
        }

        @media (max-width: 600px) {
            .item-thumb { width: 80px; min-width: 80px; height: 56px; }
            .item-name { font-size: 13px; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay"></div>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <button class="close-menu" id="closeBtn">✕</button>
    </div>

    <nav class="menu-group">
        <a href="{{ route('home') }}" class="menu-item">Início</a>
        <a href="{{ route('catalogo') }}" class="menu-item">Catálogo</a>
        <a href="#" class="menu-item">Ofertas</a>
    </nav>

    <nav class="menu-group">
        <a href="{{ route('catalogo', ['plataforma' => 'PlayStation 5']) }}" class="menu-item">Playstation</a>
        <a href="{{ route('catalogo', ['plataforma' => 'Xbox']) }}" class="menu-item">Xbox</a>
        <a href="{{ route('catalogo', ['plataforma' => 'Nintendo Switch']) }}" class="menu-item">Nintendo</a>
        <a href="{{ route('catalogo', ['plataforma' => 'PC']) }}" class="menu-item">Steam</a>
    </nav>

    <nav class="menu-group">
        @auth
            <form action="{{ route('logout') }}" method="POST" id="logout-form-side">
                @csrf
                <button type="submit" class="menu-item">Sair ({{ Auth::user()->name }})</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="menu-item">Entrar</a>
        @endauth
    </nav>
</aside>

<header>
    <div class="logo-area">
        <button class="hamburger" id="menuBtn">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="logo-box">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone Logo">
            </a>
        </div>
    </div>

    <div class="user-auth-area">
        @auth
            <a href="{{ route('usuario.perfil') }}" class="nav-avatar-container" title="Meu Perfil">
                <img class="nav-avatar-mini"
                     src="{{ Auth::user()->avatar === 'icone1.svg' || empty(Auth::user()->avatar) ? asset('images/icone1.svg') : asset('storage/' . Auth::user()->avatar) }}"
                     alt="Avatar de {{ Auth::user()->name }}"
                     onerror="this.src='https://via.placeholder.com/44/1F6D7E/FFDC74?text=GZ'">
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-entrar">Entrar</a>
        @endauth
    </div>
</header>

<div class="container">

    <h1 class="cart-page-title">Meu carrinho</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if(empty($itens))
        {{-- ===== CARRINHO VAZIO ===== --}}
        <div class="empty-cart">
            <svg class="empty-icon" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 10h8l10 40h34l6-28H26" stroke="#FFDC74" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="36" cy="62" r="4" fill="#FFDC74"/>
                <circle cx="56" cy="62" r="4" fill="#FFDC74"/>
            </svg>
            <h2>Seu carrinho está vazio</h2>
            <p>Adicione jogos do catálogo e eles aparecerão aqui.</p>
            <a href="{{ route('catalogo') }}" class="btn-ir-catalogo">Ver catálogo</a>
        </div>

    @else
        {{-- ===== CARRINHO COM ITENS ===== --}}
        <div class="cart-wrapper">

            {{-- Painel esquerdo: lista --}}
            <div class="cart-panel">
                <div class="cart-panel-header">
                    <span class="cart-panel-title">Meus itens</span>
                    {{-- Botão visual "Remover tudo" — mantém a rota original se existir --}}
                    @if(Route::has('carrinho.limpar'))
                    <form action="{{ route('carrinho.limpar') }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-remove-all">
                            <svg viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            Remover tudo
                        </button>
                    </form>
                    @endif
                </div>

                @foreach($itens as $item)
                <div class="cart-item">
                    {{-- Thumbnail com badge de plataforma --}}
                    <div class="item-thumb">
                        <div class="item-thumb-badge"
                             style="background: {{ $item['produto']->plataforma === 'PlayStation 5' || $item['produto']->plataforma === 'PlayStation 4' ? '#0066CC' : ($item['produto']->plataforma === 'Nintendo Switch' ? '#E60012' : '#1B4F5E') }};">
                            {{ $item['produto']->plataforma }}
                        </div>
                        @if($item['produto']->imagem_principal)
                            <img class="item-thumb-img"
                                 src="{{ asset('images/' . $item['produto']->imagem_principal) }}"
                                 alt="{{ $item['produto']->nome }}"
                                 onerror="this.style.display='none'">
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="item-info">
                        <div class="item-name">{{ $item['produto']->nome }}</div>
                        <div class="item-platform">{{ $item['produto']->plataforma }}</div>

                        <form action="{{ route('carrinho.atualizar', $item['produto']->id) }}" method="POST" class="item-qty-form">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantidade" value="{{ $item['quantidade'] }}" min="1" max="3" class="qty-input">
                            <button type="submit" class="btn-qty-update">Atualizar</button>
                        </form>
                    </div>

                    {{-- Preço e remover --}}
                    <div class="item-right">
                        <span class="item-price">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</span>

                        <form action="{{ route('carrinho.remover', $item['produto']->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-remove" title="Remover item">
                                <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Painel direito: resumo --}}
            <div class="cart-summary">
                <div class="summary-title">Resumo</div>

                {{-- Campo de cupom (visual do Figma) --}}
                <div class="coupon-area">
                    <input type="text" class="coupon-input" placeholder="Digite seu cupom ou código de criador">
                    <button class="btn-coupon">Aplicar</button>
                </div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>

                <div class="summary-total">
                    <span>Total</span>
                    <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                </div>

                <form action="{{ route('carrinho.finalizar') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-finalizar">Finalizar compra</button>
                </form>

                <a href="{{ route('catalogo') }}" class="btn-continuar">← Continuar comprando</a>
            </div>

        </div>
    @endif

</div>

<footer>
    <div class="footer-inner">
        <a href="/" class="footer-logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone" onerror="this.style.display='none'">
        </a>
        <div class="footer-bottom">
            <span class="footer-copy">© {{ date('Y') }} GiftZone Todos direitos Reservados</span>
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
    // Menu Lateral
    const menuBtn = document.getElementById('menuBtn');
    const closeBtn = document.getElementById('closeBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    function toggleMenu() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    menuBtn.addEventListener('click', toggleMenu);
    closeBtn.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);
</script>

</body>
</html>