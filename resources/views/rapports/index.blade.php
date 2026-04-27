@extends('layouts.app')

@section('title', 'Rapports')
@section('page-title', 'Rapports')
@section('page-breadcrumb', 'Analyse et statistiques du stock')

@section('content')

{{-- FILTRES --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('rapports.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-medium" style="font-size:13px">Date début</label>
                <input type="date" name="date_debut" class="form-control"
                       value="{{ $dateDebut }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium" style="font-size:13px">Date fin</label>
                <input type="date" name="date_fin" class="form-control"
                       value="{{ $dateFin }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-medium" style="font-size:13px">Catégorie</label>
                <select name="categorie_id" class="form-select">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                                {{ $categorieId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>Générer
                </button>
                <a href="{{ route('rapports.export-pdf', request()->query()) }}"
                   class="btn btn-outline-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- CARTES STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:4px solid #2563EB">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:12px">Total articles</span>
                    <div style="width:34px;height:34px;background:#EFF6FF;border-radius:8px;
                                display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-boxes" style="color:#2563EB;font-size:13px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#1E3A5F">{{ $stats['total_articles'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:4px solid #D97706">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:12px">Valeur stock</span>
                    <div style="width:34px;height:34px;background:#FFFBEB;border-radius:8px;
                                display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-coins" style="color:#D97706;font-size:13px"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-0" style="color:#1E3A5F;font-size:1rem">
                    {{ number_format($stats['valeur_stock'], 0, ',', ' ') }} FCFA
                </h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:4px solid #059669">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:12px">Entrées (période)</span>
                    <div style="width:34px;height:34px;background:#ECFDF5;border-radius:8px;
                                display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-arrow-down" style="color:#059669;font-size:13px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#059669">+{{ $stats['total_entrees'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card h-100" style="border-left:4px solid #DC2626">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted" style="font-size:12px">Sorties (période)</span>
                    <div style="width:34px;height:34px;background:#FEF2F2;border-radius:8px;
                                display:flex;align-items:center;justify-content:center">
                        <i class="fas fa-arrow-up" style="color:#DC2626;font-size:13px"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0" style="color:#DC2626">-{{ $stats['total_sorties'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">

    {{-- Graphique mouvements --}}
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-chart-bar me-2" style="color:#2563EB"></i>
                Mouvements sur la période
            </div>
            <div class="card-body">
                <canvas id="chartMouvements" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Articles en alerte --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-bell me-2 text-danger"></i>Stock faible</span>
                <span class="badge bg-danger">{{ $articlesAlerte->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($articlesAlerte->take(6) as $article)
                    <div class="d-flex align-items-center justify-content-between px-3 py-2"
                         style="border-bottom:1px solid #F1F5F9">
                        <div>
                            <div style="font-size:12px;font-weight:500;color:#1E3A5F">
                                {{ Str::limit($article->nom, 25) }}
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
                        <p class="text-muted small mb-0">Aucune alerte</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

<div class="row g-3">

    {{-- Stock par catégorie --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-tags me-2" style="color:#2563EB"></i>
                Stock par catégorie
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

    {{-- Top articles --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-trophy me-2" style="color:#D97706"></i>
                Top articles mouvementés
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
                        @forelse($topArticles as $i => $article)
                            <tr>
                                <td>
                                    <span class="fw-bold" style="color:
                                        {{ $i === 0 ? '#D97706' : ($i === 1 ? '#64748B' : ($i === 2 ? '#B45309' : '#94A3B8')) }}">
                                        {{ $i + 1 }}
                                    </span>
                                </td>
                                <td style="font-size:13px;font-weight:500;color:#1E3A5F">
                                    {{ Str::limit($article->nom, 30) }}
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
                                    Aucun mouvement sur cette période
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels  = @json($mouvementsParJour->pluck('date'));
    const entrees = @json($mouvementsParJour->pluck('entrees'));
    const sorties = @json($mouvementsParJour->pluck('sorties'));

    new Chart(document.getElementById('chartMouvements'), {
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