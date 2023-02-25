<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    //Add stock

    public function AjoutStock(Request $request)
    {
        $request->validate([
            'nombreAttestations' => 'required|numeric',
            'premierNumeroAttestation' => 'required|numeric',
            'dernierNumeroAttestation' => 'required|numeric',
            'stock_de' => 'required'
        ]);
        $inputs = $request->all();
        $inputs["stock_de"] = Auth::id();


        $stocks = Stock::create($request->all());

        return response([
            'stocks' => $stocks,
            'message' => 'stock ajouté avec succes !'
        ], 201);
    }
}
