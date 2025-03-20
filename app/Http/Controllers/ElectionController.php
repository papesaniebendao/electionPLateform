<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Candidat;
use App\Models\Vote;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

//middleware auth
use Illuminate\Support\Facades\Auth;

//debogeur
use Illuminate\Support\Facades\Log;



class electionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    //afficher ma page d'acceuil central
    public function afficherPageAcceuil(){
        return view('acceuil');
    }
    // afficher le formulaire d'inscription
    public function afficherPageIns(){
       
        return view('inscription');
    }

    // afficher le formulaire d'authentification
    public function afficherPageAuth(){

        return view('authentification');
    }

    // afficher la page d'acceuilsimpleutilisateur
    public function afficherPageAcceuilSimple(){
        return view('acceuilsimpleutilisateur');
    }
    
    public function afficherPageAcceuilSimpleCandidats(){
        return view('acceuilsimpleutilisateurcandidats');
    }

    public function chargerCandidates(Request $request)
    {
        $category = $request->get('category');
        
        // Logic pour récupérer les candidats selon la catégorie
        $candidats = Candidat::where('type_candidat', $category)
            ->with('user', 'list') // On récupère les relations avec user et liste
            ->get();
    
        // Retourner une vue avec les candidats
        return response()->view('partials.candidates', compact('candidats'));
    }

    public function enregistrerVote(Request $request)
    {
        $user = Auth::user(); // Récupère l'utilisateur connecté
        $candidat_id = $request->input('candidat_id'); // Récupère l'ID du candidat

        // Vérifie si l'utilisateur a déjà voté
        $existingVote = Vote::where('user_id', $user->id)->first();

        if ($existingVote) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà voté.'
            ]);
        }

        // Enregistre le vote dans la table `votes`
        Vote::create([
            'user_id' => $user->id,
            'candidat_id' => $candidat_id,
        ]);

        // Met à jour le nombre de votes du candidat
        $candidat = Candidat::find($candidat_id);
        $candidat->votes_count = $candidat->votes_count + 1;
        $candidat->save();

        $user->is_voted = true;
        $user->save();

        // Redirige vers la page avec un message de succès
        return response()->json([
            'success' => true,
            'message' => 'Votre vote a été enregistré avec succès !',
            'votes_count' => $candidat->votes_count // Retourne le nombre de votes mis à jour
        ]);
    }

    public function afficherPageAcceuilSimpleResultats(){
        return view('acceuilsimpleutilisateurresultatvotes');
    }
    
    public function chargerResultats(Request $request)
    {
        $category = $request->get('category');
        
        // Logic pour récupérer les candidats selon la catégorie
        $candidats = Candidat::where('type_candidat', $category)
            ->with('user', 'list') // On récupère les relations avec user et liste
            ->get();
    
        // Retourner une vue avec les candidats
        return response()->view('partials.resultats', compact('candidats'));
    }


    // Gère l'inscription   
    public function register(Request $request)
    {
        Log::info('Début de l\'inscription', ['data' => $request->all()]);
    
        try {
            $request->validate([
                'prenom' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
                'codeEtudiant' => 'required|string|unique:users,code_etudiant',
                'departement' => 'required|in:Mathematique,Informatique,Physique',
                'niveau' => 'required|in:Licence,Master',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);
        } catch (ValidationException $e) {
            Log::error('Erreurs de validation', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    
        Log::info('Validation réussie');
        
        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'code_etudiant' => $request->codeEtudiant,
            'departement' => $request->departement,
            'niveau' => $request->niveau,
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => true,
        ]);
    
        return redirect()->route('pageAuth')->with('success', 'Inscription réussie, vous pouvez vous connecter.');
    }
    
    

    // Gère l'authentification
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        // Vérifie les informations de connexion et que l'utilisateur est actif
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            if (!$user->is_active) {
                Auth::logout(); // Déconnexion immédiate
                return redirect()->route('pageAuth')->with('error', 'Votre compte est désactivé.');
            }
    
            return redirect()->route('pageAcceuilSimple');
        }
    
        return redirect()->route('pageAuth')->with('error', 'Échec de l\'authentification!');
    }
    
    
    
    
    
    public function logout(request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/pageAuth');
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
