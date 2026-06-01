@extends('admin.layout')

@section('title', __('messages.admin_dashboard'))

@section('extra-styles')
<style>
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253,233,162,0.1);
        border-radius: 14px;
        padding: 22px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: border-color 0.2s;
    }
    .stat-card:hover { border-color: rgba(253,233,162,0.3); }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .stat-icon.products { background: rgba(255,107,53,0.15); color: #ff6b35; }
    .stat-icon.orders   { background: rgba(90,220,232,0.15); color: #5adce8; }
    .stat-icon.sales    { background: rgba(107,255,181,0.15); color: #6bffb5; }
    .stat-icon.users    { background: rgba(253,233,162,0.15); color: #fde9a2; }

    .stat-info { flex: 1; }
    .stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.4); margin-bottom: 4px; }
    .stat-value { font-family: 'Gasoek One', sans-serif; font-size: 24px; color: var(--white); }
    .stat-sub { font-size: 11px; color: rgba(255,255,255,0.3); margin-top: 2px; }

    /* Tabela de pedidos recentes */
    .recent-table-wrap {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253,233,162,0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .recent-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .recent-title { font-family: 'Gasoek One', sans-serif; font-size: 18px; color: var(--yellow-light); }

    .recent-table {
        width: 100%;
        border-collapse: collapse;
    }

    .recent-table th {
        text-align: left;
        padding: 12px 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.35);
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .recent-table td {
        padding: 14px 20px;
        font-size: 13px;
        color: rgba(255,255,255,0.75);
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }

    .recent-table tr:hover td { background: rgba(255,255,255,0.02); }

    .badge-sm {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-paid       { background: rgba(107,255,181,0.15); color: #6bffb5; }
    .badge-pending    { background: rgba(255,193,7,0.15); color: #ffc107; }
    .badge-refunded   { background: rgba(255,80,80,0.15); color: #ff6b6b; }
    .badge-completed  { background: rgba(107,255,181,0.15); color: #6bffb5; }
    .badge-processing { background: rgba(90,220,232,0.15); color: #5adce8; }
    .badge-cancelled  { background: rgba(255,80,80,0.15); color: #ff6b6b; }

    .empty-row td {
        text-align: center;
        padding: 40px;
        color: rgba(255,255,255,0.2);
        font-size: 14px;
    }

    @media (max-width: 900px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .stats-grid { grid-template-columns: 1fr; }
        .recent-table-wrap { overflow-x: auto; }
    }
</style>
@endsection

@section('content')

<p class="page-title">{{ __('messages.admin_dashboard') }}</p>

{{-- Cards de estatísticas --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon products"><i class="fa-solid fa-gamepad"></i></div>
        <div class="stat-info">
            <div class="stat-label">{{ __('messages.admin_total_products') }}</div>
            <div class="stat-value">{{ $totalProdutos }}</div>
            <div class="stat-sub">{{ $produtosAtivos }} {{ __('messages.admin_active') }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orders"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="stat-info">
            <div class="stat-label">{{ __('messages.admin_total_orders') }}</div>
            <div class="stat-value">{{ $totalPedidos }}</div>
            <div class="stat-sub">{{ $pedidosPagos }} {{ __('messages.admin_paid_orders') }} · {{ $pedidosPendentes }} {{ __('messages.admin_pending_orders') }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon sales"><i class="fa-solid fa-chart-line"></i></div>
        <div class="stat-info">
            <div class="stat-label">{{ __('messages.admin_total_sales') }}</div>
            <div class="stat-value">R$ {{ number_format($totalVendas, 2, ',', '.') }}</div>
            <div class="stat-sub">{{ $pedidosPagos }} {{ __('messages.admin_orders') }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon users"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-label">{{ __('messages.admin_total_users') }}</div>
            <div class="stat-value">{{ $totalUsuarios }}</div>
        </div>
    </div>
</div>

{{-- Pedidos Recentes --}}
<div class="recent-table-wrap">
    <div class="recent-header">
        <span class="recent-title">{{ __('messages.admin_recent_orders') }}</span>
        <a href="{{ route('admin.pedidos') }}" class="btn-primary" style="font-size: 12px; padding: 8px 16px;">
            {{ __('messages.see_more') }}
        </a>
    </div>

    <table class="recent-table">
        <thead>
            <tr>
                <th>{{ __('messages.admin_order') }}</th>
                <th>{{ __('messages.admin_customer') }}</th>
                <th>{{ __('messages.admin_value') }}</th>
                <th>{{ __('messages.payment') }}</th>
                <th>{{ __('messages.admin_status') }}</th>
                <th>{{ __('messages.admin_date') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ultimosPedidos as $pedido)
            <tr>
                <td style="font-weight: 700; color: var(--yellow-light);">{{ $pedido->order_number }}</td>
                <td>{{ $pedido->user->name ?? '—' }}</td>
                <td style="font-weight: 600;">R$ {{ number_format($pedido->final_amount, 2, ',', '.') }}</td>
                <td>
                    <span class="badge-sm badge-{{ $pedido->payment_status }}">
                        {{ $pedido->payment_status === 'paid' ? __('messages.paid') : ($pedido->payment_status === 'pending' ? __('messages.pending') : ($pedido->payment_status === 'refunded' ? __('messages.refunded') : $pedido->payment_status)) }}
                    </span>
                </td>
                <td>
                    <span class="badge-sm badge-{{ $pedido->order_status }}">
                        {{ $pedido->order_status === 'completed' ? __('messages.completed') : ($pedido->order_status === 'processing' ? __('messages.processing') : ($pedido->order_status === 'cancelled' ? __('messages.cancelled') : $pedido->order_status)) }}
                    </span>
                </td>
                <td style="color: rgba(255,255,255,0.4);">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="6">{{ __('messages.admin_no_orders') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection