@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page_title', 'Utilisateurs')
@section('page_subtitle', 'Gestion des comptes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1E3A5F">Tous les utilisateurs</h5>
        <small class="text-muted">{{ $users->total() }} utilisateur(s) au total</small>
    </div>
    <a href="{{ route('invitations.index') }}" class="btn btn-primary">
        <i class="fas fa-key me-2"></i>Gérer les invitations
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Inscrit le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            {{-- Avatar + Nom --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:34px;height:34px;border-radius:8px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#2563EB;flex-shrink:0">
                                        {{ strtoupper(substr($user->prenom,0,1)) }}{{ strtoupper(substr($user->nom,0,1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-medium" style="color:#1E3A5F;font-size:13px">
                                            {{ $user->prenom }} {{ $user->nom }}
                                            @if($user->id === auth()->id())
                                                <span class="badge ms-1" style="background:#EFF6FF;color:#2563EB;font-size:10px">Vous</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Email --}}
                            <td>
                                <span class="text-muted" style="font-size:13px">{{ $user->email }}</span>
                            </td>

                            {{-- Rôle --}}
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge" style="background:#FEF3C7;color:#92400E">
                                        <i class="fas fa-crown me-1"></i>Admin
                                    </span>
                                @elseif($user->role === 'gestionnaire')
                                    <span class="badge" style="background:#DBEAFE;color:#1E40AF">
                                        <i class="fas fa-user-tie me-1"></i>Gestionnaire
                                    </span>
                                @else
                                    <span class="badge" style="background:#D1FAE5;color:#065F46">
                                        <i class="fas fa-eye me-1"></i>Consultant
                                    </span>
                                @endif
                            </td>

                            {{-- Statut --}}
                            <td>
                                @if($user->is_active)
                                    <span class="badge" style="background:#D1FAE5;color:#065F46">
                                        <i class="fas fa-circle me-1" style="font-size:8px"></i>Actif
                                    </span>
                                @else
                                    <span class="badge" style="background:#FEE2E2;color:#991B1B">
                                        <i class="fas fa-circle me-1" style="font-size:8px"></i>Inactif
                                    </span>
                                @endif
                            </td>

                            {{-- Date --}}
                            <td>
                                <span class="text-muted" style="font-size:12px">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex gap-2 justify-content-end">

                                    {{-- Changer le rôle --}}
                                    @if($user->id !== auth()->id())
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Modifier le rôle"
                                                onclick="ouvrirModalRole(
                                                    {{ $user->id }},
                                                    '{{ addslashes($user->prenom) }} {{ addslashes($user->nom) }}',
                                                    '{{ $user->role }}'
                                                )">
                                            <i class="fas fa-user-shield"></i>
                                        </button>

                                        {{-- Activer / Désactiver --}}
                                        <form method="POST" action="{{ route('users.toggle', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    title="{{ $user->is_active ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>

                                        {{-- Supprimer --}}
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Supprimer"
                                                onclick="ouvrirModalSuppression(
                                                    {{ $user->id }},
                                                    '{{ addslashes($user->prenom) }} {{ addslashes($user->nom) }}'
                                                )">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-users fa-3x mb-3 d-block text-muted"></i>
                                <p class="text-muted">Aucun utilisateur trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>


{{-- MODAL CHANGER RÔLE --}}
<div class="modal fade" id="modalRole" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-user-shield me-2" style="color:#2563EB"></i>Modifier le rôle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formRole" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-muted mb-3" style="font-size:13px">
                        Modifier le rôle de <strong id="roleUserNom"></strong>
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nouveau rôle</label>
                        <div class="d-flex flex-column gap-2">

                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 border cursor-pointer"
                                   style="cursor:pointer" id="labelAdmin">
                                <input type="radio" name="role" value="admin" id="roleAdmin" class="form-check-input mt-0">
                                <div>
                                    <div class="fw-medium" style="font-size:13px">
                                        <span class="badge me-1" style="background:#FEF3C7;color:#92400E">Admin</span>
                                    </div>
                                    <div class="text-muted" style="font-size:11px">Accès complet à toutes les fonctionnalités</div>
                                </div>
                            </label>

                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 border cursor-pointer"
                                   style="cursor:pointer" id="labelGestionnaire">
                                <input type="radio" name="role" value="gestionnaire" id="roleGestionnaire" class="form-check-input mt-0">
                                <div>
                                    <div class="fw-medium" style="font-size:13px">
                                        <span class="badge me-1" style="background:#DBEAFE;color:#1E40AF">Gestionnaire</span>
                                    </div>
                                    <div class="text-muted" style="font-size:11px">Gestion des stocks, articles, mouvements</div>
                                </div>
                            </label>

                            <label class="d-flex align-items-center gap-3 p-3 rounded-3 border cursor-pointer"
                                   style="cursor:pointer" id="labelConsultant">
                                <input type="radio" name="role" value="consultant" id="roleConsultant" class="form-check-input mt-0">
                                <div>
                                    <div class="fw-medium" style="font-size:13px">
                                        <span class="badge me-1" style="background:#D1FAE5;color:#065F46">Consultant</span>
                                    </div>
                                    <div class="text-muted" style="font-size:11px">Lecture seule — pas de modification</div>
                                </div>
                            </label>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- MODAL SUPPRIMER --}}
<div class="modal fade" id="modalSupprimer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-3">
                    <div style="width:56px;height:56px;background:#FEE2E2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto">
                        <i class="fas fa-trash" style="color:#EF4444;font-size:22px"></i>
                    </div>
                </div>
                <h6 class="fw-semibold mb-1">Supprimer l'utilisateur</h6>
                <p class="text-muted mb-0" style="font-size:13px">
                    Voulez-vous vraiment supprimer <strong id="supprimerNom"></strong> ?
                    <br><span style="font-size:11px;color:#EF4444">Cette action est irréversible.</span>
                </p>
            </div>
            <div class="modal-footer border-0 pt-2 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">
                    Annuler
                </button>
                <form method="POST" id="formSupprimer" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-4">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Modal RÔLE ─────────────────────────────────────
        window.ouvrirModalRole = function(id, nom, roleActuel) {
            document.getElementById('roleUserNom').textContent = nom;
            document.getElementById('formRole').action = '{{ url("users") }}/' + id;

            // Cocher le rôle actuel
            document.querySelectorAll('input[name="role"]').forEach(function(radio) {
                radio.checked = (radio.value === roleActuel);
            });

            new bootstrap.Modal(document.getElementById('modalRole')).show();
        }

        // ── Modal SUPPRESSION ──────────────────────────────
        window.ouvrirModalSuppression = function(id, nom) {
            document.getElementById('supprimerNom').textContent = nom;
            document.getElementById('formSupprimer').action = '{{ url("users") }}/' + id;
            new bootstrap.Modal(document.getElementById('modalSupprimer')).show();
        }

    });
</script>
@endpush