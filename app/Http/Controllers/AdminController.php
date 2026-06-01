<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Produto;
use App\Models\Order;
use App\Models\User;

class AdminController extends Controller
{
    // ─── Dashboard ───

    public function dashboard()
    {
        $totalProdutos  = Produto::count();
        $produtosAtivos = Produto::where('ativo', true)->count();
        $totalPedidos   = Order::count();
        $pedidosPagos   = Order::where('payment_status', 'paid')->count();
        $pedidosPendentes = Order::where('order_status', 'processing')
            ->orWhere('payment_status', 'pending')
            ->count();
        $totalVendas    = Order::where('payment_status', 'paid')->sum('final_amount');
        $totalUsuarios  = User::count();

        $ultimosPedidos = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalProdutos', 'produtosAtivos', 'totalPedidos',
            'pedidosPagos', 'pedidosPendentes', 'totalVendas',
            'totalUsuarios', 'ultimosPedidos'
        ));
    }

    // ─── Produtos ───

    public function produtos(Request $request)
    {
        $query = Produto::query();

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        if ($request->filled('status')) {
            $query->where('ativo', $request->status === 'ativo');
        }

        $produtos = $query->orderBy('created_at', 'desc')->get();

        return view('admin.produtos.index', compact('produtos'));
    }

    public function produtoCriar()
    {
        return view('admin.produtos.form', ['produto' => null]);
    }

    public function produtoSalvar(Request $request)
    {
        $request->validate([
            'nome'             => 'required|string|max:255',
            'descricao'        => 'required|string',
            'genero'           => 'nullable|string|max:100',
            'desenvolvedor'    => 'nullable|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'imagem_principal' => 'required|image|max:2048',
            'plataformas'      => 'required|array|min:1',
            'edicao_nome.*'    => 'required|string',
            'edicao_preco.*'   => 'required|numeric|min:0',
        ]);

        // Upload da imagem
        $imagemNome = time() . '_' . Str::slug($request->nome) . '.' . $request->file('imagem_principal')->getClientOriginalExtension();
        $request->file('imagem_principal')->move(public_path('images'), $imagemNome);

        // Montar edições
        $edicoes = [];
        if ($request->has('edicao_nome')) {
            foreach ($request->edicao_nome as $i => $nome) {
                $edicoes[] = [
                    'nome'  => $nome,
                    'preco' => (float) $request->edicao_preco[$i],
                ];
            }
        }

        Produto::create([
            'nome'             => $request->nome,
            'slug'             => Str::slug($request->nome) . '-' . Str::random(4),
            'descricao'        => $request->descricao,
            'genero'           => $request->genero,
            'desenvolvedor'    => $request->desenvolvedor,
            'publisher'        => $request->publisher,
            'imagem_principal' => $imagemNome,
            'galeria'          => [],
            'plataformas'      => $request->plataformas,
            'edicoes'          => $edicoes,
            'requisitos'       => null,
            'ativo'            => $request->has('ativo'),
        ]);

        return redirect()->route('admin.produtos')->with('success', 'Produto criado com sucesso!');
    }

    public function produtoEditar($id)
    {
        $produto = Produto::findOrFail($id);
        return view('admin.produtos.form', compact('produto'));
    }

    public function produtoAtualizar(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $request->validate([
            'nome'             => 'required|string|max:255',
            'descricao'        => 'required|string',
            'genero'           => 'nullable|string|max:100',
            'desenvolvedor'    => 'nullable|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'imagem_principal' => 'nullable|image|max:2048',
            'plataformas'      => 'required|array|min:1',
            'edicao_nome.*'    => 'required|string',
            'edicao_preco.*'   => 'required|numeric|min:0',
        ]);

        // Imagem
        if ($request->hasFile('imagem_principal')) {
            // Remove antiga se não for default
            $antigaPath = public_path('images/' . $produto->imagem_principal);
            if (file_exists($antigaPath) && $produto->imagem_principal) {
                // Não deletamos para evitar quebrar referências
            }

            $imagemNome = time() . '_' . Str::slug($request->nome) . '.' . $request->file('imagem_principal')->getClientOriginalExtension();
            $request->file('imagem_principal')->move(public_path('images'), $imagemNome);
            $produto->imagem_principal = $imagemNome;
        }

        // Edições
        $edicoes = [];
        if ($request->has('edicao_nome')) {
            foreach ($request->edicao_nome as $i => $nome) {
                $edicoes[] = [
                    'nome'  => $nome,
                    'preco' => (float) $request->edicao_preco[$i],
                ];
            }
        }

        $produto->update([
            'nome'          => $request->nome,
            'descricao'     => $request->descricao,
            'genero'        => $request->genero,
            'desenvolvedor' => $request->desenvolvedor,
            'publisher'     => $request->publisher,
            'plataformas'   => $request->plataformas,
            'edicoes'       => $edicoes,
            'ativo'         => $request->has('ativo'),
        ]);

        return redirect()->route('admin.produtos')->with('success', 'Produto atualizado!');
    }

    public function produtoRemover($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();

        return redirect()->route('admin.produtos')->with('success', 'Produto removido.');
    }

    public function produtoToggle($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->update(['ativo' => !$produto->ativo]);

        $status = $produto->ativo ? 'ativado' : 'desativado';
        return back()->with('success', "Produto {$status}.");
    }

    // ─── Pedidos ───

    public function pedidos(Request $request)
    {
        $query = Order::with(['user', 'items.produto']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('order_number', 'like', "%{$busca}%")
                  ->orWhereHas('user', function ($q2) use ($busca) {
                      $q2->where('name', 'like', "%{$busca}%")
                         ->orWhere('email', 'like', "%{$busca}%");
                  });
            });
        }

        $pedidos = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total'      => Order::count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'completed'  => Order::where('order_status', 'completed')->count(),
            'cancelled'  => Order::where('order_status', 'cancelled')->count(),
        ];

        return view('admin.pedidos.index', compact('pedidos', 'stats'));
    }

    public function pedidoStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:processing,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);

        $statusMap = [
            'processing' => ['order_status' => 'processing', 'payment_status' => 'pending'],
            'completed'  => ['order_status' => 'completed', 'payment_status' => 'paid'],
            'cancelled'  => ['order_status' => 'cancelled', 'payment_status' => 'refunded'],
        ];

        $order->update($statusMap[$request->order_status]);

        if ($request->order_status === 'completed') {
    \App\Models\UserNotification::criarPedidoConcluido($order->user_id, $order->order_number);
} elseif ($request->order_status === 'cancelled') {
    \App\Models\UserNotification::criarPedidoCancelado($order->user_id, $order->order_number);
}

        return back()->with('success', "Pedido {$order->order_number} atualizado.");
    }
}