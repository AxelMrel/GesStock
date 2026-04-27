<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #1E293B; margin: 0; padding: 20px; }

        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 3px solid #2563EB; padding-bottom: 12px; }
        .header h1 { color: #1E3A5F; font-size: 18px; margin: 0 0 4px; }
        .header p  { color: #64748B; font-size: 10px; margin: 0; }

        .stats { display: flex; gap: 10px; margin-bottom: 18px; }
        .stat-card { flex: 1; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px; text-align: center; }
        .stat-card .val { font-size: 18px; font-weight: bold; color: #1E3A5F; }
        .stat-card .lbl { font-size: 9px; color: #64748B; }

        h2 { font-size: 12px; color: #1E3A5F; border-left: 4px solid #2563EB; padding-left: 8px; margin: 16px 0 8px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead th { background: #2563EB; color: #fff; padding: 7px 8px; text-align: left; font-size: 9px; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #E2E8F0; font-size: 9px; }
        tbody tr:nth-child(even) { background: #F8FAFF; }

        .badge-danger  { color: #DC2626; font-weight: bold; }
        .badge-success { color: #059669; font-weight: bold; }
        .badge-entree  { color: #059669; }
        .badge-sortie  { color: #DC2626; }

        .footer { margin-top: 20px; border-top: 1px solid #E2E8F0; padding-top: 8px; display: flex; justify-content: space-between; font-size: 9px; color: #94A3B8; }
    </style>
</head>
<body>

    {{-- EN-TÊTE --}}
    <div class="header">
        <div>
            <h1>StockCentre — Rapport de stock</h1>
            <p>Période : {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</p>
            <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
        </div>
        <div style="text-align:right">
            <div style="font-size:22px;font-weight:bold;color:#2563EB">SC</div>
            <div style="font-size:9px;color:#64748B">Centre informatique du Bénin</div>
        </div>
    </div>

    {{-- STATISTIQUES --}}
    <div class="stats">
        <div class="stat-card">
            <div class="val">{{ $stats['total_articles'] }}</div>
            <div class="lbl">Articles</div>
        </div>
        <div class="stat-card">
            <div class="val" style="font-size:13px">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} F</div>
            <div class="lbl">Valeur stock</div>
        </div>
        <div class="stat-card">
            <div class="val" style="color:#059669">+{{ $stats['total_entrees'] }}</div>
            <div class="lbl">Entrées</div>
        </div>
        <div class="stat-card">
            <div class="val" style="color:#DC2626">-{{ $stats['total_sorties'] }}</div>
            <div class="lbl">Sorties</div>
        </div>
        <div class="stat-card">
            <div class="val" style="color:#DC2626">{{ $stats['articles_alerte'] }}</div>
            <div class="lbl">En alerte</div>
        </div>
    </div>

    {{-- INVENTAIRE --}}
    <h2>Inventaire complet</h2>
    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>Stock</th>
                <th>Seuil min.</th>
                <th>Prix unitaire</th>
                <th>Valeur stock</th>
                <!-- <th>Statut</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
                <tr>
                    <td>{{ $article->nom }}</td>
                    <td>{{ $article->categorie->nom ?? '—' }}</td>
                    <td>{{ $article->fournisseur->nom ?? '—' }}</td>
                    <td>{{ $article->quantite_stock }}</td>
                    <td>{{ $article->stock_minimum }}</td>
                    <td>{{ number_format($article->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($article->quantite_stock * $article->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <!-- <td>
                        @if($article->estEnAlerte())
                            <span class="badge-danger">⚠ Faible</span>
                        @else
                            <span class="badge-success">✓ Normal</span>
                        @endif
                    </td> -->
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- MOUVEMENTS --}}
    <h2>Derniers mouvements (50 max)</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Article</th>
                <th>Quantité</th>
                <th>Motif</th>
                <th>Par</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mouvements as $m)
                <tr>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($m->type === 'entree')
                            <span class="badge-entree">↓ Entrée</span>
                        @else
                            <span class="badge-sortie">↑ Sortie</span>
                        @endif
                    </td>
                    <td>{{ $m->article->nom ?? '—' }}</td>
                    <td>{{ $m->type === 'entree' ? '+' : '-' }}{{ $m->quantite }}</td>
                    <td>{{ $m->motif ?? '—' }}</td>
                    <td>{{ $m->user->prenom ?? '—' }} {{ $m->user->nom ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#94A3B8">
                        Aucun mouvement sur cette période
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <span>StockCentre · MA Info</span>
        <span>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</span>
    </div>

</body>
</html>