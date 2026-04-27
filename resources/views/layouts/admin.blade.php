<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - GesStock</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary-color:    #2563EB;
            --primary-light:    #3B82F6;
            --primary-dark:     #1E3A5F;
            --primary-xdark:    #172B47;
            --primary-bg:       #EFF6FF;
            --primary-border:   #BFDBFE;
            --success-color:    #16A34A;
            --warning-color:    #D97706;
            --danger-color:     #DC2626;
            --light-color:      #F8FAFC;
            --dark-color:       #1E293B;
            --gray-color:       #64748B;
            --border-color:     #E2E8F0;
            --sidebar-width:    250px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F1F5F9;
            color: var(--dark-color);
        }

        /* ── WRAPPER ── */
        .wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-xdark) 0%, var(--primary-dark) 60%, var(--primary-color) 100%);
            color: #fff;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 999;
            box-shadow: 4px 0 15px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
            overflow-y: auto;
        }

        /* Header sidebar */
        .sidebar-header {
            padding: 1.2rem 1rem;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header .logo-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-header .brand-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.02em;
        }

        .sidebar-header .brand-sub {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.5);
            margin-top: -2px;
        }

        /* Nav links */
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.7rem 1.2rem;
            margin: 0.1rem 0.6rem;
            border-radius: 0.6rem;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
            font-weight: 500;
            border-left: 3px solid #93C5FD;
        }

        .sidebar .nav-link i {
            width: 18px;
            font-size: 0.95rem;
            text-align: center;
            flex-shrink: 0;
        }

        /* Section heading */
        .sidebar-heading {
            padding: 0.8rem 1.5rem 0.3rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.35);
            font-weight: 500;
        }

        .sidebar-divider {
            border-color: rgba(255,255,255,0.1);
            margin: 0.4rem 1rem;
        }

        /* Alerte stock (badge dans la sidebar) */
        .sidebar .alert-badge {
            background: #DC2626;
            color: #fff;
            font-size: 0.65rem;
            padding: 1px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Logout en bas */
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 0.6rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-footer .nav-link {
            color: rgba(255,255,255,0.6);
        }

        .sidebar-footer .nav-link:hover {
            color: #FCA5A5;
            background: rgba(220,38,38,0.15);
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            background-color: #F1F5F9;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        /* ── NAVBAR ── */
        .top-navbar {
            background-color: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 998;
            padding: 0.7rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
        }

        .top-navbar .page-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin: 0;
        }

        .top-navbar .page-breadcrumb {
            font-size: 0.75rem;
            color: var(--gray-color);
            margin: 0;
        }

        /* Notification cloche */
        .notif-btn {
            position: relative;
            background: var(--primary-bg);
            border: 1px solid var(--primary-border);
            border-radius: 10px;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary-color);
            cursor: pointer;
            transition: all 0.2s;
        }

        .notif-btn:hover { background: var(--primary-border); }

        .notif-btn .notif-count {
            position: absolute;
            top: -4px; right: -4px;
            background: var(--danger-color);
            color: #fff;
            font-size: 0.6rem;
            width: 16px; height: 16px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 600;
        }

        /* User dropdown */
        .user-btn {
            display: flex; align-items: center; gap: 8px;
            background: var(--primary-bg);
            border: 1px solid var(--primary-border);
            border-radius: 10px;
            padding: 5px 12px 5px 6px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .user-btn:hover { background: var(--primary-border); }

        .user-btn .user-avatar {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: var(--primary-color);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-btn .user-name {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--dark-color);
        }

        .user-btn .user-role {
            font-size: 0.65rem;
            color: var(--gray-color);
            margin-top: -2px;
        }

        /* ── CONTENU ── */
        .content-area {
            padding: 1.5rem;
            flex: 1;
        }

        /* ── CARDS ── */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            margin-bottom: 1.5rem;
            background: #fff;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 1.2rem 1.5rem;
            border-radius: 1rem 1rem 0 0 !important;
            font-weight: 600;
            color: var(--primary-dark);
        }

        /* ── BOUTONS ── */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 0.6rem;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 0.6rem;
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* ── FORMULAIRES ── */
        .form-control, .form-select {
            border-radius: 0.6rem;
            border: 1px solid var(--border-color);
            padding: 0.6rem 1rem;
            font-size: 0.875rem;
            transition: all 0.25s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.15);
        }

        .input-group-text {
            background: var(--primary-bg);
            border-color: var(--primary-border);
            color: var(--primary-color);
            border-radius: 0.6rem 0 0 0.6rem;
        }

        /* ── TABLEAUX ── */
        .table {
            background: #fff;
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 0;
            font-size: 0.875rem;
        }

        .table thead th {
            background-color: var(--primary-bg);
            border-bottom: 2px solid var(--primary-border);
            font-weight: 600;
            color: var(--primary-dark);
            padding: 0.9rem 1.2rem;
        }

        .table tbody td {
            padding: 0.85rem 1.2rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .table tbody tr:hover { background-color: #F8FAFF; }

        /* ── BADGES ── */
        .badge {
            padding: 0.4em 0.8em;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* ── ALERTES ── */
        .alert {
            border-radius: 0.8rem;
            border: none;
            font-size: 0.875rem;
        }

        /* ── PAGINATION ── */
        .pagination .page-link {
            color: var(--primary-color);
            border: none;
            margin: 0 2px;
            border-radius: 0.5rem;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* ── DROPDOWN ── */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: 0.8rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 0.4rem;
            font-size: 0.875rem;
        }

        .dropdown-item {
            border-radius: 0.5rem;
            padding: 0.5rem 0.9rem;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: var(--primary-bg);
            color: var(--primary-dark);
        }

        /* ── TOAST ── */
        .toast-container {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
        }

        /* ── MOBILE ── */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.active { margin-left: 0; }
            .main-content { width: 100%; margin-left: 0; }
            .main-content.active { margin-left: var(--sidebar-width); }
            .content-area { padding: 1rem; }
        }
    </style>

    @stack('styles')
</head>

<body>
<div class="wrapper">

    {{-- ════════════════════ SIDEBAR ════════════════════ --}}
    <nav class="sidebar" id="sidebar">

        {{-- Logo --}}
        <div class="sidebar-header">
            <div class="logo-icon">
                <i class="fas fa-boxes text-white" style="font-size:1.1rem"></i>
            </div>
            <div>
                <div class="brand-name">GesStock</div>
                <div class="brand-sub">MA INFO</div>
            </div>
        </div>

        {{-- Navigation --}}
        <ul class="list-unstyled mt-2">

            {{-- Dashboard --}}
            <li>
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Stocks</div>

            {{-- Articles --}}
            <li>
                <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}"
                   href="{{ route('articles.index') }}">
                    <i class="fas fa-boxes"></i>
                    <span>Articles</span>
                </a>
            </li>

            {{-- Entrées --}}
            <li>
                <a class="nav-link {{ request()->routeIs('mouvements.entrees') ? 'active' : '' }}"
                   href="{{ route('mouvements.entrees') }}">
                    <i class="fas fa-arrow-circle-down"></i>
                    <span>Entrées de stock</span>
                </a>
            </li>

            {{-- Sorties --}}
            <li>
                <a class="nav-link {{ request()->routeIs('mouvements.sorties') ? 'active' : '' }}"
                   href="{{ route('mouvements.sorties') }}">
                    <i class="fas fa-arrow-circle-up"></i>
                    <span>Sorties de stock</span>
                </a>
            </li>

            {{-- Historique --}}
            <li>
                <a class="nav-link {{ request()->routeIs('mouvements.historique') ? 'active' : '' }}"
                   href="{{ route('mouvements.historique') }}">
                    <i class="fas fa-history"></i>
                    <span>Historique</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Gestion</div>

            {{-- Catégories --}}
            <li>
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                   href="{{ route('categories.index') }}">
                    <i class="fas fa-tags"></i>
                    <span>Catégories</span>
                </a>
            </li>

            {{-- Fournisseurs --}}
            <li>
                <a class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}"
                   href="{{ route('fournisseurs.index') }}">
                    <i class="fas fa-truck"></i>
                    <span>Fournisseurs</span>
                </a>
            </li>

            {{-- Alertes --}}
            <li>
                <a class="nav-link {{ request()->routeIs('alertes.*') ? 'active' : '' }}"
                   href="{{ route('alertes.index') }}">
                    <i class="fas fa-bell"></i>
                    <span>Alertes</span>
                    @php $alertes = \App\Models\Alerte::where('lu', false)->count(); @endphp
                    @if($alertes > 0)
                        <span class="alert-badge">{{ $alertes }}</span>
                    @endif
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Rapports</div>

            {{-- Rapports --}}
            <li>
                <a class="nav-link {{ request()->routeIs('rapports.*') ? 'active' : '' }}"
                   href="{{ route('rapports.index') }}">
                    <i class="fas fa-file-pdf"></i>
                    <span>Rapports</span>
                </a>
            </li>

            {{-- Admin uniquement --}}
            @if(auth()->user()->role === 'admin')
                <hr class="sidebar-divider">
                <div class="sidebar-heading">Administration</div>

                <li>
                    <a class="nav-link {{ request()->routeIs('utilisateurs.*') ? 'active' : '' }}"
                       href="{{ route('utilisateurs.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                </li>

                <li>
                    <a class="nav-link {{ request()->routeIs('parametres.*') ? 'active' : '' }}"
                       href="{{ route('parametres.index') }}">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </a>
                </li>
            @endif

        </ul>

        {{-- Logout --}}
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link border-0 w-100 bg-transparent text-start">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>

    </nav>
    {{-- ════════════════════ FIN SIDEBAR ════════════════════ --}}


    {{-- ════════════════════ CONTENU PRINCIPAL ════════════════════ --}}
    <div class="main-content" id="mainContent">

        {{-- NAVBAR --}}
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                {{-- Bouton toggle mobile --}}
                <button class="btn btn-sm d-lg-none" id="sidebarToggle"
                        style="background:var(--primary-bg);border:1px solid var(--primary-border);border-radius:8px;width:36px;height:36px">
                    <i class="fas fa-bars" style="color:var(--primary-color)"></i>
                </button>
                <div>
                    <p class="page-title">@yield('page-title', 'Tableau de bord')</p>
                    <p class="page-breadcrumb">@yield('page-breadcrumb', 'GesStock')</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">

                {{-- Cloche alertes --}}
                <div class="dropdown">
                    <button class="notif-btn" data-bs-toggle="dropdown">
                        <i class="fas fa-bell" style="font-size:0.9rem"></i>
                        @if(isset($alertes) && $alertes > 0)
                            <span class="notif-count">{{ $alertes }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:280px">
                        <li class="px-3 py-2 fw-600" style="font-size:0.8rem;color:var(--primary-dark);font-weight:600">
                            Alertes de stock
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('alertes.index') }}"
                               style="font-size:0.8rem;color:var(--gray-color)">
                                <i class="fas fa-bell me-2" style="color:var(--warning-color)"></i>
                                Voir toutes les alertes
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- User --}}
                <div class="dropdown">
                    <a class="user-btn" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom ?? '', 0, 1)) }}
                        </div>
                        <div class="d-none d-md-block">
                            <div class="user-name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
                            <div class="user-role">
                                @if(auth()->user()->role === 'admin') Administrateur
                                @elseif(auth()->user()->role === 'gestionnaire') Gestionnaire
                                @else Consultant
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-chevron-down d-none d-md-block" style="font-size:0.65rem;color:var(--gray-color)"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="fas fa-user me-2" style="color:var(--primary-color)"></i>Mon profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        {{-- FIN NAVBAR --}}

        {{-- CONTENU --}}
        <div class="content-area">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

    </div>
    {{-- ════════════════════ FIN CONTENU PRINCIPAL ════════════════════ --}}

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Toggle sidebar mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('mainContent').classList.toggle('active');
    });

    // Fermer sidebar si clic en dehors (mobile)
    document.addEventListener('click', function (e) {
        const sidebar = document.getElementById('sidebar');
        const toggle  = document.getElementById('sidebarToggle');
        if (window.innerWidth <= 768 &&
            sidebar.classList.contains('active') &&
            !sidebar.contains(e.target) &&
            !toggle.contains(e.target)) {
            sidebar.classList.remove('active');
            document.getElementById('mainContent').classList.remove('active');
        }
    });
</script>

@stack('scripts')
</body>
</html>