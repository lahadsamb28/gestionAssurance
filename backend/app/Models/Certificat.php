<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificat extends Model
{
    use HasFactory;

    protected $table= 'certificats';
    protected $primaryKey = 'id';
    protected $fillable = [
        'date_delivrance',
        'date_expiration',
        'stock',
        'proprietaire',
        'vehicule',
    ];
}
