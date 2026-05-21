<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone - Catálogo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        :root { --dark: #002830; --mid: #005363; --accent: #FDE9A2; --white: #ffffff; --radius: 9999px; }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #002830; color: white; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        nav { display: flex; align-items: center; justify-content: space-between; padding: 14px 32px; background: rgba(0,40,48,0.85); backdrop-filter: blur(8px); position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(253,233,162,0.12); }
        .nav-left { display: flex; align-items: center; gap: 16px; }
        .hamburger { background: none; border: none; cursor: pointer; display: flex; flex-direction: column; gap: 5px; }
        .hamburger span { display: block; width: 22px; height: 2px; background: var(--white); border-radius: 2px; transition: background .2s; }
        .hamburger:hover span { background: var(--accent); }
        .logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .logo img { height: 36px; width: auto; }
        .btn-entrar { border: 1px solid rgba(255,255,255,0.3); padding: 12px 36px; border-radius: 8px; color: white; text-decoration: none; font-weight: 500; font-size: 18px; transition: all 0.3s ease; background: transparent; cursor: pointer; }
        .btn-entrar:hover { background: rgba(255,255,255,0.1); }

        /* ===== MENU LATERAL ===== */
        .sidebar { position: fixed; top: 0; left: -280px; width: 280px; height: 100%; background-color: #001A20; z-index: 1000; transition: all 0.3s ease; padding: 32px 24px; display: flex; flex-direction: column; box-shadow: 5px 0 15px rgba(0,0,0,0.3); }
        .sidebar.active { left: 0; }
        .sidebar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .close-menu { cursor: pointer; font-size: 24px; background: none; border: none; color: white; }
        .menu-group { display: flex; flex-direction: column; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding: 20px 0; }
        .menu-item { display: block; color: white; text-decoration: none; font-family: 'Gasoek One', sans-serif; font-size: 16px; margin-bottom: 20px; font-weight: 400; transition: color 0.3s; text-transform: uppercase; cursor: pointer; border: none; background: none; width: 100%; text-align: left; }
        .menu-item:hover { color: #FFDC74; }
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); display: none; z-index: 999; }
        .sidebar-overlay.active { display: block; }

        /* Hero e filtros */
        .hero { position: relative; overflow: visible; padding: 72px 24px 64px; display: flex; flex-direction: column; align-items: center; gap: 28px; background: linear-gradient(160deg, var(--dark) 0%, var(--mid) 60%, #003d4a 100%); z-index: 10; }
        .hero::before { content: ''; position: absolute; width: 420px; height: 420px; border-radius: 50%; background: radial-gradient(circle, rgba(0,83,99,.55) 0%, transparent 70%); top: -120px; right: -100px; pointer-events: none; }
        .hero-title { font-family: 'Gasoek One', sans-serif; font-size: clamp(3rem, 8vw, 5rem); font-weight: 400; color: var(--accent); letter-spacing: 6px; text-align: center; animation: fadeDown .5s ease both; }
        .search-wrapper { width: 100%; max-width: 520px; position: relative; }
        .search-wrapper input { width: 100%; padding: 14px 48px 14px 20px; border-radius: var(--radius); border: none; background: #e8f0ee; color: #222; font-family: 'Inter', sans-serif; font-size: 1rem; outline: none; }
        .search-wrapper .icon-search { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #7a9a94; }
        .filters { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; position: relative; }
        .filter-wrapper { position: relative; }
        .filter-btn { display: flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: var(--radius); background: var(--mid); border: none; color: var(--white); font-weight: 700; cursor: pointer; transition: background .2s, color .2s; }
        .filter-btn:hover { background: var(--accent); color: var(--dark); }
        .dropdown-menu { position: absolute; top: 100%; left: 0; background: #001A20; border: 1px solid var(--accent); border-radius: 12px; padding: 8px 0; min-width: 180px; z-index: 9999; display: none; flex-direction: column; margin-top: 5px; }
        .dropdown-menu a { color: white; text-decoration: none; padding: 8px 16px; font-size: 0.85rem; display: block; }
        .dropdown-menu a:hover { background: var(--accent); color: var(--dark); }
        main { flex: 1; padding: 48px 32px; max-width: 1200px; margin: 0 auto; }
        .section-title { font-family: 'Inter', sans-serif; font-weight: 900; font-size: 1.8rem; color: var(--accent); text-transform: uppercase; margin-bottom: 24px; }
        .games-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 24px; }
        .game-card { background: var(--mid); border-radius: 16px; overflow: hidden; transition: transform .2s, box-shadow .2s; border: 1px solid rgba(253,233,162,.1); text-decoration: none; color: inherit; display: block; }
        .game-card:hover { transform: translateY(-6px); box-shadow: 0 12px 32px rgba(0,0,0,.4); }
        .game-card img { width: 100%; aspect-ratio: 3/2; object-fit: cover; display: block; }
        .card-body { padding: 14px 16px; }
        .card-body h3 { font-size: .95rem; font-weight: 700; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .card-body .price { font-weight: 800; font-size: 1.1rem; color: var(--accent); }
        .card-body .badge { display: inline-block; font-size: .7rem; font-weight: 700; padding: 2px 8px; border-radius: var(--radius); background: rgba(253,233,162,.15); color: var(--accent); margin-bottom: 6px; }
        .empty-state { text-align: center; padding: 80px 24px; opacity: .7; }


         /* FOOTER */
        footer { background: #001e25; border-top: 1px solid rgba(253,233,162,.1); padding: 28px 32px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
        .footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .footer-logo img { height: 30px; }
        .footer-logo span { font-family: 'Inter', sans-serif; font-weight: 900; font-size: 1.4rem; color: var(--accent); }
        .footer-bottom { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .footer-copy { font-size: .78rem; color: #6a9a94; }
        .social-links { display: flex; gap: 14px; }
        .social-links a { color: #6a9a94; text-decoration: none; transition: color .2s; }
        .social-links a:hover { color: var(--accent); }
        @keyframes fadeDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 600px) { nav { padding: 12px 16px; } main { padding: 32px 16px; } } footer { padding: 24px 16px; } .footer-bottom { flex-direction: column; align-items: flex-start; } }
    </style>
</head>
<body>

<!-- Sidebar overlay -->
<div class="sidebar-overlay" id="overlay"></div>

<!-- Menu lateral -->
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
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item">Sair ({{ Auth::user()->nickname ?? Auth::user()->name }})</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="menu-item">Entrar</a>
        @endauth
    </nav>
</aside>

<!-- Top navigation bar -->
<nav>
    <div class="nav-left">
        <button class="hamburger" id="menuBtn">
            <span></span><span></span><span></span>
        </button>
        <a href="/" class="logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="Logo">
        </a>
    </div>
    @auth
        <div style="display:flex; align-items:center; gap:12px;">
            <span style="font-size:.85rem; color:rgba(255,255,255,.6)">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-entrar" style="font-size:14px; padding:8px 20px;">Sair</button>
            </form>
        </div>
    @else
        <a href="{{ route('login') }}"><button class="btn-entrar">Entrar</button></a>
    @endauth
</nav>

<section class="hero">
    <h1 class="hero-title">CATÁLOGO</h1>
    <div class="search-wrapper">
        <input type="text" id="searchInput" placeholder="Digite seu jogo aqui...">
        <svg class="icon-search" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
    </div>
    <div class="filters" id="filtersContainer">
        <div class="filter-wrapper"><button class="filter-btn" data-filter="categoria">Categoria ▼</button><div class="dropdown-menu" id="menuCategoria"></div></div>
        <div class="filter-wrapper"><button class="filter-btn" data-filter="plataforma">Plataforma ▼</button><div class="dropdown-menu" id="menuPlataforma"></div></div>
        <div class="filter-wrapper"><button class="filter-btn" data-filter="preco">Preço ▼</button><div class="dropdown-menu" id="menuPreco"></div></div>
        <div class="filter-wrapper"><button class="filter-btn" data-filter="ordenar">Listar por ▼</button><div class="dropdown-menu" id="menuOrdenar"></div></div>
    </div>
</section>

<main>
    <h2 class="section-title">Jogos Disponíveis</h2>
    <div class="games-grid" id="gamesGrid"></div>
</main>

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
    // Dados dos jogos (enviados pelo Laravel)
    const jogosData = @json($jogos);
    function getPreco(jogo) { return (jogo.edicoes && jogo.edicoes.length) ? jogo.edicoes[0].preco : 0; }
    function getPlataforma(jogo) { return (jogo.plataformas && jogo.plataformas.length) ? jogo.plataformas[0] : 'Outras'; }

    // Preparar listas para os filtros
    const categorias = [...new Set(jogosData.map(j => j.genero).filter(Boolean))].sort();
    const plataformas = [...new Set(jogosData.map(j => getPlataforma(j)))].sort();
    const faixasPreco = [
        { label: 'Até R$ 100', min: 0, max: 100 },
        { label: 'R$ 100 - R$ 200', min: 100, max: 200 },
        { label: 'Acima de R$ 200', min: 200, max: Infinity }
    ];
    const ordenacoes = [
        { label: 'Nome A-Z', value: 'nome_asc' },
        { label: 'Nome Z-A', value: 'nome_desc' },
        { label: 'Preço crescente', value: 'preco_asc' },
        { label: 'Preço decrescente', value: 'preco_desc' }
    ];

    let filtroAtual = { categoria: null, plataforma: null, preco: null, ordenar: 'nome_asc' };
    let searchTerm = '';

    // Capturar filtro da URL (ex: ?plataforma=PlayStation 5)
    const urlParams = new URLSearchParams(window.location.search);
    const plataformaParam = urlParams.get('plataforma');
    if (plataformaParam) {
        filtroAtual.plataforma = plataformaParam;
    }

    function renderizarJogos() {
        let jogosFiltrados = [...jogosData];
        if (searchTerm) jogosFiltrados = jogosFiltrados.filter(j => j.nome.toLowerCase().includes(searchTerm.toLowerCase()));
        if (filtroAtual.categoria) jogosFiltrados = jogosFiltrados.filter(j => j.genero === filtroAtual.categoria);
        if (filtroAtual.plataforma) jogosFiltrados = jogosFiltrados.filter(j => getPlataforma(j) === filtroAtual.plataforma);
        if (filtroAtual.preco) jogosFiltrados = jogosFiltrados.filter(j => getPreco(j) >= filtroAtual.preco.min && getPreco(j) <= filtroAtual.preco.max);
        switch (filtroAtual.ordenar) {
            case 'nome_asc': jogosFiltrados.sort((a,b)=>a.nome.localeCompare(b.nome)); break;
            case 'nome_desc': jogosFiltrados.sort((a,b)=>b.nome.localeCompare(a.nome)); break;
            case 'preco_asc': jogosFiltrados.sort((a,b)=>getPreco(a)-getPreco(b)); break;
            case 'preco_desc': jogosFiltrados.sort((a,b)=>getPreco(b)-getPreco(a)); break;
        }
        const grid = document.getElementById('gamesGrid');
        if (!grid) return;
        if (jogosFiltrados.length === 0) { grid.innerHTML = '<div class="empty-state">Nenhum jogo encontrado.</div>'; return; }
        let html = '';
        for (let jogo of jogosFiltrados) {
            const preco = getPreco(jogo).toLocaleString('pt-BR', {minimumFractionDigits: 2});
            const plataforma = getPlataforma(jogo);
            html += `<a href="/produto/${jogo.slug}" class="game-card">
                        <img src="{{ asset('images') }}/${jogo.imagem_principal}" alt="${jogo.nome}">
                        <div class="card-body">
                            <span class="badge">${plataforma}</span>
                            <h3>${jogo.nome}</h3>
                            <div class="price">R$ ${preco}</div>
                        </div>
                    </a>`;
        }
        grid.innerHTML = html;
    }

    function preencherMenus() {
        const menuCat = document.getElementById('menuCategoria');
        menuCat.innerHTML = '<a href="#" data-categoria="null">Todas categorias</a>';
        categorias.forEach(c => { menuCat.innerHTML += `<a href="#" data-categoria="${c}">${c}</a>`; });
        const menuPlat = document.getElementById('menuPlataforma');
        menuPlat.innerHTML = '<a href="#" data-plataforma="null">Todas plataformas</a>';
        plataformas.forEach(p => { menuPlat.innerHTML += `<a href="#" data-plataforma="${p}">${p}</a>`; });
        const menuPreco = document.getElementById('menuPreco');
        menuPreco.innerHTML = '<a href="#" data-preco="null">Qualquer preço</a>';
        faixasPreco.forEach(f => { menuPreco.innerHTML += `<a href="#" data-preco-min="${f.min}" data-preco-max="${f.max}">${f.label}</a>`; });
        const menuOrd = document.getElementById('menuOrdenar');
        ordenacoes.forEach(o => { menuOrd.innerHTML += `<a href="#" data-ordem="${o.value}">${o.label}</a>`; });
    }

    function initDropdowns() {
        const wrappers = document.querySelectorAll('.filter-wrapper');
        let active = null;
        function closeAll() { document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none'); active = null; }
        wrappers.forEach(w => {
            const btn = w.querySelector('.filter-btn');
            const menu = w.querySelector('.dropdown-menu');
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (active === menu) { closeAll(); }
                else { closeAll(); menu.style.display = 'flex'; active = menu; }
            });
            document.addEventListener('click', (e) => { if (!w.contains(e.target)) closeAll(); });
        });
        document.getElementById('menuCategoria').addEventListener('click', (e) => {
            const a = e.target.closest('a'); if (!a) return; e.preventDefault();
            const cat = a.getAttribute('data-categoria');
            filtroAtual.categoria = (cat === 'null') ? null : cat;
            renderizarJogos(); closeAll();
        });
        document.getElementById('menuPlataforma').addEventListener('click', (e) => {
            const a = e.target.closest('a'); if (!a) return; e.preventDefault();
            const plat = a.getAttribute('data-plataforma');
            filtroAtual.plataforma = (plat === 'null') ? null : plat;
            renderizarJogos(); closeAll();
        });
        document.getElementById('menuPreco').addEventListener('click', (e) => {
            const a = e.target.closest('a'); if (!a) return; e.preventDefault();
            const min = a.getAttribute('data-preco-min');
            if (min === 'null') { filtroAtual.preco = null; }
            else { filtroAtual.preco = { min: parseFloat(min), max: parseFloat(a.getAttribute('data-preco-max')) }; }
            renderizarJogos(); closeAll();
        });
        document.getElementById('menuOrdenar').addEventListener('click', (e) => {
            const a = e.target.closest('a'); if (!a) return; e.preventDefault();
            const ordem = a.getAttribute('data-ordem');
            if (ordem) filtroAtual.ordenar = ordem;
            renderizarJogos(); closeAll();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        preencherMenus();
        initDropdowns();
        renderizarJogos();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => { searchTerm = e.target.value; renderizarJogos(); });
        }

        // Funcionalidade do menu lateral
        const menuBtn = document.getElementById('menuBtn');
        const closeBtn = document.getElementById('closeBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        if (menuBtn) menuBtn.addEventListener('click', toggleMenu);
        if (closeBtn) closeBtn.addEventListener('click', toggleMenu);
        if (overlay) overlay.addEventListener('click', toggleMenu);
    });
</script>
</body>
</html>