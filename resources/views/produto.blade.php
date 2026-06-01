<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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

        .nav-right { display: flex; align-items: center; gap: 20px; }
        .nav-cart-link { position: relative; color: var(--accent); text-decoration: none; display: flex; align-items: center; }
        .cart-badge { position: absolute; top: -8px; right: -8px; background: var(--accent); color: var(--dark); font-size: 10px; font-weight: 900; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

        nav { display: flex; align-items: center; justify-content: space-between; padding: 14px 32px; background: rgba(0,40,48,0.85); backdrop-filter: blur(8px); position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(253,233,162,0.12); }
        .nav-left { display: flex; align-items: center; gap: 16px; }
        .hamburger { background: none; border: none; cursor: pointer; display: flex; flex-direction: column; gap: 5px; }
        .hamburger span { display: block; width: 22px; height: 2px; background: var(--white); border-radius: 2px; transition: background .2s; }
        .hamburger:hover span { background: var(--accent); }
        .logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .logo img { height: 36px; width: auto; }
        .btn-entrar { border: 1px solid rgba(255,255,255,0.3); padding: 12px 36px; border-radius: 8px; color: white; text-decoration: none; font-weight: 500; font-size: 18px; transition: all 0.3s ease; background: transparent; cursor: pointer; }
        .btn-entrar:hover { background: rgba(255,255,255,0.1); }

        .nav-avatar-container { display: inline-flex; align-items: center; justify-content: center; transition: transform 0.2s; text-decoration: none; }
        .nav-avatar-container:hover { transform: scale(1.08); }
        .nav-avatar-mini { width: 44px; height: 44px; border-radius: 50%; border: 2px solid #FFDC74; object-fit: cover; background: #1F6D7E; box-shadow: 0 0 12px rgba(245, 200, 66, 0.3); }

        .hero { position: relative; overflow: hidden; padding: 48px 24px 40px; display: flex; flex-direction: column; align-items: center; gap: 6px; background: linear-gradient(160deg, var(--dark) 0%, var(--mid) 60%, #003d4a 100%); }
        .hero::before { content: ''; position: absolute; width: 420px; height: 420px; border-radius: 50%; background: radial-gradient(circle, rgba(0,83,99,.55) 0%, transparent 70%); top: -120px; right: -100px; pointer-events: none; }
        .hero::after { content: ''; position: absolute; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(253,233,162,.06) 0%, transparent 70%); bottom: -80px; left: -60px; pointer-events: none; }
        .hero-breadcrumb { font-size: .82rem; color: rgba(253,233,162,.5); letter-spacing: .06em; text-transform: uppercase; animation: fadeDown .4s ease both; }
        .hero-breadcrumb a { color: rgba(253,233,162,.5); text-decoration: none; transition: color .2s; }
        .hero-breadcrumb a:hover { color: var(--accent); }
        .hero-title { font-family: 'Gasoek One', sans-serif; font-size: clamp(1.8rem, 5vw, 3rem); font-weight: 400; color: var(--accent); letter-spacing: 4px; text-align: center; line-height: 1.1; animation: fadeDown .5s ease both; }

        main { max-width: 1100px; margin: 0 auto; padding: 40px 32px 80px; }
        .product-grid { display: grid; grid-template-columns: 1.1fr 1fr; gap: 32px; align-items: start; }
        @media (max-width: 760px) { .product-grid { grid-template-columns: 1fr; } }

        .platform-badge { display: inline-block; background: var(--cyan); color: #fff; font-size: .75rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: 4px 14px; border-radius: 4px 4px 0 0; }

        /* ─── MEDIA VIEWER (carrossel) ─── */
        .media-main { width: 100%; aspect-ratio: 16/9; border-radius: 0 12px 12px 12px; overflow: hidden; background: #000; position: relative; }
        .media-main img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .media-main iframe { width: 100%; height: 100%; border: none; display: block; }

        .media-thumbs { display: flex; gap: 8px; margin-top: 10px; overflow-x: auto; padding-bottom: 4px; }
        .media-thumb {
            width: 100px; height: 60px; border-radius: 8px; overflow: hidden;
            cursor: pointer; border: 2px solid transparent; flex-shrink: 0;
            transition: border-color .2s, transform .2s; position: relative; background: #001A20;
        }
        .media-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .media-thumb.active, .media-thumb:hover { border-color: var(--accent); transform: scale(1.05); }

        .media-thumb .play-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;
        }
        .media-thumb .play-overlay svg { width: 28px; height: 28px; fill: #ff0000; }

        .desc-box { margin-top: 16px; background: rgba(0,83,99,.35); border: 1px solid rgba(253,233,162,.1); border-radius: 12px; padding: 18px 20px; animation: fadeUp .5s .1s ease both; }
        .desc-box h3 { font-size: .75rem; font-weight: 700; color: var(--accent); letter-spacing: .12em; text-transform: uppercase; margin-bottom: 8px; }
        .desc-box p { font-size: .88rem; color: rgba(255,255,255,.65); line-height: 1.65; }

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
        .actions { display: flex; gap: 8px; margin-bottom: 16px; }
        .actions form, .actions a { flex: 1; display: flex; }
        .btn-fav { width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid var(--danger); background: transparent; color: var(--danger); font-family: 'Inter', sans-serif; font-weight: 700; font-size: .9rem; cursor: pointer; transition: all .2s; }
        .btn-fav:hover { background: rgba(232,80,32,.15); }
        .btn-cart { width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid var(--accent); background: transparent; color: var(--accent); font-family: 'Inter', sans-serif; font-weight: 700; font-size: .9rem; cursor: pointer; transition: all .2s; }
        .btn-cart:hover { background: rgba(253, 233, 162, 0.1); }
        .btn-buy { width: 100%; padding: 12px; border-radius: 10px; border: none; background: var(--accent); color: var(--dark); font-family: 'Inter', sans-serif; font-weight: 700; font-size: .9rem; cursor: pointer; transition: opacity .2s, transform .1s; }
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

        footer { background: #001e25; border-top: 1px solid rgba(253,233,162,.1); padding: 28px 32px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 16px; }
        .footer-logo { display: flex; align-items: center; gap: 8px; text-decoration: none; }
        .footer-logo img { height: 30px; }
        .footer-bottom { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .footer-copy { font-size: .78rem; color: #6a9a94; }
        .social-links { display: flex; gap: 14px; }
        .social-links a { color: #6a9a94; text-decoration: none; transition: color .2s; }
        .social-links a:hover { color: var(--accent); }

        @keyframes fadeDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 600px) { nav { padding: 12px 16px; } main { padding: 28px 16px 60px; } footer { padding: 24px 16px; } .footer-bottom { flex-direction: column; align-items: flex-start; } .media-thumbs { gap: 6px; } .media-thumb { width: 80px; height: 48px; } }
    </style>
</head>
<body>

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
                <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            @if($cartCount > 0)
                <span class="cart-badge">{{ $cartCount }}</span>
            @endif
        </a>
        @auth
            <a href="{{ route('usuario.perfil') }}" class="nav-avatar-container" title="{{ __('messages.my_profile') }}">
                <img class="nav-avatar-mini"
                     src="{{ Auth::user()->avatar === 'icone1.svg' || empty(Auth::user()->avatar) ? asset('images/icone1.svg') : asset('storage/' . Auth::user()->avatar) }}"
                     alt="Avatar de {{ Auth::user()->name }}"
                     onerror="this.src='{{ asset('images/icone1.svg') }}'">
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-entrar">{{ __('messages.enter') }}</a>
        @endauth
    </div>
</nav>

<section class="hero">
    <div class="hero-breadcrumb">
        <a href="{{ url('/catalogo') }}">{{ __('messages.catalog') }}</a> &rsaquo; {{ $produto->nome }}
    </div>
    <h1 class="hero-title">{{ strtoupper($produto->nome) }}</h1>
</section>

<main>
    <div class="product-grid">
        <div class="left-col">
            <span class="platform-badge" id="badge-platform">{{ is_array($produto->plataformas) ? ($produto->plataformas[0] ?? '') : '' }}</span>

            {{-- ═══ MEDIA VIEWER ═══ --}}
            @php
                $galeria = is_array($produto->galeria) ? $produto->galeria : [];
                $trailerUrl = $produto->trailer_url ?? null;
                $videoId = null;
                if ($trailerUrl) {
                    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $trailerUrl, $tMatches);
                    $videoId = $tMatches[1] ?? null;
                }
            @endphp

            {{-- Main display --}}
            <div class="media-main" id="mediaMain">
                <img id="mainImage" src="{{ asset('images/' . $produto->imagem_principal) }}" alt="{{ $produto->nome }}">
            </div>

            {{-- Thumbnails --}}
            <div class="media-thumbs">
                {{-- Trailer thumb --}}
                @if($videoId)
                <div class="media-thumb" onclick="showTrailer('{{ $videoId }}')" id="thumb-trailer">
                    <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg" alt="Trailer">
                    <div class="play-overlay">
                        <svg viewBox="0 0 68 48"><path d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55c-2.93.78-4.63 3.26-5.42 6.19C.06 13.05 0 24 0 24s.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.63-3.26 5.42-6.19C67.94 34.95 68 24 68 24s-.06-10.95-1.48-16.26z" fill="#f00"/><path d="M45 24L27 14v20" fill="#fff"/></svg>
                    </div>
                </div>
                @endif

                {{-- Main image thumb --}}
                <div class="media-thumb active" onclick="showImage(this, '{{ asset('images/' . $produto->imagem_principal) }}')" id="thumb-main">
                    <img src="{{ asset('images/' . $produto->imagem_principal) }}" alt="Principal">
                </div>

                {{-- Gallery thumbs --}}
                @foreach($galeria as $idx => $img)
                <div class="media-thumb" onclick="showImage(this, '{{ asset('images/' . $img) }}')">
                    <img src="{{ asset('images/' . $img) }}" alt="Screenshot {{ $idx + 1 }}">
                </div>
                @endforeach
            </div>

            <div class="desc-box">
                <h3>{{ __('messages.description') }}</h3>
                <p>{{ $produto->descricao }}</p>
            </div>
        </div>

        <div class="right-col">
            <div class="price-card">
                <div class="price-value" id="price-display">
                    R$ {{ number_format(is_array($produto->edicoes) ? ($produto->edicoes[0]['preco'] ?? 0) : 0, 2, ',', '.') }}
                </div>
                <div class="price-sub" id="edition-label">
                    {{ is_array($produto->edicoes) ? ($produto->edicoes[0]['nome'] ?? '') : '' }} — {{ is_array($produto->plataformas) ? ($produto->plataformas[0] ?? '') : '' }}
                </div>

                <div class="selector-label">{{ __('messages.platform') }}</div>
                <div class="selector-pills" id="platform-pills">
                    @foreach(is_array($produto->plataformas) ? $produto->plataformas : [] as $i => $plat)
                    <button class="pill {{ $i === 0 ? 'active' : '' }}" onclick="selectPlataforma(this, '{{ $plat }}')">{{ $plat }}</button>
                    @endforeach
                </div>

                <div class="selector-label">{{ __('messages.edition') }}</div>
                <select class="edition-select" id="edition-select" onchange="selectEdicao(this)">
                    @foreach(is_array($produto->edicoes) ? $produto->edicoes : [] as $ed)
                    <option value="{{ $ed['preco'] }}" data-nome="{{ $ed['nome'] }}">
                        {{ $ed['nome'] }} — R$ {{ number_format($ed['preco'], 2, ',', '.') }}
                    </option>
                    @endforeach
                </select>

                <div class="actions">
                    @if(auth()->check())
                        <form action="{{ route('favoritos.adicionar', $produto->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-fav">
                                {{ $isFavorited ? __('messages.favorited') : __('messages.favorite') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">
                            <button class="btn-fav" type="button">{{ __('messages.favorite') }}</button>
                        </a>
                    @endif

                    <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-cart">{{ __('messages.cart') }}</button>
                    </form>

                    <form action="{{ route('comprar.direto', $produto->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-buy">{{ __('messages.buy') }}</button>
                    </form>
                </div>

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
                <div class="req-title">{{ __('messages.system_requirements') }}</div>
                <div class="req-cols">
                    <div class="req-col">
                        <h4>{{ __('messages.minimum') }}</h4>
                        <p>
                            @foreach(($produto->requisitos['minimo'] ?? []) as $key => $val)
                                <strong>{{ ucfirst($key) }}:</strong> {{ $val }}<br>
                            @endforeach
                        </p>
                    </div>
                    <div class="req-col">
                        <h4>{{ __('messages.recommended') }}</h4>
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

    {{-- ═══ PRODUTOS RELACIONADOS ═══ --}}
    @if($relacionados->count() > 0)
    <section style="max-width: 1100px; margin: 40px auto 0; padding: 0 0 60px;">
        <h2 style="font-family: 'Gasoek One', sans-serif; font-size: clamp(1.2rem, 3vw, 1.6rem); color: var(--accent); letter-spacing: 2px; margin-bottom: 24px; text-align: center;">
            {{ __('messages.continue_shopping') }}
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 18px;">
            @foreach($relacionados as $rel)
            @php
                $relEdicoes = is_array($rel->edicoes) ? $rel->edicoes : [];
                $relPreco = !empty($relEdicoes) ? collect($relEdicoes)->min('preco') : 0;
            @endphp
            <a href="{{ route('produto.show', $rel->slug) }}" style="text-decoration: none; display: block;">
                <div style="background: rgba(0,83,99,0.35); border: 1px solid rgba(253,233,162,0.1); border-radius: 14px; overflow: hidden; transition: transform 0.25s, border-color 0.25s; cursor: pointer;"
                     onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='rgba(253,233,162,0.4)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='rgba(253,233,162,0.1)';">
                    <div style="width: 100%; height: 130px; overflow: hidden; background: #1F6D7E;">
                        <img src="{{ asset('images/' . $rel->imagem_principal) }}" alt="{{ $rel->nome }}"
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                             onmouseover="this.style.transform='scale(1.05)'"
                             onmouseout="this.style.transform='scale(1)'"
                             onerror="this.style.display='none'">
                    </div>
                    <div style="padding: 14px 16px;">
                        <div style="font-family: 'Gasoek One', sans-serif; font-size: 13px; color: #fff; margin-bottom: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $rel->nome }}
                        </div>
                        <div style="font-size: 11px; color: rgba(255,255,255,0.4); margin-bottom: 8px;">
                            {{ $rel->genero ?? '' }}
                        </div>
                        <div style="font-family: 'Inter', sans-serif; font-weight: 900; font-size: 16px; color: var(--accent);">
                            R$ {{ number_format($relPreco, 2, ',', '.') }}
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</main>

<footer>
    <div class="footer-inner">
        <a href="/" class="footer-logo">
            <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone" onerror="this.style.display='none'">
        </a>
        <div class="footer-bottom">
            <span class="footer-copy">&copy; {{ date('Y') }} GiftZone {{ __('messages.all_rights') }}</span>
            <div class="social-links">
                <a href="#" aria-label="Instagram">IG</a>
                <a href="#" aria-label="Twitter">X</a>
                <a href="#" aria-label="Facebook">FB</a>
                <a href="#" aria-label="Discord">DC</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // ─── Media Viewer ───
    const mediaMain = document.getElementById('mediaMain');
    const mainImage = document.getElementById('mainImage');

    function clearActive() {
        document.querySelectorAll('.media-thumb').forEach(t => t.classList.remove('active'));
    }

    function showImage(el, src) {
        clearActive();
        el.classList.add('active');
        mediaMain.innerHTML = '<img id="mainImage" src="' + src + '" alt="Preview" style="width:100%;height:100%;object-fit:cover;">';
    }

    function showTrailer(videoId) {
        clearActive();
        const trailerThumb = document.getElementById('thumb-trailer');
        if (trailerThumb) trailerThumb.classList.add('active');
        mediaMain.innerHTML = '<iframe src="https://www.youtube.com/embed/' + videoId + '" allowfullscreen style="width:100%;height:100%;border:none;"></iframe>';
    }

    // ─── Platform & Edition ───
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
        document.getElementById('edition-label').textContent = nomeEd + ' \u2014 ' + plataformaSelecionada;
    }
</script>
</body>
</html>