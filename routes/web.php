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

Route::get('/authentification', [ElectionController::class, 'login'])->name('authentification.submit');
// deconnexion
Route::get('/deconnexion', [ElectionController::class, 'logout']) -> name('deconnexion.submit');


// page de connexion pour l'admin
Route::get('/pageAuthAdmin', [AdminController::class, 'afficherPageAuthAdmin']) -> name('pageAuthAdmin');

Route::get('/authentificationadmin', [AdminController::class, 'login'])->name('authentificationadmin.submit');


Route::middleware(['admin'::class])->group(function () {
    Route::get('/pageAcceuilAdmin', [AdminController::class, 'afficherPageAcceuilAdmin'])->name('pageAcceuilAdmin');
});


//acceuil des differents utilisateur
Route::middleware([EmpêcheConnexion::class])->group(function () {
    Route::get('/pageAcceuilSimple', [ElectionController::class, 'afficherPageAcceuilSimple'])->name('pageAcceuilSimple');
    Route::get('/pageAcceuilSimpleCandidats', [ElectionController::class, 'afficherPageAcceuilSimpleCandidats'])->name('pageAcceuilSimpleCandidats');
    Route::get('/pageAcceuilSimpleResultats', [ElectionController::class, 'afficherPageAcceuilSimpleResultats'])->name('pageAcceuilSimpleResultats');

    Route::get('/charger-candidats', [ElectionController::class, 'chargerCandidates'])->name('charger.candidats');
    Route::get('/charger-resultats', [ElectionController::class, 'chargerResultats'])->name('charger.resultats');

    Route::post('/vote', [ElectionController::class, 'enregistrerVote'])->name('vote.submit');

});


