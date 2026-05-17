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

    /* Resumo financeiro */
    .finance-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 28px;
    }

    .finance-card {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253,233,162,0.2);
        border-radius: 16px;
        padding: 24px 22px;
        display: flex; flex-direction: column; gap: 6px;
    }
    .finance-card.highlight {
        background: rgba(31,109,126,0.35);
        border-color: var(--teal-light);
    }

    .fc-label {
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.45);
    }

    .fc-value {
        font-family: 'Inria Sans', sans-serif;
        font-size: 30px;
        font-weight: 700;
        color: var(--yellow-gold);
    }

    .fc-value.teal { color: var(--teal-light); }

    .fc-sub {
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        color: rgba(255,255,255,0.3);
        font-style: italic;
    }

    /* Seção métodos */
    .section-heading {
        font-family: 'Inter', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--white);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-heading::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255,255,255,0.08);
    }

    /* Botão adicionar método */
    .payment-method-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .payment-method-item {
        background: rgba(0, 26, 32, 0.55);
        border: 1px solid rgba(253,233,162,0.12);
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 18px;
        transition: border-color 0.2s;
    }

    /* item de método salvo — será preenchido pelo banco */
    .pm-icon {
        width: 48px; height: 32px;
        background: rgba(255,255,255,0.08);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        color: var(--yellow-main);
        flex-shrink: 0;
    }
    .pm-info { flex: 1; }
    .pm-name   { font-family: 'Inter', sans-serif; font-weight: 600; font-size: 14px; color: var(--white); }
    .pm-detail { font-family: 'Inria Sans', sans-serif; font-size: 13px; color: rgba(255,255,255,0.45); margin-top: 2px; }
    .pm-remove { color: rgba(255,100,100,0.5); font-size: 14px; cursor: pointer; transition: color 0.2s; }
    .pm-remove:hover { color: #ff6b6b; }
    .pm-badge-principal { background: rgba(245,200,66,0.15); color: var(--yellow-gold); font-family: 'Inria Sans', sans-serif; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; border: 1px solid rgba(245,200,66,0.35); }

    /* Botão de adicionar (tracejado) */
    .btn-add-method {
        background: transparent;
        border: 1px dashed rgba(253,233,162,0.25);
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        width: 100%;
        text-align: left;
    }
    .btn-add-method:hover {
        border-color: var(--yellow-gold);
        background: rgba(253,233,162,0.04);
    }

    .btn-add-icon {
        width: 48px; height: 32px;
        background: rgba(255,255,255,0.05);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px;
        color: rgba(255,255,255,0.3);
        flex-shrink: 0;
        transition: color 0.2s;
    }
    .btn-add-method:hover .btn-add-icon { color: var(--yellow-gold); }

    .btn-add-text {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: rgba(255,255,255,0.35);
        transition: color 0.2s;
    }
    .btn-add-method:hover .btn-add-text { color: var(--yellow-light); }

    @media (max-width: 768px) {
        .finance-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

<p class="page-title">Pagamentos</p>

{{-- Resumo zerado — será preenchido pelo banco --}}
<div class="finance-grid">
    <div class="finance-card">
        <span class="fc-label">Total Gasto</span>
        <span class="fc-value">R$0,00</span>
        <span class="fc-sub">aguardando dados</span>
    </div>
    <div class="finance-card highlight">
        <span class="fc-label">Cashback Acumulado</span>
        <span class="fc-value teal">R$0,00</span>
        <span class="fc-sub">aguardando dados</span>
    </div>
    <div class="finance-card">
        <span class="fc-label">Último Pagamento</span>
        <span class="fc-value" style="font-size: 18px; color: rgba(255,255,255,0.2);">—</span>
        <span class="fc-sub">nenhum registro ainda</span>
    </div>
</div>

{{-- Métodos de pagamento --}}
<p class="section-heading">Métodos de Pagamento</p>

<div class="payment-method-list">

    {{-- Métodos salvos virão do banco:
    @foreach($metodos as $metodo)
    <div class="payment-method-item">
        <div class="pm-icon"><i class="fa-brands fa-{{ $metodo->icone }}"></i></div>
        <div class="pm-info">
            <span class="pm-name">{{ $metodo->nome }}</span>
            <span class="pm-detail">{{ $metodo->detalhe }}</span>
        </div>
        @if($metodo->principal)
            <span class="pm-badge-principal">Principal</span>
        @endif
        <i class="fa-solid fa-trash pm-remove"></i>
    </div>
    @endforeach
    --}}

    {{-- Botão adicionar --}}
    <button type="button" class="btn-add-method">
        <div class="btn-add-icon"><i class="fa-solid fa-plus"></i></div>
        <span class="btn-add-text">Adicionar novo método de pagamento</span>
    </button>

</div>

@endsection