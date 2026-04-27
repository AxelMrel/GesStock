<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    // ── Liste des codes ────────────────────────────────────
    public function index()
    {
        $invitations = InvitationCode::with(['createur', 'utilisateur'])
                                     ->orderByDesc('created_at')
                                     ->paginate(15);

        return view('invitations.index', compact('invitations'));
    }

    // ── Générer des codes ──────────────────────────────────
    public function generer(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|integer|min:1|max:20',
            'expires_in' => 'required|integer|in:7,15,30,60,90',
        ], [
            'nombre.required'     => 'Le nombre de codes est obligatoire.',
            'nombre.min'          => 'Minimum 1 code.',
            'nombre.max'          => 'Maximum 20 codes à la fois.',
            'expires_in.required' => 'La durée de validité est obligatoire.',
            'expires_in.in'       => 'Durée invalide.',
        ]);

        for ($i = 0; $i < $request->nombre; $i++) {
            InvitationCode::create([
                'code'       => InvitationCode::generer(),
                'created_by' => auth()->id(),
                'expires_at' => now()->addDays($request->expires_in),
            ]);
        }

        $msg = $request->nombre == 1
            ? '1 code d\'invitation généré avec succès.'
            : "{$request->nombre} codes d'invitation générés avec succès.";

        return back()->with('success', $msg);
    }

    // ── Supprimer un code ──────────────────────────────────
    public function destroy(InvitationCode $invitation)
    {
        if ($invitation->is_used) {
            return back()->with('error', 'Impossible de supprimer un code déjà utilisé.');
        }

        $invitation->delete();

        return back()->with('success', 'Code d\'invitation supprimé.');
    }
}