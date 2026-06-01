@extends('admin.layout')

@section('title', __('messages.admin_orders'))

@section('extra-styles')
<style>
    .order-stats { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .order-stat {
        padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600;
        border: 1px solid rgba(255,255,255,0.06); background: rgba(0,26,32,0.5);
        display: flex; align-items: center; gap: 8px; cursor: pointer;
        transition: all 0.2s; text-decoration: none; color: rgba(255,255,255,0.6);
    }
    .order-stat:hover { border-color: rgba(253,233,162,0.3); color: var(--yellow-light); }
    .order-stat.active { border-color: var(--admin-accent); color: var(--admin-accent); background: rgba(255,107,53,0.08); }
    .order-stat .count { font-family: 'Gasoek One', sans-serif; font-size: 18px; color: var(--white); }

    .toolbar {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 20px;
    }
    .search-input {
        padding: 9px 16px; background: rgba(0,40,48,0.8);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;
        color: #fff; font-size: 13px; outline: none; min-width: 280px;
        transition: border-color 0.2s;
    }
    .search-input:focus { border-color: var(--yellow-gold); }
    .search-input::placeholder { color: rgba(255,255,255,0.25); }

    .orders-table-wrap {
        background: rgba(0,26,32,0.6); border: 1px solid rgba(253,233,162,0.1);
        border-radius: 16px; overflow: hidden;
    }
    .orders-table { width: 100%; border-collapse: collapse; }
    .orders-table th {
        text-align: left; padding: 14px 18px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: rgba(255,255,255,0.35); border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .orders-table td {
        padding: 14px 18px; font-size: 13px; color: rgba(255,255,255,0.75);
        border-bottom: 1px solid rgba(255,255,255,0.03); vertical-align: middle;
    }
    .orders-table tr:hover td { background: rgba(255,255,255,0.02); }

    .badge-sm {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 10px; font-weight: 700; text-transform: uppercase;
    }
    .badge-paid       { background: rgba(107,255,181,0.15); color: #6bffb5; }
    .badge-pending    { background: rgba(255,193,7,0.15); color: #ffc107; }
    .badge-refunded   { background: rgba(255,80,80,0.15); color: #ff6b6b; }
    .badge-completed  { background: rgba(107,255,181,0.15); color: #6bffb5; }
    .badge-processing { background: rgba(90,220,232,0.15); color: #5adce8; }
    .badge-cancelled  { background: rgba(255,80,80,0.15); color: #ff6b6b; }

    .status-form { display: flex; gap: 6px; align-items: center; }
    .status-select {
        padding: 6px 10px; background: rgba(0,40,48,0.8);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 6px;
        color: #fff; font-size: 11px; outline: none; cursor: pointer; appearance: none;
    }
    .status-select option { background: #002830; }
    .btn-status-save {
        padding: 6px 12px; background: var(--admin-accent); border: none;
        border-radius: 6px; color: #fff; font-size: 11px; font-weight: 700;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-status-save:hover { background: #ff8555; }

    .empty-row td { text-align: center; padding: 50px; color: rgba(255,255,255,0.2); font-size: 14px; }

    @media (max-width: 768px) {
        .orders-table-wrap { overflow-x: auto; }
        .search-input { min-width: auto; width: 100%; }
    }
</style>
@endsection

@section('content')

<p class="page-title">{{ __('messages.admin_orders') }}</p>

{{-- Stats --}}
<div class="order-stats">
    <a href="{{ route('admin.pedidos') }}" class="order-stat {{ !request('status') ? 'active' : '' }}">
        <span class="count">{{ $stats['total'] }}</span> {{ __('messages.admin_filter_all') }}
    </a>
    <a href="{{ route('admin.pedidos', ['status' => 'processing']) }}" class="order-stat {{ request('status') === 'processing' ? 'active' : '' }}">
        <span class="count">{{ $stats['processing'] }}</span> {{ __('messages.admin_mark_processing') }}
    </a>
    <a href="{{ route('admin.pedidos', ['status' => 'completed']) }}" class="order-stat {{ request('status') === 'completed' ? 'active' : '' }}">
        <span class="count">{{ $stats['completed'] }}</span> {{ __('messages.admin_mark_completed') }}
    </a>
    <a href="{{ route('admin.pedidos', ['status' => 'cancelled']) }}" class="order-stat {{ request('status') === 'cancelled' ? 'active' : '' }}">
        <span class="count">{{ $stats['cancelled'] }}</span> {{ __('messages.admin_mark_cancelled') }}
    </a>
</div>

{{-- Busca --}}
<div class="toolbar">
    <form action="{{ route('admin.pedidos') }}" method="GET">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="busca" class="search-input" placeholder="{{ __('messages.admin_search') }} (nº pedido, nome, email)" value="{{ request('busca') }}">
    </form>
</div>

{{-- Tabela --}}
<div class="orders-table-wrap">
    <table class="orders-table">
        <thead>
            <tr>
                <th>{{ __('messages.admin_order') }}</th>
                <th>{{ __('messages.admin_customer') }}</th>
                <th>{{ __('messages.admin_items') }}</th>
                <th>{{ __('messages.admin_value') }}</th>
                <th>{{ __('messages.payment') }}</th>
                <th>{{ __('messages.admin_status') }}</th>
                <th>{{ __('messages.admin_date') }}</th>
                <th>{{ __('messages.admin_change_status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)
            <tr>
                <td style="font-weight: 700; color: var(--yellow-light);">{{ $pedido->order_number }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $pedido->user->name ?? '—' }}</div>
                    <div style="font-size: 11px; color: rgba(255,255,255,0.3);">{{ $pedido->user->email ?? '' }}</div>
                </td>
                <td>
                    @foreach($pedido->items as $item)
                        <div style="font-size: 12px;">{{ $item->produto->nome ?? '—' }} × {{ $item->quantity }}</div>
                    @endforeach
                </td>
                <td style="font-weight: 600;">R$ {{ number_format($pedido->final_amount, 2, ',', '.') }}</td>
                <td>
                    @if($pedido->payment_status === 'paid')
                        <span class="badge-sm badge-paid">{{ __('messages.paid') }}</span>
                    @elseif($pedido->payment_status === 'pending')
                        <span class="badge-sm badge-pending">{{ __('messages.pending') }}</span>
                    @elseif($pedido->payment_status === 'refunded')
                        <span class="badge-sm badge-refunded">{{ __('messages.refunded') }}</span>
                    @else
                        <span class="badge-sm">{{ $pedido->payment_status }}</span>
                    @endif
                </td>
                <td>
                    @if($pedido->order_status === 'completed')
                        <span class="badge-sm badge-completed">{{ __('messages.completed') }}</span>
                    @elseif($pedido->order_status === 'processing')
                        <span class="badge-sm badge-processing">{{ __('messages.processing') }}</span>
                    @elseif($pedido->order_status === 'cancelled')
                        <span class="badge-sm badge-cancelled">{{ __('messages.cancelled') }}</span>
                    @else
                        <span class="badge-sm">{{ $pedido->order_status }}</span>
                    @endif
                </td>
                <td style="color: rgba(255,255,255,0.4); white-space: nowrap;">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.pedidos.status', $pedido->id) }}" method="POST" class="status-form">
                        @csrf
                        @method('PUT')
                        <select name="order_status" class="status-select">
                            <option value="processing" {{ $pedido->order_status === 'processing' ? 'selected' : '' }}>{{ __('messages.admin_mark_processing') }}</option>
                            <option value="completed" {{ $pedido->order_status === 'completed' ? 'selected' : '' }}>{{ __('messages.admin_mark_completed') }}</option>
                            <option value="cancelled" {{ $pedido->order_status === 'cancelled' ? 'selected' : '' }}>{{ __('messages.admin_mark_cancelled') }}</option>
                        </select>
                        <button type="submit" class="btn-status-save"><i class="fa-solid fa-check"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="8">{{ __('messages.admin_no_orders') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection