<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    //Add stock

    public function AddStock(Request $request)
    {
        $premiereAttestation = DB::table('stocks')->latest()->value('dernierNumeroAttestation');

        $request->validate([
            'nombreAttestations' => 'required|numeric',
        ]);
        $inputs = $request->all();
        if($premiereAttestation == null){
            $inputs["premierNumeroAttestation"] = 1;
        }else{
            $inputs["premierNumeroAttestation"] = $premiereAttestation + 1;
        }
        $inputs['dernierNumeroAttestation'] = ($inputs['nombreAttestations'] + $inputs['premierNumeroAttestation']) - 1;
        $inputs["stock_de"] = Auth::id();
        $stocks = Stock::create($inputs);

        if(! $stocks ){
            throw ValidationException::withMessages([
                'error' => 'les stocks sont indisponibles'
            ]);
        }
        return response([
            'stocks' => $stocks,
            'message' => 'stock ajouté avec succes !'
        ], 201);
    }

    public function ShowStocks(){
        try {
            $user_id = Auth::id();
            $typeUser = DB::table('users')->where('id', $user_id)->value('typeOfUser');

            if($typeUser == 'admin'){
                $stocks = DB::table('stocks')->join('users', 'users.id', '=', 'stock_de')->get(['stocks.id','nombreAttestations', 'premierNumeroAttestation','dernierNumeroAttestation', 'users.name', 'stocks.created_at']);
                if($stocks == null) throw new Exception('empty stocks or internal error', 505);
            }else{
                $stocks = DB::table('stocks')->where('stock_de', $user_id)->get(['nombreAttestations', 'premierNumeroAttestation','dernierNumeroAttestation']);
                if($stocks == null) throw new Exception('empty stocks or internal error', 404);
            }


            return response($stocks, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }
    }

    public function GetStock($id){
        try {
            $stock_id = Stock::find($id);
            if($stock_id == null) throw new Exception('stock not found !', 404);

            $user_id = Auth::id();
            $typeUser = DB::table('users')->where('id', $user_id)->value('typeOfUser');

            if($typeUser == 'admin'){
                $stock = DB::table('stocks')->where('stocks.id', $id)->join('users', 'users.id', '=', 'stock_de')->get(['nombreAttestations', 'premierNumeroAttestation','dernierNumeroAttestation', 'users.name', 'stocks.created_at']);
            }else{
                $stock = DB::table('stocks')->where([['stock_de', $user_id], ['stocks.id', $id]])->get(['nombreAttestations', 'premierNumeroAttestation','dernierNumeroAttestation']);
            }



            return response($stock, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }
    }

    public function UpdateStock(Request $request, $id){
        try{
            $stock = Stock::find($id);
            if($stock == null) throw new Exception('stock not found !', 404);

            $stock->update($request->all());
            $stock->update(['dernierNumeroAttestation' => ($stock->nombreAttestations + $stock->premierNumeroAttestation) - 1]);

            return response(['stock'=>$stock,'message' => 'stock mis a jour avec succes !'], 200);
        }catch(\Throwable $th){
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }
    }

    public function DeleteStock($id){
        try {
            $stock = Stock::find($id);
            if($stock == null) throw new Exception('stock not found !', 404);

            $stock->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }
    }
}
