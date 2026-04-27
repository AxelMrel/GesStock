<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Models\Article;
use App\Models\Alerte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MouvementController extends Controller
{
    public function index(Request $request)
    {
        $query = Mouvement::with(['article', 'user'])->latest();

        // Filtre type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre article
        if ($request->filled('article_id')) {
            $query->where('article_id', $request->article_id);
        }

        // Filtre date
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $mouvements = $query->paginate(15)->withQueryString();
        $articles   = Article::orderBy('nom')->get();

        return view('mouvements.index', compact('mouvements', 'articles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'type'       => 'required|in:entree,sortie',
            'quantite'   => 'required|integer|min:1',
            'motif'      => 'nullable|string|max:500',
        ], [
            'article_id.required' => 'Veuillez sélectionner un article.',
            'type.required'       => 'Le type de mouvement est obligatoire.',
            'quantite.required'   => 'La quantité est obligatoire.',
            'quantite.min'        => 'La quantité doit être au moins 1.',
        ]);

        $article = Article::findOrFail($request->article_id);

        // Vérifier stock suffisant pour une sortie
        if ($request->type === 'sortie' && $article->quantite_stock < $request->quantite) {
            return back()->withInput()->withErrors([
                'quantite' => "Stock insuffisant. Stock disponible : {$article->quantite_stock} unité(s)."
            ]);
        }

        DB::transaction(function () use ($request, $article) {
            // Créer le mouvement
            Mouvement::create([
                'article_id' => $article->id,
                'type'       => $request->type,
                'quantite'   => $request->quantite,
                'motif'      => $request->motif,
                'user_id'    => Auth::id(),
            ]);

            // Mettre à jour le stock
            if ($request->type === 'entree') {
                $article->increment('quantite_stock', $request->quantite);
            } else {
                $article->decrement('quantite_stock', $request->quantite);
            }

            // Recharger l'article
            $article->refresh();

            // Vérifier alerte stock minimum
            if ($article->quantite_stock <= $article->stock_minimum) {
                Alerte::firstOrCreate(
                    ['article_id' => $article->id, 'lu' => false],
                    ['message' => "Stock faible pour \"{$article->nom}\" : {$article->quantite_stock} unité(s) restante(s). Seuil minimum : {$article->stock_minimum}."]
                );
            }
        });

        $type = $request->type === 'entree' ? 'Entrée' : 'Sortie';

        return redirect()->route('mouvements.index')
                         ->with('success', "{$type} de stock enregistrée avec succès.");
    }

    public function destroy(Mouvement $mouvement)
    {
        // Annuler l'effet du mouvement sur le stock
        $article = $mouvement->article;

        if ($mouvement->type === 'entree') {
            if ($article->quantite_stock < $mouvement->quantite) {
                return back()->with('error', 'Impossible d\'annuler : le stock actuel est insuffisant.');
            }
            $article->decrement('quantite_stock', $mouvement->quantite);
        } else {
            $article->increment('quantite_stock', $mouvement->quantite);
        }

        $mouvement->delete();

        return redirect()->route('mouvements.index')
                         ->with('success', 'Mouvement annulé avec succès.');
    }

    public function create() { return redirect()->route('mouvements.index'); }
    public function edit(Mouvement $mouvement) { return redirect()->route('mouvements.index'); }
    public function show(Mouvement $mouvement) { return redirect()->route('mouvements.index'); }
    public function update(Request $request, Mouvement $mouvement) { return redirect()->route('mouvements.index'); }
}