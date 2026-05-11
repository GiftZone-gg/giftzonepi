@extends('usuario.layout')

@section('title', 'Pedidos')

@section('extra-styles')
<style>
    .page-title {
        font-family: 'Gasoek One', sans-serif;
        font-size: 28px;
        color: var(--yellow-gold);
        font-style: italic;
        text-decoration: underline;
        margin-bottom: 28px;
    }

    .filters-bar { display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap; }

    .filter-btn {
        padding: 8px 20px;
        border-radius: 20px;
        border: 1px solid rgba(253,233,162,0.3);
        background: transparent;
        color: rgba(255,255,255,0.6);
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-btn.active,
    .filter-btn:hover { background: var(--yellow-gold); color: #001A20; border-color: var(--yellow-gold); }

    .orders-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }

    .orders-table thead tr th {
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.45);
        padding: 0 18px 8px;
        text-align: left;
    }

    .orders-table tbody tr {
        background: rgba(0, 26, 32, 0.55);
        border: 1px solid rgba(253,233,162,0.12);
        border-radius: 12px;
        transition: border-color 0.2s;
    }
    .orders-table tbody tr:hover { border-color: rgba(253,233,162,0.35); }

    .orders-table tbody tr td {
        padding: 16px 18px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: var(--white);
        vertical-align: middle;
    }
    .orders-table tbody tr td:first-child { border-radius: 12px 0 0 12px; }
    .orders-table tbody tr td:last-child  { border-radius: 0 12px 12px 0; }

    .order-thumb { width: 56px; height: 40px; border-radius: 6px; object-fit: cover; background: #1F6D7E; display: block; }
    .order-name  { font-family: 'Gasoek One', sans-serif; font-size: 14px; color: var(--white); display: block; }
    .order-sub   { font-size: 12px; color: rgba(255,255,255,0.45); margin-top: 3px; }
    .order-id    { font-family: 'Inria Sans', sans-serif; font-size: 13px; color: var(--teal-light); }
    .order-value { font-family: 'Inria Sans', sans-serif; font-weight: 700; font-size: 16px; color: var(--yellow-gold); }
    .order-date  { font-family: 'Inria Sans', sans-serif; font-size: 13px; color: rgba(255,255,255,0.5); }

    .cashback-badge {
        display: inline-block;
        background: rgba(245,200,66,0.2);
        color: var(--yellow-gold);
        font-family: 'Inria Sans', sans-serif;
        font-size: 12px;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid rgba(245,200,66,0.4);
    }

    /* Estado vazio */
    .empty-table {
        text-align: center;
        padding: 60px 20px;
        color: rgba(255,255,255,0.25);
    }
    .empty-table i { font-size: 48px; display: block; margin-bottom: 16px; color: rgba(253,233,162,0.1); }
    .empty-table p { font-family: 'Inter', sans-serif; font-size: 15px; }

    .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 30px; }
    .page-num {
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600;
        cursor: pointer; border: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.5); transition: all 0.2s; text-decoration: none;
    }
    .page-num.active,
    .page-num:hover { background: var(--yellow-gold); color: #001A20; border-color: var(--yellow-gold); }
</style>
@endsection

@section('content')

<p class="page-title">Pedidos</p>

<div class="filters-bar">
    <button class="filter-btn active">Todos</button>
    <button class="filter-btn">Concluído</button>
    <button class="filter-btn">Pendente</button>
    <button class="filter-btn">Cancelado</button>
</div>

<div class="card" style="padding: 24px; overflow-x: auto;">
    <table class="orders-table">
        <thead>
            <tr>
                <th></th>
                <th>Produto</th>
                <th>Nº Pedido</th>
                <th>Status</th>
                <th>Cashback</th>
                <th>Valor</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            {{-- Aqui virão os pedidos do banco de dados --}}
            {{-- Exemplo de como ficará o loop quando integrar:
            @foreach($pedidos as $pedido)
            <tr>
                <td><img class="order-thumb" src="{{ asset('images/' . $pedido->imagem) }}" alt="{{ $pedido->nome }}"></td>
                <td>
                    <span class="order-name">{{ $pedido->nome }}</span>
                    <span class="order-sub">{{ $pedido->publisher }}</span>
                </td>
                <td><span class="order-id">#{{ str_pad($pedido->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                <td><span class="tag-status tag-{{ strtolower($pedido->status) }}">{{ $pedido->status }}</span></td>
                <td><span class="cashback-badge">{{ $pedido->cashback ?? '—' }}</span></td>
                <td><span class="order-value">R${{ $pedido->valor }}</span></td>
                <td><span class="order-date">{{ $pedido->created_at->format('d M Y') }}</span></td>
            </tr>
            @endforeach
            --}}
        </tbody>
    </table>

    {{-- Aparece enquanto não há pedidos --}}
    <div class="empty-table">
        <i class="fa-solid fa-box-open"></i>
        <p>Nenhum pedido encontrado.</p>
    </div>

</div>

<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>

@endsection