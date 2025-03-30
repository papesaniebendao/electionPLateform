<?php

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EmpêcheConnexion;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('welcome');
});

//page Acceuil central
Route::get('/pageAcceuil', [ElectionController::class, 'afficherPageAcceuil'])->name('pageAcceuil');



// page et formulaire  d'inscription  et de connexion

Route::get('/pageIns', [ElectionController::class, 'afficherPageIns']) -> name('pageIns');

Route::post('/inscription', [ElectionController::class, 'register'])->name('inscription.submit');

Route::get('/pageAuth', [ElectionController::class, 'afficherPageAuth']) -> name('pageAuth');

Route::post('/authentification', [ElectionController::class, 'login'])->name('authentification.submit');
// deconnexion
Route::get('/deconnexion', [ElectionController::class, 'logout']) -> name('deconnexion.submit');


// page de connexion pour l'admin
Route::get('/pageAuthAdmin', [AdminController::class, 'afficherPageAuthAdmin']) -> name('pageAuthAdmin');

Route::post('/authentificationadmin', [AdminController::class, 'login'])->name('authentificationadmin.submit');


// Routes admin
Route::middleware(['admin'])->group(function () {
    Route::get('/pageAcceuilAdmin', [AdminController::class, 'afficherPageAcceuilAdmin'])->name('pageAcceuilAdmin');
    Route::get('/gestion-utilisateurs', [AdminController::class, 'gestionUtilisateurs'])->name('gestion.utilisateurs');
    Route::post('/filtrer-utilisateurs', [AdminController::class, 'filtrerUtilisateurs'])->name('filtrer.utilisateurs');
    Route::post('/valider-modifications', [AdminController::class, 'validerModifications'])->name('valider.modifications');
    
    // Gestion des listes
    Route::get('/gestion-listes', [AdminController::class, 'gestionListes'])->name('gestion.listes');
    Route::get('/liste/create', [AdminController::class, 'createListe'])->name('liste.create');
    Route::post('/liste/store', [AdminController::class, 'storeListe'])->name('liste.store');
    Route::get('/liste/edit/{id}', [AdminController::class, 'editListe'])->name('liste.edit');
    Route::put('/liste/update/{id}', [AdminController::class, 'updateListe'])->name('liste.update');
    Route::delete('/liste/destroy/{id}', [AdminController::class, 'destroyListe'])->name('liste.destroy');
    
    // Consultation des votes
    Route::get('/consulter-votes', [AdminController::class, 'consulterVotes'])->name('consulter.votes');
    Route::get('/votes/departement', [AdminController::class, 'getVotesByDepartement'])->name('votes.departement');
    Route::get('/votes/conseil', [AdminController::class, 'getVotesForConseil'])->name('votes.conseil');
    Route::get('/votes/statistiques', [AdminController::class, 'getVotingStatistics'])->name('votes.statistiques');
});

Route::get('/deconnexion1', [AdminController::class, 'logout']) -> name('deconnexionadmin.submit');


//acceuil des differents utilisateur
Route::middleware([EmpêcheConnexion::class])->group(function () {
    Route::get('/pageAcceuilSimple', [ElectionController::class, 'afficherPageAcceuilSimple'])->name('pageAcceuilSimple');
    Route::get('/pageAcceuilSimpleCandidats', [ElectionController::class, 'afficherPageAcceuilSimpleCandidats'])->name('pageAcceuilSimpleCandidats');
    Route::get('/pageAcceuilSimpleResultats', [ElectionController::class, 'afficherPageAcceuilSimpleResultats'])->name('pageAcceuilSimpleResultats');

    Route::get('/charger-candidats', [ElectionController::class, 'chargerCandidates'])->name('charger.candidats');
    Route::get('/charger-resultats', [ElectionController::class, 'chargerResultats'])->name('charger.resultats');

    Route::post('/vote', [ElectionController::class, 'enregistrerVote'])->name('vote.submit');

});
