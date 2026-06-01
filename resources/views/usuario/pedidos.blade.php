@extends('usuario.layout')

@section('title', __('messages.my_orders'))

@section('extra-styles')
<style>
    .page-title { font-family: 'Gasoek One', sans-serif; font-size: 28px; color: var(--yellow-light); margin-bottom: 28px; }
    .alert { border-radius: 10px; padding: 12px 18px; margin-bottom: 16px; font-size: 14px; font-weight: 500; }
    .alert-success { background: rgba(107,255,181,0.1); border: 1px solid rgba(107,255,181,0.3); color: #6bffb5; }
    .alert-error { background: rgba(255,80,80,0.1); border: 1px solid rgba(255,80,80,0.3); color: #ff6b6b; }
    .order-card { background: rgba(0, 26, 32, 0.6); border: 1px solid rgba(253,233,162,0.15); border-radius: 16px; padding: 24px; margin-bottom: 20px; transition: border-color 0.2s; }
    .order-card:hover { border-color: rgba(253,233,162,0.35); }
    .order-header { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px; margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.06); }
    .order-number { font-family: 'Gasoek One', sans-serif; font-size: 16px; color: var(--yellow-light); }
    .order-meta { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 4px; }
    .order-total { font-family: 'Gasoek One', sans-serif; font-size: 20px; color: var(--yellow-gold); text-align: right; }
    .status-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 6px; }
    .badge-status { display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-paid { background: rgba(107,255,181,0.15); color: #6bffb5; border: 1px solid rgba(107,255,181,0.3); }
    .badge-pending { background: rgba(255,193,7,0.15); color: #ffc107; border: 1px solid rgba(255,193,7,0.3); }
    .badge-refunded { background: rgba(255,80,80,0.15); color: #ff6b6b; border: 1px solid rgba(255,80,80,0.3); }
    .badge-completed { background: rgba(107,255,181,0.15); color: #6bffb5; border: 1px solid rgba(107,255,181,0.3); }
    .badge-cancelled { background: rgba(255,80,80,0.15); color: #ff6b6b; border: 1px solid rgba(255,80,80,0.3); }
    .badge-processing { background: rgba(58,184,200,0.15); color: #3ab8c8; border: 1px solid rgba(58,184,200,0.3); }
    .order-item { display: flex; align-items: center; gap: 16px; padding: 12px; border-radius: 10px; margin-bottom: 8px; background: rgba(0,40,48,0.5); cursor: pointer; transition: all 0.2s; border: 1px solid transparent; }
    .order-item:hover { background: rgba(0,40,48,0.8); border-color: rgba(253,233,162,0.2); }
    .item-thumb { width: 64px; height: 44px; border-radius: 8px; overflow: hidden; flex-shrink: 0; background: #1F6D7E; }
    .item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .item-info { flex: 1; }
    .item-name { font-weight: 600; font-size: 14px; color: white; }
    .item-detail { font-size: 12px; color: rgba(255,255,255,0.4); margin-top: 2px; }
    .item-key-status { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; flex-shrink: 0; }
    .key-hidden { background: rgba(253,233,162,0.1); color: var(--yellow-gold); }
    .key-shown { background: rgba(107,255,181,0.1); color: #6bffb5; }
    .item-price { font-weight: 700; font-size: 14px; color: var(--yellow-light); flex-shrink: 0; }
    .order-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.06); }
    .btn-cancel { padding: 8px 20px; background: transparent; border: 1px solid #ff6b6b; color: #ff6b6b; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-cancel:hover { background: rgba(255,80,80,0.15); }
    .empty-pedidos { text-align: center; padding: 80px 20px; }
    .empty-pedidos i { font-size: 48px; color: rgba(253,233,162,0.15); display: block; margin-bottom: 16px; }
    .empty-pedidos h2 { font-family: 'Gasoek One', sans-serif; font-size: 22px; color: var(--yellow-light); margin-bottom: 8px; }
    .empty-pedidos p { font-size: 14px; color: rgba(255,255,255,0.3); margin-bottom: 24px; }
    .btn-catalogo { display: inline-block; padding: 12px 28px; background: var(--yellow-main); color: #001A20; border-radius: 10px; font-family: 'Gasoek One', sans-serif; font-size: 14px; text-decoration: none; transition: all 0.2s; }
    .btn-catalogo:hover { transform: translateY(-2px); background: var(--yellow-light); }
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.active { display: flex; }
    .modal-card { background: #001A20; border: 1px solid var(--yellow-gold); border-radius: 16px; max-width: 520px; width: 100%; overflow: hidden; }
    .modal-product { display: flex; gap: 16px; padding: 20px; background: rgba(0,40,48,0.6); border-bottom: 1px solid rgba(253,233,162,0.1); }
    .modal-product img { width: 80px; height: 80px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
    .modal-product-info { flex: 1; }
    .modal-product-name { font-family: 'Gasoek One', sans-serif; font-size: 16px; color: var(--yellow-light); margin-bottom: 6px; }
    .modal-product-detail { font-size: 12px; color: rgba(255,255,255,0.4); }
    .modal-body { padding: 24px 20px; text-align: center; }
    .key-label { font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
    .key-box { background: rgba(0,40,48,0.8); border: 1px solid rgba(253,233,162,0.2); border-radius: 12px; padding: 24px; margin-bottom: 20px; position: relative; }
    .key-value { font-family: 'Gasoek One', sans-serif; font-size: 24px; letter-spacing: 3px; color: white; transition: filter 0.4s ease; user-select: none; }
    .key-value.blurred { filter: blur(10px); pointer-events: none; }
    .btn-copy-key { display: none; margin-top: 12px; background: rgba(253,233,162,0.15); border: 1px solid var(--yellow-gold); color: var(--yellow-gold); border-radius: 8px; padding: 8px 20px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
    .btn-copy-key:hover { background: rgba(253,233,162,0.25); }
    .btn-copy-key.visible { display: inline-flex; align-items: center; gap: 6px; }
    .btn-revelar { width: 100%; padding: 14px; background: var(--yellow-gold); color: #001A20; border: none; border-radius: 10px; font-family: 'Gasoek One', sans-serif; font-size: 15px; cursor: pointer; transition: all 0.2s; margin-bottom: 12px; }
    .btn-revelar:hover { background: var(--yellow-light); transform: translateY(-1px); }
    .btn-revelar:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
    .key-warning { font-size: 11px; color: rgba(255,80,80,0.7); line-height: 1.5; }
    .key-revealed-msg { display: none; font-size: 13px; color: #6bffb5; font-weight: 600; margin-bottom: 12px; }
    .key-revealed-msg.visible { display: block; }
    .btn-fechar-modal { display: block; width: 100%; padding: 12px; background: transparent; border: 1px solid rgba(255,255,255,0.15); color: rgba(255,255,255,0.5); border-radius: 10px; font-size: 14px; cursor: pointer; transition: all 0.2s; }
    .btn-fechar-modal:hover { border-color: rgba(255,255,255,0.4); color: white; }
    @media (max-width: 600px) {
        .order-header { flex-direction: column; }
        .order-total { text-align: left; }
        .order-item { flex-wrap: wrap; }
        .modal-product { flex-direction: column; align-items: center; text-align: center; }
        .key-value { font-size: 18px; letter-spacing: 2px; }
    }
</style>
@endsection

@section('content')

<p class="page-title">{{ __('messages.my_orders') }}</p>

@if(session('success'))
    <div class="alert alert-success"><i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error"><i class="fa-solid fa-xmark-circle" style="margin-right: 8px;"></i>{{ session('error') }}</div>
@endif

@forelse($pedidos as $pedido)
<div class="order-card">

    <div class="order-header">
        <div>
            <div class="order-number">{{ $pedido->order_number }}</div>
            <div class="order-meta">
                <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                {{ $pedido->created_at->format('d/m/Y \à\s H:i') }}
            </div>
            <div class="status-badges">
                <span class="badge-status badge-{{ $pedido->payment_status }}">
                    {{ $pedido->payment_status === 'paid' ? __('messages.paid') : ($pedido->payment_status === 'pending' ? __('messages.pending') : ($pedido->payment_status === 'refunded' ? __('messages.refunded') : $pedido->payment_status)) }}
                </span>
                <span class="badge-status badge-{{ $pedido->order_status }}">
                    {{ $pedido->order_status === 'completed' ? __('messages.completed') : ($pedido->order_status === 'pending' ? __('messages.pending') : ($pedido->order_status === 'cancelled' ? __('messages.cancelled') : ($pedido->order_status === 'processing' ? __('messages.processing') : $pedido->order_status))) }}
                </span>
            </div>
        </div>
        <div class="order-total">
            R$ {{ number_format($pedido->final_amount, 2, ',', '.') }}
        </div>
    </div>

    @foreach($pedido->items as $item)
    <div class="order-item"
         onclick="abrirModal({{ $item->id }}, '{{ addslashes($item->produto->nome ?? __('messages.product_removed')) }}', '{{ asset('images/' . ($item->produto->imagem_principal ?? '')) }}', '{{ is_array($item->produto->plataformas ?? []) ? ($item->produto->plataformas[0] ?? '') : '' }}', {{ $item->key_revealed ? 'true' : 'false' }}, '{{ $item->key_revealed ? $item->digital_key : str_repeat('•', 17) }}')"
    >
        <div class="item-thumb">
            @if($item->produto && $item->produto->imagem_principal)
                <img src="{{ asset('images/' . $item->produto->imagem_principal) }}" alt="">
            @endif
        </div>
        <div class="item-info">
            <div class="item-name">{{ $item->produto->nome ?? __('messages.product_removed') }}</div>
            <div class="item-detail">{{ __('messages.qty') }}: {{ $item->quantity }}</div>
        </div>
        <span class="item-key-status {{ $item->key_revealed ? 'key-shown' : 'key-hidden' }}">
            {{ $item->key_revealed ? __('messages.revealed') : __('messages.key_locked') }}
        </span>
        <span class="item-price">R$ {{ number_format($item->total, 2, ',', '.') }}</span>
    </div>
    @endforeach

    @if($pedido->canCancel())
    <div class="order-actions">
        <form action="{{ route('usuario.pedidos.cancelar', $pedido->id) }}" method="POST"
              onsubmit="return confirm('{{ __('messages.cancel_order') }}: {{ $pedido->order_number }}?')">
            @csrf
            <button type="submit" class="btn-cancel">
                <i class="fa-solid fa-ban" style="margin-right: 6px;"></i>{{ __('messages.cancel_order') }}
            </button>
        </form>
    </div>
    @endif

</div>
@empty
<div class="empty-pedidos">
    <i class="fa-solid fa-bag-shopping"></i>
    <h2>{{ __('messages.no_orders') }}</h2>
    <p>{{ __('messages.orders_appear_here') }}</p>
    <a href="{{ route('catalogo') }}" class="btn-catalogo">{{ __('messages.view_catalog') }}</a>
</div>
@endforelse

{{-- MODAL DA CHAVE --}}
<div class="modal-overlay" id="keyModal">
    <div class="modal-card">
        <div class="modal-product">
            <img id="modalImg" src="" alt="">
            <div class="modal-product-info">
                <div class="modal-product-name" id="modalName"></div>
                <div class="modal-product-detail" id="modalPlatform"></div>
                <div class="modal-product-detail">Tipo: <strong style="color: var(--yellow-light);">{{ __('messages.digital_key_type') }}</strong></div>
            </div>
        </div>

        <div class="modal-body">
            <p class="key-label">{{ __('messages.digital_key_label') }}</p>

            <div class="key-box">
                <div class="key-value blurred" id="modalKey"></div>
                <button class="btn-copy-key" id="btnCopyKey" onclick="copiarChave()">
                    <i class="fa-regular fa-copy"></i> {{ __('messages.copy') }}
                </button>
            </div>

            <p class="key-revealed-msg" id="revealedMsg">
                <i class="fa-solid fa-check-circle"></i> {{ __('messages.key_revealed_success') }}
            </p>

            <button class="btn-revelar" id="btnRevelar" onclick="revelarChave()">
                {{ __('messages.reveal_key') }}
            </button>

            <p class="key-warning" id="keyWarning">
                ⚠️ {{ __('messages.key_warning') }}
            </p>

            <button class="btn-fechar-modal" onclick="fecharModal()">{{ __('messages.close') }}</button>
        </div>
    </div>
</div>

<script>
    const t = {
        revealing: @json(__('messages.revealing')),
        copy: @json(__('messages.copy')),
        copied: @json(__('messages.copied')),
        revealed: @json(__('messages.revealed')),
        error: @json(__('messages.error_try_again')),
        platform: @json(__('messages.platform')),
    };

    let currentItemId = null;

    function abrirModal(itemId, nome, img, plataforma, revealed, key) {
        currentItemId = itemId;
        document.getElementById('modalName').textContent = nome;
        document.getElementById('modalImg').src = img;
        document.getElementById('modalPlatform').textContent = t.platform + ': ' + (plataforma || 'N/A');
        document.getElementById('modalKey').textContent = key;

        const keyEl = document.getElementById('modalKey');
        const btnRevelar = document.getElementById('btnRevelar');
        const btnCopy = document.getElementById('btnCopyKey');
        const warning = document.getElementById('keyWarning');
        const revealedMsg = document.getElementById('revealedMsg');

        if (revealed) {
            keyEl.classList.remove('blurred');
            keyEl.style.userSelect = 'text';
            btnRevelar.style.display = 'none';
            btnCopy.classList.add('visible');
            warning.style.display = 'none';
            revealedMsg.classList.add('visible');
        } else {
            keyEl.classList.add('blurred');
            keyEl.style.userSelect = 'none';
            btnRevelar.style.display = 'block';
            btnRevelar.disabled = false;
            btnRevelar.textContent = @json(__('messages.reveal_key'));
            btnCopy.classList.remove('visible');
            warning.style.display = 'block';
            revealedMsg.classList.remove('visible');
        }

        document.getElementById('keyModal').classList.add('active');
    }

    function fecharModal() {
        document.getElementById('keyModal').classList.remove('active');
        currentItemId = null;
    }

    document.getElementById('keyModal').addEventListener('click', function(e) {
        if (e.target === this) fecharModal();
    });

    function revelarChave() {
        if (!currentItemId) return;
        const btn = document.getElementById('btnRevelar');
        btn.disabled = true;
        btn.textContent = t.revealing;

        fetch(`/usuario/pedidos/item/${currentItemId}/revelar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const keyEl = document.getElementById('modalKey');
                keyEl.textContent = data.key;
                keyEl.classList.remove('blurred');
                keyEl.style.userSelect = 'text';
                btn.style.display = 'none';
                document.getElementById('btnCopyKey').classList.add('visible');
                document.getElementById('keyWarning').style.display = 'none';
                document.getElementById('revealedMsg').classList.add('visible');

                const items = document.querySelectorAll('.order-item');
                items.forEach(item => {
                    const onclick = item.getAttribute('onclick');
                    if (onclick && onclick.includes(`abrirModal(${currentItemId},`)) {
                        const badge = item.querySelector('.item-key-status');
                        if (badge) {
                            badge.classList.remove('key-hidden');
                            badge.classList.add('key-shown');
                            badge.textContent = t.revealed;
                        }
                        item.setAttribute('onclick', onclick
                            .replace('false,', 'true,')
                            .replace("'•••••••••••••••••'", `'${data.key}'`)
                        );
                    }
                });

                const card = document.querySelector(`.order-item[onclick*="abrirModal(${currentItemId},"]`)?.closest('.order-card');
                if (card) {
                    const cancelForm = card.querySelector('.order-actions');
                    if (cancelForm) cancelForm.remove();
                }
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = t.error;
        });
    }

    function copiarChave() {
        const key = document.getElementById('modalKey').textContent;
        navigator.clipboard.writeText(key).then(() => {
            const btn = document.getElementById('btnCopyKey');
            btn.innerHTML = '<i class="fa-solid fa-check"></i> ' + t.copied;
            setTimeout(() => {
                btn.innerHTML = '<i class="fa-regular fa-copy"></i> ' + t.copy;
            }, 2000);
        });
    }
</script>

@endsection