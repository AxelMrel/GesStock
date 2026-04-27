@extends('layouts.app')

@section('title', 'Catégories')
@section('page_title', 'Catégories')

@section('content')

{{-- EN-TÊTE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1E3A5F">Toutes les catégories</h5>
        <small class="text-muted">{{ $categories->total() }} catégorie(s) au total</small>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreer">
        <i class="fas fa-plus me-2"></i>Nouvelle catégorie
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
                        <th>Description</th>
                        <th>Articles</th>
                        <th>Créée le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $categorie)
                        <tr>
                            <td><span class="fw-medium" style="color:#1E3A5F">{{ $categorie->nom }}</span></td>
                            <td><span class="text-muted" style="font-size:13px">{{ $categorie->description ?? '—' }}</span></td>
                            <td>
                                <span class="badge" style="background:#EFF6FF;color:#2563EB">
                                    {{ $categorie->articles_count }} article(s)
                                </span>
                            </td>
                            <td><span class="text-muted" style="font-size:12px">{{ $categorie->created_at->format('d/m/Y') }}</span></td>
                            <td>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" title="Modifier"
                                            onclick="ouvrirModalEdition({{ $categorie->id }}, '{{ addslashes($categorie->nom) }}', '{{ addslashes($categorie->description ?? '') }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                            onclick="ouvrirModalSuppression({{ $categorie->id }}, '{{ addslashes($categorie->nom) }}', {{ $categorie->articles_count }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-tags fa-3x mb-3 d-block text-muted"></i>
                                <p class="text-muted mb-3">Aucune catégorie créée</p>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreer">
                                    <i class="fas fa-plus me-1"></i>Créer la première catégorie
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- MODAL CRÉER --}}
<div class="modal fade" id="modalCreer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-plus me-2" style="color:#2563EB"></i>Nouvelle catégorie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf
                <input type="hidden" name="_action" value="create">
                <div class="modal-body">
                    @if($errors->any() && old('_action') === 'create')
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag text-muted"></i></span>
                            <input type="text" name="nom"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}"
                                   placeholder="Ex : Matériels informatiques" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-medium">Description <span class="text-muted fw-normal">(optionnelle)</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Description de la catégorie...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

{{-- MODAL ÉDITER --}}
<div class="modal fade" id="modalEditer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-edit me-2" style="color:#2563EB"></i>Modifier la catégorie
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{-- action="" est REQUIS — le JS le remplace avec l'ID correct --}}
            <form method="POST" id="formEditer" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="_action" value="edit">
                <div class="modal-body">
                    @if($errors->any() && old('_action') === 'edit')
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag text-muted"></i></span>
                            <input type="text" name="nom" id="editNom" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-medium">Description <span class="text-muted fw-normal">(optionnelle)</span></label>
                        <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
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
                <h6 class="fw-semibold mb-1">Supprimer la catégorie</h6>
                <p class="text-muted mb-1" style="font-size:13px">
                    Voulez-vous vraiment supprimer <strong id="supprimerNom"></strong> ?
                </p>
                <p class="text-danger mb-0" style="font-size:12px" id="supprimerAvertissement"></p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">
                    Annuler
                </button>
                {{-- action="" est REQUIS — le JS le remplace avec l'ID correct --}}
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

        // ── Modal ÉDITION ──────────────────────────────────────
        window.ouvrirModalEdition = function(id, nom, description) {
            document.getElementById('editNom').value         = nom;
            document.getElementById('editDescription').value = description;
            document.getElementById('formEditer').action     = '{{ url("categories") }}/' + id;
            new bootstrap.Modal(document.getElementById('modalEditer')).show();
        }

        // ── Modal SUPPRESSION ──────────────────────────────────
        window.ouvrirModalSuppression = function(id, nom, nbArticles) {
            document.getElementById('supprimerNom').textContent = nom;
            document.getElementById('formSupprimer').action     = '{{ url("categories") }}/' + id;

            const avert  = document.getElementById('supprimerAvertissement');
            const btnDel = document.querySelector('#formSupprimer button[type=submit]');

            if (nbArticles > 0) {
                avert.textContent = '⚠ Cette catégorie contient ' + nbArticles + ' article(s) et ne peut pas être supprimée.';
                btnDel.disabled   = true;
            } else {
                avert.textContent = '';
                btnDel.disabled   = false;
            }

            new bootstrap.Modal(document.getElementById('modalSupprimer')).show();
        }

        @if($errors->any() && old('_action') === 'create')
            new bootstrap.Modal(document.getElementById('modalCreer')).show();
        @endif

        @if($errors->any() && old('_action') === 'edit')
            new bootstrap.Modal(document.getElementById('modalEditer')).show();
        @endif

    });
</script>
@endpush