@extends('usuario.layout')

@section('title', __('messages.notifications'))

@section('extra-styles')
<style>
    .page-title {
        font-family: 'Gasoek One', sans-serif; font-size: 28px;
        color: var(--yellow-light); margin-bottom: 28px;
    }

    .alert { border-radius: 10px; padding: 12px 18px; margin-bottom: 16px; font-size: 14px; font-weight: 500; }
    .alert-success { background: rgba(107,255,181,0.1); border: 1px solid rgba(107,255,181,0.3); color: #6bffb5; }

    .notif-list { display: flex; flex-direction: column; gap: 12px; }

    .notif-item {
        background: rgba(0, 26, 32, 0.6);
        border: 1px solid rgba(253,233,162,0.12);
        border-radius: 14px;
        padding: 20px 22px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: border-color 0.2s;
        position: relative;
    }
    .notif-item:hover { border-color: rgba(253,233,162,0.3); }
    .notif-item.unread {
        border-color: rgba(255,107,53,0.3);
        background: rgba(255,107,53,0.04);
    }

    .notif-icon {
        width: 48px; height: 48px;
        background: rgba(255,220,116,0.08);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .notif-content { flex: 1; }

    .notif-title {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 15px;
        color: var(--white);
        margin-bottom: 4px;
    }

    .notif-message {
        font-size: 13px;
        color: rgba(255,255,255,0.5);
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .notif-time {
        font-size: 11px;
        color: rgba(255,255,255,0.25);
    }

    .notif-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-shrink: 0;
    }

    .notif-link {
        padding: 6px 14px;
        background: rgba(253,233,162,0.1);
        border: 1px solid rgba(253,233,162,0.2);
        border-radius: 6px;
        color: var(--yellow-light);
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .notif-link:hover { background: rgba(253,233,162,0.2); }

    .notif-delete {
        width: 30px; height: 30px;
        border-radius: 6px;
        border: 1px solid rgba(255,80,80,0.2);
        background: transparent;
        color: rgba(255,80,80,0.4);
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px;
        transition: all 0.2s;
    }
    .notif-delete:hover {
        border-color: #ff6b6b;
        color: #ff6b6b;
        background: rgba(255,80,80,0.08);
    }

    .unread-dot {
        position: absolute;
        top: 12px; right: 12px;
        width: 8px; height: 8px;
        background: #ff6b35;
        border-radius: 50%;
    }

    .empty-notif {
        text-align: center;
        padding: 80px 20px;
    }
    .empty-notif i {
        font-size: 56px;
        color: rgba(253,233,162,0.1);
        display: block;
        margin-bottom: 20px;
    }
    .empty-notif h2 {
        font-family: 'Gasoek One', sans-serif;
        font-size: 20px;
        color: var(--yellow-light);
        margin-bottom: 8px;
    }
    .empty-notif p {
        font-size: 14px;
        color: rgba(255,255,255,0.25);
    }

    @media (max-width: 600px) {
        .notif-item { flex-direction: column; }
        .notif-actions { width: 100%; justify-content: flex-end; }
    }
</style>
@endsection

@section('content')

<p class="page-title">{{ __('messages.notifications') }}</p>

@if(session('success'))
    <div class="alert alert-success"><i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i>{{ session('success') }}</div>
@endif

<div class="notif-list">
    @forelse($notificacoes as $notif)
    <div class="notif-item {{ !$notif->read ? 'unread' : '' }}">
        @if(!$notif->read)
            <div class="unread-dot"></div>
        @endif

        <div class="notif-icon">{{ $notif->icon }}</div>

        <div class="notif-content">
            <div class="notif-title">{{ $notif->title }}</div>
            <div class="notif-message">{{ $notif->message }}</div>
            <div class="notif-time">
                <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                {{ $notif->created_at->diffForHumans() }}
            </div>
        </div>

        <div class="notif-actions">
            @if($notif->link)
                <a href="{{ $notif->link }}" class="notif-link">Ver</a>
            @endif

            <form action="{{ route('usuario.notificacoes.excluir', $notif->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="notif-delete" title="Excluir">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-notif">
        <i class="fa-regular fa-bell-slash"></i>
        <h2>{{ __('messages.no_notifications') }}</h2>
        <p>{{ __('messages.notifications_appear_here') }}</p>
    </div>
    @endforelse
</div>

@endsection