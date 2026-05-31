<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiftZone | @yield('title', 'Usuário')</title>
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
            --black:         #000000;
            --white:         #FFFFFF;
            --sidebar-w:     280px;
            --sidebar-w-collapsed: 80px;
            --bg-from: #01313A;
            --bg-mid:  #0C4F58;
            --bg-to:   #CCCA95;
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
            background: rgba(0, 0, 0, 0.35);
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

        .sidebar.collapsed {
            width: var(--sidebar-w-collapsed);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 28px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .hamburger { 
            display: flex; 
            flex-direction: column; 
            gap: 5px; 
            cursor: pointer; 
            background: none; 
            border: none;
        }
        .hamburger span { 
            width: 26px; 
            height: 2px; 
            background: var(--white); 
            border-radius: 2px; 
            transition: 0.2s;
        }

        .logo-box { 
            height: 44px; 
            display: flex; 
            align-items: center; 
        }
        /* .logo-box a {
            display: flex;
            align-items: center;
        } */
        .logo-box img { 
            width: 160px;
            /* height: 100%;  */
        }

        .sidebar-nav {
            flex: 1;
            padding: 30px 0;
            display: flex;
            flex-direction: column;
        }

        .nav-item {
            display: block;
            padding: 16px 28px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-size: 17px;
            font-weight: 500;
            transition: all 0.25s ease;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-item span,
        .sidebar.collapsed .btn-logout span {
            display: none;
        }
        .sidebar.collapsed .nav-item,
        .sidebar.collapsed .btn-logout {
            text-align: center;
            padding-left: 0;
            padding-right: 0;
        }

        .nav-item:hover {
            color: var(--yellow-main);
            background: rgba(255,220,116,0.06);
        }

        .nav-item.active {
            color: var(--white);
            font-weight: 700;
            font-style: italic;
            background: rgba(255,220,116,0.1);
            border-left: 4px solid var(--yellow-gold);
        }

        .nav-group { flex: 1; }

        .nav-footer {
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 16px 0;
        }

        .btn-logout {
            width: 100%;
            padding: 16px 28px;
            background: transparent;
            border: none;
            border-left: 3px solid transparent;
            color: rgba(255,100,100,0.7);
            font-family: 'Inter', sans-serif;
            font-size: 17px;
            font-weight: 500;
            text-align: left;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-logout:hover {
            color: #ff6b6b;
            background: rgba(255,80,80,0.06);
            border-left-color: #ff6b6b;
        }

        .main-content {
            flex: 1;
            padding: 36px 40px;
            overflow-y: auto;
            transition: padding 0.3s ease;
        }

        @media (max-width: 768px) {
.logo-box img { 
            width: 0px;
            height: 0px; 
        }


            .sidebar:not(.collapsed) { width: var(--sidebar-w-collapsed); }
            .sidebar:not(.collapsed) .nav-item span,
            .sidebar:not(.collapsed) .btn-logout span { display: none; }
            .main-content { padding: 20px 16px; }
        }

        

        /* Componentes globais */
        .card {
            background: rgba(0, 26, 32, 0.6);
            border: 1px solid rgba(253, 233, 162, 0.2);
            border-radius: 16px;
            padding: 30px;
        }

        .btn-main {
            display: inline-block;
            padding: 12px 28px;
            background: var(--yellow-main);
            color: #001A20;
            border: none;
            border-radius: 10px;
            font-family: 'Gasoek One', sans-serif;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, background 0.2s;
            text-transform: uppercase;
        }
        .btn-main:hover { transform: translateY(-2px); background: var(--yellow-light); }

        .btn-outline {
            display: inline-block;
            padding: 12px 28px;
            background: transparent;
            color: var(--yellow-main);
            border: 1px solid var(--yellow-main);
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-outline:hover { background: var(--yellow-main); color: #001A20; }

        .tag-status {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-family: 'Inria Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
        }
        .tag-concluido { background: var(--yellow-gold); color: #001A20; }
        .tag-pendente  { background: rgba(144,221,232,0.25); color: var(--teal-light); border: 1px solid var(--teal-light); }
        .tag-cancelado { background: rgba(255,80,80,0.2); color: #ff6b6b; border: 1px solid #ff6b6b; }

        .badge-cashback {
            position: absolute;
            top: 8px; right: 8px;
            background: var(--yellow-gold);
            color: #001A20;
            font-family: 'Inria Sans', sans-serif;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .sidebar.collapsed .logo-box {
    display: none;
}
    </style>

    @yield('extra-styles')
</head>
<body>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button class="hamburger" id="menuToggleBtn">
                <span></span><span></span><span></span>
            </button>
            <div class="logo-box">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/logo-tema-escuro.svg') }}" alt="GiftZone Logo">
                </a>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group">
                <a href="{{ route('usuario.perfil') }}" class="nav-item {{ request()->routeIs('usuario.perfil') ? 'active' : '' }}">
                    <i class="fa-regular fa-user" style="margin-right: 12px;"></i> <span>Meu Perfil</span>
                </a>
                <a href="{{ route('usuario.pedidos') }}" class="nav-item {{ request()->routeIs('usuario.pedidos') ? 'active' : '' }}">
                    <i class="fa-solid fa-box" style="margin-right: 12px;"></i> <span>Pedidos</span>
                </a>
                <a href="{{ route('usuario.pagamentos') }}" class="nav-item {{ request()->routeIs('usuario.pagamentos') ? 'active' : '' }}">
                    <i class="fa-regular fa-credit-card" style="margin-right: 12px;"></i> <span>Pagamentos</span>
                </a>
                <a href="{{ route('usuario.favoritos') }}" class="nav-item {{ request()->routeIs('usuario.favoritos') ? 'active' : '' }}">
                    <i class="fa-regular fa-heart" style="margin-right: 12px;"></i> <span>Favoritos</span>
                </a>
                <a href="{{ route('usuario.editar') }}" class="nav-item {{ request()->routeIs('usuario.editar') ? 'active' : '' }}">
                    <i class="fa-regular fa-pen-to-square" style="margin-right: 12px;"></i> <span>Editar Perfil</span>
                </a>
                <a href="{{ route('home') }}" class="nav-item">
                    <i class="fa-solid fa-house" style="margin-right: 12px;"></i> <span>Início</span>
                </a>
            </div>

            <div class="nav-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket"></i> <span>Sair da conta ({{ Auth::user()->nickname ?? Auth::user()->name }})</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

    <script>
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.getElementById('menuToggleBtn');
        
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
        
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
        }
        
        menuToggle.addEventListener('click', toggleSidebar);
    </script>

    @yield('extra-scripts')
</body>
</html>