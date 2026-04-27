@extends('layouts.app')

@section('title', 'Codes d\'invitation')
@section('page_title', 'Codes d\'invitation')
@section('page_subtitle', 'Gestion des accès')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1E3A5F">Codes d'invitation</h5>
        <small class="text-muted">{{ $invitations->total() }} code(s) au total</small>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalGenerer">
        <i class="fas fa-plus me-2"></i>Générer des codes
    </button>
</div>

{{-- STATS --}}
@php
    $tous      = \App\Models\InvitationCode::count();
    $utilises  = \App\Models\InvitationCode::where('is_used', true)->count();
    $disponibles = \App\Models\InvitationCode::where('is_used', false)->count();
    $expires   = \App\Models\InvitationCode::where('is_used', false)->where('expires_at', '<', now())->count();
@endphp

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-key" style="color:#2563EB"></i>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#1E3A5F">{{ $tous }}</div>
                    <div style="font-size:11px;color:#64748B">Total</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#D1FAE5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-check-circle" style="color:#16A34A"></i>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#1E3A5F">{{ $disponibles }}</div>
                    <div style="font-size:11px;color:#64748B">Disponibles</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#DBEAFE;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-user-check" style="color:#2563EB"></i>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#1E3A5F">{{ $utilises }}</div>
                    <div style="font-size:11px;color:#64748B">Utilisés</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card mb-0">
            <div class="card-body d-flex align-items-center gap-3 py-3">
                <div style="width:42px;height:42px;background:#FEE2E2;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-clock" style="color:#DC2626"></i>
                </div>
                <div>
                    <div style="font-size:20px;font-weight:700;color:#1E3A5F">{{ $expires }}</div>
                    <div style="font-size:11px;color:#64748B">Expirés</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABLEAU --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Statut</th>
                        <th>Utilisé par</th>
                        <th>Expire le</th>
                        <th>Créé par</th>
                        <th>Créé le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invitations as $invitation)
                        <tr>
                            {{-- Code --}}
                            <td>
                                <code style="background:#EFF6FF;color:#2563EB;padding:4px 10px;border-radius:6px;font-size:13px;font-weight:600;letter-spacing:0.05em">
                                    {{ $invitation->code }}
                                </code>
                            </td>

                            {{-- Statut --}}
                            <td>
                                @if($invitation->is_used)
                                    <span class="badge" style="background:#DBEAFE;color:#1E40AF">
                                        <i class="fas fa-check me-1"></i>Utilisé
                                    </span>
                                @elseif($invitation->expires_at && $invitation->expires_at->isPast())
                                    <span class="badge" style="background:#FEE2E2;color:#991B1B">
                                        <i class="fas fa-times me-1"></i>Expiré
                                    </span>
                                @else
                                    <span class="badge" style="background:#D1FAE5;color:#065F46">
                                        <i class="fas fa-circle me-1" style="font-size:8px"></i>Disponible
                                    </span>
                                @endif
                            </td>

                            {{-- Utilisé par --}}
                            <td>
                                @if($invitation->utilisateur)
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:26px;height:26px;border-radius:6px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;color:#2563EB">
                                            {{ strtoupper(substr($invitation->utilisateur->prenom,0,1)) }}{{ strtoupper(substr($invitation->utilisateur->nom,0,1)) }}
                                        </div>
                                        <span style="font-size:13px">{{ $invitation->utilisateur->prenom }} {{ $invitation->utilisateur->nom }}</span>
                                    </div>
                                @else
                                    <span class="text-muted" style="font-size:13px">—</span>
                                @endif
                            </td>

                            {{-- Expire le --}}
                            <td>
                                @if($invitation->expires_at)
                                    <span style="font-size:12px;color:{{ $invitation->expires_at->isPast() ? '#DC2626' : '#64748B' }}">
                                        {{ $invitation->expires_at->format('d/m/Y') }}
                                        @if(!$invitation->is_used && !$invitation->expires_at->isPast())
                                            <br><small style="font-size:10px;color:#16A34A">dans {{ $invitation->expires_at->diffForHumans() }}</small>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size:12px">Illimité</span>
                                @endif
                            </td>

                            {{-- Créé par --}}
                            <td>
                                @if($invitation->createur)
                                    <span style="font-size:13px">{{ $invitation->createur->prenom }} {{ $invitation->createur->nom }}</span>
                                @else
                                    <span class="text-muted" style="font-size:13px">—</span>
                                @endif
                            </td>

                            {{-- Créé le --}}
                            <td>
                                <span class="text-muted" style="font-size:12px">
                                    {{ $invitation->created_at->format('d/m/Y') }}
                                </span>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="d-flex gap-2 justify-content-end">
                                    {{-- Copier le code --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Copier le code"
                                            onclick="copierCode('{{ $invitation->code }}', this)">
                                        <i class="fas fa-copy"></i>
                                    </button>

                                    {{-- Supprimer (seulement si non utilisé) --}}
                                    @if(!$invitation->is_used)
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Supprimer"
                                                onclick="ouvrirModalSuppression({{ $invitation->id }}, '{{ $invitation->code }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-key fa-3x mb-3 d-block text-muted"></i>
                                <p class="text-muted mb-3">Aucun code d'invitation généré</p>
                                <button type="button" class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalGenerer">
                                    <i class="fas fa-plus me-1"></i>Générer des codes
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($invitations->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $invitations->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>


{{-- MODAL GÉNÉRER --}}
<div class="modal fade" id="modalGenerer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-key me-2" style="color:#2563EB"></i>Générer des codes d'invitation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('invitations.generer') }}">
                @csrf
                <div class="modal-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nombre de codes <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            <input type="number"
                                   name="nombre"
                                   class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', 1) }}"
                                   min="1" max="20"
                                   placeholder="Entre 1 et 20"
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Maximum 20 codes à la fois</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-medium">Validité <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <select name="expires_in" class="form-select @error('expires_in') is-invalid @enderror" required>
                                <option value="7"  {{ old('expires_in') == 7  ? 'selected' : '' }}>7 jours</option>
                                <option value="15" {{ old('expires_in') == 15 ? 'selected' : '' }}>15 jours</option>
                                <option value="30" {{ old('expires_in', 30) == 30 ? 'selected' : '' }}>30 jours</option>
                                <option value="60" {{ old('expires_in') == 60 ? 'selected' : '' }}>60 jours</option>
                                <option value="90" {{ old('expires_in') == 90 ? 'selected' : '' }}>90 jours</option>
                            </select>
                            @error('expires_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key me-1"></i>Générer
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
                <h6 class="fw-semibold mb-1">Supprimer le code</h6>
                <p class="text-muted mb-0" style="font-size:13px">
                    Voulez-vous supprimer le code<br>
                    <code id="supprimerCode" style="background:#EFF6FF;color:#2563EB;padding:3px 8px;border-radius:4px;font-weight:600"></code> ?
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

        // ── Modal SUPPRESSION ──────────────────────────────
        window.ouvrirModalSuppression = function(id, code) {
            document.getElementById('supprimerCode').textContent = code;
            document.getElementById('formSupprimer').action = '{{ url("invitations") }}/' + id;
            new bootstrap.Modal(document.getElementById('modalSupprimer')).show();
        }

        // ── Copier le code ─────────────────────────────────
        window.copierCode = function(code, btn) {
            navigator.clipboard.writeText(code).then(function() {
                const icon = btn.querySelector('i');
                icon.className = 'fas fa-check';
                btn.classList.replace('btn-outline-primary', 'btn-success');
                setTimeout(function() {
                    icon.className = 'fas fa-copy';
                    btn.classList.replace('btn-success', 'btn-outline-primary');
                }, 2000);
            });
        }

        // ── Rouvrir modal si erreur validation ─────────────
        @if($errors->any())
            new bootstrap.Modal(document.getElementById('modalGenerer')).show();
        @endif

    });
</script>
@endpush