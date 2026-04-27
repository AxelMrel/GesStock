<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::withCount('articles')
                                   ->orderBy('nom')
                                   ->paginate(10);

        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string|max:100|unique:fournisseurs,nom',
            'telephone' => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255|unique:fournisseurs,email',
            'adresse'   => 'nullable|string|max:500',
        ], [
            'nom.required' => 'Le nom du fournisseur est obligatoire.',
            'nom.unique'   => 'Ce fournisseur existe déjà.',
            'email.email'  => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
        ]);

        Fournisseur::create($request->only('nom', 'telephone', 'email', 'adresse'));

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur ajouté avec succès.');
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom'       => 'required|string|max:100|unique:fournisseurs,nom,' . $fournisseur->id,
            'telephone' => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255|unique:fournisseurs,email,' . $fournisseur->id,
            'adresse'   => 'nullable|string|max:500',
        ], [
            'nom.required' => 'Le nom du fournisseur est obligatoire.',
            'nom.unique'   => 'Ce fournisseur existe déjà.',
            'email.email'  => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
        ]);

        $fournisseur->update($request->only('nom', 'telephone', 'email', 'adresse'));

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur modifié avec succès.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        if ($fournisseur->articles()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un fournisseur qui a des articles associés.');
        }

        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')
                         ->with('success', 'Fournisseur supprimé avec succès.');
    }

    public function create() { return redirect()->route('fournisseurs.index'); }
    public function edit(Fournisseur $fournisseur) { return redirect()->route('fournisseurs.index'); }
    public function show(Fournisseur $fournisseur) { return redirect()->route('fournisseurs.index'); }
}