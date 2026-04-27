<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - GesStock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color:   #2563EB;
            --secondary-color: #1E3A5F;
            --accent-color:    #3B82F6;
            --text-color:      #1E293B;
            --light-color:     #F1F5F9;
        }

        body {
            background: linear-gradient(135deg, #EFF6FF 0%, #F1F5F9 60%, #DBEAFE 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(37, 99, 235, 0.12);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            transition: transform 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-4px);
        }

        /* ── SIDEBAR ── */
        .auth-sidebar {
            background: linear-gradient(160deg, var(--secondary-color) 0%, #0F2A4A 100%);
            color: white;
            padding: 40px;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .auth-sidebar::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: rgba(37, 99, 235, 0.2);
        }

        .auth-sidebar::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.15);
        }

        .auth-sidebar > * {
            position: relative;
            z-index: 1;
        }

        .auth-sidebar h3 {
            color: white;
            font-weight: 600;
            font-size: 1.3rem;
        }

        /* Logo circle */
        .logo-circle {
            width: 72px; height: 72px;
            background: var(--primary-color);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
        }

        /* ── FORM SECTION ── */
        .auth-form {
            padding: 40px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 11px 15px;
            border: 2px solid #E2E8F0;
            font-size: 14px;
            transition: all 0.25s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #E2E8F0;
            border-right: none;
        }

        .input-group .form-control,
        .input-group .form-select {
            border-radius: 0 10px 10px 0;
        }

        .input-group .form-control:not(:last-child),
        .input-group .form-select:not(:last-child) {
            border-radius: 0;
        }

        .input-group > .btn {
            border-radius: 0 10px 10px 0;
            border: 2px solid #E2E8F0;
            border-left: none;
        }

        .form-label {
            font-weight: 500;
            font-size: 13px;
            margin-bottom: 6px;
            color: var(--text-color);
        }

        /* ── BUTTONS ── */
        .btn {
            padding: 11px 20px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.25s ease;
        }

        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        .btn-outline-light { border-width: 2px; }

        /* ── BENEFITS LIST ── */
        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .benefits-list li {
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            font-size: 14px;
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }

        .benefits-list li i {
            margin-right: 12px;
            font-size: 15px;
            width: 32px; height: 32px;
            background: rgba(255,255,255,0.12);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── ALERTS ── */
        .alert {
            border-radius: 10px;
            font-size: 13px;
            padding: 12px 16px;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .auth-sidebar {
                border-radius: 20px 20px 0 0;
                padding: 30px;
            }
            .auth-form {
                padding: 28px;
            }
        }
    </style>

    @yield('styles')
</head>
<body>

    <div class="auth-container">
        <div class="container">
            <div class="auth-card m-auto animate__animated animate__fadeIn">
                <div class="row g-0">

                    {{-- Sidebar gauche --}}
                    <div class="col-md-5 auth-sidebar d-flex flex-column justify-content-center">
                        @yield('sidebar')
                    </div>

                    {{-- Formulaire droite --}}
                    <div class="col-md-7">
                        <div class="auth-form">
                            @yield('content')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Animation liste benefits
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.benefits-list li, .features-list li').forEach((item, i) => {
                item.style.animationDelay = `${i * 0.1}s`;
            });
        });

        // Spinner sur submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Chargement...';
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>