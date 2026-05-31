<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar com PIX – GiftZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        :root { --dark: #002830; --mid: #005363; --accent: #FDE9A2; --cyan: #00B4CC; --danger: #e85020; }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--dark); color: white; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }

        .card {
            background: var(--mid);
            border-radius: 16px;
            padding: 40px 36px;
            max-width: 460px;
            width: 100%;
            text-align: center;
        }

        .logo-pix { height: 28px; margin-bottom: 8px; filter: brightness(0) invert(1); }

        h1 {
            font-family: 'Gasoek One', sans-serif;
            color: var(--accent);
            font-size: 1.8rem;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }

        .valor {
            font-size: 2.4rem;
            font-weight: 900;
            color: white;
            margin: 16px 0;
        }
        .valor span { font-size: 1rem; font-weight: 400; opacity: 0.5; display: block; margin-bottom: 2px; }

        /* TIMER */
        .timer-wrap { margin: 0 auto 24px; }
        .timer-label { font-size: 0.75rem; opacity: 0.5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .timer {
            font-family: 'Gasoek One', sans-serif;
            font-size: 2.8rem;
            color: var(--accent);
            letter-spacing: 4px;
            transition: color .3s;
        }
        .timer.urgente { color: var(--danger); }

        /* BARRA */
        .progress-bar { height: 4px; background: rgba(255,255,255,0.1); border-radius: 99px; overflow: hidden; margin-bottom: 28px; }
        .progress-fill { height: 100%; background: var(--accent); border-radius: 99px; transition: width 1s linear, background .3s; }

        /* QR */
        .qr-wrap {
            background: white;
            border-radius: 12px;
            padding: 16px;
            display: inline-block;
            margin-bottom: 24px;
        }
        .qr-wrap img { display: block; width: 220px; height: 220px; }

        /* COPIA E COLA */
        .copy-label { font-size: 0.75rem; opacity: 0.5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .copy-wrap { display: flex; gap: 8px; margin-bottom: 28px; }
        .copy-input {
            flex: 1; background: rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px; padding: 10px 12px; color: white; font-size: 0.7rem;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap; outline: none;
        }
        .btn-copy {
            background: rgba(253,233,162,0.15); border: 1px solid var(--accent);
            color: var(--accent); border-radius: 8px; padding: 10px 16px;
            font-weight: 700; font-size: 0.8rem; cursor: pointer; transition: .2s; white-space: nowrap;
        }
        .btn-copy:hover { background: rgba(253,233,162,0.25); }
        .btn-copy.copiado { background: rgba(0,180,204,0.2); border-color: var(--cyan); color: var(--cyan); }

        /* BOTÃO CONFIRMAR */
        .btn-confirmar {
            width: 100%; background: var(--cyan); color: white; border: none;
            padding: 15px; border-radius: 8px; font-weight: 900; font-size: 1rem;
            cursor: pointer; text-transform: uppercase; transition: .2s; margin-bottom: 14px;
        }
        .btn-confirmar:hover { filter: brightness(1.1); }

        .btn-voltar {
            display: block; color: rgba(255,255,255,0.4); text-decoration: none;
            font-size: 0.85rem; transition: .2s;
        }
        .btn-voltar:hover { color: rgba(255,255,255,0.7); }

        /* EXPIRADO */
        .expirado-msg { display: none; color: var(--danger); font-weight: 700; margin-bottom: 16px; font-size: 0.9rem; }
    </style>
</head>
<body>
<div class="card">

    <img class="logo-pix"
         src="https://upload.wikimedia.org/wikipedia/commons/5/50/Pix_%28Brazil%29_logo.svg"
         alt="PIX">

    <h1>PAGUE COM PIX</h1>

    <div class="valor">
        <span>Valor a pagar</span>
        R$ {{ number_format($total, 2, ',', '.') }}
    </div>

    <div class="timer-wrap">
        <p class="timer-label">Tempo restante</p>
        <div class="timer" id="timer">10:00</div>
    </div>
    <div class="progress-bar">
        <div class="progress-fill" id="progressFill" style="width: 100%"></div>
    </div>

    <div class="qr-wrap">
        <img src="{{ $qrCodeUrl }}" alt="QR Code PIX">
    </div>

    <p class="copy-label">PIX Copia e Cola</p>
    <div class="copy-wrap">
        <input class="copy-input" id="pixPayload" type="text" value="{{ $payload }}" readonly>
        <button class="btn-copy" id="btnCopy" onclick="copiarPix()">Copiar</button>
    </div>

    <p class="expirado-msg" id="expiradoMsg">⚠️ QR Code expirado. Volte ao carrinho e tente novamente.</p>

    <form action="{{ route('pagamento.pix.confirmar') }}" method="POST">
        @csrf
        <button type="submit" class="btn-confirmar" id="btnConfirmar">
            Já paguei — Confirmar pedido
        </button>
    </form>

    <a href="{{ route('carrinho.index') }}" class="btn-voltar">← Voltar ao carrinho</a>
</div>

<script>
    const TOTAL_SECONDS = 10 * 60;
    let remaining = TOTAL_SECONDS;

    const timerEl       = document.getElementById('timer');
    const fillEl        = document.getElementById('progressFill');
    const btnConfirmar  = document.getElementById('btnConfirmar');
    const expiradoMsg   = document.getElementById('expiradoMsg');

    const interval = setInterval(() => {
        remaining--;

        const m = String(Math.floor(remaining / 60)).padStart(2, '0');
        const s = String(remaining % 60).padStart(2, '0');
        timerEl.textContent = `${m}:${s}`;

        const pct = (remaining / TOTAL_SECONDS) * 100;
        fillEl.style.width = pct + '%';

        if (remaining <= 60) {
            timerEl.classList.add('urgente');
            fillEl.style.background = '#e85020';
        }

        if (remaining <= 0) {
            clearInterval(interval);
            timerEl.textContent = '00:00';
            fillEl.style.width = '0%';
            btnConfirmar.disabled = true;
            btnConfirmar.style.opacity = '0.4';
            btnConfirmar.style.cursor = 'not-allowed';
            expiradoMsg.style.display = 'block';
        }
    }, 1000);

    function copiarPix() {
        const payload = document.getElementById('pixPayload').value;
        navigator.clipboard.writeText(payload).then(() => {
            const btn = document.getElementById('btnCopy');
            btn.textContent = 'Copiado!';
            btn.classList.add('copiado');
            setTimeout(() => {
                btn.textContent = 'Copiar';
                btn.classList.remove('copiado');
            }, 2000);
        });
    }
</script>
</body>
</html>