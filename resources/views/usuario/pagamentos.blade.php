@extends('usuario.layout')

@section('title', 'Pagamentos')

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

    .finance-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 28px; }

    .finance-card {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253,233,162,0.2);
        border-radius: 16px;
        padding: 24px 22px;
        display: flex; flex-direction: column; gap: 6px;
    }
    .finance-card .fc-label { font-family: 'Inter', sans-serif; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.45); }
    .finance-card .fc-value { font-family: 'Inria Sans', sans-serif; font-size: 30px; font-weight: 700; color: var(--yellow-gold); }
    .finance-card .fc-sub   { font-family: 'Inter', sans-serif; font-size: 12px; color: rgba(255,255,255,0.4); }
    .finance-card.highlight { background: rgba(31,109,126,0.35); border-color: var(--teal-light); }

    .section-heading {
        font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 700; color: var(--white);
        margin-bottom: 16px; display: flex; align-items: center; gap: 10px;
    }
    .section-heading::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.08); }

    .payment-method-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 28px; }

    .payment-method-item {
        background: rgba(0, 26, 32, 0.55);
        border: 1px solid rgba(253,233,162,0.12);
        border-radius: 12px; padding: 16px 20px;
        display: flex; align-items: center; gap: 18px;
        transition: border-color 0.2s;
    }
    .payment-method-item:hover { border-color: rgba(253,233,162,0.35); }

    .pm-icon { width: 48px; height: 32px; background: rgba(255,255,255,0.08); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--yellow-main); flex-shrink: 0; }
    .pm-info { flex: 1; }
    .pm-name   { font-family: 'Inter', sans-serif; font-weight: 600; font-size: 14px; color: var(--white); }
    .pm-detail { font-family: 'Inria Sans', sans-serif; font-size: 13px; color: rgba(255,255,255,0.45); margin-top: 2px; }

    .pm-badge-principal { background: rgba(245,200,66,0.15); color: var(--yellow-gold); font-family: 'Inria Sans', sans-serif; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; border: 1px solid rgba(245,200,66,0.35); }

    .pm-remove { color: rgba(255,100,100,0.5); font-size: 14px; cursor: pointer; transition: color 0.2s; }
    .pm-remove:hover { color: #ff6b6b; }

    .finance-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .finance-table thead th { font-family: 'Inter', sans-serif; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.4); padding: 0 16px 6px; text-align: left; }
    .finance-table tbody tr { background: rgba(0, 26, 32, 0.5); border: 1px solid rgba(253,233,162,0.1); border-radius: 10px; }
    .finance-table tbody td { padding: 14px 16px; font-family: 'Inria Sans', sans-serif; font-size: 14px; color: var(--white); vertical-align: middle; }
    .finance-table tbody tr td:first-child { border-radius: 10px 0 0 10px; }
    .finance-table tbody tr td:last-child  { border-radius: 0 10px 10px 0; }

    .tx-negative { color: #ff6b6b; font-weight: 700; }
    .tx-positive { color: #6bffb5; font-weight: 700; }

    /* Estado vazio */
    .empty-table { text-align: center; padding: 60px 20px; color: rgba(255,255,255,0.25); }
    .empty-table i { font-size: 48px; display: block; margin-bottom: 16px; color: rgba(253,233,162,0.1); }
    .empty-table p { font-family: 'Inter', sans-serif; font-size: 15px; }

    @media (max-width: 768px) { .finance-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')

<p class="page-title">Pagamentos</p>

{{-- Resumo --}}
<div class="finance-grid">
    <div class="finance-card">
        <span class="fc-label">Total Gasto</span>
        <span class="fc-value">R$520</span>
        <span class="fc-sub">em 35 pedidos</span>
    </div>
    <div class="finance-card highlight">
        <span class="fc-label">Cashback Acumulado</span>
        <span class="fc-value" style="color: var(--teal-light);">R$15,50</span>
        <span class="fc-sub">disponível para uso</span>
    </div>
    <div class="finance-card">
        <span class="fc-label">Último Pagamento</span>
        <span class="fc-value" style="font-size: 20px; line-height: 1.4;">13 fev 2026</span>
        <span class="fc-sub">League of Legends - R$100</span>
    </div>
</div>

<p class="section-heading">Métodos de Pagamento</p>

<div class="payment-method-list">
    <div class="payment-method-item">
        <div class="pm-icon"><i class="fa-brands fa-pix"></i></div>
        <div class="pm-info">
            <span class="pm-name">Pix</span>
            <span class="pm-detail">MJ@gmail.com</span>
        </div>
        <span class="pm-badge-principal">Principal</span>
        <i class="fa-solid fa-trash pm-remove"></i>
    </div>
    <div class="payment-method-item">
        <div class="pm-icon"><i class="fa-brands fa-cc-visa"></i></div>
        <div class="pm-info">
            <span class="pm-name">Cartão de Crédito</span>
            <span class="pm-detail">•••• •••• •••• 4892 · Visa</span>
        </div>
        <i class="fa-solid fa-trash pm-remove"></i>
    </div>
    <div class="payment-method-item" style="border-style: dashed; cursor: pointer;">
        <div class="pm-icon" style="color: rgba(255,255,255,0.3);"><i class="fa-solid fa-plus"></i></div>
        <span style="font-family: 'Inter', sans-serif; font-size: 14px; color: rgba(255,255,255,0.35);">Adicionar novo método</span>
    </div>
</div>

<p class="section-heading">Histórico de Transações</p>

<div class="card" style="padding: 20px; overflow-x: auto;">
    <table class="finance-table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Método</th>
                <th>Valor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- Aqui virão as transações do banco de dados --}}
            {{-- Exemplo de como ficará o loop quando integrar:
            @foreach($transacoes as $tx)
            <tr>
                <td>{{ $tx->created_at->format('d M Y') }}</td>
                <td>{{ $tx->descricao }}</td>
                <td>{{ $tx->metodo }}</td>
                <td class="{{ $tx->tipo === 'debito' ? 'tx-negative' : 'tx-positive' }}">
                    {{ $tx->tipo === 'debito' ? '-' : '+' }}R${{ $tx->valor }}
                </td>
                <td><span class="tag-status tag-{{ strtolower($tx->status) }}">{{ $tx->status }}</span></td>
            </tr>
            @endforeach
            --}}
        </tbody>
    </table>

    <div class="empty-table">
        <i class="fa-solid fa-receipt"></i>
        <p>Nenhuma transação encontrada.</p>
    </div>
</div>

@endsection