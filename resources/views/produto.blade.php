<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produto->nome }} – GiftZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:        #0f2a2a;
            --bg2:       #162e2e;
            --card:      #1a1008;
            --gold:      #e8b820;
            --gold2:     #c89a10;
            --teal:      #2a7070;
            --teal2:     #1e5555;
            --cyan:      #3ab8c8;
            --text:      #e0eeee;
            --muted:     #8aabab;
            --danger:    #e85020;
            --gradient:  linear-gradient(160deg, #1a4a4a 0%, #2a6a5a 35%, #3a7a60 55%, #b8b860 100%);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--gradient);
            min-height: 100vh;
            color: var(--text);
        }

        /* ── NAVBAR ── */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 28px;
            background: rgba(15,30,30,0.55);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .nav-logo svg { width: 30px; height: 30px; }

        .logo-text { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; }
        .logo-text .gift { color: #fff; }
        .logo-text .zone { color: var(--gold); }

        .nav-avatar {
            width: 42px; height: 42px;
            border-radius: 50%;
            border: 2px solid var(--gold);
            overflow: hidden;
            background: var(--teal);
            display: flex; align-items: center; justify-content: center;
        }

        .nav-avatar img { width: 100%; height: 100%; object-fit: cover; }

        /* ── MAIN LAYOUT ── */
        main {
            max-width: 960px;
            margin: 32px auto;
            padding: 0 20px 60px;
        }

        /* ── PRODUCT GRID ── */
        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 700px) {
            .product-grid { grid-template-columns: 1fr; }
        }

        /* ── LEFT COLUMN ── */
        .left-col {}

        /* Platform badge */
        .platform-badge {
            background: var(--cyan);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            padding: 4px 14px;
            border-radius: 4px 4px 0 0;
            display: inline-block;
            text-transform: uppercase;
        }

        /* Main image */
        .main-image-wrap {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 0 8px 8px 8px;
            overflow: hidden;
            background: var(--teal2);
            position: relative;
        }

        .main-image-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        /* Gallery */
        .gallery {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            align-items: center;
        }

        .gallery-btn {
            background: none;
            border: none;
            color: var(--gold);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 4px;
            flex-shrink: 0;
        }

        .gallery-thumbs {
            display: flex;
            gap: 6px;
            overflow: hidden;
            flex: 1;
        }

        .gallery-thumb {
            width: 80px;
            height: 52px;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            flex-shrink: 0;
            transition: border-color 0.2s, transform 0.2s;
        }

        .gallery-thumb img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .gallery-thumb.active,
        .gallery-thumb:hover {
            border-color: var(--gold);
            transform: scale(1.05);
        }

        /* Description */
        .desc-box {
            margin-top: 16px;
            background: rgba(15,30,30,0.6);
            border-radius: 10px;
            padding: 16px;
        }

        .desc-box h3 {
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.95rem;
            color: var(--gold);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .desc-box p {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── RIGHT COLUMN ── */
        .right-col {}

        .product-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 16px;
        }

        /* Price card */
        .price-card {
            background: rgba(15,30,30,0.7);
            border: 1.5px solid rgba(232,184,32,0.3);
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 16px;
        }

        .price-value {
            font-family: 'Rajdhani', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold);
        }

        .price-sub {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 2px;
            margin-bottom: 14px;
        }

        /* Platform selector */
        .selector-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .selector-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 14px;
        }

        .pill {
            padding: 5px 14px;
            border-radius: 20px;
            border: 1.5px solid var(--teal);
            background: transparent;
            color: var(--text);
            font-size: 0.82rem;
            font-family: 'Nunito', sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pill:hover { border-color: var(--gold); color: var(--gold); }
        .pill.active { border-color: var(--gold); background: rgba(232,184,32,0.15); color: var(--gold); }

        /* Edition selector */
        .edition-select {
            width: 100%;
            background: var(--teal2);
            border: 1.5px solid var(--teal);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Nunito', sans-serif;
            font-size: 0.9rem;
            padding: 9px 12px;
            margin-bottom: 16px;
            outline: none;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .edition-select:focus { border-color: var(--gold); }

        /* Action buttons */
        .actions {
            display: flex;
            gap: 10px;
        }

        .btn-fav {
            flex: 1;
            padding: 11px;
            border-radius: 8px;
            border: 1.5px solid var(--danger);
            background: transparent;
            color: var(--danger);
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-fav:hover { background: rgba(232,80,32,0.15); }
        .btn-fav.faved { background: rgba(232,80,32,0.2); }

        .btn-buy {
            flex: 1;
            padding: 11px;
            border-radius: 8px;
            border: none;
            background: var(--cyan);
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-buy:hover { background: #2fa8b8; }
        .btn-buy:active { transform: scale(0.97); }

        /* Requirements */
        .req-box {
            margin-top: 16px;
            background: rgba(15,30,30,0.7);
            border: 1.5px solid rgba(232,184,32,0.25);
            border-radius: 12px;
            overflow: hidden;
        }

        .req-title {
            background: var(--gold2);
            color: #1a1008;
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 8px 16px;
            text-align: center;
        }

        .req-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .req-col {
            padding: 12px 14px;
        }

        .req-col:first-child {
            border-right: 1px solid rgba(255,255,255,0.07);
        }

        .req-col h4 {
            font-family: 'Rajdhani', sans-serif;
            font-size: 0.82rem;
            color: var(--gold);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .req-col p {
            font-size: 0.78rem;
            color: var(--muted);
            line-height: 1.7;
        }

        /* ── FOOTER ── */
        footer {
            background: #0d1f1f;
            padding: 24px 28px;
            margin-top: 40px;
        }

        .footer-inner {
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-copy {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .footer-social {
            display: flex;
            gap: 14px;
        }

        .footer-social a {
            color: var(--muted);
            font-size: 1.1rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-social a:hover { color: var(--gold); }
    </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav>
    <a href="{{ url('/') }}" class="nav-logo">
        <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="4" y="16" width="28" height="16" rx="2" fill="#e8b820"/>
            <rect x="4" y="10" width="28" height="7" rx="1.5" fill="#c89a10"/>
            <rect x="15.5" y="10" width="5" height="22" fill="#0f2a2a"/>
            <path d="M18 10 Q13 4 9 6 Q5 8 9 11 Q13 13 18 10Z" fill="#e8b820"/>
            <path d="M18 10 Q23 4 27 6 Q31 8 27 11 Q23 13 18 10Z" fill="#c89a10"/>
        </svg>
        <div class="logo-text"><span class="gift">Gift</span><span class="zone">Zone</span></div>
    </a>
    <div class="nav-avatar">
        @auth
            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2a7070&color=fff' }}" alt="Avatar">
        @else
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" fill="#e8b820"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="#e8b820" stroke-width="2" stroke-linecap="round"/></svg>
        @endauth
    </div>
</nav>

{{-- ── MAIN ── --}}
<main>
    <div class="product-grid">

        {{-- ── LEFT ── --}}
        <div class="left-col">

            {{-- Platform badge (primeira plataforma selecionada) --}}
            <span class="platform-badge" id="badge-platform">
                {{ $produto->plataformas[0] ?? '' }}
            </span>

            {{-- Main image --}}
            <div class="main-image-wrap">
                <img id="main-image"
                     src="{{ asset('images/' . $produto->imagem_principal) }}"
                     alt="{{ $produto->nome }}">
            </div>

            {{-- Gallery --}}
            @if($produto->galeria && count($produto->galeria) > 0)
            <div class="gallery">
                <button class="gallery-btn" onclick="galleryPrev()">&#8249;</button>
                <div class="gallery-thumbs" id="gallery-thumbs">
                    {{-- Thumb da imagem principal --}}
                    <div class="gallery-thumb active" onclick="selectThumb(this, '{{ asset('images/' . $produto->imagem_principal) }}')">
                        <img src="{{ asset('images/' . $produto->imagem_principal) }}" alt="Thumb principal">
                    </div>
                    {{-- Thumbs da galeria --}}
                    @foreach($produto->galeria as $img)
                    <div class="gallery-thumb" onclick="selectThumb(this, '{{ asset('images/' . $img) }}')">
                        <img src="{{ asset('images/' . $img) }}" alt="Thumb">
                    </div>
                    @endforeach
                </div>
                <button class="gallery-btn" onclick="galleryNext()">&#8250;</button>
            </div>
            @endif

            {{-- Description --}}
            <div class="desc-box">
                <h3>Descrição</h3>
                <p>{{ $produto->descricao }}</p>
            </div>

        </div>

        {{-- ── RIGHT ── --}}
        <div class="right-col">

            <h1 class="product-title">{{ $produto->nome }}</h1>

            <div class="price-card">

                {{-- Price --}}
                <div class="price-value" id="price-display">
                    R$ {{ number_format($produto->edicoes[0]['preco'] ?? 0, 2, ',', '.') }}
                </div>
                <div class="price-sub" id="edition-label">
                    {{ $produto->edicoes[0]['nome'] ?? '' }} —
                    {{ $produto->plataformas[0] ?? '' }}
                </div>

                {{-- Platform selector --}}
                <div class="selector-label">Plataforma</div>
                <div class="selector-pills" id="platform-pills">
                    @foreach($produto->plataformas as $i => $plat)
                    <button class="pill {{ $i === 0 ? 'active' : '' }}"
                            onclick="selectPlataforma(this, '{{ $plat }}')">
                        {{ $plat }}
                    </button>
                    @endforeach
                </div>

                {{-- Edition selector --}}
                <div class="selector-label">Edição</div>
                <select class="edition-select" id="edition-select" onchange="selectEdicao(this)">
                    @foreach($produto->edicoes as $ed)
                    <option value="{{ $ed['preco'] }}" data-nome="{{ $ed['nome'] }}">
                        {{ $ed['nome'] }} — R$ {{ number_format($ed['preco'], 2, ',', '.') }}
                    </option>
                    @endforeach
                </select>

                {{-- Actions --}}
                <div class="actions">
                    <button class="btn-fav" id="btn-fav" onclick="toggleFav(this)">
                        Favoritar ♡
                    </button>
                    <button class="btn-buy">Comprar</button>
                </div>

            </div>

            {{-- Requirements --}}
            @if($produto->requisitos)
            <div class="req-box">
                <div class="req-title">Requisitos do Sistema</div>
                <div class="req-cols">
                    <div class="req-col">
                        <h4>Mínimos</h4>
                        <p>
                            @foreach($produto->requisitos['minimo'] ?? [] as $key => $val)
                                <strong>{{ ucfirst($key) }}:</strong> {{ $val }}<br>
                            @endforeach
                        </p>
                    </div>
                    <div class="req-col">
                        <h4>Recomendados</h4>
                        <p>
                            @foreach($produto->requisitos['recomendado'] ?? [] as $key => $val)
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

{{-- ── FOOTER ── --}}
<footer>
    <div class="footer-inner">
        <a href="{{ url('/') }}" class="nav-logo">
            <svg viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" width="24" height="24">
                <rect x="4" y="16" width="28" height="16" rx="2" fill="#e8b820"/>
                <rect x="4" y="10" width="28" height="7" rx="1.5" fill="#c89a10"/>
                <rect x="15.5" y="10" width="5" height="22" fill="#0d1f1f"/>
                <path d="M18 10 Q13 4 9 6 Q5 8 9 11 Q13 13 18 10Z" fill="#e8b820"/>
                <path d="M18 10 Q23 4 27 6 Q31 8 27 11 Q23 13 18 10Z" fill="#c89a10"/>
            </svg>
            <div class="logo-text" style="font-size:1.1rem"><span class="gift">Gift</span><span class="zone">Zone</span></div>
        </a>
        <span class="footer-copy">© {{ date('Y') }} GiftZone Todos os direitos Reservados</span>
        <div class="footer-social">
            <a href="#" title="Instagram">&#9400;</a>
            <a href="#" title="Twitter">&#120143;</a>
            <a href="#" title="Facebook">&#9993;</a>
        </div>
    </div>
</footer>

<script>
    // ── Galeria ──
    let thumbOffset = 0;

    function selectThumb(el, src) {
        document.getElementById('main-image').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    function galleryNext() {
        const wrap = document.getElementById('gallery-thumbs');
        thumbOffset = Math.min(thumbOffset + 88, wrap.scrollWidth - wrap.clientWidth);
        wrap.scrollTo({ left: thumbOffset, behavior: 'smooth' });
    }

    function galleryPrev() {
        thumbOffset = Math.max(thumbOffset - 88, 0);
        document.getElementById('gallery-thumbs').scrollTo({ left: thumbOffset, behavior: 'smooth' });
    }

    // ── Plataforma ──
    let plataformaSelecionada = document.querySelector('.pill.active')?.textContent.trim() || '';

    function selectPlataforma(el, plat) {
        document.querySelectorAll('#platform-pills .pill').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        plataformaSelecionada = plat;
        document.getElementById('badge-platform').textContent = plat;
        atualizarLabel();
    }

    // ── Edição / Preço ──
    function selectEdicao(sel) {
        const preco = parseFloat(sel.value).toFixed(2).replace('.', ',');
        document.getElementById('price-display').textContent = 'R$ ' + preco;
        atualizarLabel();
    }

    function atualizarLabel() {
        const sel = document.getElementById('edition-select');
        const nomeEd = sel.options[sel.selectedIndex]?.dataset.nome || '';
        document.getElementById('edition-label').textContent = nomeEd + ' — ' + plataformaSelecionada;
    }

    // ── Favoritar ──
    function toggleFav(btn) {
        btn.classList.toggle('faved');
        btn.textContent = btn.classList.contains('faved') ? 'Favoritado ♥' : 'Favoritar ♡';
    }
</script>

</body>
</html>