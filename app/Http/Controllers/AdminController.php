<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Produto;
use App\Models\Order;
use App\Models\User;
use App\Services\SupabaseStorage;

class AdminController extends Controller
{
    protected SupabaseStorage $storage;

    public function __construct()
    {
        $this->storage = new SupabaseStorage();
    }

    // --- Dashboard ---

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

    // --- Produtos ---

    public function produtos(Request $request)
    {
        $query = Produto::query();

        if ($request->filled('busca')) {
            $query->where('nome', 'ilike', '%' . $request->busca . '%');
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

    private function uploadImagem($file, string $nome = ''): string
    {
        $url = $this->storage->upload($file, 'produtos');
        if ($url) {
            return $url;
        }

        $filename = time() . '_' . Str::slug($nome ?: Str::random(6)) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $filename);
        return $filename;
    }

    public function produtoSalvar(Request $request)
    {
        $request->validate([
            'nome'             => 'required|string|max:255',
            'descricao'        => 'required|string',
            'genero'           => 'nullable|string|max:100',
            'desenvolvedor'    => 'nullable|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'imagem_principal' => 'required|image|mimes:jpg,jpeg,webp|max:4096',
            'plataformas'      => 'required|array|min:1',
            'edicao_nome.*'    => 'required|string',
            'edicao_preco.*'   => 'required|numeric|min:0',
            'galeria.*'        => 'nullable|image|mimes:jpg,jpeg,webp|max:4096',
            'trailer_url'      => 'nullable|url|max:500',
        ]);

        $imagemNome = $this->uploadImagem($request->file('imagem_principal'), $request->nome);

        $galeria = [];
        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                $galeria[] = $this->uploadImagem($img);
            }
        }

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
            'galeria'          => $galeria,
            'plataformas'      => $request->plataformas,
            'edicoes'          => $edicoes,
            'requisitos'       => null,
            'trailer_url'      => $request->trailer_url,
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
            'imagem_principal' => 'nullable|image|mimes:jpg,jpeg,webp|max:4096',
            'plataformas'      => 'required|array|min:1',
            'edicao_nome.*'    => 'required|string',
            'edicao_preco.*'   => 'required|numeric|min:0',
            'galeria.*'        => 'nullable|image|mimes:jpg,jpeg,webp|max:4096',
            'trailer_url'      => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('imagem_principal')) {
            if (SupabaseStorage::isSupabaseUrl($produto->imagem_principal)) {
                $this->storage->delete($produto->imagem_principal);
            }
            $produto->imagem_principal = $this->uploadImagem($request->file('imagem_principal'), $request->nome);
        }

        $galeria = is_array($produto->galeria) ? $produto->galeria : [];

        if ($request->hasFile('galeria')) {
            foreach ($request->file('galeria') as $img) {
                $galeria[] = $this->uploadImagem($img);
            }
        }

        if ($request->has('remover_galeria')) {
            foreach ($request->remover_galeria as $remover) {
                if (SupabaseStorage::isSupabaseUrl($remover)) {
                    $this->storage->delete($remover);
                } else {
                    $path = public_path('images/' . $remover);
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                }
                $galeria = array_values(array_diff($galeria, [$remover]));
            }
        }

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
            'galeria'       => $galeria,
            'trailer_url'   => $request->trailer_url,
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

    // --- Pedidos ---

    public function pedidos(Request $request)
    {
        $query = Order::with(['user', 'items.produto']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('order_number', 'ilike', "%{$busca}%")
                  ->orWhereHas('user', function ($q2) use ($busca) {
                      $q2->where('name', 'ilike', "%{$busca}%")
                         ->orWhere('email', 'ilike', "%{$busca}%");
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

        try {
            if ($request->order_status === 'completed') {
                \App\Models\UserNotification::criarPedidoConcluido($order->user_id, $order->order_number);
            } elseif ($request->order_status === 'cancelled') {
                \App\Models\UserNotification::criarPedidoCancelado($order->user_id, $order->order_number);
            }
        } catch (\Throwable $e) {}

        return back()->with('success', "Pedido {$order->order_number} atualizado.");
    }

    // --- Gestao de Usuarios ---

    public function usuarios(Request $request)
    {
        $query = User::query();

        if ($request->tipo === 'admin') {
            $query->where('is_admin', true);
        } elseif ($request->tipo === 'usuario') {
            $query->where('is_admin', false);
        }

        if ($request->busca) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('name', 'ilike', "%{$busca}%")
                  ->orWhere('email', 'ilike', "%{$busca}%")
                  ->orWhere('nickname', 'ilike', "%{$busca}%");
            });
        }

        $usuarios = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total'       => User::count(),
            'admins'      => User::where('is_admin', true)->count(),
            'verificados' => User::whereNotNull('email_verified_at')->count(),
            'recentes'    => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.usuarios.index', compact('usuarios', 'stats'));
    }

    public function usuarioToggleAdmin($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Voce nao pode alterar seu proprio status.');
        }
        $user->is_admin = !$user->is_admin;
        $user->save();
        $msg = $user->is_admin ? "'{$user->name}' agora e admin." : "'{$user->name}' nao e mais admin.";
        return back()->with('success', $msg);
    }

    public function usuarioExcluir($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Voce nao pode excluir sua propria conta.');
        }
        $nome = $user->name;
        $user->delete();
        return back()->with('success', "Conta de '{$nome}' excluida.");
    }

    public function usuarioVerificar($id)
    {
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail()) {
            $user->email_verified_at = now();
            $user->save();
            return back()->with('success', "E-mail de '{$user->name}' verificado.");
        }
        return back()->with('error', 'E-mail ja verificado.');
    }
}