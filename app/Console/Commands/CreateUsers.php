<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUsers extends Command
{
    protected $signature = 'create:users';
    protected $description = 'Crée des utilisateurs pour l\'application avec des mots de passe sécurisés';

    public function handle()
    {
        $users = [
            ['prenom' => 'Ali', 'nom' => 'Mohamed', 'email' => 'ali.mohamed@ugb.edu.sn', 'code_etudiant' => 'P 28 1001', 'departement' => 'Informatique', 'niveau' => 'Licence'],
            ['prenom' => 'Fatou', 'nom' => 'Diop', 'email' => 'fatou.diop@ugb.edu.sn', 'code_etudiant' => 'P 28 1002', 'departement' => 'Informatique', 'niveau' => 'Master'],
            ['prenom' => 'Jean', 'nom' => 'Paul', 'email' => 'jean.paul@ugb.edu.sn', 'code_etudiant' => 'P 28 1003', 'departement' => 'Mathématique', 'niveau' => 'Licence'],
            ['prenom' => 'Awa', 'nom' => 'Sow', 'email' => 'awa.sow@ugb.edu.sn', 'code_etudiant' => 'P 28 1004', 'departement' => 'Mathématique', 'niveau' => 'Master'],
            ['prenom' => 'Moussa', 'nom' => 'Ba', 'email' => 'moussa.ba@ugb.edu.sn', 'code_etudiant' => 'P 28 1005', 'departement' => 'Physique', 'niveau' => 'Licence'],
            ['prenom' => 'Sophie', 'nom' => 'Gaye', 'email' => 'sophie.gaye@ugb.edu.sn', 'code_etudiant' => 'P 28 1006', 'departement' => 'Physique', 'niveau' => 'Master'],
            ['prenom' => 'Ibrahima', 'nom' => 'Fall', 'email' => 'ibrahima.fall@ugb.edu.sn', 'code_etudiant' => 'P 28 2001', 'departement' => 'Informatique', 'niveau' => 'Licence'],
            ['prenom' => 'Ndeye', 'nom' => 'Thiam', 'email' => 'ndeye.thiam@ugb.edu.sn', 'code_etudiant' => 'P 28 2002', 'departement' => 'Physique', 'niveau' => 'Master'],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'prenom' => $userData['prenom'],
                'nom' => $userData['nom'],
                'email' => $userData['email'],
                'code_etudiant' => $userData['code_etudiant'],
                'password' => Hash::make('mot de passe de ton choix'), // Mot de passe par défaut (à modifier par l'administrateur)
                'departement' => $userData['departement'],
                'niveau' => $userData['niveau'],
                'is_voted' => 0,
                'is_active' => 1, // Mettre actif par défaut
            ]);

            $this->info("Utilisateur {$user->prenom} {$user->nom} créé avec succès.");
        }

        $this->info('Tous les utilisateurs ont été créés avec succès!');
    }
}
