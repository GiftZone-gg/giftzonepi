<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone | {{ __('messages.catalog') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Gasoek+One&family=Inria+Sans:ital,wght@1,700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        .nav-cart-link { position: relative; color: white; text-decoration: none; display: inline-flex; align-items: center; transition: color 0.2s, transform 0.2s; }
        .nav-cart-link:hover { color: #FFDC74; transform: scale(1.1); }
        .cart-badge { position: absolute; top: -8px; right: -8px; background: #FFDC74; color: #001A20; font-size: 10px; font-weight: 900; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .nav-avatar-container { display: inline-flex; align-items: center; justify-content: center; transition: transform 0.2s; }
        .nav-avatar-container:hover { transform: scale(1.08); }
        .nav-avatar-mini { width: 44px; height: 44px; border-radius: 50%; border: 2px solid #FFDC74; object-fit: cover; background: #1F6D7E; box-shadow: 0 0 12px rgba(245, 200, 66, 0.3); }

        body { font-family: 'Inter', sans-serif; background-color: #002830; color: white; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        .container { max-width: 1400px; margin: 0 auto; padding: 96px 60px 0 60px; }

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
        .user-auth-area { display: flex; align-items: center; gap: 15px; }
        .btn-entrar { border: 1px solid rgba(255,255,255,0.3); padding: 12px 36px; border-radius: 8px; color: white; text-decoration: none; font-weight: 500; font-size: 18px; transition: all 0.3s ease; background: transparent; cursor: pointer; }
        .btn-entrar:hover { background: rgba(255,255,255,0.1); }

        .hero-catalogo { position: relative; overflow: hidden; padding: 48px 24px 40px; display: flex; flex-direction: column; align-items: center; gap: 20px; background: linear-gradient(160deg, #002830 0%, #005363 60%, #003d4a 100%); }
        .hero-catalogo::before { content: ''; position: absolute; width: 420px; height: 420px; border-radius: 50%; background: radial-gradient(circle, rgba(0,83,99,.55) 0%, transparent 70%); top: -120px; right: -100px; pointer-events: none; }
        .hero-catalogo h1 { font-family: 'Gasoek One', sans-serif; font-size: clamp(2rem,5vw,3.2rem); color: #FDE9A2; letter-spacing: 6px; text-align: center; }
        .search-wrap { position: relative; width: 100%; max-width: 520px; }
        .search-wrap input { width: 100%; padding: 14px 20px 14px 48px; border-radius: 9999px; border: 1.5px solid rgba(253,233,162,.3); background: rgba(0,40,48,.7); color: white; font-size: .95rem; outline: none; transition: border-color .2s; }
        .search-wrap input:focus { border-color: #FDE9A2; }
        .search-wrap svg { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); stroke: rgba(253,233,162,.5); }

        .filters-bar { display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin: 24px auto 32px; max-width: 900px; padding: 0 20px; }
        .filter-dropdown { position: relative; }
        .filter-btn { display: flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: 9999px; border: 1.5px solid rgba(0,83,99,1); background: transparent; color: rgba(255,255,255,.7); font-family: 'Inter', sans-serif; font-weight: 600; font-size: .82rem; cursor: pointer; transition: all .2s; }
        .filter-btn:hover, .filter-btn.active { border-color: #FDE9A2; color: #FDE9A2; }
        .filter-menu { display: none; position: absolute; top: 110%; left: 0; min-width: 180px; background: #002830; border: 1px solid rgba(253,233,162,.2); border-radius: 10px; overflow: hidden; z-index: 50; box-shadow: 0 8px 24px rgba(0,0,0,.4); }
        .filter-menu.show { display: block; }
        .filter-menu a { display: block; padding: 10px 16px; color: rgba(255,255,255,.7); text-decoration: none; font-size: .82rem; transition: all .15s; }
        .filter-menu a:hover { background: rgba(253,233,162,.08); color: #FDE9A2; }

        .section-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 28px; padding: 0 20px; max-width: 1400px; margin-left: auto; margin-right: auto; }
        .section-title { font-family: 'Gasoek One', sans-serif; font-size: 28px; color: #FDE9A2; font-weight: 400; }

        .grid-games { display: grid; grid-template-columns: repeat(3,1fr); gap: 28px; max-width: 1400px; margin: 0 auto; padding: 0 20px 60px; }
        .card { position: relative; background: #001A20; border-radius: 20px; overflow: hidden; aspect-ratio: 388/287; transition: transform .3s ease; text-decoration: none; display: block; }
        .card:hover { transform: translateY(-8px); }
        .card-header { position: absolute; top: 0; left: 0; width: 100%; padding: 12px 0; text-align: center; font-weight: 600; font-size: 13px; z-index: 2; text-transform: uppercase; color: white; }
        .card-img { width: 100%; height: 100%; background-size: cover; background-position: center; }
        .bg-ps { background-color: #0066CC; }
        .bg-steam { background-color: #1B4F5E; }
        .bg-nintendo { background-color: #E60012; }
        .bg-xbox { background-color: #107C10; }
        .empty-state { grid-column: 1/-1; text-align: center; padding: 60px; color: rgba(255,255,255,.3); font-size: 1.1rem; }

        footer { background: #001e25; border-top: 1px solid rgba(253,233,162,.1); padding: 28px 32px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
        .footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .footer-logo img { height: 30px; }
        .footer-bottom { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .footer-copy { font-size: .78rem; color: #6a9a94; }
        .social-links { display: flex; gap: 14px; }
        .social-links a { color: #6a9a94; text-decoration: none; transition: color .2s; }
        .social-links a:hover { color: #FFDC74; }

        @media (max-width: 900px) { .grid-games { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 600px) { .grid-games { grid-template-columns: 1fr; } header { padding: 12px 16px; } }
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
        <a href="{{ route('carrinho.index') }}" class="nav-cart-link" title="{{ __('messages.my_cart') }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            @if($cartCount > 0)<span class="cart-badge">{{ $cartCount }}</span>@endif
        </a>
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

<section class="hero-catalogo">
    <h1>{{ __('messages.catalog_title') }}</h1>
    <div class="search-wrap">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="searchInput" placeholder="{{ __('messages.search_placeholder') }}">
    </div>
</section>

<div class="filters-bar">
    <div class="filter-dropdown">
        <button class="filter-btn" id="btnCategoria" onclick="toggleMenu('menuCategoria')">{{ __('messages.category') }} ▼</button>
        <div class="filter-menu" id="menuCategoria"></div>
    </div>
    <div class="filter-dropdown">
        <button class="filter-btn" id="btnPlataforma" onclick="toggleMenu('menuPlataforma')">{{ __('messages.platform') }} ▼</button>
        <div class="filter-menu" id="menuPlataforma"></div>
    </div>
    <div class="filter-dropdown">
        <button class="filter-btn" id="btnPreco" onclick="toggleMenu('menuPreco')">{{ __('messages.price') }} ▼</button>
        <div class="filter-menu" id="menuPreco"></div>
    </div>
    <div class="filter-dropdown">
        <button class="filter-btn" id="btnOrdem" onclick="toggleMenu('menuOrdem')">{{ __('messages.sort_by') }} ▼</button>
        <div class="filter-menu" id="menuOrdem"></div>
    </div>
</div>

<div class="section-header">
    <h2 class="section-title" id="tituloSecao">{{ __('messages.available_games') }}</h2>
</div>

<div class="grid-games" id="gamesGrid"></div>

<footer>
    <div class="footer-inner">
        <a href="/" class="footer-logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone" onerror="this.style.display='none'">
        </a>
        <div class="footer-bottom">
            <span class="footer-copy">&copy; {{ date('Y') }} GiftZone {{ __('messages.all_rights') }}</span>
            <div class="social-links">
                <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" aria-label="Discord"><i class="fa-brands fa-discord"></i></a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Traduções para JS
    const t = {
        no_games_found: @json(__('messages.no_games_found')),
        all_categories: @json(__('messages.all_categories')),
        all_platforms: @json(__('messages.all_platforms')),
        any_price: @json(__('messages.any_price')),
        up_to_100: @json(__('messages.up_to_100')),
        range_100_200: @json(__('messages.range_100_200')),
        above_200: @json(__('messages.above_200')),
        name_az: @json(__('messages.name_az')),
        name_za: @json(__('messages.name_za')),
        price_asc: @json(__('messages.price_asc')),
        price_desc: @json(__('messages.price_desc')),
        category: @json(__('messages.category')),
        platform: @json(__('messages.platform')),
        price: @json(__('messages.price')),
        sort_by: @json(__('messages.sort_by')),
    };

    const menuBtn = document.getElementById('menuBtn');
    const closeBtn = document.getElementById('closeBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    function toggleMenuSide() { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); }
    menuBtn.addEventListener('click', toggleMenuSide);
    closeBtn.addEventListener('click', toggleMenuSide);
    overlay.addEventListener('click', toggleMenuSide);

    const jogos = @json($jogos);
    let filtroCategoria = null, filtroPlataforma = null, filtroPrecoMin = null, filtroPrecoMax = null, filtroOrdem = 'nome_asc', filtroBusca = '';

    function getPreco(jogo) {
        let edicoes = jogo.edicoes;
        if (typeof edicoes === 'string') edicoes = JSON.parse(edicoes);
        return edicoes && edicoes[0] ? edicoes[0].preco : 0;
    }

    function getPlataformas(jogo) {
        let p = jogo.plataformas;
        if (typeof p === 'string') p = JSON.parse(p);
        return Array.isArray(p) ? p : [];
    }

    function badgeClass(plats) {
        if (plats.includes('PlayStation 5') || plats.includes('PlayStation 4')) return 'bg-ps';
        if (plats.includes('Nintendo Switch')) return 'bg-nintendo';
        if (plats.includes('Xbox')) return 'bg-xbox';
        return 'bg-steam';
    }

    function badgeLabel(plats) {
        if (plats.includes('PlayStation 5') || plats.includes('PlayStation 4')) return 'PLAYSTATION STORE';
        if (plats.includes('Nintendo Switch')) return 'NINTENDO ESHOP';
        if (plats.includes('Xbox')) return 'XBOX STORE';
        return 'STEAM';
    }

    function renderizarJogos() {
        const getImg = (img) => {
            if (!img) return '';
            return img.startsWith('http') ? img : `/images/${img}`;
        };

        let lista = jogos.filter(j => {
            if (filtroBusca && !j.nome.toLowerCase().includes(filtroBusca.toLowerCase())) return false;
            if (filtroCategoria && j.genero !== filtroCategoria) return false;
            if (filtroPlataforma && !getPlataformas(j).includes(filtroPlataforma)) return false;
            const preco = getPreco(j);
            if (filtroPrecoMin !== null && preco < filtroPrecoMin) return false;
            if (filtroPrecoMax !== null && filtroPrecoMax !== Infinity && preco > filtroPrecoMax) return false;
            return true;
        });

        lista.sort((a, b) => {
            switch (filtroOrdem) {
                case 'nome_desc': return b.nome.localeCompare(a.nome);
                case 'preco_asc': return getPreco(a) - getPreco(b);
                case 'preco_desc': return getPreco(b) - getPreco(a);
                default: return a.nome.localeCompare(b.nome);
            }
        });

        const grid = document.getElementById('gamesGrid');
        if (lista.length === 0) {
            grid.innerHTML = `<div class="empty-state">${t.no_games_found}</div>`;
            return;
        }

        grid.innerHTML = lista.map(j => {
            const plats = getPlataformas(j);
            return `<a href="/produto/${j.slug}" class="card">
                <div class="card-header ${badgeClass(plats)}">${badgeLabel(plats)}</div>
                <div class="card-img" style="background-image: url('${getImg(j.imagem_principal)}');"></div>
            </a>`;
        }).join('');
    }

    function preencherMenus() {
        const categorias = [...new Set(jogos.map(j => j.genero).filter(Boolean))];
        const plataformas = [...new Set(jogos.flatMap(j => getPlataformas(j)))];
        const faixasPreco = [
            { label: t.up_to_100, min: 0, max: 100 },
            { label: t.range_100_200, min: 100, max: 200 },
            { label: t.above_200, min: 200, max: Infinity }
        ];
        const ordenacoes = [
            { label: t.name_az, value: 'nome_asc' },
            { label: t.name_za, value: 'nome_desc' },
            { label: t.price_asc, value: 'preco_asc' },
            { label: t.price_desc, value: 'preco_desc' }
        ];

        const menuCat = document.getElementById('menuCategoria');
        menuCat.innerHTML = `<a href="#" data-categoria="null">${t.all_categories}</a>`;
        categorias.forEach(c => { menuCat.innerHTML += `<a href="#" data-categoria="${c}">${c}</a>`; });
        menuCat.querySelectorAll('a').forEach(a => a.addEventListener('click', e => {
            e.preventDefault();
            const v = a.dataset.categoria;
            filtroCategoria = v === 'null' ? null : v;
            document.getElementById('btnCategoria').textContent = (filtroCategoria || t.category) + ' ▼';
            menuCat.classList.remove('show');
            renderizarJogos();
        }));

        const menuPlat = document.getElementById('menuPlataforma');
        menuPlat.innerHTML = `<a href="#" data-plataforma="null">${t.all_platforms}</a>`;
        plataformas.forEach(p => { menuPlat.innerHTML += `<a href="#" data-plataforma="${p}">${p}</a>`; });
        menuPlat.querySelectorAll('a').forEach(a => a.addEventListener('click', e => {
            e.preventDefault();
            const v = a.dataset.plataforma;
            filtroPlataforma = v === 'null' ? null : v;
            document.getElementById('btnPlataforma').textContent = (filtroPlataforma || t.platform) + ' ▼';
            menuPlat.classList.remove('show');
            renderizarJogos();
        }));

        const menuPreco = document.getElementById('menuPreco');
        menuPreco.innerHTML = `<a href="#" data-preco-min="null" data-preco-max="null">${t.any_price}</a>`;
        faixasPreco.forEach(f => { menuPreco.innerHTML += `<a href="#" data-preco-min="${f.min}" data-preco-max="${f.max}">${f.label}</a>`; });
        menuPreco.querySelectorAll('a').forEach(a => a.addEventListener('click', e => {
            e.preventDefault();
            filtroPrecoMin = a.dataset.precoMin === 'null' ? null : parseFloat(a.dataset.precoMin);
            filtroPrecoMax = a.dataset.precoMax === 'null' ? null : (a.dataset.precoMax === 'Infinity' ? Infinity : parseFloat(a.dataset.precoMax));
            document.getElementById('btnPreco').textContent = (filtroPrecoMin === null ? t.price : a.textContent) + ' ▼';
            menuPreco.classList.remove('show');
            renderizarJogos();
        }));

        const menuOrdem = document.getElementById('menuOrdem');
        menuOrdem.innerHTML = '';
        ordenacoes.forEach(o => { menuOrdem.innerHTML += `<a href="#" data-ordem="${o.value}">${o.label}</a>`; });
        menuOrdem.querySelectorAll('a').forEach(a => a.addEventListener('click', e => {
            e.preventDefault();
            filtroOrdem = a.dataset.ordem;
            document.getElementById('btnOrdem').textContent = a.textContent + ' ▼';
            menuOrdem.classList.remove('show');
            renderizarJogos();
        }));
    }

    function toggleMenu(id) {
        document.querySelectorAll('.filter-menu').forEach(m => { if (m.id !== id) m.classList.remove('show'); });
        document.getElementById(id).classList.toggle('show');
    }

    document.addEventListener('click', e => {
        if (!e.target.closest('.filter-dropdown')) document.querySelectorAll('.filter-menu').forEach(m => m.classList.remove('show'));
    });

    document.getElementById('searchInput').addEventListener('input', e => { filtroBusca = e.target.value; renderizarJogos(); });

    // Filtro via URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('plataforma')) filtroPlataforma = urlParams.get('plataforma');

    preencherMenus();
    renderizarJogos();
</script>

</body>
</html>