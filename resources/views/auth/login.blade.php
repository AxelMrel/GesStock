@extends('layouts.auth')

@section('title', 'Connexion')

@section('sidebar')
    <div class="w-100 text-center animate__animated animate__fadeIn">
        <div class="logo-circle mx-auto mb-3">
            <i class="fas fa-boxes fa-2x text-white"></i>
        </div>
        <h3 class="animate__animated animate__fadeInUp text-white">GesStock</h3>
        <p class="animate__animated animate__fadeInUp animate__delay-1s text-white-50">
            Système de gestion des stocks — MA INFO
        </p>
    </div>
    <ul class="benefits-list mt-4">
        <li class="animate__animated animate__fadeInLeft animate__delay-1s">
            <i class="fas fa-cubes"></i> Gérez vos articles en temps réel
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-2s">
            <i class="fas fa-exchange-alt"></i> Suivez les entrées et sorties
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-3s">
            <i class="fas fa-bell"></i> Recevez des alertes de stock
        </li>
        <li class="animate__animated animate__fadeInLeft animate__delay-4s">
            <i class="fas fa-file-pdf"></i> Exportez vos rapports PDF
        </li>
    </ul>
    <div class="mt-4 text-center animate__animated animate__fadeInUp animate__delay-5s">
        <p class="mb-3 text-white-50">Pas encore inscrit ?</p>
        <a href="{{ route('register') }}" class="btn btn-outline-light w-100">
            <i class="fas fa-user-plus me-2"></i>Créer un compte
        </a>
    </div>
@endsection

@section('content')
    <div class="animate__animated animate__fadeIn">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color:#1E3A5F">Connexion à votre compte</h2>
            <p class="text-muted">Accédez à votre espace de gestion des stocks</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger animate__animated animate__shakeX">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="form-label fw-medium">Adresse email</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                        <i class="fas fa-envelope" style="color:#2563EB"></i>
                    </span>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="exemple@centre.bj"
                           required
                           autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Mot de passe --}}
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label fw-medium">Mot de passe</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-muted small text-decoration-none">
                            <i class="fas fa-question-circle"></i> Mot de passe oublié ?
                        </a>
                    @endif
                </div>
                <div class="input-group">
                    <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                        <i class="fas fa-lock" style="color:#2563EB"></i>
                    </span>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="••••••••"
                           required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Se souvenir de moi --}}
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label user-select-none" for="remember">
                        <i class="fas fa-clock text-muted me-1"></i>Se souvenir de moi
                    </label>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-lg text-white fw-medium"
                        style="background:#2563EB;border-color:#2563EB">
                    <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                </button>
                <a href="{{ route('register') }}" class="btn btn-lg btn-outline-secondary">
                    <i class="fas fa-user-plus me-2"></i>Pas de compte ? S'inscrire
                </a>
            </div>

        </form>

        <div class="text-center mt-4">
            <p class="text-muted small">
                En vous connectant, vous acceptez les
                <a href="#" class="text-decoration-none" style="color:#2563EB">conditions d'utilisation</a>
                du Centre informatique du Bénin.
            </p>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    (function () {
        'use strict'
        const forms = document.querySelectorAll('.needs-validation')
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password')
        const icon = document.getElementById('eyeIcon')
        if (input.type === 'password') {
            input.type = 'text'
            icon.classList.replace('fa-eye', 'fa-eye-slash')
        } else {
            input.type = 'password'
            icon.classList.replace('fa-eye-slash', 'fa-eye')
        }
    })
</script>
@endsection