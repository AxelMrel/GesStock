@extends('layouts.app')

@section('title', 'Mouvements')
@section('page-title', 'Mouvements de stock')
@section('page-breadcrumb', 'Entrées et sorties de stock')

@section('content')

{{-- EN-TÊTE --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1E3A5F">Historique des mouvements</h5>
        <small class="text-muted">{{ $mouvements->total() }} mouvement(s) au total</small>
    </div>
    <button type="button" class="btn btn-primary"
            data-bs-toggle="modal" data-bs-target="#modalMouvement">
        <i class="fas fa-plus me-2"></i>Nouveau mouvement
    </button>
</div>

{{-- FILTRES --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('mouvements.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-medium" style="font-size:13px">Type</label>
                <select name="type" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="entree" {{ request('type') === 'entree' ? 'selected' : '' }}>
                        Entrées uniquement
                    </option>
                    <option value="sortie" {{ request('type') === 'sortie' ? 'selected' : '' }}>
                        Sorties uniquement
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium" style="font-size:13px">Article</label>
                <select name="article_id" class="form-select">
                    <option value="">Tous les articles</option>
                    @foreach($articles as $article)
                        <option value="{{ $article->id }}"
                                {{ request('article_id') == $article->id ? 'selected' : '' }}>
                            {{ $article->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-medium" style="font-size:13px">Date début</label>
                <input type="date" name="date_debut" class="form-control"
                       value="{{ request('date_debut') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-medium" style="font-size:13px">Date fin</label>
                <input type="date" name="date_fin" class="form-control"
                       value="{{ request('date_fin') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>Filtrer
                </button>
                @if(request()->hasAny(['type', 'article_id', 'date_debut', 'date_fin']))
                    <a href="{{ route('mouvements.index') }}" class="btn btn-outline-secondary">
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
                        <th>Date</th>
                        <th>Type</th>
                        <th>Article</th>
                        <th>Quantité</th>
                        <th>Stock après</th>
                        <th>Motif</th>
                        <th>Par</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mouvements as $mouvement)
                        <tr>
                            <td>
                                <div style="font-size:13px;font-weight:500">
                                    {{ $mouvement->created_at->format('d/m/Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ $mouvement->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                @if($mouvement->type === 'entree')
                                    <span class="badge d-flex align-items-center gap-1"
                                          style="background:#ECFDF5;color:#059669;width:fit-content">
                                        <i class="fas fa-arrow-down"></i> Entrée
                                    </span>
                                @else
                                    <span class="badge d-flex align-items-center gap-1"
                                          style="background:#FEF2F2;color:#DC2626;width:fit-content">
                                        <i class="fas fa-arrow-up"></i> Sortie
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-medium" style="color:#1E3A5F;font-size:13px">
                                    {{ $mouvement->article->nom ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-bold" style="font-size:15px;
                                    color:{{ $mouvement->type === 'entree' ? '#059669' : '#DC2626' }}">
                                    {{ $mouvement->type === 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size:13px;color:#64748B">
                                    {{ $mouvement->article->quantite_stock ?? '—' }} unité(s)
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size:13px">
                                    {{ $mouvement->motif ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:28px;height:28px;border-radius:8px;
                                                background:#EFF6FF;color:#2563EB;
                                                display:flex;align-items:center;justify-content:center;
                                                font-size:11px;font-weight:600">
                                        {{ strtoupper(substr($mouvement->user->prenom ?? 'U', 0, 1)) }}{{ strtoupper(substr($mouvement->user->nom ?? '', 0, 1)) }}
                                    </div>
                                    <span style="font-size:12px">
                                        {{ $mouvement->user->prenom ?? '—' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Annuler ce mouvement"
                                            onclick="ouvrirModalAnnulation(
                                                {{ $mouvement->id }},
                                                '{{ $mouvement->type === 'entree' ? 'entrée' : 'sortie' }}',
                                                '{{ addslashes($mouvement->article->nom ?? '') }}',
                                                {{ $mouvement->quantite }}
                                            )">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-exchange-alt fa-3x mb-3 d-block text-muted"></i>
                                <p class="text-muted mb-3">Aucun mouvement enregistré</p>
                                <button type="button" class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#modalMouvement">
                                    <i class="fas fa-plus me-1"></i>Enregistrer le premier mouvement
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($mouvements->hasPages())
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $mouvements->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

{{-- MODAL NOUVEAU MOUVEMENT --}}
<div class="modal fade" id="modalMouvement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" style="color:#1E3A5F">
                    <i class="fas fa-exchange-alt me-2" style="color:#2563EB"></i>
                    Nouveau mouvement de stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('mouvements.store') }}" id="formMouvement">
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

                    {{-- Type de mouvement --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">Type de mouvement <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="type"
                                       id="typeEntree" value="entree"
                                       {{ old('type', 'entree') === 'entree' ? 'checked' : '' }}>
                                <label class="btn w-100 btn-outline-success" for="typeEntree">
                                    <i class="fas fa-arrow-down me-2"></i>Entrée
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="type"
                                       id="typeSortie" value="sortie"
                                       {{ old('type') === 'sortie' ? 'checked' : '' }}>
                                <label class="btn w-100 btn-outline-danger" for="typeSortie">
                                    <i class="fas fa-arrow-up me-2"></i>Sortie
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Article --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">Article <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                            <select name="article_id" id="selectArticle"
                                    class="form-select @error('article_id') is-invalid @enderror"
                                    required onchange="afficherStock(this)">
                                <option value="">Sélectionner un article</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}"
                                            data-stock="{{ $article->quantite_stock }}"
                                            {{ old('article_id') == $article->id ? 'selected' : '' }}>
                                        {{ $article->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('article_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- Affichage stock actuel --}}
                        <div id="stockActuel" class="mt-2 d-none">
                            <small>
                                Stock actuel :
                                <span id="stockValeur" class="fw-bold" style="color:#2563EB"></span>
                                unité(s)
                            </small>
                        </div>
                    </div>

                    {{-- Quantité --}}
                    <div class="mb-3">
                        <label class="form-label fw-medium">Quantité <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-sort-numeric-up"></i></span>
                            <input type="number" name="quantite" min="1"
                                   class="form-control @error('quantite') is-invalid @enderror"
                                   value="{{ old('quantite', 1) }}" required>
                            @error('quantite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Motif --}}
                    <div class="mb-2">
                        <label class="form-label fw-medium">
                            Motif <span class="text-muted fw-normal">(optionnel)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-comment"></i></span>
                            <textarea name="motif" class="form-control" rows="2"
                                      placeholder="Ex: Livraison fournisseur, Utilisation interne...">{{ old('motif') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnMouvement">
                        <span id="btnMouvementTexte">
                            <i class="fas fa-save me-1"></i>Enregistrer
                        </span>
                        <span id="btnMouvementLoader" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Enregistrement...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ANNULATION --}}
<div class="modal fade" id="modalAnnuler" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pt-0">
                <div class="mb-3">
                    <div style="width:56px;height:56px;background:#FEF3C7;border-radius:50%;
                                display:flex;align-items:center;justify-content:center;margin:0 auto">
                        <i class="fas fa-undo" style="color:#D97706;font-size:22px"></i>
                    </div>
                </div>
                <h6 class="fw-semibold mb-1">Annuler le mouvement</h6>
                <p class="text-muted mb-0" style="font-size:13px" id="annulationMessage"></p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                        data-bs-dismiss="modal">Non</button>
                <form method="POST" id="formAnnuler" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning btn-sm px-4" id="btnAnnuler">
                        <span id="btnAnnulerTexte">
                            <i class="fas fa-undo me-1"></i>Oui, annuler
                        </span>
                        <span id="btnAnnulerLoader" class="d-none">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Annulation...
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

        // ── Loader ─────────────────────────────────────────────
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

        // ── Loader formulaire mouvement ─────────────────────────
        document.getElementById('formMouvement').addEventListener('submit', function () {
            activerLoader('btnMouvementTexte', 'btnMouvementLoader', 'btnMouvement');
        });

        // ── Loader annulation ───────────────────────────────────
        document.getElementById('formAnnuler').addEventListener('submit', function () {
            activerLoader('btnAnnulerTexte', 'btnAnnulerLoader', 'btnAnnuler');
        });

        // ── Afficher stock actuel ───────────────────────────────
        window.afficherStock = function(select) {
            const option = select.options[select.selectedIndex];
            const stock  = option.dataset.stock;
            const div    = document.getElementById('stockActuel');
            const val    = document.getElementById('stockValeur');

            if (select.value) {
                div.classList.remove('d-none');
                val.textContent = stock;
                val.style.color = parseInt(stock) <= 5 ? '#DC2626' : '#2563EB';
            } else {
                div.classList.add('d-none');
            }
        }

        // ── Modal annulation ────────────────────────────────────
        window.ouvrirModalAnnulation = function(id, type, nom, quantite) {
            document.getElementById('annulationMessage').textContent =
                `Voulez-vous annuler cette ${type} de ${quantite} unité(s) pour "${nom}" ? Le stock sera restauré.`;
            document.getElementById('formAnnuler').action = '{{ url("mouvements") }}/' + id;
            resetLoader('btnAnnulerTexte', 'btnAnnulerLoader', 'btnAnnuler');
            new bootstrap.Modal(document.getElementById('modalAnnuler')).show();
        }

        // ── Rouvrir modal si erreur ─────────────────────────────
        @if($errors->any())
            new bootstrap.Modal(document.getElementById('modalMouvement')).show();
        @endif

    });
</script>
@endpush