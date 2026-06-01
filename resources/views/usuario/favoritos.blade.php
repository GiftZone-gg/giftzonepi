@extends('usuario.layout')

@section('title', __('messages.favorites'))

@section('extra-styles')
<style>
    .page-title { font-family: 'Gasoek One', sans-serif; font-size: 28px; color: var(--yellow-light);  margin-bottom: 28px; }
    .favorites-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
    .fav-card { background: rgba(0, 26, 32, 0.6); border: 1px solid rgba(253,233,162,0.15); border-radius: 16px; overflow: hidden; transition: transform 0.25s, border-color 0.25s; cursor: pointer; position: relative; }
    .fav-card:hover { transform: translateY(-4px); border-color: rgba(253,233,162,0.45); }
    .fav-thumb { width: 100%; height: 140px; object-fit: cover; display: block; background: #1F6D7E; }
    .fav-body { padding: 16px; }
    .fav-name { font-family: 'Gasoek One', sans-serif; font-size: 15px; color: var(--white); display: block; margin-bottom: 6px; }
    .fav-publisher { font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,0.4); margin-bottom: 12px; display: block; }
    .fav-price { font-family: 'Inria Sans', sans-serif; font-weight: 700; font-size: 18px; color: var(--yellow-gold); }
    .fav-actions { display: flex; gap: 8px; margin-top: 12px; }
    .fav-btn-buy { flex: 1; padding: 9px; background: var(--yellow-main); color: #001A20; border: none; border-radius: 8px; font-family: 'Gasoek One', sans-serif; font-size: 13px; cursor: pointer; transition: background 0.2s; text-align: center; text-decoration: none; }
    .fav-btn-buy:hover { background: var(--yellow-light); }
    .empty-state { text-align: center; padding: 80px 20px; color: rgba(255,255,255,0.25); grid-column: 1 / -1; }
    .empty-state i { font-size: 64px; margin-bottom: 20px; display: block; color: rgba(253,233,162,0.1); }
    .empty-state p { font-family: 'Inter', sans-serif; font-size: 15px; }
</style>
@endsection

@section('content')

<p class="page-title">{{ __('messages.favorites') }}</p>

<div class="favorites-grid">
    @forelse($favoritos as $item)
    @php
        $edicoes = is_array($item->produto->edicoes) ? $item->produto->edicoes : json_decode($item->produto->edicoes, true);
        $primeiraEdicao = $edicoes[0] ?? null;
        $preco = $primeiraEdicao ? $primeiraEdicao['preco'] : 0;
        $plataformas = is_array($item->produto->plataformas) ? $item->produto->plataformas : json_decode($item->produto->plataformas, true);
        $plataforma = $plataformas[0] ?? 'Multi';
        $imagem = $item->produto->imagem_principal ?? 'default.jpg';
    @endphp
    <div class="fav-card">
        <img class="fav-thumb" src="{{ asset('images/' . $imagem) }}" alt="{{ $item->produto->nome }}">
        <form action="{{ route('usuario.favoritos.remover', $item->id) }}" method="POST" style="position: absolute; top: 10px; right: 12px;">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: none; border: none; cursor: pointer; color: #ff5f5f; font-size: 20px; text-shadow: 0 0 8px rgba(255,80,80,0.6);">❤️</button>
        </form>
        <div class="fav-body">
            <span class="fav-name">{{ $item->produto->nome }}</span>
            <span class="fav-publisher">{{ $item->produto->publisher ?? '—' }}</span>
            <span class="fav-price">{{ __('messages.starting_from') }} R$ {{ number_format($preco, 2, ',', '.') }}</span>
            <div class="fav-actions">
                <a href="{{ route('produto.show', $item->produto->slug) }}" class="fav-btn-buy">{{ __('messages.buy') }}</a>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fa-regular fa-heart"></i>
        <p>{{ __('messages.no_favorites') }}</p>
    </div>
    @endforelse
</div>

@endsection