@extends('layouts.auth')

@section('title', 'Inscription')

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
        <p class="mb-3 text-white-50">Déjà inscrit ?</p>
        <a href="{{ route('login') }}" class="btn btn-outline-light w-100">
            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
        </a>
    </div>
@endsection

@section('content')
    <div class="animate__animated animate__fadeIn">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="color:#1E3A5F">Créer votre compte</h2>
            <p class="text-muted">Remplissez le formulaire pour accéder au système</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger animate__animated animate__shakeX">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
            @csrf
            <div class="row">

                {{-- Nom --}}
                <div class="col-md-6 mb-3">
                    <label for="nom" class="form-label fw-medium">Nom</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                            <i class="fas fa-user" style="color:#2563EB"></i>
                        </span>
                        <input type="text"
                               class="form-control @error('nom') is-invalid @enderror"
                               id="nom" name="nom"
                               value="{{ old('nom') }}"
                               placeholder="Votre nom"
                               required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Prénom --}}
                <div class="col-md-6 mb-3">
                    <label for="prenom" class="form-label fw-medium">Prénom</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                            <i class="fas fa-user" style="color:#2563EB"></i>
                        </span>
                        <input type="text"
                               class="form-control @error('prenom') is-invalid @enderror"
                               id="prenom" name="prenom"
                               value="{{ old('prenom') }}"
                               placeholder="Votre prénom"
                               required>
                        @error('prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label fw-medium">Adresse email</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                            <i class="fas fa-envelope" style="color:#2563EB"></i>
                        </span>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="exemple@centre.bj"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Rôle --}}
                <div class="col-md-6 mb-3">
                    <label for="role" class="form-label fw-medium">Rôle</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                            <i class="fas fa-user-shield" style="color:#2563EB"></i>
                        </span>
                        <select class="form-select @error('role') is-invalid @enderror"
                                id="role" name="role" required>
                            <option value="" disabled selected>Choisir un rôle</option>
                            <option value="gestionnaire" {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>
                                Gestionnaire de stock
                            </option>
                            <option value="consultant" {{ old('role') == 'consultant' ? 'selected' : '' }}>
                                Consultant (lecture seule)
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Le rôle Admin est attribué uniquement par l'administrateur.
                    </small>
                </div>

                {{-- Mot de passe --}}
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label fw-medium">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                            <i class="fas fa-lock" style="color:#2563EB"></i>
                        </span>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password"
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

                {{-- Confirmation mot de passe --}}
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label fw-medium">Confirmer le mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                            <i class="fas fa-lock" style="color:#2563EB"></i>
                        </span>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="••••••••"
                               required>
                    </div>
                </div>

            </div>

            {{-- Code d'invitation --}}
            <div class="mb-3">
                <label for="invitation_code" class="form-label fw-medium">
                    Code d'invitation
                    <span class="badge ms-1" style="background:#DBEAFE;color:#1E3A5F;font-size:10px">
                        Requis
                    </span>
                </label>
                <div class="input-group">
                    <span class="input-group-text" style="background:#EFF6FF;border-color:#BFDBFE">
                        <i class="fas fa-key" style="color:#2563EB"></i>
                    </span>
                    <input type="password"
                           class="form-control @error('invitation_code') is-invalid @enderror"
                           id="invitation_code" name="invitation_code"
                           placeholder="Demandez ce code à l'administrateur"
                           required>
                    @error('invitation_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    Ce code garantit que seules les personnes autorisées peuvent créer un compte.
                </small>
            </div>

            {{-- Boutons --}}
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-lg text-white fw-medium"
                        style="background:#2563EB;border-color:#2563EB">
                    <i class="fas fa-user-plus me-2"></i>Créer mon compte
                </button>
                <a href="{{ route('login') }}" class="btn btn-lg btn-outline-secondary">
                    <i class="fas fa-sign-in-alt me-2"></i>Déjà inscrit ? Se connecter
                </a>
            </div>

        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Validation Bootstrap
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

    // Afficher / masquer le mot de passe
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