<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'prenom',
        'nom',
        'code_etudiant',
        'departement',
        'niveau',
        'email',
        'password',
        'is_voted',
        'is_active',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });

        static::updating(function ($user) {
            if ($user->isDirty('password')) {
                $user->password = Hash::make($user->password);
            }
        });
    }

    // Un utilisateur peut être un candidat dans une liste
    public function candidat()
    {
        return $this->hasOne(Candidat::class, 'code_etudiant', 'code_etudiant');
    }

    // Un utilisateur peut voter pour un candidat (relation avec Votes)
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
