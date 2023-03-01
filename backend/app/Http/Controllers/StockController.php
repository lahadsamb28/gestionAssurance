<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    //Add stock

    public function AjoutStock(Request $request)
    {
        $request->validate([
            'nombreAttestations' => 'required|numeric',
            'premierNumeroAttestation' => 'required|numeric',
            'dernierNumeroAttestation' => 'required|numeric',
        ]);
        $inputs = $request->all();
        $inputs["stock_de"] = Auth::id();
        $stocks = Stock::create($inputs);

        if(! $stocks ){
            throw ValidationException::withMessages([
                'numero stock' => 'les numeros de stock saisis sont indisponibles'
            ]);
        }
        return response([
            'stocks' => $stocks,
            'message' => 'stock ajouté avec succes !'
        ], 201);
    }
}
