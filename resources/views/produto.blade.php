<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produto->nome }} – GiftZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark:   #002830;
            --mid:    #005363;
            --accent: #FDE9A2;
            --white:  #ffffff;
            --radius: 9999px;
            --danger: #e85020;
            --cyan:   #3ab8c8;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background-color: #002830; color: white; -webkit-font-smoothing: antialiased; overflow-x: hidden; }

        /* NAVBAR */
.nav-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.nav-cart-link {
    position: relative;
    color: var(--accent); /* Cor amarela definida no seu :root */
    text-decoration: none;
    display: flex;
    align-items: center;
}

.cart-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: var(--accent);
    color: var(--dark);
    font-size: 10px;
    font-weight: 900;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

        nav { display: flex; align-items: center; justify-content: space-between; padding: 14px 32px; background: rgba(0,40,48,0.85); backdrop-filter: blur(8px); position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(253,233,162,0.12); }
        .nav-left { display: flex; align-items: center; gap: 16px; }
        .hamburger { background: none; border: none; cursor: pointer; display: flex; flex-direction: column; gap: 5px; }
        .hamburger span { display: block; width: 22px; height: 2px; background: var(--white); border-radius: 2px; transition: background .2s; }
        .hamburger:hover span { background: var(--accent); }
        .logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .logo img { height: 36px; width: auto; }
        .logo-text { font-family: 'Inter', sans-serif; font-weight: 900; font-size: 1.6rem; color: var(--accent); letter-spacing: 1px; }
        .btn-entrar { border: 1px solid rgba(255,255,255,0.3); padding: 12px 36px; border-radius: 8px; color: white; text-decoration: none; font-weight: 500; font-size: 18px; transition: all 0.3s ease; background: transparent; cursor: pointer; }
        .btn-entrar:hover { background: rgba(255,255,255,0.1); }

        /* HERO */
        .hero { position: relative; overflow: hidden; padding: 48px 24px 40px; display: flex; flex-direction: column; align-items: center; gap: 6px; background: linear-gradient(160deg, var(--dark) 0%, var(--mid) 60%, #003d4a 100%); }
        .hero::before { content: ''; position: absolute; width: 420px; height: 420px; border-radius: 50%; background: radial-gradient(circle, rgba(0,83,99,.55) 0%, transparent 70%); top: -120px; right: -100px; pointer-events: none; }
        .hero::after { content: ''; position: absolute; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(253,233,162,.06) 0%, transparent 70%); bottom: -80px; left: -60px; pointer-events: none; }
        .hero-breadcrumb { font-size: .82rem; color: rgba(253,233,162,.5); letter-spacing: .06em; text-transform: uppercase; animation: fadeDown .4s ease both; }
        .hero-breadcrumb a { color: rgba(253,233,162,.5); text-decoration: none; transition: color .2s; }
        .hero-breadcrumb a:hover { color: var(--accent); }
        .hero-title { font-family: 'Gasoek One', sans-serif; font-size: clamp(1.8rem, 5vw, 3rem); font-weight: 400; color: var(--accent); letter-spacing: 4px; text-align: center; line-height: 1.1; animation: fadeDown .5s ease both; }

        /* MAIN */
        main { max-width: 1100px; margin: 0 auto; padding: 40px 32px 80px; }
        .product-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: 32px; align-items: start; }
        @media (max-width: 760px) { .product-grid { grid-template-columns: 1fr; } }

        /* LEFT */
        .platform-badge { display: inline-block; background: var(--cyan); color: #fff; font-size: .75rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: 4px 14px; border-radius: 4px 4px 0 0; }
        .main-image-wrap { width: 100%; aspect-ratio: 16/9; border-radius: 0 12px 12px 12px; overflow: hidden; background: var(--mid); }
        .main-image-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
        .main-image-wrap:hover img { transform: scale(1.03); }
        .gallery { display: flex; align-items: center; gap: 8px; margin-top: 10px; }
        .gallery-btn { background: none; border: none; color: var(--accent); font-size: 1.4rem; cursor: pointer; padding: 4px 6px; flex-shrink: 0; transition: opacity .2s; }
        .gallery-btn:hover { opacity: .7; }
        .gallery-thumbs { display: flex; gap: 8px; overflow: hidden; flex: 1; }
        .gallery-thumb { width: 82px; height: 54px; border-radius: 8px; overflow: hidden; cursor: pointer; border: 2px solid transparent; flex-shrink: 0; transition: border-color .2s, transform .2s; }
        .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .gallery-thumb.active, .gallery-thumb:hover { border-color: var(--accent); transform: scale(1.05); }
        .desc-box { margin-top: 16px; background: rgba(0,83,99,.35); border: 1px solid rgba(253,233,162,.1); border-radius: 12px; padding: 18px 20px; animation: fadeUp .5s .1s ease both; }
        .desc-box h3 { font-size: .75rem; font-weight: 700; color: var(--accent); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; }
        .desc-box p { font-size: .88rem; color: rgba(255,255,255,.65); line-height: 1.65; }

        /* RIGHT */
        .product-title { font-family: 'Gasoek One', sans-serif; font-size: clamp(1.5rem, 3vw, 2.2rem); font-weight: 400; color: var(--accent); letter-spacing: 2px; line-height: 1.15; margin-bottom: 20px; animation: fadeDown .45s ease both; }
        .price-card { background: rgba(0,83,99,.4); border: 1px solid rgba(253,233,162,.18); border-radius: 16px; padding: 22px 24px; margin-bottom: 16px; animation: fadeUp .5s ease both; }
        .price-value { font-family: 'Inter', sans-serif; font-weight: 900; font-size: 2.2rem; color: var(--accent); letter-spacing: 1px; }
        .price-sub { font-size: .78rem; color: rgba(255,255,255,.45); margin-top: 3px; margin-bottom: 18px; }
        .selector-label { font-size: .72rem; font-weight: 700; color: rgba(253,233,162,.6); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 8px; }
        .selector-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
        .pill { padding: 6px 16px; border-radius: var(--radius); border: 1.5px solid rgba(0,83,99,1); background: transparent; color: rgba(255,255,255,.7); font-family: 'Inter', sans-serif; font-weight: 600; font-size: .82rem; cursor: pointer; transition: all .2s; }
        .pill:hover { border-color: var(--accent); color: var(--accent); }
        .pill.active { border-color: var(--accent); background: rgba(253,233,162,.12); color: var(--accent); }
        .edition-select { width: 100%; background: rgba(0,40,48,.7); border: 1.5px solid rgba(0,83,99,1); border-radius: 10px; color: var(--white); font-family: 'Inter', sans-serif; font-size: .9rem; padding: 10px 14px; margin-bottom: 18px; outline: none; cursor: pointer; transition: border-color .2s; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23FDE9A2' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; }
        .edition-select:focus { border-color: var(--accent); }
        .edition-select option { background: #002830; }
        .actions { 
    display: flex; 
    gap: 8px; 
    margin-bottom: 16px; 
}
.actions form, .actions a { 
    flex: 1; 
    display: flex; 
}
.btn-fav { 
    width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid var(--danger); background: transparent; color: var(--danger); font-family: 'Inter', sans-serif; font-weight: 700; font-size: .9rem; cursor: pointer; transition: all .2s; 
}
.btn-fav:hover { background: rgba(232,80,32,.15); }

.btn-cart { 
    width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid var(--cyan); background: transparent; color: var(--cyan); font-family: 'Inter', sans-serif; font-weight: 700; font-size: .9rem; cursor: pointer; transition: all .2s; 
}
.btn-cart:hover { background: rgba(58,184,200,.15); }

.btn-buy { 
    width: 100%; padding: 12px; border-radius: 10px; border: none; background: var(--accent); color: var(--dark); font-family: 'Inter', sans-serif; font-weight: 700; font-size: .9rem; cursor: pointer; transition: opacity .2s, transform .1s; 
}
.btn-buy:hover { opacity: .88; }
.btn-buy:active { transform: scale(0.97); }
        .flash-message { padding: 12px; border-radius: 8px; margin: 10px 0; font-size: 0.9rem; }
        .flash-success { background: #2e7d32; color: white; }
        .flash-error { background: #c62828; color: white; }
        .flash-info { background: #ff9800; color: white; }
        .req-box { margin-top: 16px; background: rgba(0,83,99,.4); border: 1px solid rgba(253,233,162,.18); border-radius: 16px; overflow: hidden; animation: fadeUp .5s .15s ease both; }
        .req-title { background: rgba(253,233,162,.12); color: var(--accent); font-family: 'Inter', sans-serif; font-size: .75rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; padding: 10px 18px; border-bottom: 1px solid rgba(253,233,162,.1); }
        .req-cols { display: grid; grid-template-columns: 1fr 1fr; }
        .req-col { padding: 14px 18px; }
        .req-col:first-child { border-right: 1px solid rgba(255,255,255,.06); }
        .req-col h4 { font-size: .72rem; font-weight: 700; color: var(--accent); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 8px; }
        .req-col p { font-size: .78rem; color: rgba(255,255,255,.55); line-height: 1.75; }

        .nav-avatar-container {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
    text-decoration: none;
}

.nav-avatar-container:hover {
    transform: scale(1.08);
}

.nav-avatar-mini {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 2px solid #FFDC74;
    object-fit: cover;
    background: #1F6D7E;
    box-shadow: 0 0 12px rgba(245, 200, 66, 0.3);
}<nav>
    <div class="nav-left">
        <button class="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
        <a href="/" class="logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone Logo">
        </a>
    </div>

    <div class="nav-right">
        <a href="{{ route('carrinho.index') }}" class="nav-cart-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
        @php $qtdCarrinho = array_sum(array_column(session('carrinho', []), 'quantidade')); @endphp
        @if($qtdCarrinho > 0)
            <span class="cart-badge">{{ $qtdCarrinho }}</span>
        @endif
        </a>

        @auth
            <a href="{{ route('usuario.perfil') }}" class="nav-avatar-container">
                <img class="nav-avatar-mini" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/icone1.svg') }}">
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-entrar">Entrar</a>
        @endauth
    </div>
</nav>


        /* Estilo para o novo botão Carrinho */
.btn-cart {
    flex: 1;
    padding: 12px;
    border-radius: 10px;
    border: 1.5px solid var(--accent);
    background: transparent;
    color: var(--accent);
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    cursor: pointer;
    transition: all .2s;
}

.btn-cart:hover {
    background: rgba(253, 233, 162, 0.1);
}
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
        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 600px) { nav { padding: 12px 16px; } main { padding: 28px 16px 60px; } footer { padding: 24px 16px; } .footer-bottom { flex-direction: column; align-items: flex-start; } }
    </style>
</head>
<body>

{{-- NAVBAR --}}
{{-- NAVBAR --}}
<!-- <nav>
    <div class="nav-left">
        <a href="{{ route('carrinho.index') }}" style="color: white; margin-right: 15px; text-decoration: none;">
            🛒 Carrinho
        </a>
        <button class="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
        <a href="/" class="logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="logo-text" style="display:none">GiftZone</span>
        </a>
    </div>

    @auth
        <a href="{{ route('usuario.perfil') }}" class="nav-avatar-container" title="Meu Perfil">
            <img class="nav-avatar-mini" 
                 src="{{ Auth::user()->avatar === 'icone1.svg' || empty(Auth::user()->avatar) ? asset('images/icone1.svg') : asset('storage/' . Auth::user()->avatar) }}" 
                 alt="Avatar de {{ Auth::user()->name }}"
                 onerror="this.src='https://via.placeholder.com/44/1F6D7E/FFDC74?text=GZ'">
        </a>
    @else
        <a href="{{ route('login') }}"><button class="btn-entrar">Entrar</button></a>
    @endauth
</nav> -->

<nav>
    <div class="nav-left">
        <button class="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
        <a href="/" class="logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone Logo">
        </a>
    </div>

    <div class="nav-right">
        <a href="{{ route('carrinho.index') }}" class="nav-cart-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
           @php $qtdCarrinho = array_sum(array_column(session('carrinho', []), 'quantidade')); @endphp
            @if($qtdCarrinho > 0)
                <span class="cart-badge">{{ $qtdCarrinho }}</span>
            @endif
        </a>

        @auth
            <a href="{{ route('usuario.perfil') }}" class="nav-avatar-container">
                <img class="nav-avatar-mini" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/icone1.svg') }}">
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-entrar">Entrar</a>
        @endauth
    </div>
</nav>
{{-- HERO --}}
<section class="hero">
    <div class="hero-breadcrumb">
        <a href="{{ url('/catalogo') }}">Catálogo</a> &rsaquo; {{ $produto->nome }}
    </div>
    <h1 class="hero-title">{{ strtoupper($produto->nome) }}</h1>
</section>

{{-- MAIN --}}
<main>
    <div class="product-grid">

        {{-- LEFT --}}
        <div class="left-col">
            <span class="platform-badge" id="badge-platform">{{ is_array($produto->plataformas) ? ($produto->plataformas[0] ?? '') : '' }}</span>
            <div class="main-image-wrap">
                <img id="main-image" src="{{ asset('images/' . $produto->imagem_principal) }}" alt="{{ $produto->nome }}">
            </div>

            @php
                $galeria = is_array($produto->galeria) ? $produto->galeria : [];
            @endphp
            @if(count($galeria) > 0)
            <div class="gallery">
                <button class="gallery-btn" onclick="galleryPrev()">&#8249;</button>
                <div class="gallery-thumbs" id="gallery-thumbs">
                    <div class="gallery-thumb active" onclick="selectThumb(this, '{{ asset('images/' . $produto->imagem_principal) }}')">
                        <img src="{{ asset('images/' . $produto->imagem_principal) }}" alt="Principal">
                    </div>
                    @foreach($galeria as $img)
                    <div class="gallery-thumb" onclick="selectThumb(this, '{{ asset('images/' . $img) }}')">
                        <img src="{{ asset('images/' . $img) }}" alt="Thumb">
                    </div>
                    @endforeach
                </div>
                <button class="gallery-btn" onclick="galleryNext()">&#8250;</button>
            </div>
            @endif

            <div class="desc-box">
                <h3>Descrição</h3>
                <p>{{ $produto->descricao }}</p>
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="right-col">
            <h2 class="product-title">{{ $produto->nome }}</h2>

            <div class="price-card">
                <div class="price-value" id="price-display">
                    R$ {{ number_format(is_array($produto->edicoes) ? ($produto->edicoes[0]['preco'] ?? 0) : 0, 2, ',', '.') }}
                </div>
                <div class="price-sub" id="edition-label">
                    {{ is_array($produto->edicoes) ? ($produto->edicoes[0]['nome'] ?? '') : '' }} — {{ is_array($produto->plataformas) ? ($produto->plataformas[0] ?? '') : '' }}
                </div>

                <div class="selector-label">Plataforma</div>
                <div class="selector-pills" id="platform-pills">
                    @foreach(is_array($produto->plataformas) ? $produto->plataformas : [] as $i => $plat)
                    <button class="pill {{ $i === 0 ? 'active' : '' }}" onclick="selectPlataforma(this, '{{ $plat }}')">{{ $plat }}</button>
                    @endforeach
                </div>

                <div class="selector-label">Edição</div>
                <select class="edition-select" id="edition-select" onchange="selectEdicao(this)">
                    @foreach(is_array($produto->edicoes) ? $produto->edicoes : [] as $ed)
                    <option value="{{ $ed['preco'] }}" data-nome="{{ $ed['nome'] }}">
                        {{ $ed['nome'] }} — R$ {{ number_format($ed['preco'], 2, ',', '.') }}
                    </option>
                    @endforeach
                </select>


   <div class="actions">
    {{-- Botão Favoritar --}}
    @if(auth()->check())
        <form action="{{ route('favoritos.adicionar', $produto->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-fav">
                {{ $isFavorited ? '❤️ Fav' : '♡ Favoritar' }}
            </button>
        </form>
    @else
        <a href="{{ route('login') }}">
            <button class="btn-fav" type="button">♡ Favoritar</button>
        </a>
    @endif

    {{-- Botão Adicionar ao Carrinho --}}
    <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn-cart">Carrinho</button>
    </form>

    {{-- Botão Comprar (Direto) --}}
    <form action="{{ route('comprar.direto', $produto->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn-buy">Comprar</button>
    </form>
</div>

                {{-- Mensagens flash --}}
                @if(session('success'))
                    <div class="flash-message flash-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="flash-message flash-error">{{ session('error') }}</div>
                @endif
                @if(session('info'))
                    <div class="flash-message flash-info">{{ session('info') }}</div>
                @endif
            </div>

            @if($produto->requisitos && is_array($produto->requisitos))
            <div class="req-box">
                <div class="req-title">Requisitos do Sistema</div>
                <div class="req-cols">
                    <div class="req-col">
                        <h4>Mínimos</h4>
                        <p>
                            @foreach(($produto->requisitos['minimo'] ?? []) as $key => $val)
                                <strong>{{ ucfirst($key) }}:</strong> {{ $val }}<br>
                            @endforeach
                        </p>
                    </div>
                    <div class="req-col">
                        <h4>Recomendados</h4>
                        <p>
                            @foreach(($produto->requisitos['recomendado'] ?? []) as $key => $val)
                                <strong>{{ ucfirst($key) }}:</strong> {{ $val }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>
</main>

{{-- FOOTER --}}
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
    let thumbOffset = 0;
    function selectThumb(el, src) {
        document.getElementById('main-image').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }
    function galleryNext() {
        const wrap = document.getElementById('gallery-thumbs');
        thumbOffset = Math.min(thumbOffset + 90, wrap.scrollWidth - wrap.clientWidth);
        wrap.scrollTo({ left: thumbOffset, behavior: 'smooth' });
    }
    function galleryPrev() {
        thumbOffset = Math.max(thumbOffset - 90, 0);
        document.getElementById('gallery-thumbs').scrollTo({ left: thumbOffset, behavior: 'smooth' });
    }
    let plataformaSelecionada = document.querySelector('.pill.active')?.textContent.trim() || '';
    function selectPlataforma(el, plat) {
        document.querySelectorAll('#platform-pills .pill').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        plataformaSelecionada = plat;
        document.getElementById('badge-platform').textContent = plat;
        atualizarLabel();
    }
    function selectEdicao(sel) {
        const preco = parseFloat(sel.value).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
        document.getElementById('price-display').textContent = 'R$ ' + preco;
        atualizarLabel();
    }
    function atualizarLabel() {
        const sel = document.getElementById('edition-select');
        const nomeEd = sel.options[sel.selectedIndex]?.dataset.nome || '';
        document.getElementById('edition-label').textContent = nomeEd + ' — ' + plataformaSelecionada;
    }
</script>
</body>
</html>