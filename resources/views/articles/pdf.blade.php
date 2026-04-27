<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1E293B; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #1E3A5F; font-size: 18px; margin: 0; }
        .header p { color: #64748B; font-size: 11px; margin: 4px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th {
            background-color: #2563EB;
            color: #fff;
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
        }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #E2E8F0; font-size: 10px; }
        tbody tr:nth-child(even) { background-color: #EFF6FF; }
        .badge-danger  { color: #DC2626; font-weight: bold; }
        .badge-success { color: #059669; font-weight: bold; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #64748B; }
        .total { margin-top: 10px; text-align: right; font-size: 11px; font-weight: bold; color: #1E3A5F; }
    </style>
</head>
<body>

    <div class="header">
        <h1>GesStock — Rapport des articles</h1>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }} · {{ $articles->count() }} article(s)</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Article</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>Stock</th>
                <th>Stock min.</th>
                <th>Prix unitaire</th>
                <th>Valeur stock</th>
                
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
                    
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Valeur totale du stock :
        {{ number_format($articles->sum(fn($a) => $a->quantite_stock * $a->prix_unitaire), 0, ',', ' ') }} FCFA
    </div>

    <div class="footer">GesStock · MA INFO· {{ now()->format('d/m/Y') }}</div>

</body>
</html>