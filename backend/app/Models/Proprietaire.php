<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proprietaire extends Model
{
    use HasFactory;

    protected $table = 'proprietaires';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nom',
        'prenom',
        'sexe',
        'dateDeNaissance',
        'adresse',
        'telephone',
        'email',
        'profession',
    ];
}
