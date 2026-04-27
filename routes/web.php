<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlerteController;

/*
|--------------------------------------------------------------------------
| Routes publiques — Auth
|--------------------------------------------------------------------------
*/

// Redirige / vers login ou dashboard selon la session
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Connexion
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Inscription
Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Routes protégées — authentification requise
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard (tous les rôles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil/modifier', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil',          [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    /*
    |----------------------------------------------------------------------
    | Routes admin & gestionnaire uniquement
    |----------------------------------------------------------------------
    */
    Route::middleware(['role:admin,gestionnaire'])->group(function () {

        // Articles
        Route::resource('articles', \App\Http\Controllers\ArticleController::class)
            ->parameters(['articles' => 'article']);

        // Catégories
        Route::resource('categories', \App\Http\Controllers\CategorieController::class)
            ->parameters(['categories' => 'categorie']);

        // Mouvements (entrées / sorties)
        Route::resource('mouvements', \App\Http\Controllers\MouvementController::class)
            ->parameters(['mouvements' => 'mouvement']);

        // Exportations
        Route::get('/articles/export/pdf',   [\App\Http\Controllers\ArticleController::class, 'exportPdf'])->name('articles.export.pdf');
        Route::get('/articles/export/excel', [\App\Http\Controllers\ArticleController::class, 'exportExcel'])->name('articles.export.excel');

        // Fournisseurs
        Route::resource('fournisseurs', \App\Http\Controllers\FournisseurController::class)
            ->parameters(['fournisseurs' => 'fournisseur']);

        // Rapports
        Route::get('/rapports',            [\App\Http\Controllers\RapportController::class, 'index'])->name('rapports.index');
        Route::get('/rapports/export-pdf', [\App\Http\Controllers\RapportController::class, 'exportPdf'])->name('rapports.export-pdf');
    });

    /*
    |----------------------------------------------------------------------
    | Alertes — tous les rôles (consultant en lecture seule)
    |----------------------------------------------------------------------
    */
    Route::get('/alertes', [AlerteController::class, 'index'])->name('alertes.index');
    Route::patch('/alertes/{alerte}/lire', [AlerteController::class, 'marquerLue'])->name('alertes.lire')
        ->middleware('role:admin,gestionnaire');
    Route::post('/alertes/tout-lire', [AlerteController::class, 'marquerToutesLues'])->name('alertes.tout-lire')
        ->middleware('role:admin,gestionnaire');
    Route::delete('/alertes/{alerte}', [AlerteController::class, 'destroy'])->name('alertes.destroy')
        ->middleware('role:admin,gestionnaire');
    // Route::get('/alertes',                   [\App\Http\Controllers\AlerteController::class, 'index'])->name('alertes.index');
    // Route::patch('/alertes/{alerte}/lire',   [\App\Http\Controllers\AlerteController::class, 'marquerLue'])->name('alertes.lire')
    //      ->middleware('role:admin,gestionnaire');

    /*
    |----------------------------------------------------------------------
    | Routes admin uniquement
    |----------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {

        // Gestion des utilisateurs
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::patch('/users/{user}/toggle', [\App\Http\Controllers\UserController::class, 'toggleActif'])->name('users.toggle');

        // Codes d'invitation
        Route::get('/invitations',          [\App\Http\Controllers\InvitationController::class, 'index'])->name('invitations.index');
        Route::post('/invitations/generer', [\App\Http\Controllers\InvitationController::class, 'generer'])->name('invitations.generer');
        Route::delete('/invitations/{invitation}', [\App\Http\Controllers\InvitationController::class, 'destroy'])->name('invitations.destroy');

        // Paramètres
        Route::get('/parametres',  [\App\Http\Controllers\ParametreController::class, 'index'])->name('parametres.index');
        Route::post('/parametres', [\App\Http\Controllers\ParametreController::class, 'update'])->name('parametres.update');
    });
});