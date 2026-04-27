<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use Illuminate\Http\Request;

class AlerteController extends Controller
{
    // ── Liste des alertes ──────────────────────────────────
    public function index()
    {
        $alertes = Alerte::with('article')
                         ->orderBy('lu')
                         ->orderByDesc('created_at')
                         ->paginate(15);

        $nbNonLues = Alerte::where('lu', false)->count();

        return view('alertes.index', compact('alertes', 'nbNonLues'));
    }

    // ── Marquer une alerte comme lue ───────────────────────
    public function marquerLue(Alerte $alerte)
    {
        $alerte->update(['lu' => true]);

        return back()->with('success', 'Alerte marquée comme lue.');
    }

    // ── Marquer toutes les alertes comme lues ──────────────
    public function marquerToutesLues()
    {
        Alerte::where('lu', false)->update(['lu' => true]);

        return back()->with('success', 'Toutes les alertes ont été marquées comme lues.');
    }

    // ── Supprimer une alerte ───────────────────────────────
    public function destroy(Alerte $alerte)
    {
        $alerte->delete();

        return back()->with('success', 'Alerte supprimée.');
    }
}