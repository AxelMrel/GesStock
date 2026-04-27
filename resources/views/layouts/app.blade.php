<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de bord') - GesStock</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary:        #2563EB;
            --primary-light:  #3B82F6;
            --primary-dark:   #1E3A5F;
            --primary-xdark:  #172B47;
            --primary-bg:     #EFF6FF;
            --primary-border: #BFDBFE;
            --success:        #16A34A;
            --warning:        #D97706;
            --danger:         #DC2626;
            --light:          #F8FAFC;
            --dark:           #1E293B;
            --muted:          #64748B;
            --border:         #E2E8F0;
            --sidebar-width:  250px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #F1F5F9;
            color: var(--dark);
        }

        .wrapper { display: flex; width: 100%; min-height: 100vh; }

        /* ══════════════ SIDEBAR ══════════════ */
        .sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-xdark) 0%, var(--primary-dark) 60%, var(--primary) 100%);
            position: fixed; top: 0; left: 0;
            height: 100vh; z-index: 999;
            box-shadow: 4px 0 15px rgba(0,0,0,0.15);
            display: flex; flex-direction: column;
            transition: all 0.3s; overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.2rem 1rem;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; gap: 10px;
        }

        .sidebar-header .logo-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-header .brand-name { font-size: 1.1rem; font-weight: 600; color: #fff; }
        .sidebar-header .brand-sub  { font-size: 0.65rem; color: rgba(255,255,255,0.5); margin-top: -2px; }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.7rem 1.2rem; margin: 0.1rem 0.6rem;
            border-radius: 0.6rem; transition: all 0.25s;
            display: flex; align-items: center; gap: 10px;
            font-size: 0.875rem; text-decoration: none;
        }

        .sidebar .nav-link:hover { background: rgba(255,255,255,0.12); color: #fff; transform: translateX(4px); }
        .sidebar .nav-link.active { background: rgba(255,255,255,0.2); color: #fff; font-weight: 500; border-left: 3px solid #93C5FD; }
        .sidebar .nav-link i { width: 18px; font-size: 0.95rem; text-align: center; flex-shrink: 0; }

        .sidebar-heading { padding: 0.8rem 1.5rem 0.3rem; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.12em; color: rgba(255,255,255,0.35); font-weight: 500; }
        .sidebar-divider { border-color: rgba(255,255,255,0.1); margin: 0.4rem 1rem; }

        .sidebar .alert-badge { background: #DC2626; color: #fff; font-size: 0.65rem; padding: 1px 6px; border-radius: 10px; margin-left: auto; }

        .sidebar-footer { margin-top: auto; padding: 1rem 0.6rem; border-top: 1px solid rgba(255,255,255,0.1); }
        .sidebar-footer .nav-link { color: rgba(255,255,255,0.6); }
        .sidebar-footer .nav-link:hover { color: #FCA5A5; background: rgba(220,38,38,0.15); }

        /* ══════════════ MAIN ══════════════ */
        .main-content {
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh; margin-left: var(--sidebar-width);
            background: #F1F5F9; transition: all 0.3s;
            display: flex; flex-direction: column;
        }

        /* ══════════════ TOPBAR ══════════════ */
        .top-navbar {
            background: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            position: sticky; top: 0; z-index: 998;
            padding: 0.7rem 1.5rem;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .top-navbar .page-title    { font-size: 1rem; font-weight: 600; color: var(--primary-dark); margin: 0; }
        .top-navbar .page-subtitle { font-size: 0.75rem; color: var(--muted); margin: 0; }

        .notif-btn {
            position: relative; background: var(--primary-bg);
            border: 1px solid var(--primary-border); border-radius: 10px;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); cursor: pointer; transition: all 0.2s;
        }
        .notif-btn:hover { background: var(--primary-border); }
        .notif-btn .notif-count {
            position: absolute; top: -4px; right: -4px;
            background: var(--danger); color: #fff;
            font-size: 0.6rem; width: 16px; height: 16px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;
        }

        .user-btn {
            display: flex; align-items: center; gap: 8px;
            background: var(--primary-bg); border: 1px solid var(--primary-border);
            border-radius: 10px; padding: 5px 12px 5px 6px;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .user-btn:hover { background: var(--primary-border); }
        .user-btn .user-avatar {
            width: 28px; height: 28px; border-radius: 8px;
            background: var(--primary); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 600; flex-shrink: 0;
        }
        .user-btn .user-name  { font-size: 0.8rem; font-weight: 500; color: var(--dark); }
        .user-btn .user-role  { font-size: 0.65rem; color: var(--muted); margin-top: -2px; }

        /* ══════════════ CONTENU ══════════════ */
        .content-area { padding: 1.5rem; flex: 1; }

        /* ══════════════ CARDS ══════════════ */
        .card { border: none; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); transition: transform 0.25s, box-shadow 0.25s; margin-bottom: 1.5rem; background: #fff; }
        .card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card-header { background: #fff; border-bottom: 1px solid var(--border); padding: 1.2rem 1.5rem; border-radius: 1rem 1rem 0 0 !important; font-weight: 600; color: var(--primary-dark); }

        /* ══════════════ BOUTONS ══════════════ */
        .btn { font-family: 'Poppins', sans-serif; font-weight: 500; }
        .btn-primary { background: var(--primary); border-color: var(--primary); border-radius: 0.6rem; transition: all 0.25s; }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); transform: translateY(-1px); }
        .btn-outline-primary { color: var(--primary); border-color: var(--primary); border-radius: 0.6rem; }
        .btn-outline-primary:hover { background: var(--primary); border-color: var(--primary); }

        /* ══════════════ FORMULAIRES ══════════════ */
        .form-control, .form-select { border-radius: 0.6rem; border: 1px solid var(--border); padding: 0.6rem 1rem; font-size: 0.875rem; transition: all 0.25s; font-family: 'Poppins', sans-serif; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.15); }
        .input-group-text { background: var(--primary-bg); border-color: var(--primary-border); color: var(--primary); border-radius: 0.6rem 0 0 0.6rem; }

        /* ══════════════ TABLEAUX ══════════════ */
        .table { background: #fff; border-radius: 1rem; overflow: hidden; margin-bottom: 0; font-size: 0.875rem; }
        .table thead th { background: var(--primary-bg); border-bottom: 2px solid var(--primary-border); font-weight: 600; color: var(--primary-dark); padding: 0.9rem 1.2rem; }
        .table tbody td { padding: 0.85rem 1.2rem; vertical-align: middle; border-color: var(--border); }
        .table tbody tr:hover { background: #F8FAFF; }

        /* ══════════════ AUTRES ══════════════ */
        .badge { padding: 0.4em 0.8em; border-radius: 0.5rem; font-weight: 500; font-size: 0.75rem; }
        .alert { border-radius: 0.8rem; border: none; font-size: 0.875rem; }
        .pagination .page-link { color: var(--primary); border: none; margin: 0 2px; border-radius: 0.5rem; }
        .pagination .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }
        .dropdown-menu { border: 1px solid var(--border); border-radius: 0.8rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 0.4rem; font-size: 0.875rem; }
        .dropdown-item { border-radius: 0.5rem; padding: 0.5rem 0.9rem; transition: all 0.2s; }
        .dropdown-item:hover { background: var(--primary-bg); color: var(--primary-dark); }
        .modal-content { border: none; border-radius: 1rem; }
        .modal-header { background: var(--primary-bg); border-bottom: 1px solid var(--primary-border); border-radius: 1rem 1rem 0 0; padding: 1.2rem 1.5rem; }

        /* ══════════════ MOBILE ══════════════ */
        @media (max-width: 768px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
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

    {{-- ════════════════ SIDEBAR ════════════════ --}}
    <nav class="sidebar" id="sidebar">

        <div class="sidebar-header">
            <div class="logo-icon">
                <i class="fas fa-boxes text-white" style="font-size:1.1rem"></i>
            </div>
            <div>
                <div class="brand-name">GesStock</div>
                <div class="brand-sub">MA INFO</div>
            </div>
        </div>

        @php
            try { $alertes_count = \App\Models\Alerte::where('lu', false)->count(); }
            catch(\Exception $e) { $alertes_count = 0; }
        @endphp

        <ul class="list-unstyled mt-2">

            <li>
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-chart-pie"></i><span>Tableau de bord</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <li>
                <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                    <i class="fas fa-boxes"></i><span>Articles</span>
                </a>
            </li>

            @if(auth()->user()->role !== 'consultant')
            <li>
                <a class="nav-link {{ request()->routeIs('mouvements.*') ? 'active' : '' }}" href="{{ route('mouvements.index') }}">
                    <i class="fas fa-exchange-alt"></i><span>Mouvements</span>
                </a>
            </li>
            @endif

            <hr class="sidebar-divider">

            <li>
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <i class="fas fa-tags"></i><span>Catégories</span>
                </a>
            </li>

            @if(auth()->user()->role !== 'consultant')
            <li>
                <a class="nav-link {{ request()->routeIs('fournisseurs.*') ? 'active' : '' }}" href="{{ route('fournisseurs.index') }}">
                    <i class="fas fa-truck"></i><span>Fournisseurs</span>
                </a>
            </li>
            @endif

            <!-- <li>
                <a class="nav-link {{ request()->routeIs('alertes.*') ? 'active' : '' }}" href="{{ route('alertes.index') }}">
                    <i class="fas fa-bell"></i><span>Alertes</span>
                    @if($alertes_count > 0)
                        <span class="alert-badge">{{ $alertes_count }}</span>
                    @endif
                </a>
            </li> -->

            @if(auth()->user()->role !== 'consultant')
            <hr class="sidebar-divider">

            <li>
                <a class="nav-link {{ request()->routeIs('rapports.*') ? 'active' : '' }}" href="{{ route('rapports.index') }}">
                    <i class="fas fa-file-pdf"></i><span>Rapports</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->role === 'admin')
            <hr class="sidebar-divider">

            <li>
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="fas fa-users"></i><span>Utilisateurs</span>
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('invitations.*') ? 'active' : '' }}" href="{{ route('invitations.index') }}">
                    <i class="fas fa-key"></i><span>Invitations</span>
                </a>
            </li>
            <!-- <li>
                <a class="nav-link {{ request()->routeIs('parametres.*') ? 'active' : '' }}" href="{{ route('parametres.index') }}">
                    <i class="fas fa-cog"></i><span>Paramètres</span>
                </a>
            </li> -->
            @endif

        </ul>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link border-0 w-100 bg-transparent text-start">
                    <i class="fas fa-sign-out-alt"></i><span>Déconnexion</span>
                </button>
            </form>
        </div>

    </nav>

    {{-- ════════════════ MAIN CONTENT ════════════════ --}}
    <div class="main-content" id="mainContent">

        {{-- TOPBAR --}}
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm d-lg-none" id="sidebarToggle"
                        style="background:var(--primary-bg);border:1px solid var(--primary-border);border-radius:8px;width:36px;height:36px">
                    <i class="fas fa-bars" style="color:var(--primary)"></i>
                </button>
                <div>
                    <p class="page-title">@yield('page_title', 'Tableau de bord')</p>
                    <p class="page-subtitle">@yield('page_subtitle', 'GesStock')</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">

                {{-- Notifications --}}
                <div class="dropdown">
                    <button class="notif-btn" data-bs-toggle="dropdown">
                        <i class="fas fa-bell" style="font-size:0.9rem"></i>
                        @if($alertes_count > 0)
                            <span class="notif-count">{{ $alertes_count }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:280px">
                        <li class="px-3 py-2" style="font-size:0.8rem;color:var(--primary-dark);font-weight:600">
                            Alertes de stock
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('alertes.index') }}" style="font-size:0.8rem">
                                <i class="fas fa-bell me-2" style="color:var(--warning)"></i>
                                Voir toutes les alertes
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Profil --}}
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
                        <i class="fas fa-chevron-down d-none d-md-block" style="font-size:0.65rem;color:var(--muted)"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="px-3 py-2 border-bottom mb-1">
                                <div style="font-size:13px;font-weight:600;color:var(--primary-dark)">
                                    {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                                </div>
                                <div style="font-size:11px;color:var(--muted)">{{ auth()->user()->email }}</div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user me-2" style="color:var(--primary)"></i>Mon profil
                            </a>
                        </li>
                        @if(auth()->user()->role === 'admin')
                        <li>
                            <a class="dropdown-item" href="{{ route('parametres.index') }}">
                                <i class="fas fa-cog me-2" style="color:var(--primary)"></i>Paramètres
                            </a>
                        </li>
                        @endif
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

        {{-- CONTENU --}}
        <div class="content-area">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown mb-4">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown mb-4">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show animate__animated animate__fadeInDown mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')

        </div>
    </div>

</div>

{{-- Scripts — Bootstrap DOIT être chargé AVANT @stack('scripts') --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Toggle sidebar mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('mainContent').classList.toggle('active');
    });

    // Fermer sidebar en cliquant à l'extérieur (mobile)
    document.addEventListener('click', function (e) {
        const sidebar = document.getElementById('sidebar');
        const toggle  = document.getElementById('sidebarToggle');
        if (window.innerWidth <= 768 &&
            sidebar.classList.contains('active') &&
            !sidebar.contains(e.target) &&
            !toggle?.contains(e.target)) {
            sidebar.classList.remove('active');
            document.getElementById('mainContent').classList.remove('active');
        }
    });

    // Auto-hide alerts après 4s
    setTimeout(function () {
        document.querySelectorAll('.alert-dismissible').forEach(function (el) {
            var alert = bootstrap.Alert.getOrCreateInstance(el);
            alert.close();
        });
    }, 4000);
</script>

@stack('scripts')

</body>
</html>