<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArticlesExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Article::with(['categorie', 'fournisseur'])
                      ->orderBy('nom')
                      ->get()
                      ->map(function ($article) {
                          return [
                              'Nom'            => $article->nom,
                              'Description'    => $article->description ?? '—',
                              'Catégorie'      => $article->categorie->nom ?? '—',
                              'Fournisseur'    => $article->fournisseur->nom ?? '—',
                              'Stock'          => $article->quantite_stock,
                              'Stock minimum'  => $article->stock_minimum,
                              'Prix unitaire'  => $article->prix_unitaire . ' FCFA',
                              'Valeur stock'   => ($article->quantite_stock * $article->prix_unitaire) . ' FCFA',
                              'Statut'         => $article->estEnAlerte() ? 'Stock faible' : 'Normal',
                          ];
                      });
    }

    public function headings(): array
    {
        return [
            'Nom', 'Description', 'Catégorie', 'Fournisseur',
            'Stock', 'Stock minimum', 'Prix unitaire', 'Valeur stock', 'Statut'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => '2563EB']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}