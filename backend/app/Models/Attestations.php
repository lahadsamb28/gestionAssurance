<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attestations extends Model
{
    use HasFactory;

    protected $table = 'attestations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'stock_numero',
        'numero_attestation',
        'dernier_numero_stock'
    ];
}
