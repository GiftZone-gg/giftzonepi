@extends('admin.layout')

@section('title', __('messages.admin_products'))

@section('extra-styles')
<style>
    .toolbar {
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
    }
    .toolbar-left { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    .search-input {
        padding: 9px 16px; background: rgba(0,40,48,0.8);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;
        color: #fff; font-size: 13px; outline: none; min-width: 220px;
        transition: border-color 0.2s;
    }
    .search-input:focus { border-color: var(--yellow-gold); }
    .search-input::placeholder { color: rgba(255,255,255,0.25); }

    .filter-select {
        padding: 9px 14px; background: rgba(0,40,48,0.8);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;
        color: #fff; font-size: 13px; outline: none; cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23FDE9A2' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
        padding-right: 32px;
    }
    .filter-select option { background: #002830; }

    /* Tabela */
    .products-table-wrap {
        background: rgba(0,26,32,0.6); border: 1px solid rgba(253,233,162,0.1);
        border-radius: 16px; overflow: hidden;
    }
    .products-table { width: 100%; border-collapse: collapse; }

    .products-table th {
        text-align: left; padding: 14px 20px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.08em;
        color: rgba(255,255,255,0.35); border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .products-table td {
        padding: 14px 20px; font-size: 13px; color: rgba(255,255,255,0.75);
        border-bottom: 1px solid rgba(255,255,255,0.03); vertical-align: middle;
    }
    .products-table tr:hover td { background: rgba(255,255,255,0.02); }

    .prod-thumb {
        width: 56px; height: 38px; border-radius: 6px; object-fit: cover;
        background: #1F6D7E; flex-shrink: 0;
    }

    .prod-name-cell { display: flex; align-items: center; gap: 12px; }
    .prod-name { font-weight: 600; color: var(--white); }
    .prod-slug { font-size: 11px; color: rgba(255,255,255,0.3); }

    .badge-active {
        display: inline-block; padding: 3px 10px; border-radius: 20px;
        font-size: 10px; font-weight: 700; text-transform: uppercase;
    }
    .badge-on { background: rgba(107,255,181,0.15); color: #6bffb5; }
    .badge-off { background: rgba(255,80,80,0.15); color: #ff6b6b; }

    .plat-tag {
        display: inline-block; padding: 2px 8px; border-radius: 4px;
        font-size: 10px; font-weight: 600; margin: 1px 2px;
        background: rgba(90,220,232,0.12); color: #5adce8;
    }

    .actions-cell { display: flex; gap: 6px; align-items: center; }

    .btn-icon {
        width: 32px; height: 32px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1);
        background: transparent; color: rgba(255,255,255,0.4); cursor: pointer;
        display: flex; align-items: center; justify-content: center; font-size: 12px;
        transition: all 0.2s;
    }
    .btn-icon:hover { border-color: rgba(255,255,255,0.3); color: rgba(255,255,255,0.7); }
    .btn-icon.edit:hover { border-color: var(--yellow-gold); color: var(--yellow-gold); }
    .btn-icon.toggle:hover { border-color: #5adce8; color: #5adce8; }
    .btn-icon.danger:hover { border-color: #ff6b6b; color: #ff6b6b; background: rgba(255,80,80,0.08); }

    .empty-row td { text-align: center; padding: 50px; color: rgba(255,255,255,0.2); font-size: 14px; }

    @media (max-width: 768px) {
        .products-table-wrap { overflow-x: auto; }
        .toolbar { flex-direction: column; align-items: stretch; }
    }
</style>
@endsection

@section('content')

<p class="page-title">{{ __('messages.admin_products') }}</p>

<div class="toolbar">
    <div class="toolbar-left">
        <form method="GET" action="{{ route('admin.produtos') }}" style="display: flex; gap: 10px; flex-wrap: wrap;">
            <input type="text" name="busca" class="search-input" placeholder="{{ __('messages.admin_search') }}" value="{{ request('busca') }}">
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">{{ __('messages.admin_filter_all') }}</option>
                <option value="ativo" {{ request('status') === 'ativo' ? 'selected' : '' }}>{{ __('messages.admin_filter_active') }}</option>
                <option value="inativo" {{ request('status') === 'inativo' ? 'selected' : '' }}>{{ __('messages.admin_filter_inactive') }}</option>
            </select>
        </form>
    </div>
    <a href="{{ route('admin.produtos.criar') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> {{ __('messages.admin_add_product') }}
    </a>
</div>

<div class="products-table-wrap">
    <table class="products-table">
        <thead>
            <tr>
                <th>{{ __('messages.admin_product_name') }}</th>
                <th>{{ __('messages.admin_product_genre') }}</th>
                <th>{{ __('messages.admin_product_platforms') }}</th>
                <th>{{ __('messages.price') }}</th>
                <th>{{ __('messages.admin_status') }}</th>
                <th>{{ __('messages.admin_actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produtos as $produto)
            @php
                $edicoes = is_array($produto->edicoes) ? $produto->edicoes : [];
                $precoMin = !empty($edicoes) ? collect($edicoes)->min('preco') : 0;
                $plats = is_array($produto->plataformas) ? $produto->plataformas : [];
            @endphp
            <tr>
                <td>
                    <div class="prod-name-cell">
                        <img class="prod-thumb" src="{{ asset('images/' . $produto->imagem_principal) }}" alt="" onerror="this.style.display='none'">
                        <div>
                            <div class="prod-name">{{ $produto->nome }}</div>
                            <div class="prod-slug">{{ $produto->slug }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $produto->genero ?? '—' }}</td>
                <td>
                    @foreach($plats as $p)
                        <span class="plat-tag">{{ $p }}</span>
                    @endforeach
                </td>
                <td style="font-weight: 600; color: var(--yellow-light);">R$ {{ number_format($precoMin, 2, ',', '.') }}</td>
                <td>
                    <span class="badge-active {{ $produto->ativo ? 'badge-on' : 'badge-off' }}">
                        {{ $produto->ativo ? __('messages.admin_filter_active') : __('messages.admin_filter_inactive') }}
                    </span>
                </td>
                <td>
                    <div class="actions-cell">
                        <a href="{{ route('admin.produtos.editar', $produto->id) }}" class="btn-icon edit" title="{{ __('messages.admin_edit_product') }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form action="{{ route('admin.produtos.toggle', $produto->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-icon toggle" title="{{ $produto->ativo ? 'Desativar' : 'Ativar' }}">
                                <i class="fa-solid {{ $produto->ativo ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                            </button>
                        </form>

                        <form action="{{ route('admin.produtos.remover', $produto->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.admin_confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="{{ __('messages.admin_delete') }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="6">{{ __('messages.admin_no_products') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection