<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone | Home</title>
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
            font-weight: 400; /* Menos bold */
            transition: color 0.3s;
            text-transform: uppercase;
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

        .btn-entrar {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px 36px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: 500; /* Reduzido de 600 */
            font-size: 18px;
            transition: all 0.3s ease;
            background: transparent;
            cursor: pointer;
        }

        /* --- Banner Hero --- */
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
        <a href="#" class="menu-item">Início</a>
        <a href="#" class="menu-item">Catálogo</a>
        <a href="#" class="menu-item">Ofertas</a>
    </nav>

    <nav class="menu-group">
        <a href="#" class="menu-item">Playstation</a>
        <a href="#" class="menu-item">Xbox</a>
        <a href="#" class="menu-item">Nintendo</a>
        <a href="#" class="menu-item">Steam</a>
    </nav>

    <nav class="menu-group">
        <a href="#" class="menu-item">Entrar</a>
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
                <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone Logo">
            </div>
        </div>
        <a href="#" class="btn-entrar">Entrar</a>
    </header>

    <div class="hero-banner">
        <div class="carousel-container">
            <div class="carousel-slides">
                <div class="carousel-slide active" style="background-image: url('{{ asset('images/deathstran2.png') }}');">
                    <div class="slide-content">
                        <div class="slide-platform">PS5</div>
                        <div class="slide-title">DEATH STRANDING 2</div>
                        <div class="slide-subtitle">ON THE BEACH</div>
                        <div class="slide-badge">JÁ DISPONÍVEL</div>
                    </div>
                </div>
                <div class="carousel-slide" style="background-image: url('{{ asset('images/sillenthillF.png') }}');">
                    <div class="slide-content">
                        <div class="slide-platform">PS5 / PC</div>
                        <div class="slide-title">SILENT HILL</div>
                        <div class="slide-subtitle">RETORNO AO PESADELO</div>
                        <div class="slide-badge">EM BREVE</div>
                    </div>
                </div>
                <div class="carousel-slide" style="background-image: url('{{ asset('images/exped33.png') }}');">
                    <div class="slide-content">
                        <div class="slide-platform">XBOX / PC</div>
                        <div class="slide-title">EXPEDIÇÃO</div>
                        <div class="slide-subtitle">A GRANDE AVENTURA</div>
                        <div class="slide-badge">PRÉ-VENDA AGORA</div>
                    </div>
                </div>
            </div>
            <div class="carousel-thumbnails">
                <div class="thumbnail active" style="background-image: url('{{ asset('images/deathstran2.png') }}');" onclick="currentSlide(0)"></div>
                <div class="thumbnail" style="background-image: url('{{ asset('images/sillenthillF.png') }}');" onclick="currentSlide(1)"></div>
                <div class="thumbnail" style="background-image: url('{{ asset('images/exped33.png') }}');" onclick="currentSlide(2)"></div>
            </div>
        </div>
    </div>

    <section>
        <div class="section-header">
            <h2 class="section-title">Mais Procurados</h2>
            <a href="#" class="ver-mais">Ver Mais →</a>
        </div>
        <div class="grid-games">
            <div class="card">
                <div class="card-header bg-ps">PLAYSTATION STORE</div>
                <div class="card-img" style="background-image: url('{{ asset('images/deathstra.png') }}');"></div>
            </div>
            <div class="card">
                <div class="card-header bg-steam">STEAM</div>
                <div class="card-img" style="background-image: url('{{ asset('images/bf6.png') }}');"></div>
            </div>
            <div class="card">
                <div class="card-header bg-nintendo">NINTENDO ESHOP</div>
                <div class="card-img" style="background-image: url('{{ asset('images/pokemon.png') }}');"></div>
            </div>
        </div>
    </section>

    <section>
        <div class="section-header">
            <h2 class="section-title">Jogos PlayStation</h2>
            <a href="#" class="ver-mais">Ver Mais →</a>
        </div>
        <div class="grid-games">
            <div class="card">
                <div class="card-header bg-ps">PLAYSTATION STORE</div>
                <div class="card-img" style="background-image: url('{{ asset('images/ghost.png') }}');"></div>
            </div>
            <div class="card">
                <div class="card-header bg-ps">PLAYSTATION STORE</div>
                <div class="card-img" style="background-image: url('{{ asset('images/resevil.png') }}');"></div>
            </div>
            <div class="card">
                <div class="card-header bg-ps">PLAYSTATION STORE</div>
                <div class="card-img" style="background-image: url('{{ asset('images/gow.png') }}');"></div>
            </div>
        </div>
    </section>

    <section>
        <div class="section-header">
            <h2 class="section-title">Jogos Steam</h2>
            <a href="#" class="ver-mais">Ver Mais →</a>
        </div>
        <div class="grid-games">
            <div class="card">
                <div class="card-header bg-steam">STEAM</div>
                <div class="card-img" style="background-image: url('{{ asset('images/tlou.png') }}');"></div>
            </div>
            <div class="card">
                <div class="card-header bg-steam">STEAM</div>
                <div class="card-img" style="background-image: url('{{ asset('images/hunt.png') }}');"></div>
            </div>
            <div class="card">
                <div class="card-header bg-steam">STEAM</div>
                <div class="card-img" style="background-image: url('{{ asset('images/hogleg.png') }}');"></div>
            </div>
        </div>
    </section>
</div>

<footer>
    <div class="container">
        <div class="footer-info">
            <p>© 2026 GiftZone Todos direitos Reservados</p>
            <div class="social-links">
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384063.png" alt="IG"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="X"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/1384/1384060.png" alt="YT"></a>
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

    // Carrossel
    let currentIndex = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const thumbnails = document.querySelectorAll('.thumbnail');

    function showSlide(index) {
        if (index >= slides.length) index = 0;
        slides.forEach(s => s.classList.remove('active'));
        thumbnails.forEach(t => t.classList.remove('active'));
        slides[index].classList.add('active');
        thumbnails[index].classList.add('active');
        currentIndex = index;
    }

    function currentSlide(index) { showSlide(index); }
    setInterval(() => showSlide(currentIndex + 1), 6000);
</script>

</body>
</html>