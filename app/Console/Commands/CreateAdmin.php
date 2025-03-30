<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin {name} {email} {password}';
    protected $description = 'Crée un compte administrateur avec un mot de passe sécurisé';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        // Vérifier si un administrateur existe déjà avec cet email
        if (Admin::where('email', $email)->exists()) {
            $this->error("Un administrateur avec cet email existe déjà !");
            return;
        }

        // Création de l'administrateur
        $admin = Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("Administrateur {$admin->name} créé avec succès !");
    }
}
