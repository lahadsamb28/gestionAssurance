<?php

namespace App\Http\Controllers;

use App\Models\Attestations;
use App\Models\Certificat;
use App\Models\Proprietaire;
use App\Models\Stock;
use App\Models\Vehicule;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// *************************To Do: Update ATtestations*************************
class AttestationController extends Controller
{
    // controle ajout attestation
    public function AddAttestation(Request $request){
        // Inputs and Rules;
        $proprioRules = [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'sexe' => 'in:HOMME,FEMME',
            'dateDeNaissance' => 'required',
            'adresse' => 'required',
            'telephone' => 'required|numeric',
            'email' => 'required|email:rfc,dns|unique:proprietaires,email',
            'profession' => 'required',
        ];


        $vehiculeRules = [
            'typeDeVehicule' => 'required',
            'immatriculation' => 'required|unique:vehicules,immatriculation',
            'categorie' => 'required',
            'marque' => 'required|string',
            'model' => 'required',
            'annee' => 'required|numeric',
            'transmission' => 'required',
            'energie' => 'required',
        ];

        $certificatInputs = [
            'typeCertificat' => 'required|in:vip,premium,standard',
        ];

        //controle si le stock est epuise
        $stock = Stock::where('stock_de', '=', Auth::id())->latest()->value('id');

        // validate all request for certificats
        $requestCertificat = $request->validate($certificatInputs);

        $requestCertificat["stock"] = $stock;


        $last_num_stock = Stock::where('id','=', $requestCertificat["stock"])->value("dernierNumeroAttestation");
        $last_id_certificat = Certificat::where('stock', '=', $requestCertificat["stock"])->latest()->value('id');

        //controle date delivrance and expiration
        $requestCertificat["date_delivrance"] = Carbon::now();

        if($requestCertificat["typeCertificat"] == "vip"){
            $requestCertificat["date_expiration"] = Carbon::now()->addYear();
        }else if($requestCertificat["typeCertificat"] == "premium"){
            $requestCertificat["date_expiration"] = Carbon::now()->addMonths(3);
        }else if($requestCertificat["typeCertificat"] == "standard"){
            $requestCertificat["date_expiration"] = Carbon::now()->addMonth();
        }


        //MOdel created
        try {
            if($last_id_certificat >= $last_num_stock){
                throw new Exception("this stock is sold out", 403);
            }
            if(($last_num_stock - $last_id_certificat) == 1){
                echo "last item in this stock";
            }

            if($request->has(app(Proprietaire::class)->getFillable())){
                $requestProprietaire = $request->validate($proprioRules);
                $proprio = Proprietaire::create($requestProprietaire);
                $requestCertificat["proprietaire"]= $proprio->id;
            }else{
                if(!empty($request->input('proprietaire'))){
                    $requestCertificat["proprietaire"] = $request->input('proprietaire');
                }else{
                    throw new Exception("proprietaire id is not filled", 400);
                }
            }

            if($request->has(app(Vehicule::class)->getFillable())){
                $requestVehicule = $request->validate($vehiculeRules);
                $vehicule = Vehicule::create($requestVehicule);
                $requestCertificat["vehicule"] = $vehicule->id;
            }else{
                if(!empty($request->input('vehicule'))){
                    $requestCertificat["vehicule"] = $request->input('vehicule');
                }else{
                    throw new Exception("vehicule id is not filled", 400);
                }
            }

            $certificat = Certificat::create($requestCertificat);

            $attestations = new Attestations;
            $attestations->stock_numero = $certificat->stock;
            $attestations->numero_attestation = $certificat->id;
            $attestations->dernier_numero_stock = $last_num_stock;
            $attestations->save();

            if($certificat){
                return response([
                    'attestation' => $certificat,
                    'message' => 'attestation enregistre avec succes !'
                ], 201);
            }else{
                throw new Exception("error saved certificat", 500);
            }

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], $th->getCode());
        }



    }

    public function ShowAttestations(){
        try {
            $certificat = DB::table('certificats')
            ->join('proprietaires', 'proprietaires.id', '=', 'proprietaire')
            ->join('vehicules', 'vehicules.id', '=', 'vehicule')
            ->get([
                'certificats.id',
                'typeCertificat',
                'date_delivrance',
                'date_expiration',
                'stock',
                'nom',
                'prenom',
                'sexe',
                'dateDeNaissance',
                'adresse',
                'telephone',
                'email',
                'profession',
                'typeDeVehicule',
                'immatriculation',
                'categorie',
                'marque',
                'model',
                'annee',
                'transmission',
                'energie',
            ]);

            if(!$certificat) throw new Exception('error retrieving informations', 505);
            return response($certificat, 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], $th->getCode());
        }



    }

    public function GetAttestation($id){
        try {
            //code...
            $certificatId = Certificat::find($id);
            if(!$certificatId){ throw new Exception('certificat does not exist ', 404);}

            $certificat = $certificatId->join('proprietaires', 'proprietaires.id', '=', 'proprietaire')
                ->join('vehicules', 'vehicules.id', '=', 'vehicule')
                ->get([
                    "certificats.id",
                    "typeCertificat",
                    "date_delivrance",
                    "date_expiration",
                    "stock",
                    "nom",
                    "prenom",
                    "sexe",
                    "dateDeNaissance",
                    "adresse",
                    "telephone",
                    "email",
                    "profession",
                    'typeDeVehicule',
                    'immatriculation',
                    'categorie',
                    'marque',
                    'model',
                    'annee',
                    'transmission',
                    'energie',
                ]);
                    return response($certificat, 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                        'message' => $th->getMessage(),
                    ], $th->getCode());
        }
    }

    public function UpdateAttestation(Request $request, $id){
        try{
            $certificatId = Certificat::find($id);
            if(!$certificatId){ throw new Exception('certificat does not exist ', 404);}



            $proprietaire = Proprietaire::find($certificatId->proprietaire);
            if(!$proprietaire){ throw new Exception('error proprietaire does not exist ', 404);}
            $vehicule = Vehicule::find($certificatId->vehicule);
            if(!$vehicule){ throw new Exception('vehicule does not exist ', 404);}

            if($request->input("typeCertificat") == "vip"){
                $certificatId->update(['date_expiration' => Carbon::now()->addYear()]);
            }else if($request->input("typeCertificat") == "premium"){
                $certificatId->update(['date_expiration' => Carbon::now()->addMonths(3)]);
            }else if($request->input("typeCertificat") == "standard"){
                $certificatId->update(['date_expiration' => Carbon::now()->addMonth()]);
            }

            $certificatId->update($request->all());
            $proprietaire->update($request->all());
            $vehicule->update($request->all());

            return response([
                'message' => 'attestation mise a jour avec succes!',
            ], 200);


        }catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    public function DeleteAttestation($id){
        try {
            $certificat = Certificat::find($id);
            if($certificat == null) throw new Exception('certificat not found', 404);

            $certificat->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function DeleteAttestationProprio($id){
        try {
            $proprietaire = Proprietaire::find($id);
            if($proprietaire == null) throw new Exception('proprietaire not found', 404);

            $proprietaire->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }
    public function DeleteAttestationVehicule($id){
        try {
            $vehicule = Vehicule::find($id);
            if($vehicule == null) throw new Exception('vehicule not found', 404);

            $vehicule->delete();
            return response(null, 204);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }



}
