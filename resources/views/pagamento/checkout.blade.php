<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone - Pagamento</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #002830; color: white; }
        .container { max-width: 800px; margin: 0 auto; padding: 2rem; }
        .card { background: #001A20; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; }
        .metodo { margin: 0.5rem 0; }
        label { margin-left: 0.5rem; }
        .btn { padding: 0.5rem 1rem; background: #FFDC74; color: #001A20; border: none; border-radius: 8px; cursor: pointer; }
        hr { margin: 1rem 0; border-color: rgba(255,255,255,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Escolha a forma de pagamento</h1>
        <div class="card">
            <h3>Resumo do pedido</h3>
            @foreach($itens as $item)
            <div>{{ $item['produto']->nome }} x {{ $item['quantidade'] }} = R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</div>
            @endforeach
            <hr>
            <div><strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong></div>
        </div>
        <div class="card">
            <form action="{{ route('pagamento.processar') }}" method="POST">
                @csrf
                <div class="metodo"><input type="radio" name="metodo" value="boleto" id="boleto" required> <label for="boleto">Boleto bancário</label></div>
                <div class="metodo"><input type="radio" name="metodo" value="credito" id="credito"> <label for="credito">Cartão de crédito</label></div>
                <div class="metodo"><input type="radio" name="metodo" value="debito" id="debito"> <label for="debito">Cartão de débito</label></div>
                <div class="metodo"><input type="radio" name="metodo" value="pix" id="pix"> <label for="pix">PIX</label></div>
                <button type="submit" class="btn">Confirmar pagamento</button>
            </form>
        </div>
    </div>
</body>
</html>