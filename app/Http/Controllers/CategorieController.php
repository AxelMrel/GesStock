<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::withCount('articles')
                               ->orderBy('nom')
                               ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:100|unique:categories,nom',
            'description' => 'nullable|string|max:500',
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.unique'   => 'Cette catégorie existe déjà.',
        ]);

        Categorie::create($request->only('nom', 'description'));

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'nom' => [
                'required',
                'string',
                'max:100',
                // ✅ Rule::unique()->ignore() est obligatoire avec PostgreSQL
                Rule::unique('categories', 'nom')->ignore($categorie->id),
            ],
            'description' => 'nullable|string|max:500',
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.unique'   => 'Cette catégorie existe déjà.',
        ]);

        $categorie->update($request->only('nom', 'description'));

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy(Categorie $categorie)
    {
        if ($categorie->articles()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une catégorie qui contient des articles.');
        }

        $categorie->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'Catégorie supprimée avec succès.');
    }
}