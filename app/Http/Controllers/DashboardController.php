<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Mouvement;
use App\Models\Fournisseur;
use App\Models\Alerte;
use App\Models\User;

class DashboardController extends Controller
{
     public function index()
    {
        // ── Statistiques principales ──
        $stats = [
            'total_articles'     => Article::count(),
            'total_categories'   => Categorie::count(),
            'total_fournisseurs' => Fournisseur::count(),
            'total_utilisateurs' => User::count(),
            'articles_alerte'    => Article::whereColumn('quantite_stock', '<=', 'stock_minimum')->count(),
            'total_entrees'      => Mouvement::where('type', 'entree')
                                             ->whereDate('created_at', '>=', now()->subDays(30))
                                             ->sum('quantite'),
            'total_sorties'      => Mouvement::where('type', 'sortie')
                                             ->whereDate('created_at', '>=', now()->subDays(30))
                                             ->sum('quantite'),
        ];

        // ── Valeur totale du stock ──
        $valeur_stock = Article::selectRaw('SUM(quantite_stock * prix_unitaire) as total')
                               ->value('total') ?? 0;

        // ── Articles en alerte ──
        $articles_alerte = Article::whereColumn('quantite_stock', '<=', 'stock_minimum')
                                  ->with('categorie')
                                  ->orderBy('quantite_stock')
                                  ->take(5)
                                  ->get();

        // ── Derniers mouvements ──
        $derniers_mouvements = Mouvement::with(['article', 'user'])
                                        ->latest()
                                        ->take(8)
                                        ->get();

        // ── Mouvements 7 derniers jours ──
        $mouvements_semaine = Mouvement::selectRaw("
                TO_CHAR(created_at, 'DD/MM') as date,
                SUM(CASE WHEN type = 'entree' THEN quantite ELSE 0 END) as entrees,
                SUM(CASE WHEN type = 'sortie' THEN quantite ELSE 0 END) as sorties
            ")
            ->where('created_at', '>=', now()->subDays(7))
            ->groupByRaw("TO_CHAR(created_at, 'DD/MM')")
            ->orderByRaw("MIN(created_at)")
            ->get();

        // ── Stock par catégorie ──
        $articlesByCategorie = Categorie::withCount('articles')
                                        ->with(['articles' => function ($q) {
                                            $q->select('id', 'categorie_id', 'quantite_stock', 'prix_unitaire');
                                        }])
                                        ->get()
                                        ->map(function ($cat) {
                                            $cat->valeur_stock = $cat->articles->sum(
                                                fn($a) => $a->quantite_stock * $a->prix_unitaire
                                            );
                                            return $cat;
                                        });

        // ── Top articles mouvementés ──
        $top_articles = Article::withCount(['mouvements' => function ($q) {
                            $q->where('created_at', '>=', now()->subDays(30));
                        }])
                        ->orderByDesc('mouvements_count')
                        ->take(5)
                        ->get();

        // ── Alertes non lues ──
        $alertes_count = Alerte::where('lu', false)->count();

        return view('dashboard', compact(
            'stats', 'valeur_stock', 'articles_alerte',
            'derniers_mouvements', 'mouvements_semaine',
            'articlesByCategorie', 'top_articles', 'alertes_count'
        ));
    }
}