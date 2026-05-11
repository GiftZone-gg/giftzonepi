@extends('usuario.layout')

@section('title', 'Meu Perfil')

@section('extra-styles')
<style>
    .profile-header-card {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253, 233, 162, 0.25);
        border-radius: 16px;
        padding: 32px 36px;
        display: flex;
        align-items: center;
        gap: 40px;
        margin-bottom: 28px;
    }

    .profile-avatar-wrap { position: relative; flex-shrink: 0; }

    .profile-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 4px solid var(--yellow-gold);
        object-fit: cover;
        display: block;
        background: #1F6D7E;
        image-rendering: pixelated;
    }

    .profile-info { flex: 1; display: flex; flex-direction: column; gap: 14px; }

    .info-row { display: flex; border-radius: 10px; overflow: hidden; }

    .info-label {
        background: var(--yellow-light);
        color: #001A20;
        font-family: 'Crimson Pro', serif;
        font-style: italic;
        font-weight: 600;
        font-size: 16px;
        padding: 14px 22px;
        min-width: 140px;
        display: flex;
        align-items: center;
    }

    .info-value {
        background: rgba(253,233,162,0.15);
        border: 1px solid rgba(253,233,162,0.3);
        border-left: none;
        color: var(--white);
        font-family: 'Crimson Pro', serif;
        font-weight: 600;
        font-size: 16px;
        padding: 14px 22px;
        flex: 1;
        display: flex;
        align-items: center;
    }

    .bottom-row { display: grid; grid-template-columns: 280px 1fr; gap: 24px; align-items: start; }

    .visao-geral-card {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253, 233, 162, 0.25);
        border-radius: 16px;
        padding: 28px 24px;
    }

    .visao-title { font-family: 'Inter', sans-serif; font-weight: 700; font-size: 22px; color: var(--yellow-main); margin-bottom: 22px; }

    .stat-box { background: rgba(31, 109, 126, 0.45); border-radius: 12px; padding: 22px 18px; text-align: center; margin-bottom: 14px; }
    .stat-box:last-child { margin-bottom: 0; }

    .stat-number { font-family: 'Inria Sans', sans-serif; font-size: 32px; font-weight: 700; color: var(--yellow-gold); display: block; }
    .stat-label  { font-family: 'Inria Sans', sans-serif; font-size: 14px; font-style: italic; color: rgba(255,255,255,0.75); margin-top: 4px; display: block; }

    .ultimas-compras-col {
        background: rgba(0, 26, 32, 0.5);
        border: 1px solid rgba(253, 233, 162, 0.2);
        border-radius: 16px;
        padding: 28px;
    }

    .section-title { font-family: 'Gasoek One', sans-serif; font-size: 22px; color: var(--yellow-gold); font-style: italic; text-decoration: underline; margin-bottom: 20px; }

    .purchase-item {
        background: rgba(0,0,0,0.25);
        border: 1px solid rgba(253,233,162,0.15);
        border-radius: 12px;
        padding: 16px;
        display: grid;
        grid-template-columns: 120px 1fr auto;
        gap: 18px;
        align-items: center;
        position: relative;
        transition: border-color 0.2s;
    }
    .purchase-item:hover { border-color: rgba(253,233,162,0.4); }

    .purchase-thumb { width: 120px; height: 80px; border-radius: 8px; object-fit: cover; background: #1F6D7E; display: block; }

    .purchase-meta { min-width: 0; }

    .purchase-name { font-family: 'Gasoek One'; font-size: 15px; color: var(--white); text-decoration: underline; display: block; margin-bottom: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .purchase-date { font-family: 'Inria Sans', sans-serif; font-size: 12px; color: rgba(255,255,255,0.45); display: block; margin-top: 10px; text-align: right; }

    .purchase-price-col { text-align: right; }

    .price-label { font-family: 'Inria Sans', sans-serif; font-size: 11px; color: var(--yellow-gold); font-style: italic; display: block; }
    .price-value { font-family: 'Inria Sans', sans-serif; font-size: 22px; font-weight: 700; color: var(--white); display: block; }

    @media (max-width: 900px) {
        .bottom-row { grid-template-columns: 1fr; }
        .profile-header-card { flex-direction: column; text-align: center; }
        .purchase-item { grid-template-columns: 80px 1fr; }
        .purchase-price-col { grid-column: 2; }
    }
</style>
@endsection

@section('content')

{{-- CARD PERFIL --}}
<div class="profile-header-card">
    <div class="profile-avatar-wrap">
        <img class="profile-avatar"
             src="{{ asset('images/icone1.svg') }}"
             alt="Avatar do usuário"
             onerror="this.src='https://via.placeholder.com/140/1F6D7E/FFDC74?text=GZ'">
    </div>
    <div class="profile-info">
        <div class="info-row">
            <span class="info-label">Nickname</span>
            <span class="info-value">Michael (MJ) Jackson</span>
        </div>
        <div class="info-row">
            <span class="info-label">E-mail</span>
            <span class="info-value">MJ@gmail.com</span>
        </div>
    </div>
</div>

{{-- LINHA INFERIOR --}}
<div class="bottom-row">

    {{-- VISÃO GERAL --}}
    <div class="visao-geral-card">
        <p class="visao-title">Visão Geral</p>
        <div class="stat-box">
            <span class="stat-number">35</span>
            <span class="stat-label">Pedidos</span>
        </div>
        <div class="stat-box">
            <span class="stat-number" style="font-style: italic;">R$520</span>
            <span class="stat-label">Gasto Total</span>
        </div>
        <div class="stat-box">
            <span class="stat-number">5</span>
            <span class="stat-label">Favoritos</span>
        </div>
    </div>

    <div class="ultimas-compras-col">
        <p class="section-title">Últimas Compras</p>

        <div class="purchase-item">
            <img class="purchase-thumb"
                 src="{{ asset('images/deathstran.svg') }}"
                 alt="Death Stranding 2"
                 onerror="this.src='https://via.placeholder.com/120x80/1F6D7E/FFDC74?text=DS2'">
            <div class="purchase-meta">
                <span class="purchase-name">Death Stranding 2: On The Beach</span>
                <span class="tag-status tag-concluido">Concluído</span>
                <span class="purchase-date">13 fev 2026</span>
            </div>
            <div class="purchase-price-col">
                <span class="price-label">Valor</span>
                <span class="price-value">R$350</span>
            </div>
        </div>

    </div>
</div>

@endsection