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

    /*
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
*/

    public function enregistrerVote(Request $request)
    {
        try{ 
            $user = Auth::user(); 
            $candidat_id = $request->input('candidat_id'); 

            // 🔍 Récupère le candidat avec sa liste
            $candidat = Candidat::with('list')->where('id', $candidat_id)->first();
            if (!$candidat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Candidat introuvable.'
                ], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            }

            // 🔍 Récupère le candidat depuis la table users pour connaître son niveau et département
            $candidat_user = User::where('code_etudiant', $candidat->code_etudiant)->first();
            if (!$candidat_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le candidat n\'est pas valide.'
                ], 404, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
            }

            // Poste basé sur le niveau et le département du candidat
            $poste = "{$candidat_user->niveau}-{$candidat_user->departement}";
            

            // Filtrer selon le type de candidat
            if ($candidat->type_candidat === 'departement') {
                // L'utilisateur ne peut voter qu'une seule fois pour un candidat de type niveau-département
                $existingVote = Vote::where('user_id', $user->id)
                    ->where('poste', $poste)
                    ->exists();

                if ($existingVote) {
                    return response()->json([
                        'success' => false,
                        'message' => "Vous avez déjà voté pour un candidat dans $poste."
                    ], 409, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);

                }
            }

            if ($candidat->type_candidat === 'conseil') {
                // L'utilisateur peut voter une fois pour Licence et une fois pour Master
                $niveau = $candidat_user->niveau; // "Licence" ou "Master"
                $poste = $candidat_user->niveau;

                $existingVote = Vote::where('user_id', $user->id)
                    ->where('poste', $poste)
                    ->exists();

                if ($existingVote) {
                    return response()->json([
                        'success' => false,
                        'message' => "Vous avez déjà voté pour un délégué de $niveau dans le Conseil."
                    ], 409, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
                }
            }

            // ✅ Enregistre le vote dans la table `votes`
            Vote::create([
                'user_id' => $user->id,
                'candidat_id' => $candidat_id,
                'poste' => $poste
            ]);

            // Met à jour le nombre de votes du candidat
            $candidat->votes_count = $candidat->votes_count + 1;
            $candidat->save();

            // Marque l'utilisateur comme ayant voté
            $user->is_voted = true;
            $user->save();


            return response()->json([
                'success' => true,
                'message' => "Votre vote pour $candidat_user->prenom $candidat_user->nom au poste de $poste a été enregistré avec succès !",
                'votes_count' => $candidat->votes_count,
                'poste' => $poste,
                'candidat' => [
                    'nom' => $candidat->nom,
                    'prenom' => $candidat->prenom,
                    'niveau' => $candidat->niveau,
                    'departement' => $candidat->departement,
                    'poste' => $poste,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_UNESCAPED_UNICODE);
        }
    }




    public function afficherPageAcceuilSimpleResultats(){
        return view('acceuilsimpleutilisateurresultatvotes');
    }
    
    public function chargerResultats(Request $request)
    {

        $category = $request->get('category');
    

        $candidats = Candidat::where('type_candidat', $category)
            ->with('user', 'list') // Charger les relations User et List
            ->get();

        $departementOrder = ['Informatique', 'Mathématiques', 'Physique'];
    

        $candidats = $candidats->sortBy(function ($candidat) use ($departementOrder) {
            return array_search($candidat->user->departement, $departementOrder); // Classement par département
        })->sortBy(function ($candidat) {
            return $candidat->user->niveau === 'Master' ? 1 : 0; 
        })->sortByDesc('votes_count'); // Trier par votes décroissants

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
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
        } catch (ValidationException $e) {
            Log::error('Erreurs de validation', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    
        Log::info('Validation réussie');
        
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');  // Stocke la photo dans le dossier 'photos' du disque 'public'
        }
        
        $user = User::create([
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'code_etudiant' => $request->codeEtudiant,
            'departement' => $request->departement,
            'niveau' => $request->niveau,
            'email' => $request->email,
            'password' => $request->password,
            'photo' => $photoPath,
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
