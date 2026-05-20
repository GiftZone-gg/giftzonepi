<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone - Carrinho</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Gasoek+One&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #002830; color: white; }
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .cart-item { background: #001A20; border-radius: 12px; padding: 1rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .total { font-size: 1.5rem; font-weight: bold; margin-top: 1rem; text-align: right; }
        .btn { padding: 0.5rem 1rem; background: #FFDC74; color: #001A20; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-primary { background: #2ecc71; }
        .btn-secondary { background: #3498db; }
        .empty { text-align: center; margin-top: 3rem; }
        .cart-actions { margin-top: 1rem; display: flex; gap: 1rem; justify-content: flex-end; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Meu Carrinho</h1>

        @if(session('success'))
            <div style="background: #2e7d32; padding: 0.5rem; margin-bottom: 1rem;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background: #c62828; padding: 0.5rem; margin-bottom: 1rem;">{{ session('error') }}</div>
        @endif

        @if(empty($itens))
            <div class="empty">
                <p>Seu carrinho está vazio.</p>
                <a href="{{ route('catalogo') }}" class="btn">Continuar comprando</a>
            </div>
        @else
            @foreach($itens as $item)
            <div class="cart-item">
                <div>
                    <strong>{{ $item['produto']->nome }}</strong><br>
                    Preço: R$ {{ number_format($item['preco'], 2, ',', '.') }}<br>
                    Quantidade: 
                    <form action="{{ route('carrinho.atualizar', $item['produto']->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="quantidade" value="{{ $item['quantidade'] }}" min="1" max="3" style="width: 60px;">
                        <button type="submit" class="btn btn-secondary">Atualizar</button>
                    </form>
                </div>
                <div>
                    Subtotal: R$ {{ number_format($item['subtotal'], 2, ',', '.') }}
                    <form action="{{ route('carrinho.remover', $item['produto']->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Remover</button>
                    </form>
                </div>
            </div>
            @endforeach
            <div class="total">Total: R$ {{ number_format($total, 2, ',', '.') }}</div>
            <div class="cart-actions">
                <a href="{{ route('catalogo') }}" class="btn">Continuar comprando</a>
                <form action="{{ route('carrinho.finalizar') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Finalizar compra</button>
                </form>
            </div>
        @endif
    </div>
</body>
</html>