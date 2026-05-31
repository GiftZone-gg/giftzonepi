<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - GiftZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        :root { --dark: #002830; --mid: #005363; --accent: #FDE9A2; --white: #ffffff; --cyan: #00B4CC; }
        body { font-family: 'Inter', sans-serif; background-color: var(--dark); color: white; padding: 40px 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        
        h1 { font-family: 'Gasoek One', sans-serif; color: var(--accent); margin-bottom: 24px; letter-spacing: 2px; }
        h3 { color: var(--accent); margin-bottom: 15px; font-size: 0.9rem; text-transform: uppercase; }

        /* Card de Itens */
        .card-items { background: var(--mid); border-radius: 12px; padding: 25px; margin-bottom: 24px; }
        .item-list { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
        .item-img { width: 120px; height: 80px; background: #002830; border-radius: 8px; object-fit: cover; }
        .subtotal-row { border-top: 1px solid rgba(255,255,255,0.2); padding-top: 15px; font-weight: 900; font-size: 1.4rem; color: var(--accent); text-align: right; }

        /* Grade de Pagamento */
        .payment-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px; align-items: start; }
        .payment-card { background: var(--mid); padding: 25px; border-radius: 12px; }
        
        .metodo-opcao { 
            display: flex; align-items: center; gap: 15px; padding: 14px; 
            background: rgba(0,40,48,0.3); border-radius: 8px; margin-bottom: 12px; 
            cursor: pointer; transition: 0.2s; border: 1px solid transparent;
        }
        .metodo-opcao:hover { background: rgba(0,40,48,0.6); border-color: var(--accent); }
        .metodo-opcao input { accent-color: var(--accent); cursor: pointer; }
        .payment-icon { margin-left: auto; font-size: 1.2rem; }

        .btn-finalizar { 
            width: 100%; background: var(--cyan); color: white; border: none; 
            padding: 16px; border-radius: 8px; font-weight: 900; cursor: pointer; 
            text-transform: uppercase; transition: 0.2s;
        }
        .btn-finalizar:hover { filter: brightness(1.1); }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('carrinho.index') }}" style="display:inline-flex; align-items:center; gap:8px; color: var(--accent); text-decoration:none; font-size:0.875rem; font-weight:600; margin-bottom:20px; opacity:0.8; transition:.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.8">← Voltar ao carrinho</a>

        <h1>Meu carrinho</h1>

        <div class="card-items">
            <h3>Meus Itens:</h3>
            @foreach($itens as $item)
            @php
                $plataformas = is_array($item['produto']->plataformas)
                    ? $item['produto']->plataformas
                    : json_decode($item['produto']->plataformas, true) ?? [];
            @endphp
            <div class="item-list">
                <img src="{{ asset('images/' . $item['produto']->imagem_principal) }}" class="item-img" alt="Capa">
                <div>
                    <strong>{{ $item['produto']->nome }}</strong><br>
                    <small>Plataforma: {{ $plataformas[0] ?? 'N/A' }}</small>
                </div>
            </div>
            @endforeach
            <div class="subtotal-row">Total: R$ {{ number_format($total, 2, ',', '.') }}</div>
        </div>

        <form action="{{ route('pagamento.processar') }}" method="POST">
            @csrf
            <div class="payment-grid">
                <div class="payment-card">
                    <h3>Pagamento</h3>
                    <p style="font-size: 0.8rem; opacity: 0.7; margin-bottom: 20px;">Escolha a forma de pagamento ao lado e finalize sua compra.</p>
                    <button type="submit" class="btn-finalizar">Finalizar compra</button>
                </div>
                
                <div class="payment-card">
                    <label class="metodo-opcao">
                        <input type="radio" name="metodo" value="credito" required>
                        <span>Cartão de Crédito</span>
                        <div class="payment-icon">💳</div>
                    </label>
                    <label class="metodo-opcao">
                        <input type="radio" name="metodo" value="debito">
                        <span>Cartão de Débito</span>
                        <div class="payment-icon">💳</div>
                    </label>
                    <label class="metodo-opcao">
                        <input type="radio" name="metodo" value="boleto">
                        <span>Boleto Expresso</span>
                        <div class="payment-icon">📄</div>
                    </label>
                    <label class="metodo-opcao">
                        <input type="radio" name="metodo" value="pix">
                        <span>PIX</span>
                        <div class="payment-icon">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/50/Pix_%28Brazil%29_logo.svg" alt="PIX" style="height: 20px; width: auto; filter: brightness(0) invert(1);">
                        </div>
                    </label>
                </div>
            </div>
        </form>
    </div>
</body>
</html>