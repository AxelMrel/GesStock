<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Mouvement;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        // ── Filtres ──
        $dateDebut   = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin     = $request->get('date_fin',   now()->format('Y-m-d'));
        $categorieId = $request->get('categorie_id');

        // ── Statistiques globales ──
        $stats = [
            'total_articles'     => Article::count(),
            'total_categories'   => Categorie::count(),
            'total_fournisseurs' => Fournisseur::count(),
            'valeur_stock'       => Article::selectRaw('SUM(quantite_stock * prix_unitaire) as total')->value('total') ?? 0,
            'articles_alerte'    => Article::whereColumn('quantite_stock', '<=', 'stock_minimum')->count(),
        ];

        // ── Mouvements sur la période ──
        $mouvementsQuery = Mouvement::with(['article', 'user'])
            ->whereBetween('created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59']);

        $stats['total_entrees'] = (clone $mouvementsQuery)->where('type', 'entree')->sum('quantite');
        $stats['total_sorties'] = (clone $mouvementsQuery)->where('type', 'sortie')->sum('quantite');

        // ── Articles par catégorie ──
        $articlesByCategorie = Categorie::withCount('articles')
                                        ->with(['articles' => function ($q) {
                                            $q->select('id', 'categorie_id', 'quantite_stock', 'prix_unitaire');
                                        }])
                                        ->get()
                                        ->map(function ($cat) {
                                            $cat->valeur_stock = $cat->articles->sum(fn($a) => $a->quantite_stock * $a->prix_unitaire);
                                            return $cat;
                                        });

        // ── Articles en alerte ──
        $articlesAlerte = Article::whereColumn('quantite_stock', '<=', 'stock_minimum')
                                 ->with(['categorie', 'fournisseur'])
                                 ->orderBy('quantite_stock')
                                 ->get();

        // ── Top 10 articles les plus mouvementés ──
        $topArticles = Article::withCount(['mouvements' => function ($q) use ($dateDebut, $dateFin) {
                            $q->whereBetween('created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59']);
                        }])
                        ->orderByDesc('mouvements_count')
                        ->take(10)
                        ->get();

        // ── Mouvements par jour (graphique) ──
        $mouvementsParJour = Mouvement::selectRaw("
                TO_CHAR(created_at, 'DD/MM') as date,
                SUM(CASE WHEN type = 'entree' THEN quantite ELSE 0 END) as entrees,
                SUM(CASE WHEN type = 'sortie' THEN quantite ELSE 0 END) as sorties
            ")
            ->whereBetween('created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->groupByRaw("TO_CHAR(created_at, 'DD/MM')")
            ->orderByRaw("MIN(created_at)")
            ->get();

        $categories = Categorie::orderBy('nom')->get();

        return view('rapports.index', compact(
            'stats', 'articlesByCategorie', 'articlesAlerte',
            'topArticles', 'mouvementsParJour', 'categories',
            'dateDebut', 'dateFin', 'categorieId'
        ));
    }

    public function exportPdf(Request $request)
    {
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->get('date_fin',   now()->format('Y-m-d'));

        $articles = Article::with(['categorie', 'fournisseur'])
                           ->orderBy('nom')
                           ->get();

        $stats = [
            'total_articles'  => $articles->count(),
            'valeur_stock'    => $articles->sum(fn($a) => $a->quantite_stock * $a->prix_unitaire),
            'articles_alerte' => $articles->filter(fn($a) => $a->estEnAlerte())->count(),
            'total_entrees'   => Mouvement::where('type', 'entree')
                                          ->whereBetween('created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
                                          ->sum('quantite'),
            'total_sorties'   => Mouvement::where('type', 'sortie')
                                          ->whereBetween('created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
                                          ->sum('quantite'),
        ];

        $mouvements = Mouvement::with(['article', 'user'])
                               ->whereBetween('created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
                               ->latest()
                               ->take(50)
                               ->get();

        $pdf = Pdf::loadView('rapports.pdf', compact('articles', 'stats', 'mouvements', 'dateDebut', 'dateFin'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('rapport_stock_' . date('d-m-Y') . '.pdf');
    }
}