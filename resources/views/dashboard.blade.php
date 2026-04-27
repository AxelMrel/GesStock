@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-breadcrumb', 'Vue générale du stock')

@section('content')

{{-- ── CARTES STATISTIQUES ── --}}
<div class="row g-3 mb-4">

    {{-- Total articles --}}
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left: 4px solid #2563EB">
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

    {{-- Catégories --}}
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left: 4px solid #7C3AED">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Catégories</span>
                    <div style="width:38px;height:38px;background:#F5F3FF;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-tags" style="color:#7C3AED;font-size:15px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#1E3A5F">{{ $stats['total_categories'] }}</h3>
                <small class="text-muted">catégories</small>
            </div>
        </div>
    </div>

    {{-- Fournisseurs --}}
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left: 4px solid #059669">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Fournisseurs</span>
                    <div style="width:38px;height:38px;background:#ECFDF5;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-truck" style="color:#059669;font-size:15px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#1E3A5F">{{ $stats['total_fournisseurs'] }}</h3>
                <small class="text-muted">fournisseurs</small>
            </div>
        </div>
    </div>

    {{-- Valeur stock --}}
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left: 4px solid #D97706">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:13px">Valeur stock</span>
                    <div style="width:38px;height:38px;background:#FFFBEB;border-radius:10px;display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-coins" style="color:#D97706;font-size:15px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#1E3A5F;font-size:1.2rem">
                    {{ number_format($valeur_stock, 0, ',', ' ') }} FCFA
                </h3>
                <small class="text-muted">valeur totale</small>
            </div>
        </div>
    </div>

</div>

{{-- ── GRAPHIQUE + ALERTES ── --}}
<div class="row g-3 mb-4">

    {{-- Graphique mouvements --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-chart-bar me-2" style="color:#2563EB"></i>Mouvements des 7 derniers jours</span>
            </div>
            <div class="card-body">
                <canvas id="mouvementsChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Alertes stock faible --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="fas fa-bell me-2 text-danger"></i>Stock faible</span>
                <span class="badge bg-danger">{{ $articles_alerte->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($articles_alerte as $article)
                    <div class="d-flex align-items-center justify-content-between px-3 py-2"
                         style="border-bottom: 1px solid #F1F5F9">
                        <div>
                            <div style="font-size:13px;font-weight:500;color:#1E3A5F">
                                {{ $article->nom }}
                            </div>
                            <div style="font-size:11px;color:#64748B">
                                {{ $article->categorie->nom ?? '—' }}
                            </div>
                        </div>
                        <span class="badge"
                              style="background:#FEF2F2;color:#DC2626;font-size:11px">
                            {{ $article->quantite_stock }} / {{ $article->stock_minimum }}
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

{{-- ── DERNIERS MOUVEMENTS ── --}}
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="fas fa-history me-2" style="color:#2563EB"></i>Derniers mouvements</span>
        <a href="{{ route('mouvements.index') }}"
           class="btn btn-sm btn-outline-primary">Voir tout</a>
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
                                    <span class="badge"
                                          style="background:#ECFDF5;color:#059669">
                                        <i class="fas fa-arrow-down me-1"></i>Entrée
                                    </span>
                                @else
                                    <span class="badge"
                                          style="background:#FEF2F2;color:#DC2626">
                                        <i class="fas fa-arrow-up me-1"></i>Sortie
                                    </span>
                                @endif
                            </td>
                            <td><strong>{{ $m->quantite }}</strong></td>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($mouvements_semaine->pluck('date'));
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
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#F1F5F9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endsection