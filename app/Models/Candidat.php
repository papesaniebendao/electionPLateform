<?php

// app/Models/Candidat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_etudiant', 'type_candidat', 'list_id', 'is_active', 'votes_count'
    ];

    protected $appends = ['count_votes'];

    // Le candidat appartient à une liste
    public function list()
    {
        return $this->belongsTo(Lists::class, 'list_id');
    }

    // Le candidat appartient à un utilisateur (candidat est un utilisateur)
    public function user()
    {
        return $this->belongsTo(User::class, 'code_etudiant', 'code_etudiant');
    }

    // Un candidat peut recevoir plusieurs votes
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

        // Accesseur dynamique pour compter les votes


    public function getCountVotesAttribute()
    {
        return $this->votes()->count();
    }
}

