@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-breadcrumb', 'Vue générale du stock')

@section('content')

{{-- ── CARTES STATISTIQUES ── --}}
<div class="row g-3 mb-4">

    {{-- ── EXPORT ── --}}
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold mb-1" style="color:#1E3A5F">
                        <i class="fas fa-download me-2" style="color:#2563EB"></i>
                        Exporter les données
                    </h6>
                    <small class="text-muted">Téléchargez un rapport complet du stock actuel</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('rapports.export-pdf') }}"
                    class="btn btn-outline-danger"
                    target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Exporter en PDF
                    </a>
                    <a href="{{ route('articles.export.excel') }}"
                    class="btn btn-outline-success">
                        <i class="fas fa-file-excel me-2"></i>Exporter en Excel
                    </a>
                    <a href="{{ route('rapports.index') }}"
                    class="btn btn-primary">
                        <i class="fas fa-chart-bar me-2"></i>Voir les rapports
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:4px solid #2563EB">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Articles</span>
                    <div style="width:38px;height:38px;background:#EFF6FF;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-boxes" style="color:#2563EB;font-size:15px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#1E3A5F">{{ $stats['total_articles'] }}</h3>
                <small class="text-muted">articles en stock</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:4px solid #D97706">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Valeur stock</span>
                    <div style="width:38px;height:38px;background:#FFFBEB;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-coins" style="color:#D97706;font-size:15px"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-0" style="color:#1E3A5F;font-size:1rem">
                    {{ number_format($valeur_stock, 0, ',', ' ') }} FCFA
                </h5>
                <small class="text-muted">valeur totale</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:4px solid #059669">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Entrées (30j)</span>
                    <div style="width:38px;height:38px;background:#ECFDF5;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-arrow-down" style="color:#059669;font-size:15px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#059669">+{{ $stats['total_entrees'] }}</h3>
                <small class="text-muted">unités reçues</small>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:4px solid #DC2626">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Sorties (30j)</span>
                    <div style="width:38px;height:38px;background:#FEF2F2;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-arrow-up" style="color:#DC2626;font-size:15px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#DC2626">-{{ $stats['total_sorties'] }}</h3>
                <small class="text-muted">unités sorties</small>
            </div>
        </div>
    </div>

</div>

{{-- ── GRAPHIQUE + ALERTES ── --}}
<div class="row g-3 mb-4">

    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-chart-bar me-2" style="color:#2563EB"></i>Mouvements des 7 derniers jours</span>
                <a href="{{ route('rapports.index') }}" class="btn btn-sm btn-outline-primary">
                    Rapport complet
                </a>
            </div>
            <div class="card-body">
                <canvas id="mouvementsChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-bell me-2 text-danger"></i>Stock faible</span>
                <span class="badge bg-danger">{{ $articles_alerte->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($articles_alerte as $article)
                    <div class="d-flex align-items-center justify-content-between px-3 py-2"
                         style="border-bottom:1px solid #F1F5F9">
                        <div>
                            <div style="font-size:13px;font-weight:500;color:#1E3A5F">
                                {{ Str::limit($article->nom, 22) }}
                            </div>
                            <div style="font-size:11px;color:#64748B">
                                {{ $article->categorie->nom ?? '—' }}
                            </div>
                        </div>
                        <span class="badge" style="background:#FEF2F2;color:#DC2626;font-size:11px">
                            {{ $article->quantite_stock }}/{{ $article->stock_minimum }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p class="text-muted small mb-0">Aucune alerte de stock</p>
                    </div>
                @endforelse
            </div>
            @if($articles_alerte->count() > 0)
                <div class="card-footer text-center" style="background:#fff;border-top:1px solid #F1F5F9">
                    <a href="{{ route('articles.index') }}"
                       style="font-size:12px;color:#2563EB;text-decoration:none">
                        Voir tous les articles →
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- ── STOCK PAR CATÉGORIE + TOP ARTICLES ── --}}
<div class="row g-3 mb-4">

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-tags me-2" style="color:#2563EB"></i>Stock par catégorie
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Catégorie</th>
                            <th class="text-center">Articles</th>
                            <th class="text-end">Valeur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articlesByCategorie as $cat)
                            <tr>
                                <td style="font-size:13px;font-weight:500;color:#1E3A5F">
                                    {{ $cat->nom }}
                                </td>
                                <td class="text-center">
                                    <span class="badge" style="background:#EFF6FF;color:#2563EB">
                                        {{ $cat->articles_count }}
                                    </span>
                                </td>
                                <td class="text-end" style="font-size:12px;color:#64748B">
                                    {{ number_format($cat->valeur_stock, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-trophy me-2" style="color:#D97706"></i>
                Top articles mouvementés (30j)
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Article</th>
                            <th class="text-center">Mouvements</th>
                            <th class="text-end">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top_articles as $i => $article)
                            <tr>
                                <td>
                                    <span class="fw-bold" style="color:
                                        {{ $i === 0 ? '#D97706' : ($i === 1 ? '#64748B' : ($i === 2 ? '#B45309' : '#94A3B8')) }}">
                                        {{ $i + 1 }}
                                    </span>
                                </td>
                                <td style="font-size:13px;font-weight:500;color:#1E3A5F">
                                    {{ Str::limit($article->nom, 28) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge" style="background:#EFF6FF;color:#2563EB">
                                        {{ $article->mouvements_count }}
                                    </span>
                                </td>
                                <td class="text-end" style="font-size:13px">
                                    {{ $article->quantite_stock }} unité(s)
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">
                                    Aucun mouvement ce mois-ci
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ── DERNIERS MOUVEMENTS ── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-history me-2" style="color:#2563EB"></i>Derniers mouvements</span>
        <a href="{{ route('mouvements.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Motif</th>
                        <th>Par</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($derniers_mouvements as $m)
                        <tr>
                            <td>
                                <span style="font-weight:500;color:#1E3A5F">
                                    {{ $m->article->nom ?? '—' }}
                                </span>
                            </td>
                            <td>
                                @if($m->type === 'entree')
                                    <span class="badge" style="background:#ECFDF5;color:#059669">
                                        <i class="fas fa-arrow-down me-1"></i>Entrée
                                    </span>
                                @else
                                    <span class="badge" style="background:#FEF2F2;color:#DC2626">
                                        <i class="fas fa-arrow-up me-1"></i>Sortie
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong style="color:{{ $m->type === 'entree' ? '#059669' : '#DC2626' }}">
                                    {{ $m->type === 'entree' ? '+' : '-' }}{{ $m->quantite }}
                                </strong>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size:13px">
                                    {{ $m->motif ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size:13px">
                                    {{ $m->user->prenom ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size:12px">
                                    {{ $m->created_at->format('d/m/Y H:i') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Aucun mouvement enregistré
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels  = @json($mouvements_semaine->pluck('date'));
    const entrees = @json($mouvements_semaine->pluck('entrees'));
    const sorties = @json($mouvements_semaine->pluck('sorties'));

    new Chart(document.getElementById('mouvementsChart'), {
        type: 'bar',
        data: {
            labels: labels.length ? labels : ['Aucune donnée'],
            datasets: [
                {
                    label: 'Entrées',
                    data: entrees.length ? entrees : [0],
                    backgroundColor: '#BFDBFE',
                    borderColor: '#2563EB',
                    borderWidth: 2,
                    borderRadius: 6,
                },
                {
                    label: 'Sorties',
                    data: sorties.length ? sorties : [0],
                    backgroundColor: '#FECACA',
                    borderColor: '#DC2626',
                    borderWidth: 2,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F1F5F9' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush