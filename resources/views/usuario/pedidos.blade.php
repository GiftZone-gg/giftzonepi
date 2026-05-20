@extends('usuario.layout')

@section('title', 'Meus Pedidos')

@section('content')
<h1>Meus Pedidos</h1>
@forelse($pedidos as $pedido)
<div style="background: #001A20; border-radius: 12px; padding: 1rem; margin-bottom: 1rem;">
    <strong>Pedido: {{ $pedido->order_number }}</strong><br>
    Data: {{ $pedido->created_at->format('d/m/Y H:i') }}<br>
    Total: R$ {{ number_format($pedido->final_amount, 2, ',', '.') }}<br>
    Status: {{ ucfirst($pedido->order_status) }}<br>
    Pagamento: {{ ucfirst($pedido->payment_status) }}
</div>
@empty
<p>Nenhum pedido encontrado.</p>
@endforelse
@endsection