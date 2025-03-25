<?php

// app/Models/Vote.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'candidat_id', 'poste', 'created_at'
    ];

    // Un vote appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Un vote est associé à un candidat
    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'candidat_id');
    }

        // Compteur des votes dynamiquement
}
