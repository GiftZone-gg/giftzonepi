<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone - Catálogo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark:   #002830;
            --mid:    #005363;
            --accent: #FDE9A2;
            --white:  #ffffff;
            --radius: 9999px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #002830;
            color: white;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 32px;
            background: rgba(0, 40, 48, 0.85);
            backdrop-filter: blur(8px);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(253, 233, 162, 0.12);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .hamburger {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--white);
            border-radius: 2px;
            transition: background .2s;
        }
        .hamburger:hover span { background: var(--accent); }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .logo img { height: 36px; width: auto; }
        .logo-text {
            font-family: 'Inter', sans-serif;
            font-weight: 900;
            font-size: 1.6rem;
            color: var(--accent);
            letter-spacing: 1px;
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

        /* ── HERO / BANNER ── */
        .hero {
            position: relative;
            overflow: hidden;
            padding: 72px 24px 64px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 28px;
            background: linear-gradient(160deg, var(--dark) 0%, var(--mid) 60%, #003d4a 100%);
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0,83,99,.55) 0%, transparent 70%);
            top: -120px;
            right: -100px;
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(253,233,162,.06) 0%, transparent 70%);
            bottom: -80px;
            left: -60px;
            pointer-events: none;
        }

        /* ── TÍTULO HERO — Gasoek One igual ao Figma ── */
        .hero-title {
            font-family: 'Gasoek One', sans-serif;
            font-size: clamp(3rem, 8vw, 5rem);
            font-weight: 400; /* Gasoek One já é display black */
            color: var(--accent);
            letter-spacing: 6px;
            text-align: center;
            line-height: 1;
            animation: fadeDown .5s ease both;
        }

        /* ── SEARCH BAR ── */
        .search-wrapper {
            width: 100%;
            max-width: 520px;
            position: relative;
            animation: fadeDown .55s .08s ease both;
        }
        .search-wrapper input {
            width: 100%;
            padding: 14px 48px 14px 20px;
            border-radius: var(--radius);
            border: none;
            background: #e8f0ee;
            color: #222;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            outline: none;
            transition: box-shadow .2s;
        }
        .search-wrapper input::placeholder { color: #7a9a94; }
        .search-wrapper input:focus { box-shadow: 0 0 0 3px rgba(253,233,162,.45); }
        .search-wrapper .icon-search {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #7a9a94;
            pointer-events: none;
        }

        /* ── FILTROS ── */
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            animation: fadeDown .6s .14s ease both;
        }
        .filter-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: var(--radius);
            background: var(--mid);
            border: none;
            color: var(--white);
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: .85rem;
            cursor: pointer;
            transition: background .2s, color .2s, transform .15s;
        }
        .filter-btn svg { flex-shrink: 0; }
        .filter-btn:hover {
            background: var(--accent);
            color: var(--dark);
            transform: translateY(-2px);
        }

        /* ── GRID DE JOGOS ── */
        main {
            flex: 1;
            padding: 48px 32px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        /* ── TÍTULO DA SEÇÃO — Inter Black ── */
        .section-title {
            font-family: 'Inter', sans-serif;
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--accent);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .games-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 24px;
        }

        .game-card {
            background: var(--mid);
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s;
            border: 1px solid rgba(253,233,162,.1);
            animation: fadeUp .5s ease both;
        }
        .game-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(0,0,0,.4);
        }
        .game-card img {
            width: 100%;
            aspect-ratio: 3/2;
            object-fit: cover;
            display: block;
        }
        .game-card .thumb-placeholder {
            width: 100%;
            aspect-ratio: 3/2;
            background: linear-gradient(135deg, #003d4a 0%, #005363 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(253,233,162,.3);
            font-size: 2.5rem;
        }
        .card-body { padding: 14px 16px; }
        .card-body h3 {
            font-size: .95rem;
            font-weight: 700;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .card-body .price {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--accent);
            letter-spacing: 1px;
        }
        .card-body .badge {
            display: inline-block;
            font-size: .7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--radius);
            background: rgba(253,233,162,.15);
            color: var(--accent);
            margin-bottom: 6px;
        }

        /* ── ESTADO VAZIO ── */
        .empty-state {
            text-align: center;
            padding: 80px 24px;
            opacity: .7;
        }
        .empty-state svg { margin-bottom: 16px; }
        .empty-state p { font-size: 1rem; color: #a8c8c4; }

        /* ── FOOTER ── */
        footer {
            background: #001e25;
            border-top: 1px solid rgba(253,233,162,.1);
            padding: 28px 32px;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .footer-logo img { height: 30px; }
        .footer-logo span {
            font-family: 'Inter', sans-serif;
            font-weight: 900;
            font-size: 1.4rem;
            color: var(--accent);
        }
        .footer-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-copy { font-size: .78rem; color: #6a9a94; }
        .social-links { display: flex; gap: 14px; }
        .social-links a {
            color: #6a9a94;
            text-decoration: none;
            transition: color .2s;
        }
        .social-links a:hover { color: var(--accent); }

        /* ── ANIMAÇÕES ── */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVO ── */
        @media (max-width: 600px) {
            nav { padding: 12px 16px; }
            main { padding: 32px 16px; }
            footer { padding: 24px 16px; }
            .footer-bottom { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-left">
        <button class="hamburger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <a href="/" class="logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone Logo"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
        </a>
    </div>
    <a href="{{ route('login') }}">
        <button class="btn-entrar">Entrar</button>
    </a>
</nav>

<section class="hero">
    <h1 class="hero-title">CATÁLOGO</h1>

    <div class="search-wrapper">
        <input type="text" id="searchInput" placeholder="Digite seu jogo aqui...">
        <svg class="icon-search" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
        </svg>
    </div>

    <div class="filters">
        @foreach(['Categoria','Plataforma','Preço','Sistema','Listar por'] as $filtro)
        <button class="filter-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                 fill="currentColor" viewBox="0 0 16 16">
                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1
                         1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
            </svg>
            {{ $filtro }}
        </button>
        @endforeach
    </div>
</section>

<main>
    <h2 class="section-title">Jogos Disponíveis</h2>

    @if(isset($jogos) && count($jogos) > 0)
        <div class="games-grid" id="gamesGrid">
            @foreach($jogos as $jogo)
            <div class="game-card" data-name="{{ strtolower($jogo->nome) }}">
                @if($jogo->imagem)
                    <img src="{{ asset('images/' . $jogo->imagem) }}" alt="{{ $jogo->nome }}">
                @else
                    <div class="thumb-placeholder">🎮</div>
                @endif
                <div class="card-body">
                    @if(isset($jogo->plataforma))
                        <span class="badge">{{ $jogo->plataforma }}</span>
                    @endif
                    <h3>{{ $jogo->nome }}</h3>
                    <div class="price">
                        R$ {{ number_format($jogo->preco, 2, ',', '.') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56"
                 fill="none" viewBox="0 0 24 24" stroke="#FDE9A2" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
            <p>Nenhum jogo encontrado.<br>Tente outro termo ou ajuste os filtros.</p>
        </div>
    @endif
</main>

<footer>
    <div class="footer-inner">
        <a href="/" class="footer-logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone"
                 onerror="this.style.display='none'">
        </a>
        <div class="footer-bottom">
            <span class="footer-copy">© 2026 GiftZone Todos direitos Reservados</span>
            <div class="social-links">
                <a href="#" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.336 3.608 1.311.975.975 1.249 2.242 1.311 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.336 2.633-1.311 3.608-.975.975-2.242 1.249-3.608 1.311-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.336-3.608-1.311-.975-.975-1.249-2.242-1.311-3.608C2.175 15.584 2.163 15.204 2.163 12s.012-3.584.07-4.85c.062-1.366.336-2.633 1.311-3.608C4.519 2.567 5.786 2.293 7.152 2.231 8.418 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.014 7.052.072 5.197.157 3.355.673 2.014 2.014.673 3.355.157 5.197.072 7.052.014 8.332 0 8.741 0 12c0 3.259.014 3.668.072 4.948.085 1.855.601 3.697 1.942 5.038 1.341 1.341 3.183 1.857 5.038 1.942C8.332 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 1.855-.085 3.697-.601 5.038-1.942 1.341-1.341 1.857-3.183 1.942-5.038C23.986 15.668 24 15.259 24 12c0-3.259-.014-3.668-.072-4.948-.085-1.855-.601-3.697-1.942-5.038C20.645.673 18.803.157 16.948.072 15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073C24 5.404 18.627 0 12 0S0 5.404 0 12.073c0 6.031 4.388 11.031 10.125 11.927v-8.437H7.078v-3.49h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796v8.437C19.612 23.104 24 18.104 24 12.073z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Discord">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

<script>
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('#gamesGrid .game-card').forEach(card => {
                const name = card.dataset.name || '';
                card.style.display = name.includes(q) ? '' : 'none';
            });
        });
    }
</script>

</body>
</html>
