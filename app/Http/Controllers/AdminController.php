<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\User;
use App\Models\Candidat;
use App\Models\Vote;
use App\Models\Lists;

class AdminController extends Controller
{
    //=======================================================
    // MODULE 1: AUTHENTIFICATION ADMIN
    //=======================================================
    
    /**
     * Afficher la page d'authentification admin.
     */
    public function afficherPageAuthAdmin()
    {
        return view('authentificationadmin');
    }

    /**
     * Gérer la connexion de l'admin.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('pageAcceuilAdmin'));
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }

    /**
     * Déconnecter l'admin.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // Déconnexion de l'admin
        $request->session()->invalidate(); // Invalide la session
        $request->session()->regenerateToken(); // Régénère le token CSRF pour éviter les attaques

        return redirect('/pageAuthAdmin'); // Redirige vers la page de connexion de l'admin
    }

    //=======================================================
    // MODULE 2: TABLEAU DE BORD ADMIN
    //=======================================================
    
    /**
     * Afficher la page d'accueil admin avec des données dynamiques
     */
    public function afficherPageAcceuilAdmin()
    {
        try {
            // Récupérer les statistiques de base
            $stats = [
                'totalEtudiants' => User::count(),
                'totalListes' => Lists::count(),
                'totalCandidats' => Candidat::count(),
                'totalVotes' => Vote::count(),
            ];

            // Récupérer les activités récentes
            $activitesRecent = $this->getRecentActivities();

            return view('acceuiladminutilisateur', array_merge($stats, [
                'activitesRecent' => $activitesRecent,
            ]));

        } catch (\Exception $e) {
            Log::error('Erreur dans afficherPageAcceuilAdmin: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors du chargement du tableau de bord.');
        }
    }

    /**
     * Récupérer les activités récentes pour le tableau de bord
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Inscriptions récentes
        User::latest()->take(5)->get()->each(function ($user) use ($activities) {
            $activities->push([
                'description' => "Nouvel utilisateur inscrit : {$user->prenom} {$user->nom}",
                'date' => $user->created_at,
                'icon' => '👤'
            ]);
        });

        // Votes récents
        Vote::with(['user', 'candidat.list'])
            ->latest()
            ->take(5)
            ->get()
            ->each(function ($vote) use ($activities) {
                $activities->push([
                    'description' => "{$vote->user->prenom} {$vote->user->nom} a voté pour la liste " . ($vote->candidat->list->nom_liste ?? 'Inconnue'),
                    'date' => $vote->created_at,
                    'icon' => '🗳️'
                ]);
            });

        // Listes récentes
        Lists::latest()->take(5)->get()->each(function ($liste) use ($activities) {
            $activities->push([
                'description' => "Nouvelle liste créée : {$liste->nom_liste}",
                'date' => $liste->created_at,
                'icon' => '📋'
            ]);
        });

        return $activities->sortByDesc('date')->take(10);
    }

    //=======================================================
    // MODULE 3: GESTION DES UTILISATEURS
    //=======================================================
    
    /**
     * Afficher la page de gestion des utilisateurs.
     */
    public function gestionUtilisateurs()
    {
        // Récupérer tous les utilisateurs
        $utilisateurs = User::all();

        // Définir le filtre par défaut
        $filtre = 'tous';

        // Par défaut, la modification n'est pas active
        $modificationActive = false;

        // Passer les utilisateurs, le filtre et l'état de modification à la vue
        return view('gestionutilisateurs', [
            'utilisateurs' => $utilisateurs,
            'filtre' => $filtre,
            'modificationActive' => $modificationActive,
        ]);
    }

    /**
     * Filtrer les utilisateurs en fonction du statut (actifs, désactivés, tous).
     */
    public function filtrerUtilisateurs(Request $request)
    {
        // Récupérer le filtre depuis la requête AJAX
        $filtre = $request->input('filtre');

        // Filtrer les utilisateurs en fonction du filtre
        $utilisateurs = User::query();

        switch ($filtre) {
            case 'actifs':
                $utilisateurs->where('is_active', true);
                break;
            case 'desactives':
                $utilisateurs->where('is_active', false);
                break;
            // Par défaut, afficher tous les utilisateurs
        }

        // Récupérer les utilisateurs filtrés
        $utilisateurs = $utilisateurs->get();

        // Retourner la vue partielle avec les utilisateurs filtrés et le filtre actuel
        return view('partials.utilisateurs_table', [
            'utilisateurs' => $utilisateurs,
            'filtre' => $filtre,
            'modificationActive' => false, // La modification n'est pas active par défaut
        ]);
    }

    /**
     * Valider les modifications des utilisateurs (activation/désactivation).
     */
    public function validerModifications(Request $request)
    {
        // Valider les données reçues
        $request->validate([
            'utilisateurs' => 'required|array',
            'utilisateurs.*.id' => 'required|integer',
            'utilisateurs.*.is_active' => 'required|in:0,1,true,false', // Accepter 0, 1, true, false
        ]);

        // Mettre à jour chaque utilisateur
        foreach ($request->utilisateurs as $utilisateur) {
            // Convertir 'true'/'false' en 1/0 si nécessaire
            $isActive = filter_var($utilisateur['is_active'], FILTER_VALIDATE_BOOLEAN); // Convertit en booléen
            $isActive = $isActive ? 1 : 0; // Convertit en 1 ou 0

            User::where('id', $utilisateur['id'])->update([
                'is_active' => $isActive,
            ]);
        }

        // Retourner une réponse JSON pour AJAX
        return response()->json([
            'success' => true,
            'message' => 'Modifications enregistrées avec succès !',
        ]);
    }

    //=======================================================
    // MODULE 4: GESTION DES LISTES
    //=======================================================
    
    /**
     * Afficher la page de gestion des listes.
     */
    public function gestionListes()
    {
        $lists = Lists::all(); // Récupérer toutes les listes
        return view('gestionlistes', compact('lists'));
    }

    /**
     * Afficher le formulaire de création d'une liste.
     */
    public function createListe()
    {
        return view('lists.create');
    }

    /**
     * Enregistrer une nouvelle liste.
     */
    public function storeListe(Request $request)
    {
        Log::info('Début de la fonction storeListe');

        // Valider les données reçues
        $validator = Validator::make($request->all(), [
            'nom_liste' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
            'code_etu_info_licence' => 'required|string|max:255',
            'code_etu_info_master' => 'required|string|max:255',
            'code_etu_math_licence' => 'required|string|max:255',
            'code_etu_math_master' => 'required|string|max:255',
            'code_etu_phys_licence' => 'required|string|max:255',
            'code_etu_phys_master' => 'required|string|max:255',
            'code_etu_conseil_licence' => 'required|string|max:255',
            'code_etu_conseil_master' => 'required|string|max:255',
        ]);

        // Si la validation échoue, renvoyer les erreurs en JSON
        if ($validator->fails()) {
            Log::info('Validation échouée', $validator->errors()->toArray());
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Récupérer tous les codes étudiants pour les postes de département
        $codesDepartement = [
            $request->code_etu_info_licence,
            $request->code_etu_info_master,
            $request->code_etu_math_licence,
            $request->code_etu_math_master,
            $request->code_etu_phys_licence,
            $request->code_etu_phys_master,
        ];

        // Vérifier si un candidat de conseil est déjà dans un département
        if (in_array($request->code_etu_conseil_licence, $codesDepartement)) {
            Log::info('Candidat déjà dans un département (Conseil Licence)');
            return response()->json([
                'errors' => [
                    'code_etu_conseil_licence' => ['Ce candidat est déjà assigné à un poste de département.']
                ],
            ], 422);
        }

        if (in_array($request->code_etu_conseil_master, $codesDepartement)) {
            Log::info('Candidat déjà dans un département (Conseil Master)');
            return response()->json([
                'errors' => [
                    'code_etu_conseil_master' => ['Ce candidat est déjà assigné à un poste de département.']
                ],
            ], 422);
        }

        // Définir les candidats avec leurs critères de validation
        $codesEtudiants = [
            'info_licence' => [
                'code' => $request->code_etu_info_licence,
                'departement' => 'Informatique',
                'niveau' => 'Licence',
            ],
            'info_master' => [
                'code' => $request->code_etu_info_master,
                'departement' => 'Informatique',
                'niveau' => 'Master',
            ],
            'math_licence' => [
                'code' => $request->code_etu_math_licence,
                'departement' => 'Mathematique',
                'niveau' => 'Licence',
            ],
            'math_master' => [
                'code' => $request->code_etu_math_master,
                'departement' => 'Mathematique',
                'niveau' => 'Master',
            ],
            'phys_licence' => [
                'code' => $request->code_etu_phys_licence,
                'departement' => 'Physique',
                'niveau' => 'Licence',
            ],
            'phys_master' => [
                'code' => $request->code_etu_phys_master,
                'departement' => 'Physique',
                'niveau' => 'Master',
            ],
            'conseil_licence' => [
                'code' => $request->code_etu_conseil_licence,
                'departement' => null, // Conseil n'est pas lié à un département
                'niveau' => 'Licence',
            ],
            'conseil_master' => [
                'code' => $request->code_etu_conseil_master,
                'departement' => null, // Conseil n'est pas lié à un département
                'niveau' => 'Master',
            ],
        ];

        // Vérifier chaque candidat
        foreach ($codesEtudiants as $type => $data) {
            $etudiant = User::where('code_etudiant', $data['code'])
                            ->where('is_active', true)
                            ->when($data['departement'], function ($query, $departement) {
                                return $query->where('departement', $departement);
                            })
                            ->where('niveau', $data['niveau'])
                            ->first();

            if (!$etudiant) {
                $message = "Le code étudiant {$data['code']} ne correspond pas ";
                $message .= $data['departement'] ? "au département {$data['departement']} ou " : "";
                $message .= "au niveau {$data['niveau']} requis.";

                Log::info('Code étudiant invalide', ['type' => $type, 'message' => $message]);
                return response()->json([
                    'errors' => [
                        'code_etu_' . $type => [$message],
                    ],
                ], 422);
            }
        }

        // Créer la liste
        $list = Lists::create($request->all());

        // Remplir la table `candidats`
        foreach ($codesEtudiants as $type => $data) {
            Candidat::create([
                'code_etudiant' => $data['code'],
                'type_candidat' => str_contains($type, 'conseil') ? 'conseil' : 'departement',
                'list_id' => $list->id,
                'is_active' => true,
                'votes_count' => 0,
            ]);
        }

        Log::info('Liste créée avec succès');

        // Renvoyer une réponse JSON en cas de succès
        return response()->json([
            'success' => true,
            'message' => 'Liste créée avec succès !',
        ]);
    }

    /**
     * Afficher le formulaire de modification d'une liste.
     */
    public function editListe($id)
    {
        $list = Lists::findOrFail($id); // Récupérer la liste par son ID
        return view('lists.edit', compact('list'));
    }

    /**
     * Mettre à jour une liste existante.
     */
    public function updateListe(Request $request, $id)
    {
        $request->validate([
            'nom_liste' => 'required|string|max:255',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $list = Lists::findOrFail($id);
        $list->update($request->all());

        return redirect()->route('gestion.listes')->with('success', 'Liste mise à jour avec succès !');
    }

    /**
     * Supprimer une liste.
     */
    public function destroyListe($id)
    {
        $list = Lists::findOrFail($id);
        $list->delete();

        return redirect()->route('gestion.listes')->with('success', 'Liste supprimée avec succès !');
    }
//=======================================================
// MODULE 5: CONSULTATION DES VOTES ET STATISTIQUES
//=======================================================

/**
 * Afficher la page de consultation des votes avec filtres.
 */
public function consulterVotes(Request $request)
{
    // Définir des options par défaut avec "all" inclus
    $departements = ['all', 'Informatique', 'Mathematique', 'Physique'];
    $niveaux = ['all', 'Licence', 'Master'];

    $vue = $request->input('vue', 'departement');
    
    // Initialiser les variables
    $votes_departement = [];
    $votes_conseil = [];
    $stats = null;
    
    // Récupération des données selon la vue sélectionnée
    switch($vue) {
        case 'departement':
            $votes_departement = $this->getVotesDepartement([
                'departement' => $request->input('departement', 'all'),
                'niveau' => $request->input('niveau', 'all')
            ]);
            break;
            
        case 'conseil':
            $votes_conseil = $this->getVotesConseil([
                'niveau' => $request->input('niveau', 'all')
            ]);
            break;
            
        case 'statistiques':
            $stats = $this->getVotesStatistics($request);
            break;
    }
    
    return view('consultervotes', [
        'vue_active' => $vue,
        'filters' => [
            'departement' => $request->input('departement', 'all'),
            'niveau' => $request->input('niveau', 'all')
        ],
        'departements' => $departements,
        'niveaux' => $niveaux,
        'votes_departement' => $votes_departement,
        'votes_conseil' => $votes_conseil,
        'stats' => $stats
    ]);
}

/**
 * Récupérer les votes par département avec filtres
 */
protected function getVotesDepartement(array $filters)
{
    $query = Candidat::with(['user', 'list'])
        ->where('type_candidat', 'departement')
        ->has('list')
        ->has('user')  // S'assurer que l'utilisateur existe
        ->withCount('votes');

    // Appliquer les filtres de département et niveau
    if ($filters['departement'] !== 'all') {
        $query->whereHas('user', fn($q) => $q->where('departement', $filters['departement']));
    }

    if ($filters['niveau'] !== 'all') {
        $query->whereHas('user', fn($q) => $q->where('niveau', $filters['niveau']));
    }

    $candidats = $query->get();
    
    // Calcul du total des votes APRÈS filtrage pour le pourcentage
    $totalVotes = $candidats->sum('votes_count');

    return $candidats->map(function($candidat) use ($totalVotes) {
        return [
            'liste' => $candidat->list->nom_liste ?? 'Liste non spécifiée',
            'candidat' => ($candidat->user->prenom ?? '') . ' ' . ($candidat->user->nom ?? ''),
            'departement' => $candidat->user->departement ?? 'N/A',
            'niveau' => $candidat->user->niveau ?? 'N/A',
            'votes' => $candidat->votes_count,
            'pourcentage' => $totalVotes > 0 ? round(($candidat->votes_count / $totalVotes) * 100, 2) : 0,
        ];
    })->toArray();
}

/**
 * Récupérer les votes pour le conseil avec filtres
 */
protected function getVotesConseil(array $filters)
{
    $query = Candidat::with(['user', 'list'])
        ->where('type_candidat', 'conseil')
        ->has('user')
        ->has('list')
        ->withCount('votes');

    if ($filters['niveau'] !== 'all') {
        $query->whereHas('user', function($q) use ($filters) {
            $q->where('niveau', $filters['niveau']);
        });
    }

    $candidats = $query->get();
    
    // Calcul du total des votes APRÈS filtrage pour le pourcentage
    $totalVotes = $candidats->sum('votes_count');

    return $candidats->map(function($candidat) use ($totalVotes) {
        return [
            'liste' => $candidat->list->nom_liste ?? 'Liste inconnue',
            'candidat' => ($candidat->user->prenom ?? '') . ' ' . ($candidat->user->nom ?? ''),
            'niveau' => $candidat->user->niveau ?? 'N/A',
            'votes' => $candidat->votes_count,
            'pourcentage' => $totalVotes > 0 ? round(($candidat->votes_count / $totalVotes) * 100, 2) : 0,
        ];
    })->toArray();
}

/**
 * Récupérer les statistiques globales
 */
        protected function getVotesStatistics(Request $request)
        {
            $vue_active = $request->get('vue', 'departement');
            $filters = [
                'departement' => $request->get('departement', 'all'),
                'niveau' => $request->get('niveau', 'all'),
            ];
            
            // Récupérer les options pour les filtres
            $departements = User::distinct('departement')->pluck('departement')->toArray();
            $niveaux = User::distinct('niveau')->pluck('niveau')->toArray();
            array_unshift($departements, 'all');
            array_unshift($niveaux, 'all');
            
            // Initialiser les variables
            $votes_departement = [];
            $votes_conseil = [];
            
            // Récupérer les données selon la vue active
            if ($vue_active === 'departement') {
                $votes_departement = $this->getVotesDepartement($filters);
            } elseif ($vue_active === 'conseil') {
                $votes_conseil = $this->getVotesConseil($filters);
            }
            
            return view('consulter-votes', compact(
                'vue_active', 
                'filters', 
                'departements', 
                'niveaux', 
                'votes_departement', 
                'votes_conseil'
            ));
        }
        
        public function getVotingStatistics()
        {
            try {
                $stats = $this->calculateVotesStatistics();
                return response()->json($stats);
            } catch (\Exception $e) {
                Log::error('Erreur statistiques API: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'error' => 'Une erreur est survenue lors du calcul des statistiques',
                    'details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
        }
        protected function calculateVotesStatistics()
        {
            try {
    
                $total_users = User::where('is_active', true)->count();
                
                // Récupérer le nombre total de votes
                $total_votes = Vote::count();
                
                // Calculer la participation globale
                $participation_percentage = $total_users > 0 ? round(($total_votes / ($total_users*8)) * 100, 2) : 0;
                
                // Statistiques par département
                $departements = [];
                $depts = User::distinct('departement')->pluck('departement');
                foreach ($depts as $dept) {
                    $users_in_dept = User::where('departement', $dept)->where('is_active', true)->count();
                    $votes_in_dept = Vote::whereHas('user', function($query) use ($dept) {
                        $query->where('departement', $dept);
                    })->count();
                    
                    $dept_percentage = $users_in_dept > 0 ? round(($votes_in_dept / $users_in_dept) * 100, 2) : 0;
                    
                    $departements[$dept] = [
                        'total' => $users_in_dept,
                        'votes' => $votes_in_dept,
                        'percentage' => $dept_percentage
                    ];
                }
                
                // Statistiques par niveau
                $niveaux = [];
                $levels = User::distinct('niveau')->pluck('niveau');
                foreach ($levels as $level) {
                    $users_in_level = User::where('niveau', $level)->where('is_active', true)->count();
                    $votes_in_level = Vote::whereHas('user', function($query) use ($level) {
                        $query->where('niveau', $level);
                    })->count();
                    
                    $level_percentage = $users_in_level > 0 ? round(($votes_in_level / $users_in_level) * 100, 2) : 0;
                    
                    $niveaux[$level] = [
                        'total' => $users_in_level,
                        'votes' => $votes_in_level,
                        'percentage' => $level_percentage
                    ];
                }
                
                // Performance des listes
                $listes = DB::table('votes')
                    ->join('candidats', 'votes.candidat_id', '=', 'candidats.id')
                    ->join('lists', 'candidats.list_id', '=', 'lists.id')
                    ->select('lists.nom_liste as liste', DB::raw('count(*) as votes'))
                    ->groupBy('lists.nom_liste')
                    ->orderBy('votes', 'desc')
                    ->get()
                    ->toArray();
                
                return [
                    'participation' => [
                        'total_users' => $total_users,
                        'total_votes' => $total_votes,
                        'percentage' => $participation_percentage,
                        'text' => "$participation_percentage% ($total_votes/$total_users)"
                    ],
                    'departements' => $departements,
                    'niveaux' => $niveaux,
                    'listes' => $listes
                ];
            } catch (\Exception $e) {
                Log::error('Erreur calcul statistiques: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                
                return [
                    'error' => 'Une erreur est survenue lors du calcul des statistiques',
                    'details' => config('app.debug') ? $e->getMessage() : null
                ];
            }
        }
    }