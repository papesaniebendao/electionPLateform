<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;


class adminController extends Controller
{

    // afficher la page d'acceuiladminutilisateur
    public function afficherPageAcceuilAdmin(){
        return view('acceuiladminutilisateur');
    }
    
    public function afficherPageAuthAdmin(){

        return view('authentificationadmin');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        Log::info('Tentative de connexion admin:', ['email' => $credentials['email']]);

        // Recherche de l'admin par email
        $admin = Admin::where('email', $credentials['email'])->first();

        if ($admin) {
            Log::info('Admin trouvé:', ['id' => $admin->id, 'email' => $admin->email]);

            // Vérification du mot de passe
            if (Hash::check($credentials['password'], $admin->password)) {
                Auth::guard('admin')->login($admin);
                Log::info('Admin authentifié avec succès:', ['id' => $admin->id]);

                return redirect()->route('pageAcceuilAdmin');
            } else {
                Log::warning('Échec de l\'authentification admin : mot de passe incorrect.', ['email' => $credentials['email']]);
            }
        } else {
            Log::warning('Échec de l\'authentification admin : admin non trouvé.', ['email' => $credentials['email']]);
        }

        return redirect()->route('pageAuthAdmin')->with('error', 'Échec de l\'authentification !');
    }

    /**
     * Display a listing of the resource.
     */
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
