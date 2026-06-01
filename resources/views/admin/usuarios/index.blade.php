@extends('admin.layout')

@section('title', 'Gestão de Usuários')

@section('content')

<style>
    .user-stats { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .user-stat {
        padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600;
        border: 1px solid rgba(255,255,255,0.06); background: rgba(0,26,32,0.5);
        display: flex; align-items: center; gap: 8px; cursor: pointer;
        transition: all 0.2s; text-decoration: none; color: rgba(255,255,255,0.6);
    }
    .user-stat:hover { border-color: rgba(253,233,162,0.3); color: #FDE9A2; }
    .user-stat .count { font-family: 'Gasoek One', sans-serif; font-size: 18px; color: #fff; }
    .toolbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; }
    .search-input {
        padding: 9px 16px; background: rgba(0,40,48,0.8);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;
        color: #fff; font-size: 13px; outline: none; min-width: 280px; transition: border-color 0.2s;
    }
    .search-input:focus { border-color: #FDE9A2; }
    .search-input::placeholder { color: rgba(255,255,255,0.25); }
    .users-table-wrap {
        background: rgba(0,26,32,0.6); border: 1px solid rgba(253,233,162,0.1);
        border-radius: 16px; overflow: hidden;
    }
    .users-table { width: 100%; border-collapse: collapse; }
    .users-table th {
        text-align: left; padding: 14px 18px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: rgba(255,255,255,0.35); border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .users-table td {
        padding: 14px 18px; font-size: 13px; color: rgba(255,255,255,0.75);
        border-bottom: 1px solid rgba(255,255,255,0.03); vertical-align: middle;
    }
    .users-table tr:hover td { background: rgba(255,255,255,0.02); }
    .badge-sm {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 10px; font-weight: 700; text-transform: uppercase;
    }
    .badge-admin { background: rgba(255,107,53,0.15); color: #ff6b35; }
    .badge-user { background: rgba(90,220,232,0.15); color: #5adce8; }
    .badge-verified { background: rgba(107,255,181,0.15); color: #6bffb5; }
    .badge-unverified { background: rgba(255,193,7,0.15); color: #ffc107; }
    .user-info { display: flex; align-items: center; gap: 12px; }
    .user-avatar-mini {
        width: 36px; height: 36px; border-radius: 50%;
        object-fit: cover; background: #1F6D7E; border: 2px solid rgba(255,220,116,0.3);
    }
    .user-name { font-weight: 600; color: #fff; }
    .user-email { font-size: 11px; color: rgba(255,255,255,0.3); }
    .user-nick { font-size: 11px; color: rgba(255,220,116,0.5); }
    .action-btns { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-sm {
        padding: 5px 10px; border-radius: 6px; font-size: 10px; font-weight: 700;
        border: 1px solid; cursor: pointer; transition: all 0.2s; text-transform: uppercase;
        background: transparent;
    }
    .btn-toggle-admin { border-color: rgba(255,107,53,0.3); color: #ff6b35; }
    .btn-toggle-admin:hover { background: rgba(255,107,53,0.1); }
    .btn-verify { border-color: rgba(107,255,181,0.3); color: #6bffb5; }
    .btn-verify:hover { background: rgba(107,255,181,0.1); }
    .btn-delete { border-color: rgba(255,80,80,0.3); color: #ff6b6b; }
    .btn-delete:hover { background: rgba(255,80,80,0.1); }
    .alert { border-radius: 10px; padding: 12px 18px; margin-bottom: 16px; font-size: 14px; }
    .alert-success { background: rgba(107,255,181,0.1); border: 1px solid rgba(107,255,181,0.3); color: #6bffb5; }
    .alert-error { background: rgba(255,80,80,0.1); border: 1px solid rgba(255,80,80,0.3); color: #ff6b6b; }
    .empty-row td { text-align: center; padding: 50px; color: rgba(255,255,255,0.2); font-size: 14px; }
    @media (max-width: 768px) { .users-table-wrap { overflow-x: auto; } .search-input { min-width: auto; width: 100%; } }
</style>

<p class="page-title" style="font-family: 'Gasoek One', sans-serif; font-size: 24px; color: #FDE9A2; margin-bottom: 24px;">Gestão de Usuários</p>

@if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">❌ {{ session('error') }}</div>
@endif

<div class="user-stats">
    <a href="{{ route('admin.usuarios') }}" class="user-stat {{ !request('tipo') ? 'active' : '' }}" style="{{ !request('tipo') ? 'border-color: rgba(253,233,162,0.3); color: #FDE9A2;' : '' }}">
        <span class="count">{{ $stats['total'] }}</span> Todos
    </a>
    <a href="{{ route('admin.usuarios', ['tipo' => 'admin']) }}" class="user-stat {{ request('tipo') === 'admin' ? 'active' : '' }}" style="{{ request('tipo') === 'admin' ? 'border-color: rgba(253,233,162,0.3); color: #FDE9A2;' : '' }}">
        <span class="count">{{ $stats['admins'] }}</span> Admins
    </a>
    <a href="{{ route('admin.usuarios', ['tipo' => 'usuario']) }}" class="user-stat {{ request('tipo') === 'usuario' ? 'active' : '' }}" style="{{ request('tipo') === 'usuario' ? 'border-color: rgba(253,233,162,0.3); color: #FDE9A2;' : '' }}">
        <span class="count">{{ $stats['total'] - $stats['admins'] }}</span> Usuários
    </a>
    <div class="user-stat">
        <span class="count">{{ $stats['verificados'] }}</span> Verificados
    </div>
    <div class="user-stat">
        <span class="count">{{ $stats['recentes'] }}</span> Últimos 7 dias
    </div>
</div>

<div class="toolbar">
    <form action="{{ route('admin.usuarios') }}" method="GET" style="display: flex; gap: 8px;">
        @if(request('tipo'))
            <input type="hidden" name="tipo" value="{{ request('tipo') }}">
        @endif
        <input type="text" name="busca" class="search-input" placeholder="Buscar por nome, e-mail ou nickname..." value="{{ request('busca') }}">
    </form>
</div>

<div class="users-table-wrap">
    <table class="users-table">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>CPF</th>
                <th>Tipo</th>
                <th>E-mail</th>
                <th>Cadastro</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $u)
            <tr>
                <td>
                    <div class="user-info">
                        <img class="user-avatar-mini"
                             src="{{ $u->avatar === 'icone1.svg' || empty($u->avatar) ? asset('images/icone1.svg') : asset('storage/' . $u->avatar) }}"
                             alt="{{ $u->name }}"
                             onerror="this.src='{{ asset('images/icone1.svg') }}'">
                        <div>
                            <div class="user-name">{{ $u->name }}</div>
                            <div class="user-email">{{ $u->email }}</div>
                            <div class="user-nick">{{ '@' . $u->nickname }}</div>
                        </div>
                    </div>
                </td>
                <td style="font-size: 12px; color: rgba(255,255,255,0.4);">
                    {{ $u->cpf ? substr($u->cpf, 0, 3) . '.' . substr($u->cpf, 3, 3) . '.' . substr($u->cpf, 6, 3) . '-' . substr($u->cpf, 9, 2) : '—' }}
                </td>
                <td>
                    @if($u->is_admin)
                        <span class="badge-sm badge-admin">Admin</span>
                    @else
                        <span class="badge-sm badge-user">Usuário</span>
                    @endif
                </td>
                <td>
                    @if($u->hasVerifiedEmail())
                        <span class="badge-sm badge-verified">Verificado</span>
                    @else
                        <span class="badge-sm badge-unverified">Pendente</span>
                    @endif
                </td>
                <td style="color: rgba(255,255,255,0.4); white-space: nowrap; font-size: 12px;">
                    {{ $u->created_at->format('d/m/Y H:i') }}
                </td>
                <td>
                    <div class="action-btns">
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.usuarios.toggle-admin', $u->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-sm btn-toggle-admin">
                                {{ $u->is_admin ? '✕ Remover Admin' : '★ Dar Admin' }}
                            </button>
                        </form>
                        @endif

                        @if(!$u->hasVerifiedEmail())
                        <form action="{{ route('admin.usuarios.verificar', $u->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-sm btn-verify">✓ Verificar</button>
                        </form>
                        @endif

                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.usuarios.excluir', $u->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Excluir conta de {{ $u->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-delete">🗑 Excluir</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="6">Nenhum usuário encontrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection