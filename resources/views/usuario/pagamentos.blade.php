@extends('usuario.layout')

@section('title', __('messages.payments'))

@section('extra-styles')
<style>
    .page-title {
        font-family: 'Gasoek One', sans-serif;
        font-size: 28px;
        color: var(--yellow-light);
        margin-bottom: 28px;
    }

    /* Alertas */
    .alert { border-radius: 10px; padding: 12px 18px; margin-bottom: 16px; font-size: 14px; font-weight: 500; }
    .alert-success { background: rgba(107,255,181,0.1); border: 1px solid rgba(107,255,181,0.3); color: #6bffb5; }
    .alert-error { background: rgba(255,80,80,0.1); border: 1px solid rgba(255,80,80,0.3); color: #ff6b6b; }

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

    .section-sub {
        font-size: 12px;
        color: rgba(255,255,255,0.3);
        margin-bottom: 16px;
    }

    /* Lista de cartões */
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
    .payment-method-item:hover { border-color: rgba(253,233,162,0.3); }
    .payment-method-item.is-primary { border-color: rgba(253,233,162,0.35); background: rgba(253,233,162,0.04); }

    .pm-icon {
        width: 56px; height: 38px;
        background: rgba(255,255,255,0.08);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px;
        font-weight: 700;
        color: var(--yellow-main);
        flex-shrink: 0;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .pm-icon.visa { background: linear-gradient(135deg, #1a1f71, #2a3f9d); color: #fff; }
    .pm-icon.mastercard { background: linear-gradient(135deg, #eb001b, #f79e1b); color: #fff; }
    .pm-icon.elo { background: linear-gradient(135deg, #000, #ffcb05); color: #fff; }
    .pm-icon.amex { background: linear-gradient(135deg, #006fcf, #00aeef); color: #fff; }
    .pm-icon.hipercard { background: linear-gradient(135deg, #822124, #b52427); color: #fff; }
    .pm-icon.diners { background: linear-gradient(135deg, #004c97, #0079c1); color: #fff; }
    .pm-icon.discover { background: linear-gradient(135deg, #ff6000, #ff8c00); color: #fff; }
    .pm-icon.jcb { background: linear-gradient(135deg, #0e4c96, #bc0e35); color: #fff; }
    .pm-icon.generic { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5); }

    .pm-info { flex: 1; }
    .pm-name { font-family: 'Inter', sans-serif; font-weight: 600; font-size: 14px; color: var(--white); }
    .pm-detail { font-family: 'Inria Sans', sans-serif; font-size: 13px; color: rgba(255,255,255,0.45); margin-top: 2px; }

    .pm-badge-principal {
        background: rgba(245,200,66,0.15);
        color: var(--yellow-gold);
        font-family: 'Inria Sans', sans-serif;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        border: 1px solid rgba(245,200,66,0.35);
        flex-shrink: 0;
    }

    .pm-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .pm-btn {
        background: none;
        border: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.4);
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .pm-btn:hover { border-color: rgba(255,255,255,0.3); color: rgba(255,255,255,0.7); }
    .pm-btn.danger { border-color: rgba(255,80,80,0.3); color: rgba(255,80,80,0.5); }
    .pm-btn.danger:hover { border-color: #ff6b6b; color: #ff6b6b; background: rgba(255,80,80,0.1); }
    .pm-btn.primary-btn { border-color: rgba(245,200,66,0.3); color: rgba(245,200,66,0.5); }
    .pm-btn.primary-btn:hover { border-color: var(--yellow-gold); color: var(--yellow-gold); background: rgba(245,200,66,0.08); }

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
    .btn-add-method:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }
    .btn-add-method:disabled:hover {
        border-color: rgba(253,233,162,0.25);
        background: transparent;
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

    /* ═══ MODAL ═══ */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-overlay.active { display: flex; }

    .modal-card {
        background: #001A20;
        border: 1px solid var(--yellow-gold);
        border-radius: 16px;
        max-width: 460px;
        width: 100%;
        padding: 32px 28px;
    }

    .modal-title {
        font-family: 'Gasoek One', sans-serif;
        font-size: 20px;
        color: var(--yellow-light);
        margin-bottom: 24px;
        text-align: center;
    }

    .card-preview {
        background: linear-gradient(135deg, #0C4F58, #01313A);
        border: 1px solid rgba(253,233,162,0.2);
        border-radius: 14px;
        padding: 20px 22px;
        margin-bottom: 24px;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s;
    }

    .card-preview-brand {
        font-family: 'Gasoek One', sans-serif;
        font-size: 16px;
        color: var(--yellow-gold);
        text-align: right;
        min-height: 22px;
        transition: color 0.3s;
    }

    .card-preview-number {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 600;
        letter-spacing: 3px;
        color: rgba(255,255,255,0.7);
        margin: 14px 0;
    }

    .card-preview-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .card-preview-holder {
        font-size: 11px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.45);
        letter-spacing: 1px;
    }

    .card-preview-expiry {
        font-size: 12px;
        color: rgba(255,255,255,0.45);
    }

    .form-row {
        margin-bottom: 16px;
    }

    .form-row label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,0.5);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 6px;
    }

    .form-row input, .form-row select {
        width: 100%;
        padding: 12px 14px;
        background: rgba(0,40,48,0.8);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        color: var(--white);
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        outline: none;
        transition: border-color 0.25s;
    }
    .form-row input:focus, .form-row select:focus { border-color: var(--yellow-gold); }
    .form-row input::placeholder { color: rgba(255,255,255,0.2); }
    .form-row select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23FDE9A2' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; }
    .form-row select option { background: #002830; }

    .form-row-half {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .modal-actions button {
        flex: 1;
        padding: 14px;
        border-radius: 10px;
        font-family: 'Gasoek One', sans-serif;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-cancel {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.15);
        color: rgba(255,255,255,0.5);
    }
    .btn-modal-cancel:hover { border-color: rgba(255,255,255,0.4); color: white; }

    .btn-modal-save {
        background: var(--yellow-gold);
        border: none;
        color: #001A20;
    }
    .btn-modal-save:hover { background: var(--yellow-light); transform: translateY(-1px); }

    @media (max-width: 768px) {
        .finance-grid { grid-template-columns: 1fr; }
        .form-row-half { grid-template-columns: 1fr; }
        .payment-method-item { flex-wrap: wrap; }
        .pm-actions { width: 100%; justify-content: flex-end; }
    }
</style>
@endsection

@section('content')

<p class="page-title">{{ __('messages.payments') }}</p>

@if(session('success'))
    <div class="alert alert-success"><i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error"><i class="fa-solid fa-xmark-circle" style="margin-right: 8px;"></i>{{ session('error') }}</div>
@endif

{{-- Resumo financeiro --}}
<div class="finance-grid">
    <div class="finance-card">
        <span class="fc-label">{{ __('messages.total_spent') }}</span>
        <span class="fc-value">R$ {{ number_format($totalGasto, 2, ',', '.') }}</span>
        <span class="fc-sub">
            {{ $ultimoPagamento ? ($ultimoPagamento->created_at->diffForHumans()) : __('messages.awaiting_data') }}
        </span>
    </div>
    <div class="finance-card highlight">
        <span class="fc-label">{{ __('messages.cashback') }}</span>
        <span class="fc-value teal">R$ {{ number_format($totalGasto * 0.02, 2, ',', '.') }}</span>
        <span class="fc-sub">2% cashback</span>
    </div>
    <div class="finance-card">
        <span class="fc-label">{{ __('messages.last_payment') }}</span>
        @if($ultimoPagamento)
            <span class="fc-value" style="font-size: 18px;">R$ {{ number_format($ultimoPagamento->final_amount, 2, ',', '.') }}</span>
            <span class="fc-sub">{{ $ultimoPagamento->created_at->format('d/m/Y H:i') }}</span>
        @else
            <span class="fc-value" style="font-size: 18px; color: rgba(255,255,255,0.2);">—</span>
            <span class="fc-sub">{{ __('messages.no_record') }}</span>
        @endif
    </div>
</div>

{{-- Métodos de pagamento --}}
<p class="section-heading">{{ __('messages.payment_methods') }}</p>
<p class="section-sub">{{ $metodos->count() }}/4 {{ __('messages.cards_count') }}</p>

<div class="payment-method-list">

    @foreach($metodos as $metodo)
    <div class="payment-method-item {{ $metodo->is_primary ? 'is-primary' : '' }}">
        <div class="pm-icon {{ $metodo->brand }}">
            {{ strtoupper(substr($metodo->brand, 0, 4)) }}
        </div>
        <div class="pm-info">
            <span class="pm-name">{{ \App\Models\PaymentMethod::brandIcon($metodo->brand) }} •••• {{ $metodo->last_four }}</span>
            <span class="pm-detail">{{ $metodo->holder_name }} · {{ ucfirst($metodo->type === 'credito' ? __('messages.credit') : __('messages.debit')) }} · {{ $metodo->expiry }}</span>
        </div>

        @if($metodo->is_primary)
            <span class="pm-badge-principal">{{ __('messages.primary') }}</span>
        @endif

        <div class="pm-actions">
            @if(!$metodo->is_primary)
            <form action="{{ route('usuario.cartao.principal', $metodo->id) }}" method="POST">
                @csrf
                <button type="submit" class="pm-btn primary-btn" title="{{ __('messages.set_primary') }}">
                    <i class="fa-solid fa-star"></i>
                </button>
            </form>
            @endif

            <form action="{{ route('usuario.cartao.remover', $metodo->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.remove_card') }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="pm-btn danger" title="{{ __('messages.remove_card') }}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach

    {{-- Botão adicionar --}}
    <button type="button" class="btn-add-method" id="btnOpenModal" {{ $metodos->count() >= 4 ? 'disabled' : '' }}>
        <div class="btn-add-icon"><i class="fa-solid fa-plus"></i></div>
        <span class="btn-add-text">
            {{ $metodos->count() >= 4 ? __('messages.max_cards_reached') : __('messages.add_payment_method') }}
        </span>
    </button>

</div>

{{-- ═══ MODAL ADICIONAR CARTÃO ═══ --}}
<div class="modal-overlay" id="cardModal">
    <div class="modal-card">
        <p class="modal-title">{{ __('messages.add_card') }}</p>

        {{-- Preview do cartão --}}
        <div class="card-preview" id="cardPreview">
            <div class="card-preview-brand" id="previewBrand">💳</div>
            <div class="card-preview-number" id="previewNumber">•••• •••• •••• ••••</div>
            <div class="card-preview-bottom">
                <div class="card-preview-holder" id="previewHolder">{{ __('messages.holder_name') }}</div>
                <div class="card-preview-expiry" id="previewExpiry">MM/AAAA</div>
            </div>
        </div>

        <form action="{{ route('usuario.cartao.adicionar') }}" method="POST" id="cardForm">
            @csrf

            <div class="form-row">
                <label>{{ __('messages.card_number') }}</label>
                <input type="text" name="card_number" id="inputNumber" placeholder="0000 0000 0000 0000" maxlength="19" required autocomplete="cc-number">
            </div>

            <div class="form-row">
                <label>{{ __('messages.holder_name') }}</label>
                <input type="text" name="holder_name" id="inputHolder" placeholder="NOME COMO NO CARTÃO" required autocomplete="cc-name" style="text-transform: uppercase;">
            </div>

            <div class="form-row-half">
                <div class="form-row">
                    <label>{{ __('messages.expiry_date') }}</label>
                    <input type="text" name="expiry" id="inputExpiry" placeholder="MM/AAAA" maxlength="7" required autocomplete="cc-exp">
                </div>
                <div class="form-row">
                    <label>{{ __('messages.card_type') }}</label>
                    <select name="card_type" id="inputType" required>
                        <option value="credito">{{ __('messages.credit') }}</option>
                        <option value="debito">{{ __('messages.debit') }}</option>
                    </select>
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="fecharModal()">{{ __('messages.cancel') }}</button>
                <button type="submit" class="btn-modal-save">{{ __('messages.add_card') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ─── Modal ───
    const modal = document.getElementById('cardModal');
    document.getElementById('btnOpenModal')?.addEventListener('click', () => {
        modal.classList.add('active');
    });
    function fecharModal() {
        modal.classList.remove('active');
        document.getElementById('cardForm').reset();
        document.getElementById('previewBrand').textContent = '💳';
        document.getElementById('previewNumber').textContent = '•••• •••• •••• ••••';
        document.getElementById('previewHolder').textContent = @json(__('messages.holder_name'));
        document.getElementById('previewExpiry').textContent = 'MM/AAAA';
        document.getElementById('cardPreview').style.background = '';
    }
    modal.addEventListener('click', (e) => { if (e.target === modal) fecharModal(); });

    // ─── Detecção de bandeira em tempo real ───
    const brandData = {
        visa:       { name: 'VISA',       gradient: 'linear-gradient(135deg, #1a1f71, #2a3f9d)' },
        mastercard: { name: 'MASTERCARD', gradient: 'linear-gradient(135deg, #eb001b, #f79e1b)' },
        amex:       { name: 'AMEX',       gradient: 'linear-gradient(135deg, #006fcf, #00aeef)' },
        elo:        { name: 'ELO',        gradient: 'linear-gradient(135deg, #000, #ffcb05)' },
        hipercard:  { name: 'HIPERCARD',  gradient: 'linear-gradient(135deg, #822124, #b52427)' },
        diners:     { name: 'DINERS',     gradient: 'linear-gradient(135deg, #004c97, #0079c1)' },
        discover:   { name: 'DISCOVER',   gradient: 'linear-gradient(135deg, #ff6000, #ff8c00)' },
        jcb:        { name: 'JCB',        gradient: 'linear-gradient(135deg, #0e4c96, #bc0e35)' },
        generic:    { name: '💳',          gradient: 'linear-gradient(135deg, #0C4F58, #01313A)' },
    };

    function detectBrand(number) {
        const n = number.replace(/\D/g, '');
        if (/^4/.test(n)) return 'visa';
        if (/^(5[1-5]|2[2-7])/.test(n)) return 'mastercard';
        if (/^3[47]/.test(n)) return 'amex';
        if (/^(636368|636297|504175|438935|451416|509)/.test(n)) return 'elo';
        if (/^(606282|3841)/.test(n)) return 'hipercard';
        if (/^3(0[0-5]|[68])/.test(n)) return 'diners';
        if (/^6(011|5)/.test(n)) return 'discover';
        if (/^(2131|1800|35)/.test(n)) return 'jcb';
        return 'generic';
    }

    // ─── Máscara do número ───
    const inputNumber = document.getElementById('inputNumber');
    inputNumber.addEventListener('input', (e) => {
        let val = e.target.value.replace(/\D/g, '');
        // Formata em grupos de 4
        val = val.replace(/(\d{4})(?=\d)/g, '$1 ');
        e.target.value = val.substring(0, 19);

        // Detecta bandeira
        const brand = detectBrand(val);
        const info = brandData[brand];
        document.getElementById('previewBrand').textContent = info.name;
        document.getElementById('cardPreview').style.background = info.gradient;

        // Preview do número
        const clean = val.replace(/\D/g, '');
        let display = '';
        for (let i = 0; i < 16; i++) {
            if (i > 0 && i % 4 === 0) display += ' ';
            display += clean[i] || '•';
        }
        document.getElementById('previewNumber').textContent = display;
    });

    // ─── Preview do nome ───
    document.getElementById('inputHolder').addEventListener('input', (e) => {
        const val = e.target.value.toUpperCase();
        document.getElementById('previewHolder').textContent = val || @json(__('messages.holder_name'));
    });

    // ─── Máscara da validade ───
    const inputExpiry = document.getElementById('inputExpiry');
    inputExpiry.addEventListener('input', (e) => {
        let val = e.target.value.replace(/\D/g, '');
        if (val.length >= 2) {
            val = val.substring(0, 2) + '/' + val.substring(2, 6);
        }
        e.target.value = val;
        document.getElementById('previewExpiry').textContent = val || 'MM/AAAA';
    });
</script>

@endsection