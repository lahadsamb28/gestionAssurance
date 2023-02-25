<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;
    protected $table = 'vehicules';
    protected $primaryKey = 'id';
    protected $fillable = [
        'typeDeVehicule',
        'immatriculation',
        'categorie',
        'marque',
        'model',
        'annee',
        'transmission',
        'energie',
    ];
}
