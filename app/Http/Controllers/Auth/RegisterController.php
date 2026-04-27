<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // ── Affiche le formulaire d'inscription ────────────────

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // ── Traite l'inscription ───────────────────────────────

    public function register(Request $request)
    {
        $request->validate([
            'nom'             => ['required', 'string', 'max:100'],
            'prenom'          => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email'],
            'role'            => ['required', 'in:gestionnaire,consultant'],
            'password'        => ['required', 'confirmed', Password::min(8)],
            'invitation_code' => ['required', 'string'],
        ], [
            'nom.required'             => 'Le nom est obligatoire.',
            'prenom.required'          => 'Le prénom est obligatoire.',
            'email.required'           => 'L\'adresse email est obligatoire.',
            'email.email'              => 'L\'adresse email n\'est pas valide.',
            'email.unique'             => 'Cette adresse email est déjà utilisée.',
            'role.required'            => 'Veuillez choisir un rôle.',
            'role.in'                  => 'Le rôle sélectionné est invalide.',
            'password.required'        => 'Le mot de passe est obligatoire.',
            'password.confirmed'       => 'Les mots de passe ne correspondent pas.',
            'password.min'             => 'Le mot de passe doit contenir au moins 8 caractères.',
            'invitation_code.required' => 'Le code d\'invitation est obligatoire.',
        ]);

        // Vérification du code d'invitation
        $invitation = InvitationCode::where('code', $request->invitation_code)->first();

        if (!$invitation || !$invitation->estValide()) {
            return back()
                ->withInput($request->except('password', 'password_confirmation', 'invitation_code'))
                ->withErrors(['invitation_code' => 'Ce code d\'invitation est invalide ou déjà utilisé.']);
        }

        // Création de l'utilisateur
        $user = User::create([
            'nom'      => $request->nom,
            'prenom'   => $request->prenom,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Marquer le code comme utilisé
        $invitation->marquerUtilise($user->id);

        // Connexion automatique
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', "Bienvenue, {$user->prenom} ! Votre compte a été créé avec succès.");
    }
}