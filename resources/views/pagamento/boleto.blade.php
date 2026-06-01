<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.boleto_title') }} – GiftZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        :root { --dark: #002830; --mid: #005363; --accent: #FDE9A2; --cyan: #00B4CC; --danger: #e85020; }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--dark); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .card { background: var(--mid); border-radius: 16px; padding: 40px 36px; max-width: 460px; width: 100%; text-align: center; }
        h1 { font-family: 'Gasoek One', sans-serif; color: var(--accent); font-size: 1.8rem; letter-spacing: 2px; margin-bottom: 6px; }
        .subtitulo { font-size: 0.85rem; opacity: 0.5; margin-bottom: 24px; }
        .valor { font-size: 2.4rem; font-weight: 900; color: white; margin-bottom: 6px; }
        .valor span { font-size: 1rem; font-weight: 400; opacity: 0.5; display: block; margin-bottom: 2px; }
        .vencimento { font-size: 0.8rem; opacity: 0.5; margin-bottom: 28px; }
        .vencimento strong { color: var(--accent); opacity: 1; }
        .barcode { margin: 0 auto 24px; padding: 16px; background: white; border-radius: 10px; display: inline-block; }
        .barcode-lines { display: flex; gap: 2px; align-items: stretch; height: 60px; }
        .barcode-lines div { background: #111; border-radius: 1px; }
        .copy-label { font-size: 0.75rem; opacity: 0.5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .copy-wrap { display: flex; gap: 8px; margin-bottom: 28px; }
        .copy-input { flex: 1; background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 10px 12px; color: white; font-size: 0.7rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; outline: none; }
        .btn-copy { background: rgba(253,233,162,0.15); border: 1px solid var(--accent); color: var(--accent); border-radius: 8px; padding: 10px 16px; font-weight: 700; font-size: 0.8rem; cursor: pointer; transition: .2s; white-space: nowrap; }
        .btn-copy:hover { background: rgba(253,233,162,0.25); }
        .btn-copy.copiado { background: rgba(0,180,204,0.2); border-color: var(--cyan); color: var(--cyan); }
        .btn-confirmar { width: 100%; background: var(--cyan); color: white; border: none; padding: 15px; border-radius: 8px; font-weight: 900; font-size: 1rem; cursor: pointer; text-transform: uppercase; transition: .2s; margin-bottom: 14px; }
        .btn-confirmar:hover { filter: brightness(1.1); }
        .btn-voltar { display: block; color: rgba(255,255,255,0.4); text-decoration: none; font-size: 0.85rem; transition: .2s; }
        .btn-voltar:hover { color: rgba(255,255,255,0.7); }
        .aviso { background: rgba(253,233,162,0.07); border: 1px solid rgba(253,233,162,0.2); border-radius: 8px; padding: 12px 16px; font-size: 0.78rem; color: rgba(255,255,255,0.6); margin-bottom: 24px; text-align: left; line-height: 1.6; }
        .aviso strong { color: var(--accent); }
    </style>
</head>
<body>
<div class="card">
    <h1>{{ __('messages.boleto_title') }}</h1>
    <p class="subtitulo">{{ __('messages.pay_at_bank') }}</p>

    <div class="valor">
        <span>{{ __('messages.amount_to_pay') }}</span>
        R$ {{ number_format($total, 2, ',', '.') }}
    </div>
    <p class="vencimento">{{ __('messages.due_date') }}: <strong>{{ $vencimento }}</strong></p>

    <div class="barcode">
        <div class="barcode-lines" id="barras"></div>
    </div>

    <p class="copy-label">{{ __('messages.digital_line') }}</p>
    <div class="copy-wrap">
        <input class="copy-input" id="codigoBoleto" type="text" value="{{ $codigo }}" readonly>
        <button class="btn-copy" id="btnCopy" onclick="copiarCodigo()">{{ __('messages.copy') }}</button>
    </div>

    <div class="aviso">
        ⚠️ <strong>{{ __('messages.attention') }}:</strong> {!! __('messages.boleto_warning') !!}
    </div>

    <form action="{{ route('pagamento.boleto.confirmar') }}" method="POST">
        @csrf
        <button type="submit" class="btn-confirmar">{{ __('messages.already_paid') }}</button>
    </form>

    <a href="{{ route('carrinho.index') }}" class="btn-voltar">{{ __('messages.back_to_cart') }}</a>
</div>

<script>
    const wrap = document.getElementById('barras');
    const widths = [2,1,3,1,2,2,1,4,1,2,3,1,2,1,3,2,1,2,1,3,1,2,2,1,3,1,2,1,2,3,1,2,1,3,2,1,2,1,2,3];
    widths.forEach(w => { const d = document.createElement('div'); d.style.width = w + 'px'; wrap.appendChild(d); });

    const txtCopied = @json(__('messages.copied'));
    const txtCopy = @json(__('messages.copy'));

    function copiarCodigo() {
        const codigo = document.getElementById('codigoBoleto').value;
        navigator.clipboard.writeText(codigo).then(() => {
            const btn = document.getElementById('btnCopy');
            btn.textContent = txtCopied;
            btn.classList.add('copiado');
            setTimeout(() => { btn.textContent = txtCopy; btn.classList.remove('copiado'); }, 2000);
        });
    }
</script>
</body>
</html>