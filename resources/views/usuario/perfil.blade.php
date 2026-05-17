@extends('usuario.layout')

@section('title', 'Meu Perfil')

@section('extra-styles')
<style>

    /* ===== CARD PERFIL ===== */
    .profile-header-card {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid #fde9a2;
        border-radius: 20px;
        padding: 36px 40px;
        display: flex;
        align-items: center;
        gap: 44px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }

    .profile-header-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--yellow-gold), var(--teal-light), transparent);
        border-radius: 20px 20px 0 0;
    }

    .profile-avatar-wrap { position: relative; flex-shrink: 0; }

    .profile-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 4px solid var(--yellow-gold);
        object-fit: cover;
        display: block;
        background: #1F6D7E;
        image-rendering: pixelated;
        box-shadow: 0 0 24px rgba(245,200,66,0.25);
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 4px; right: 4px;
        width: 28px; height: 28px;
        background: var(--yellow-gold);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; color: #001A20;
        text-decoration: none;
        transition: transform 0.2s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }
    .avatar-edit-btn:hover { transform: scale(1.15); }

    .profile-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .profile-greeting {
        font-family: 'Gasoek One', sans-serif;
        font-size: 15px;
        color: #fde9a2;
        letter-spacing: 0.08em;
        margin-bottom: 4px;
    }

    .info-row {
        display: flex;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .info-label {
        background: var(--yellow-light);
        color: #001A20;
        font-family: 'Crimson Pro', serif;
        font-style: italic;
        font-weight: 600;
        font-size: 15px;
        padding: 13px 20px;
        min-width: 130px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        background: rgba(253,233,162,0.1);
        border: 1px solid rgba(253,233,162,0.25);
        border-left: none;
        color: var(--white);
        font-family: 'Crimson Pro', serif;
        font-weight: 600;
        font-size: 15px;
        padding: 13px 20px;
        flex: 1;
        display: flex;
        align-items: center;
    }

    /* ===== ÚLTIMAS COMPRAS (tela cheia) ===== */
    .ultimas-compras-col {
        background: rgba(0, 26, 32, 0.55);
        border: 1px solid rgba(253, 233, 162, 0.2);
        border-radius: 20px;
        padding: 32px 36px;
    }

    .section-title {
        font-family: 'Gasoek One', sans-serif;
        font-size: 22px;
        color: var(--yellow-gold);
        font-style: italic;
        text-decoration: underline;
        margin-bottom: 24px;
        text-align: center;
    }

    .empty-compras {
        text-align: center;
        padding: 50px 20px;
        color: rgba(255,255,255,0.2);
    }
    .empty-compras i {
        font-size: 48px;
        display: block;
        margin-bottom: 14px;
        color: rgba(253,233,162,0.1);
    }
    .empty-compras p {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
    }

    .purchase-item {
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(253,233,162,0.12);
        border-radius: 12px;
        padding: 14px;
        display: grid;
        grid-template-columns: 110px 1fr auto;
        gap: 16px;
        align-items: center;
        margin-bottom: 12px;
        position: relative;
        transition: border-color 0.2s;
    }
    .purchase-item:last-child { margin-bottom: 0; }
    .purchase-item:hover { border-color: rgba(253,233,162,0.35); }

    .purchase-thumb { width: 110px; height: 72px; border-radius: 8px; object-fit: cover; background: #1F6D7E; display: block; }
    .purchase-meta { min-width: 0; }
    .purchase-name { font-family: 'Gasoek One'; font-size: 14px; color: var(--white); text-decoration: underline; display: block; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .purchase-date { font-family: 'Inria Sans', sans-serif; font-size: 11px; color: rgba(255,255,255,0.4); display: block; margin-top: 8px; text-align: right; }
    .purchase-price-col { text-align: right; }
    .price-label { font-family: 'Inria Sans', sans-serif; font-size: 11px; color: var(--yellow-gold); font-style: italic; display: block; }
    .price-value { font-family: 'Inria Sans', sans-serif; font-size: 20px; font-weight: 700; color: var(--white); display: block; }

    @media (max-width: 900px) {
        .profile-header-card { flex-direction: column; align-items: center; text-align: center; padding: 28px 20px; }
        .info-label { min-width: 110px; }
        .purchase-item { grid-template-columns: 80px 1fr; }
        .purchase-price-col { grid-column: 2; }
        .ultimas-compras-col { padding: 24px 18px; }
    }
</style>
@endsection

@section('content')

{{-- CARD PERFIL --}}
<div class="profile-header-card">
    <div class="profile-avatar-wrap">
        <img class="profile-avatar"
             src="{{ asset('images/icone2.svg') }}"
             alt="Avatar do usuário"
             onerror="this.src='https://via.placeholder.com/130/1F6D7E/FFDC74?text=GZ'">
        <a href="{{ route('usuario.editar') }}" class="avatar-edit-btn" title="Editar perfil">
            <i class="fa-solid fa-pen"></i>
        </a>
    </div>

    <div class="profile-info">
        <p class="profile-greeting">Bem-Vindo(a)!</p>
        <div class="info-row">
            <span class="info-label">
                <i class="fa-solid fa-user" style="font-size:12px; opacity:0.6;"></i>
                Nickname
            </span>
            <span class="info-value">Michael (MJ) Jackson</span>
        </div>
        <div class="info-row">
            <span class="info-label">
                <i class="fa-solid fa-envelope" style="font-size:12px; opacity:0.6;"></i>
                E-mail
            </span>
            <span class="info-value">MJ@gmail.com</span>
        </div>
    </div>
</div>

{{-- ÚLTIMAS COMPRAS — largura total --}}
<div class="ultimas-compras-col">
    <p class="section-title">Últimas Compras</p>

    {{-- Loop quando integrar o banco:
    @foreach($ultimasCompras as $compra)
    <div class="purchase-item">
        @if($compra->cashback)
            <span class="badge-cashback">{{ $compra->cashback }}% cashback</span>
        @endif
        <img class="purchase-thumb" src="{{ asset('images/' . $compra->imagem) }}" alt="{{ $compra->nome }}">
        <div class="purchase-meta">
            <span class="purchase-name">{{ $compra->nome }}</span>
            <span class="tag-status tag-{{ strtolower($compra->status) }}">{{ $compra->status }}</span>
            <span class="purchase-date">{{ $compra->created_at->format('d M Y') }}</span>
        </div>
        <div class="purchase-price-col">
            <span class="price-label">Valor</span>
            <span class="price-value">R${{ $compra->valor }}</span>
        </div>
    </div>
    @endforeach
    --}}

    <div class="empty-compras">
        <i class="fa-solid fa-bag-shopping"></i>
        <p>Nenhuma compra realizada ainda.</p>
    </div>
</div>

@endsection