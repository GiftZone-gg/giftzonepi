<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone | Meus Pagamentos</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Gasoek+One&family=Inria+Sans:ital,wght@1,700&display=swap" rel="stylesheet">
    <style>
        /* --- Reset e Base --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            padding: 0 60px;
        }

        /* --- Sidebar (Menu Lateral) --- */
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

        .sidebar.active {
            left: 0;
        }

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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            z-index: 999;
        }

        .sidebar-overlay.active { display: block; }

        /* --- Header --- */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 32px 0;
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

        .logo-box img {
            height: 100%;
            width: auto;
        }

        .user-auth-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-name-display {
            font-family: 'Gasoek One';
            color: #FFDC74;
            font-size: 16px;
            text-transform: uppercase;
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

        .btn-entrar:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* --- Banner Hero (mantido para consistência, mas não usado nessa página) --- */
        .hero-banner {
            width: 100%;
            aspect-ratio: 1688 / 803;
            border-radius: 24px;
            margin-bottom: 56px;
            position: relative;
            overflow: hidden;
        }

        .carousel-container, .carousel-slides { width: 100%; height: 100%; position: relative; }

        .carousel-slide {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover; background-position: center;
            opacity: 0; transition: opacity 0.6s ease;
        }

        .carousel-slide.active { opacity: 1; }

        .slide-content { position: absolute; bottom: 15%; left: 8%; z-index: 5; }

        .slide-platform { font-family: 'Gasoek One', sans-serif; font-size: 18px; color: #FFDC74; margin-bottom: 12px; font-weight: 400; }

        .slide-title { font-family: 'Gasoek One', sans-serif; font-size: 56px; line-height: 1.1; margin-bottom: 8px; font-weight: 400; }

        .slide-subtitle { font-size: 24px; font-weight: 400; margin-bottom: 16px; color: rgba(255,255,255,0.8); }

        .slide-badge {
            display: inline-block;
            background-color: #0066CC;
            padding: 8px 24px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 14px;
        }

        .carousel-thumbnails { position: absolute; bottom: 24px; right: 24px; display: flex; gap: 16px; z-index: 10; }

        .thumbnail {
            width: 140px; height: 78px; border-radius: 12px;
            background-size: cover; cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.2); opacity: 0.7;
        }

        .thumbnail.active { border-color: white; opacity: 1; }

        /* --- Seções --- */
        section { margin-bottom: 64px; }

        .section-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 28px; }

        .section-title { font-family: 'Gasoek One', sans-serif; font-size: 32px; color: #FDE9A2; font-weight: 400; }

        .ver-mais { font-family: 'Inria Sans', sans-serif; font-style: italic; font-weight: 700; color: #FFDC74; text-decoration: none; }

        .grid-games { display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px; }

        .card {
            position: relative; background: #001A20; border-radius: 20px;
            overflow: hidden; aspect-ratio: 388 / 287; transition: transform 0.3s ease;
        }

        .card:hover { transform: translateY(-8px); }

        .card-header {
            position: absolute; top: 0; left: 0; width: 100%; padding: 12px 0;
            text-align: center; font-weight: 600; font-size: 13px; z-index: 2; text-transform: uppercase;
        }

        .card-img { width: 100%; height: 100%; background-size: cover; background-position: center; }

        .bg-ps { background-color: #0066CC; }
        .bg-steam { background-color: #1B4F5E; }
        .bg-nintendo { background-color: #E60012; }

        /* --- Estilos específicos para a página de pagamentos --- */
        .pagamentos-container {
            background: #001A20;
            border-radius: 24px;
            padding: 40px;
            margin: 30px 0;
        }

        .pagamentos-titulo {
            font-family: 'Gasoek One', sans-serif;
            font-size: 28px;
            color: #FFDC74;
            margin-bottom: 24px;
        }

        .pagamentos-subtitulo {
            font-size: 18px;
            margin-bottom: 20px;
            color: #ccc;
        }

        /* --- Footer --- */
        footer { border-top: 1px solid #FFDC74; padding: 60px 0 30px 0; margin-top: 40px; }

        .footer-info { display: flex; justify-content: space-between; align-items: center; }

        .social-links { display: flex; gap: 20px; }

        .social-links img { width: 24px; filter: brightness(0) invert(1); opacity: 0.7; }

        /* Responsividade */
        @media (max-width: 900px) {
            .grid-games { grid-template-columns: repeat(2, 1fr); }
            .container { padding: 0 20px; }
            .slide-title { font-size: 32px; }
            .thumbnail { width: 80px; height: 50px; }
        }

        @media (max-width: 600px) {
            .grid-games { grid-template-columns: 1fr; }
            .btn-entrar { padding: 8px 20px; font-size: 14px; }
            .footer-info { flex-direction: column; gap: 20px; text-align: center; }
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
        <a href="#" class="menu-item">Playstation</a>
        <a href="#" class="menu-item">Xbox</a>
        <a href="#" class="menu-item">Nintendo</a>
        <a href="#" class="menu-item">Steam</a>
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

<div class="container">
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
                <span class="user-name-display">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-entrar">Sair</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-entrar">Entrar</a>
            @endauth
        </div>
    </header>

    <!-- PÁGINA DE PAGAMENTOS (conteúdo principal) -->
    <div class="pagamentos-container">
    <h1 class="pagamentos-titulo">Meus Métodos de Pagamento</h1>
    <p class="pagamentos-subtitulo">Gerencie seus cartões e formas de pagamento.</p>

    @if($metodos->count() > 0)
        @foreach($metodos as $metodo)
            <div style="background: #002830; padding: 15px; border-radius: 12px; margin-bottom: 10px;">
                <strong>{{ $metodo->card_brand }}</strong> - Final {{ $metodo->card_last_digits }}
                @if($metodo->is_default)
                    <span style="background: #FFDC74; color: #002830; padding: 2px 8px; border-radius: 20px; font-size: 12px;">Padrão</span>
                @endif
            </div>
        @endforeach
    @else
        <p style="margin-top: 20px; color: #bbb;">Nenhum método de pagamento cadastrado ainda.</p>
    @endif
</div>

    <!-- Você pode adicionar mais seções conforme evoluir -->
</div>

<footer>
    <div class="container">
        <div class="footer-info">
            <p>© 2026 GiftZone Todos direitos Reservados</p>
            <div class="social-links">
                <a href="#"><img src="https://cdn-icons-webp.flaticon.com/512/1384/1384063.webp" alt="IG"></a>
                <a href="#"><img src="https://cdn-icons-webp.flaticon.com/512/733/733579.webp" alt="X"></a>
                <a href="#"><img src="https://cdn-icons-webp.flaticon.com/512/1384/1384060.webp" alt="YT"></a>
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