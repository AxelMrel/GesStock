@extends('layouts.app')

@section('title', 'Articles')
@section('page-title', 'Articles')
@section('page-breadcrumb', 'Gestion des articles')

@section('content')

{{-- EN-TÊTE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1E3A5F">Tous les articles</h5>
        <small class="text-muted">{{ $articles->total() }} article(s) au total</small>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreer">
        <i class="fas fa-plus me-2"></i>Nouvel article
    </button>
</div>

{{-- FILTRES --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('articles.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-medium" style="font-size:13px">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control"
                           placeholder="Nom ou description..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium" style="font-size:13px">Catégorie</label>
                <select name="categorie_id" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                                {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium" style="font-size:13px">Stock</label>
                <select name="stock_faible" class="form-select">
                    <option value="">Tous les articles</option>
                    <option value="1" {{ request('stock_faible') ? 'selected' : '' }}>
                        Stock faible uniquement
                    </option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>Filtrer
                </button>
                @if(request()->hasAny(['search', 'categorie_id', 'stock_faible']))
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- TABLEAU --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Catégorie</th>
                        <th>Fournisseur</th>
                        <th>Stock</th>
                        <th>Prix unitaire</th>
                        <th>Valeur stock</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>
                                <div class="fw-medium" style="color:#1E3A5F">{{ $article->nom }}</div>
                                @if($article->description)
                                    <small class="text-muted">{{ Str::limit($article->description, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background:#F5F3FF;color:#7C3AED">
                                    {{ $article->categorie->nom ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size:13px">
                                    {{ $article->fournisseur->nom ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $article->quantite_stock }}</div>
                                <!-- <small class="text-muted">min: {{ $article->stock_minimum }}</small> -->
                            </td>
                            <td>
                                <span style="font-size:13px">
                                    {{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium" style="color:#1E3A5F;font-size:13px">
                                    {{ number_format($article->quantite_stock * $article->prix_unitaire, 0, ',', ' ') }} FCFA
                                </span>
                            </td>
                            <td>
                                @if($article->quantite_stock <= $article->stock_minimum)
                                    <span class="badge" style="background:#FEF2F2;color:#DC2626">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Stock faible
                                    </span>
                                @else
                                    <span class="badge" style="background:#ECFDF5;color:#059669">
                                        <i class="fas fa-check me-1"></i>Normal
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Modifier"
                                            onclick="ouvrirModalEdition(
                                                {{ $article->id }},
                                                '{{ addslashes($article->nom) }}',
                                                '{{ addslashes($article->description ?? '') }}',
                                                '{{ $article->categorie_id ?? '' }}',
                                                '{{ $article->fournisseur_id ?? '' }}',
                                                {{ $article->quantite_stock }},
                                                {{ $article->stock_minimum }},
                                                {{ $article->prix_unitaire }}
                                            )">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Supprimer"
                                            onclick="ouvrirModalSuppression(
                                                {{ $article->id }},
                                                '{{ addslashes($article->nom) }}',
                                                {{ $article->mouvements_count }}
                                            )">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-boxes fa-3x mb-3 d-block text-muted"></i>
                                <p class="text-muted mb-3">Aucun article trouvé</p>
                                <button type="button" class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalCreer">
                                    <i class="fas fa-plus me-1"></i>Créer le premier article
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($articles->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $articles->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- MODAL CRÉER --}}
<div class="modal fade" id="modalCreer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-plus me-2" style="color:#2563EB"></i>Nouvel article
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('articles.store') }}" id="formCreer">
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

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-box"></i></span>
                                <input type="text" name="nom"
                                       class="form-control @error('nom') is-invalid @enderror"
                                       value="{{ old('nom') }}"
                                       placeholder="Nom de l'article" required>
                                @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium">Prix unitaire (FCFA) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                <input type="number" name="prix_unitaire" min="0" step="1"
                                       class="form-control @error('prix_unitaire') is-invalid @enderror"
                                       value="{{ old('prix_unitaire', 0) }}" required>
                                @error('prix_unitaire')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Description <span class="text-muted fw-normal">(optionnelle)</span></label>
                        <textarea name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="2"
                                  placeholder="Description de l'article...">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Catégorie</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <select name="categorie_id"
                                        class="form-select @error('categorie_id') is-invalid @enderror">
                                    <option value="">Sans catégorie</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                                {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Fournisseur</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                <select name="fournisseur_id"
                                        class="form-select @error('fournisseur_id') is-invalid @enderror">
                                    <option value="">Sans fournisseur</option>
                                    @foreach($fournisseurs as $f)
                                        <option value="{{ $f->id }}"
                                                {{ old('fournisseur_id') == $f->id ? 'selected' : '' }}>
                                            {{ $f->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Quantité en stock <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                <input type="number" name="quantite_stock" min="0"
                                       class="form-control @error('quantite_stock') is-invalid @enderror"
                                       value="{{ old('quantite_stock', 0) }}" required>
                                @error('quantite_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Stock minimum <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-exclamation-triangle"></i></span>
                                <input type="number" name="stock_minimum" min="0"
                                       class="form-control @error('stock_minimum') is-invalid @enderror"
                                       value="{{ old('stock_minimum', 5) }}" required>
                                @error('stock_minimum')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <small class="text-muted">Une alerte sera déclenchée en dessous de ce seuil</small>
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

{{-- BOUTONS EXPORT --}}
<div class="d-flex gap-2 justify-content-end mt-3">
    <a href="{{ route('articles.export.pdf') }}"
       class="btn btn-outline-danger"
       target="_blank">
        <i class="fas fa-file-pdf me-2"></i>Exporter en PDF
    </a>
    <a href="{{ route('articles.export.excel') }}"
       class="btn btn-outline-success">
        <i class="fas fa-file-excel me-2"></i>Exporter en Excel
    </a>
</div>

{{-- MODAL ÉDITER --}}
<div class="modal fade" id="modalEditer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-edit me-2" style="color:#2563EB"></i>Modifier l'article
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

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-medium">Nom <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-box"></i></span>
                                <input type="text" name="nom" id="editNom"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-medium">Prix unitaire (FCFA) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-coins"></i></span>
                                <input type="number" name="prix_unitaire" id="editPrix"
                                       min="0" step="1" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" id="editDescription"
                                  class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Catégorie</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                <select name="categorie_id" id="editCategorie" class="form-select">
                                    <option value="">Sans catégorie</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Fournisseur</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-truck"></i></span>
                                <select name="fournisseur_id" id="editFournisseur" class="form-select">
                                    <option value="">Sans fournisseur</option>
                                    @foreach($fournisseurs as $f)
                                        <option value="{{ $f->id }}">{{ $f->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Quantité en stock <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                <input type="number" name="quantite_stock" id="editQuantite"
                                       min="0" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-medium">Stock minimum <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-exclamation-triangle"></i></span>
                                <input type="number" name="stock_minimum" id="editStockMin"
                                       min="0" class="form-control" required>
                            </div>
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
                <h6 class="fw-semibold mb-1">Supprimer l'article</h6>
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

        // Loaders
        document.getElementById('formCreer').addEventListener('submit', function () {
            activerLoader('btnCreerTexte', 'btnCreerLoader', 'btnCreer');
        });

        document.getElementById('formEditer').addEventListener('submit', function () {
            activerLoader('btnEditerTexte', 'btnEditerLoader', 'btnEditer');
        });

        document.getElementById('formSupprimer').addEventListener('submit', function () {
            activerLoader('btnSupprimerTexte', 'btnSupprimerLoader', 'btnSupprimer');
        });

        // Modal ÉDITION
        window.ouvrirModalEdition = function(id, nom, description, categorieId, fournisseurId, quantite, stockMin, prix) {
            document.getElementById('editNom').value         = nom;
            document.getElementById('editDescription').value = description;
            document.getElementById('editQuantite').value    = quantite;
            document.getElementById('editStockMin').value    = stockMin;
            document.getElementById('editPrix').value        = prix;
            document.getElementById('formEditer').action     = '{{ url("articles") }}/' + id;

            // Sélectionner catégorie
            const selCat = document.getElementById('editCategorie');
            selCat.value = categorieId || '';

            // Sélectionner fournisseur
            const selFou = document.getElementById('editFournisseur');
            selFou.value = fournisseurId || '';

            resetLoader('btnEditerTexte', 'btnEditerLoader', 'btnEditer');
            new bootstrap.Modal(document.getElementById('modalEditer')).show();
        }

        // Modal SUPPRESSION
        window.ouvrirModalSuppression = function(id, nom, nbMouvements) {
            document.getElementById('supprimerNom').textContent = nom;
            document.getElementById('formSupprimer').action     = '{{ url("articles") }}/' + id;

            const avert  = document.getElementById('supprimerAvertissement');
            const btnDel = document.getElementById('btnSupprimer');

            resetLoader('btnSupprimerTexte', 'btnSupprimerLoader', 'btnSupprimer');

            if (nbMouvements > 0) {
                avert.textContent = '⚠ Cet article a ' + nbMouvements + ' mouvement(s) et ne peut pas être supprimé.';
                btnDel.disabled   = true;
            } else {
                avert.textContent = '';
                btnDel.disabled   = false;
            }

            new bootstrap.Modal(document.getElementById('modalSupprimer')).show();
        }

        // Rouvrir modal si erreur
        @if($errors->any() && old('_action') === 'create')
            new bootstrap.Modal(document.getElementById('modalCreer')).show();
        @endif

        @if($errors->any() && old('_action') === 'edit')
            new bootstrap.Modal(document.getElementById('modalEditer')).show();
        @endif

    });
</script>
@endpush