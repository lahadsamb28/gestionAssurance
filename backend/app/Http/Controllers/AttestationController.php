<?php

namespace App\Http\Controllers;

use App\Models\Certificat;
use App\Models\Proprietaire;
use App\Models\Stock;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


// ******************************************To DO: Proprietaire bug to fix**************************************************
class AttestationController extends Controller
{
    // controle ajout attestation
    public function ajouter_certificat(Request $request){
        // Inputs and validations in a array
        $proprioInputs = [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'sexe' => 'in:HOMME,FEMME',
            'dateDeNaissance' => 'required',
            'adresse' => 'required',
            'telephone' => 'required|numeric',
            'email' => 'required|email:rfc,dns|unique:proprietaires,email',
            'profession' => 'required',
        ];
        $vehiculeInputs = [
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
            'stock' => 'bail|required|numeric|exists:App\Models\Stock,id'
        ];

        // validations

        $requestCertificat = $request->validate($certificatInputs);
        //controle date delivrance and expiration
        $requestCertificat["date_delivrance"] = Carbon::now();

        if($requestCertificat["typeCertificat"] == "vip"){
            $requestCertificat["date_expiration"] = Carbon::now()->addYear();
        }else if($requestCertificat["typeCertificat"] == "premium"){
            $requestCertificat["date_expiration"] = Carbon::now()->addMonths(3);
        }else if($requestCertificat["typeCertificat"] == "standard"){
            $requestCertificat["date_expiration"] = Carbon::now()->addMonth();
        }

        $requestProprietaire = $request->validate($proprioInputs);
        $requestVehicule = $request->validate($vehiculeInputs);

        $last_num_stock = Stock::where('id', $requestCertificat["stock"])->value("dernierNumeroAttestation");
        $last_id_certificat = DB::table('stocks')->latest()->value('id');
        if($last_num_stock <= $last_id_certificat){
            return response(["message" => "veuillez verifier le numero de stock saisi ou le stock est epuise"]);
        }

        //MOdel created
        $proprio = Proprietaire::create($requestProprietaire);
        $vehicule = Vehicule::create($requestVehicule);

        $requestCertificat["proprietaire"] = $proprio->id;
        $requestCertificat["vehicule"] = $vehicule->id;
        $certificat = Certificat::create($requestCertificat);


        if(!$proprio){
            return response(["ErrorProprietaire" => "erreur de sauvegarde des informations du proprietaire"], 401);
        }else if(!$vehicule){
            return response(["ErrorVehicule" => "erreur de sauvegarde des informations du vehicule"], 401);
        }else if(!$certificat){
            return response(["ErrorCertificat" => "erreur de sauvegarde des informations du certificat"], 401);
        }

        return response([
            'proprietaire' => $proprio,
            'vehicule' => $vehicule,
            'attestation' => $certificat,
            'message' => 'attestation enregistre avec succes !'
        ], 201);
    }

}
