<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone Admin | @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Gasoek+One&family=Inria+Sans:ital,wght@0,400;0,700;1,400&family=Crimson+Pro:ital,wght@0,400;0,600;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --yellow-light:  #FDE9A2;
            --yellow-main:   #FFDC74;
            --yellow-gold:   #F5C842;
            --teal-dark:     #1F6D7E;
            --teal-light:    #90DDE8;
            --white:         #FFFFFF;
            --sidebar-w:     260px;
            --sidebar-w-collapsed: 72px;
            --bg-from: #01313A;
            --bg-mid:  #0C4F58;
            --bg-to:   #1a6a5a;
            --admin-accent: #ff6b35;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--bg-from) 0%, var(--bg-mid) 55%, var(--bg-to) 100%);
            color: var(--white);
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            transition: width 0.3s ease;
        }
        .sidebar.collapsed { width: var(--sidebar-w-collapsed); }

        .sidebar-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .hamburger { display: flex; flex-direction: column; gap: 5px; cursor: pointer; background: none; border: none; }
        .hamburger span { width: 24px; height: 2px; background: var(--white); border-radius: 2px; }

        .logo-box { height: 40px; display: flex; align-items: center; }
        .logo-box img { width: 140px; }

        .admin-badge {
            display: inline-block; background: var(--admin-accent); color: #fff;
            font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 4px;
            text-transform: uppercase; letter-spacing: 1px; margin-left: 8px; vertical-align: middle;
        }

        .sidebar-nav { flex: 1; padding: 24px 0; display: flex; flex-direction: column; }

        .nav-item {
            display: block; padding: 14px 24px; color: rgba(255,255,255,0.6);
            text-decoration: none; font-size: 15px; font-weight: 500;
            transition: all 0.25s; border-left: 3px solid transparent; white-space: nowrap;
        }
        .nav-item:hover { color: var(--yellow-main); background: rgba(255,220,116,0.06); }
        .nav-item.active { color: var(--white); font-weight: 700; background: rgba(255,107,53,0.12); border-left: 4px solid var(--admin-accent); }

        .nav-item i { width: 22px; text-align: center; margin-right: 10px; font-size: 14px; }

        .sidebar.collapsed .nav-item span { display: none; }
        .sidebar.collapsed .nav-item { text-align: center; padding-left: 0; padding-right: 0; }
        .sidebar.collapsed .logo-box, .sidebar.collapsed .admin-badge { display: none; }

        .nav-group { flex: 1; }
        .nav-footer { border-top: 1px solid rgba(255,255,255,0.07); padding: 16px 0; }

        .btn-logout {
            width: 100%; padding: 14px 24px; background: transparent; border: none;
            border-left: 3px solid transparent; color: rgba(255,100,100,0.6);
            font-size: 15px; font-weight: 500; text-align: left; cursor: pointer;
            display: flex; align-items: center; gap: 10px; transition: all 0.25s;
        }
        .btn-logout:hover { color: #ff6b6b; background: rgba(255,80,80,0.06); border-left-color: #ff6b6b; }

        .sidebar.collapsed .btn-logout span { display: none; }
        .sidebar.collapsed .btn-logout { text-align: center; padding-left: 0; padding-right: 0; }

        /* ===== MAIN ===== */
        .main-content { flex: 1; padding: 32px 36px; overflow-y: auto; }

        /* ===== COMPONENTES ===== */
        .alert { border-radius: 10px; padding: 12px 18px; margin-bottom: 16px; font-size: 14px; font-weight: 500; }
        .alert-success { background: rgba(107,255,181,0.1); border: 1px solid rgba(107,255,181,0.3); color: #6bffb5; }
        .alert-error { background: rgba(255,80,80,0.1); border: 1px solid rgba(255,80,80,0.3); color: #ff6b6b; }

        .page-title {
            font-family: 'Gasoek One', sans-serif; font-size: 26px; color: var(--yellow-light); margin-bottom: 24px;
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 22px; background: var(--admin-accent); color: #fff;
            border: none; border-radius: 8px; font-weight: 600; font-size: 13px;
            cursor: pointer; text-decoration: none; transition: all 0.2s;
        }
        .btn-primary:hover { background: #ff8555; transform: translateY(-1px); }

        .btn-sm {
            padding: 6px 14px; border-radius: 6px; font-size: 12px; font-weight: 600;
            border: none; cursor: pointer; transition: all 0.2s;
        }

        .card {
            background: rgba(0, 26, 32, 0.6); border: 1px solid rgba(253,233,162,0.12);
            border-radius: 16px; padding: 24px;
        }

        @media (max-width: 768px) {
            .sidebar:not(.collapsed) { width: var(--sidebar-w-collapsed); }
            .sidebar:not(.collapsed) .nav-item span,
            .sidebar:not(.collapsed) .btn-logout span,
            .sidebar:not(.collapsed) .logo-box,
            .sidebar:not(.collapsed) .admin-badge { display: none; }
            .main-content { padding: 20px 16px; }
        }
    </style>

    @yield('extra-styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <button class="hamburger" id="menuToggleBtn"><span></span><span></span><span></span></button>
        <div class="logo-box">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone">
            </a>
        </div>
        <span class="admin-badge">Admin</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> <span>{{ __('messages.admin_dashboard') }}</span>
            </a>
            <a href="{{ route('admin.produtos') }}" class="nav-item {{ request()->routeIs('admin.produtos*') ? 'active' : '' }}">
                <i class="fa-solid fa-gamepad"></i> <span>{{ __('messages.admin_products') }}</span>
            </a>
            <a href="{{ route('admin.pedidos') }}" class="nav-item {{ request()->routeIs('admin.pedidos*') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked"></i> <span>{{ __('messages.admin_orders') }}</span>
            </a>
        </div>

        <div class="nav-footer">
            <a href="{{ route('home') }}" class="nav-item">
                <i class="fa-solid fa-arrow-left"></i> <span>{{ __('messages.admin_back_site') }}</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>{{ __('messages.logout') }}</span>
                </button>
            </form>
        </div>
    </nav>
</aside>

<main class="main-content">
    @if(session('success'))
        <div class="alert alert-success"><i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="fa-solid fa-xmark-circle" style="margin-right: 8px;"></i>{{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<script>
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggleBtn');
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('adminSidebarCollapsed', sidebar.classList.contains('collapsed'));
    }
    if (localStorage.getItem('adminSidebarCollapsed') === 'true') sidebar.classList.add('collapsed');
    menuToggle.addEventListener('click', toggleSidebar);
</script>

@yield('extra-scripts')
</body>
</html>