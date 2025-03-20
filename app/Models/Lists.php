<?php


    // app/Models/List.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    use HasFactory;

    protected $table = 'lists';

    protected $fillable = [
        'nom_liste', 'description', 'code_etu_info_licence', 'code_etu_info_master', 
        'code_etu_math_licence', 'code_etu_math_master', 'code_etu_phys_licence', 
        'code_etu_phys_master', 'code_etu_conseil_licence', 'code_etu_conseil_master', 'is_active'
    ];

    // Une liste peut avoir plusieurs candidats
    public function candidats()
    {
        return $this->hasMany(Candidat::class);
    }
}


