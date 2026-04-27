@extends('layouts.app')

@section('title', 'Fournisseurs')
@section('page-title', 'Fournisseurs')
@section('page-breadcrumb', 'Gestion des fournisseurs')

@section('content')

{{-- EN-TÊTE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1E3A5F">Tous les fournisseurs</h5>
        <small class="text-muted">{{ $fournisseurs->total() }} fournisseur(s) au total</small>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreer">
        <i class="fas fa-plus me-2"></i>Nouveau fournisseur
    </button>
</div>

{{-- TABLEAU --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Articles</th>
                        <th>Ajouté le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fournisseurs as $fournisseur)
                        <tr>
                            <td>
                                <span class="fw-medium" style="color:#1E3A5F">
                                    {{ $fournisseur->nom }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size:13px">
                                    {{ $fournisseur->telephone ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size:13px">
                                    {{ $fournisseur->email ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size:13px">
                                    {{ $fournisseur->adresse ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="background:#EFF6FF;color:#2563EB">
                                    {{ $fournisseur->articles_count }} article(s)
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size:12px">
                                    {{ $fournisseur->created_at->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Modifier"
                                            onclick="ouvrirModalEdition(
                                                {{ $fournisseur->id }},
                                                '{{ addslashes($fournisseur->nom) }}',
                                                '{{ addslashes($fournisseur->telephone ?? '') }}',
                                                '{{ addslashes($fournisseur->email ?? '') }}',
                                                '{{ addslashes($fournisseur->adresse ?? '') }}'
                                            )">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Supprimer"
                                            onclick="ouvrirModalSuppression(
                                                {{ $fournisseur->id }},
                                                '{{ addslashes($fournisseur->nom) }}',
                                                {{ $fournisseur->articles_count }}
                                            )">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-truck fa-3x mb-3 d-block text-muted"></i>
                                <p class="text-muted mb-3">Aucun fournisseur ajouté</p>
                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCreer">
                                    <i class="fas fa-plus me-1"></i>Ajouter le premier fournisseur
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($fournisseurs->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $fournisseurs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- MODAL CRÉER --}}
<div class="modal fade" id="modalCreer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-plus me-2" style="color:#2563EB"></i>Nouveau fournisseur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('fournisseurs.store') }}" id="formCreer">
                @csrf
                <input type="hidden" name="_action" value="create">
                <div class="modal-body">
                    @if($errors->any() && old('_action') === 'create')
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-truck"></i></span>
                            <input type="text" name="nom"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}"
                                   placeholder="Nom du fournisseur" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" name="telephone"
                                       class="form-control @error('telephone') is-invalid @enderror"
                                       value="{{ old('telephone') }}"
                                       placeholder="+229 01 23 45 67">
                                @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}"
                                       placeholder="contact@fournisseur.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-medium">
                            Adresse <span class="text-muted fw-normal">(optionnelle)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea name="adresse"
                                      class="form-control @error('adresse') is-invalid @enderror"
                                      rows="2"
                                      placeholder="Adresse du fournisseur...">{{ old('adresse') }}</textarea>
                            @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnCreer">
                        <span id="btnCreerTexte">
                            <i class="fas fa-save me-1"></i>Enregistrer
                        </span>
                        <span id="btnCreerLoader" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ÉDITER --}}
<div class="modal fade" id="modalEditer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-edit me-2" style="color:#2563EB"></i>Modifier le fournisseur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formEditer" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="_action" value="edit">
                <div class="modal-body">
                    @if($errors->any() && old('_action') === 'edit')
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-truck"></i></span>
                            <input type="text" name="nom" id="editNom"
                                   class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" name="telephone" id="editTelephone"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" id="editEmail"
                                       class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-medium">Adresse</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea name="adresse" id="editAdresse"
                                      class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnEditer">
                        <span id="btnEditerTexte">
                            <i class="fas fa-save me-1"></i>Enregistrer
                        </span>
                        <span id="btnEditerLoader" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Enregistrement...
                        </span>
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
                    <div style="width:56px;height:56px;background:#FEE2E2;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;margin:0 auto">
                        <i class="fas fa-trash" style="color:#EF4444;font-size:22px"></i>
                    </div>
                </div>
                <h6 class="fw-semibold mb-1">Supprimer le fournisseur</h6>
                <p class="text-muted mb-1" style="font-size:13px">
                    Voulez-vous vraiment supprimer <strong id="supprimerNom"></strong> ?
                </p>
                <p class="text-danger mb-0" style="font-size:12px" id="supprimerAvertissement"></p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                        data-bs-dismiss="modal">Annuler</button>
                <form method="POST" id="formSupprimer" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm px-4" id="btnSupprimer">
                        <span id="btnSupprimerTexte">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </span>
                        <span id="btnSupprimerLoader" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Suppression...
                        </span>
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

        // ── Fonction loader générique ──────────────────────────
        function activerLoader(texteId, loaderId, btnId) {
            document.getElementById(texteId).classList.add('d-none');
            document.getElementById(loaderId).classList.remove('d-none');
            document.getElementById(btnId).disabled = true;
        }

        function resetLoader(texteId, loaderId, btnId) {
            document.getElementById(texteId).classList.remove('d-none');
            document.getElementById(loaderId).classList.add('d-none');
            document.getElementById(btnId).disabled = false;
        }

        // ── Loader création ────────────────────────────────────
        document.getElementById('formCreer').addEventListener('submit', function () {
            activerLoader('btnCreerTexte', 'btnCreerLoader', 'btnCreer');
        });

        // ── Loader édition ─────────────────────────────────────
        document.getElementById('formEditer').addEventListener('submit', function () {
            activerLoader('btnEditerTexte', 'btnEditerLoader', 'btnEditer');
        });

        // ── Loader suppression ─────────────────────────────────
        document.getElementById('formSupprimer').addEventListener('submit', function () {
            activerLoader('btnSupprimerTexte', 'btnSupprimerLoader', 'btnSupprimer');
        });

        // ── Ouvrir modal ÉDITION ───────────────────────────────
        window.ouvrirModalEdition = function(id, nom, telephone, email, adresse) {
            document.getElementById('editNom').value       = nom;
            document.getElementById('editTelephone').value = telephone;
            document.getElementById('editEmail').value     = email;
            document.getElementById('editAdresse').value   = adresse;
            document.getElementById('formEditer').action   = '{{ url("fournisseurs") }}/' + id;
            resetLoader('btnEditerTexte', 'btnEditerLoader', 'btnEditer');
            new bootstrap.Modal(document.getElementById('modalEditer')).show();
        }

        // ── Ouvrir modal SUPPRESSION ───────────────────────────
        window.ouvrirModalSuppression = function(id, nom, nbArticles) {
            document.getElementById('supprimerNom').textContent = nom;
            document.getElementById('formSupprimer').action     = '{{ url("fournisseurs") }}/' + id;

            const avert  = document.getElementById('supprimerAvertissement');
            const btnDel = document.getElementById('btnSupprimer');

            resetLoader('btnSupprimerTexte', 'btnSupprimerLoader', 'btnSupprimer');

            if (nbArticles > 0) {
                avert.textContent = '⚠ Ce fournisseur a ' + nbArticles + ' article(s) associé(s) et ne peut pas être supprimé.';
                btnDel.disabled   = true;
            } else {
                avert.textContent = '';
                btnDel.disabled   = false;
            }

            new bootstrap.Modal(document.getElementById('modalSupprimer')).show();
        }

        // ── Rouvrir modal si erreur validation ─────────────────
        @if($errors->any() && old('_action') === 'create')
            new bootstrap.Modal(document.getElementById('modalCreer')).show();
        @endif

        @if($errors->any() && old('_action') === 'edit')
            new bootstrap.Modal(document.getElementById('modalEditer')).show();
        @endif

    });
</script>
@endpush