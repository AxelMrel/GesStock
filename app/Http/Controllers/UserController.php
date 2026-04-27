<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ── Liste des utilisateurs ─────────────────────────────
    public function index()
    {
        $users = User::orderBy('nom')->paginate(10);
        return view('users.index', compact('users'));
    }

    // ── Modifier le rôle ───────────────────────────────────
    public function update(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $request->validate([
            'role' => ['required', Rule::in(['admin', 'gestionnaire', 'consultant'])],
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', "Rôle de {$user->prenom} modifié avec succès.");
    }

    // ── Activer / Désactiver ───────────────────────────────
    public function toggleActif(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $statut = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Compte de {$user->prenom} {$statut} avec succès.");
    }

    // ── Supprimer ──────────────────────────────────────────
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return back()->with('success', "Utilisateur {$user->prenom} {$user->nom} supprimé avec succès.");
    }
}