<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Alerte;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArticlesExport;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['categorie', 'fournisseur'])
                        ->withCount('mouvements');

        // Recherche
        if ($request->filled('search')) {
            $query->where('nom', 'ilike', '%' . $request->search . '%')
                  ->orWhere('description', 'ilike', '%' . $request->search . '%');
        }

        // Filtre catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre stock faible
        if ($request->filled('stock_faible')) {
            $query->whereColumn('quantite_stock', '<=', 'stock_minimum');
        }

        $articles    = $query->orderBy('nom')->paginate(10)->withQueryString();
        $categories  = Categorie::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('articles.index', compact('articles', 'categories', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'            => 'required|string|max:255|unique:articles,nom',
            'description'    => 'nullable|string|max:500',
            'categorie_id'   => 'nullable|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'quantite_stock' => 'required|integer|min:0',
            'stock_minimum'  => 'required|integer|min:0',
            'prix_unitaire'  => 'required|numeric|min:0',
        ], [
            'nom.required'          => 'Le nom de l\'article est obligatoire.',
            'nom.unique'            => 'Cet article existe déjà.',
            'quantite_stock.required' => 'La quantité est obligatoire.',
            'stock_minimum.required'  => 'Le stock minimum est obligatoire.',
            'prix_unitaire.required'  => 'Le prix unitaire est obligatoire.',
        ]);

        $article = Article::create($request->only(
            'nom', 'description', 'categorie_id',
            'fournisseur_id', 'quantite_stock',
            'stock_minimum', 'prix_unitaire'
        ));

        // Vérifier alerte stock
        if ($article->quantite_stock <= $article->stock_minimum) {
            Alerte::create([
                'article_id' => $article->id,
                'message'    => "Stock faible pour l'article \"{$article->nom}\" : {$article->quantite_stock} unité(s) restante(s).",
            ]);
        }

        return redirect()->route('articles.index')
                         ->with('success', 'Article créé avec succès.');
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'nom'            => 'required|string|max:255|unique:articles,nom,' . $article->id,
            'description'    => 'nullable|string|max:500',
            'categorie_id'   => 'nullable|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'quantite_stock' => 'required|integer|min:0',
            'stock_minimum'  => 'required|integer|min:0',
            'prix_unitaire'  => 'required|numeric|min:0',
        ], [
            'nom.required' => 'Le nom de l\'article est obligatoire.',
            'nom.unique'   => 'Cet article existe déjà.',
        ]);

        $article->update($request->only(
            'nom', 'description', 'categorie_id',
            'fournisseur_id', 'quantite_stock',
            'stock_minimum', 'prix_unitaire'
        ));

        // Vérifier alerte stock
        if ($article->quantite_stock <= $article->stock_minimum) {
            Alerte::firstOrCreate(
                ['article_id' => $article->id, 'lu' => false],
                ['message' => "Stock faible pour l'article \"{$article->nom}\" : {$article->quantite_stock} unité(s) restante(s)."]
            );
        }

        return redirect()->route('articles.index')
                         ->with('success', 'Article modifié avec succès.');
    }

    public function destroy(Article $article)
    {
        if ($article->mouvements()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un article qui a des mouvements enregistrés.');
        }

        $article->delete();

        return redirect()->route('articles.index')
                         ->with('success', 'Article supprimé avec succès.');
    }

    public function create()   { return redirect()->route('articles.index'); }
    public function edit(Article $article) { return redirect()->route('articles.index'); }
    public function show(Article $article) { return redirect()->route('articles.index'); }

    public function exportPdf()
{
    $articles = Article::with(['categorie', 'fournisseur'])
                       ->orderBy('nom')
                       ->get();

    $pdf = Pdf::loadView('articles.pdf', compact('articles'))
              ->setPaper('a4', 'landscape');

    return $pdf->download('articles_' . date('d-m-Y') . '.pdf');
}

public function exportExcel()
{
    return Excel::download(
        new ArticlesExport,
        'articles_' . date('d-m-Y') . '.xlsx'
    );
}
}