<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Inscription

    public function Inscription(Request $request){
        $request->validate([
            'NIN' => 'required|numeric|min:8',
            'name' => 'required|string',
            'gender' => 'required',
            'dateOfBirth' => 'required',
            'phone' => 'required|numeric|min:8',
            'email' => 'required|email:rfc,dns',
            'login' => 'required|unique:users,login|alpha_num|min:8',
            'password' => 'required|confirmed|alpha_num|between:8,30',
        ]);

        $inputs = $request->all();
        $inputs["password"] = bcrypt($inputs["password"]);

        $users = User::create($inputs);
        $token = $users->createToken($request->login)->plainTextToken;

        return response([
            "users" => $users,
            "token" => $token,
            "message" => "utilisateur enregistré avec success"
        ], 201);

    }

    public function Connexion(Request $request)
    {
        try{
            $connexionData = $request->validate([
                "username" => ["required"],
                "password" => ["required"]
            ]);
            $utilisateur = User::where("email", $connexionData["username"])->orWhere("login", $connexionData["username"])->first();
            if(!$utilisateur) return response(["message" => "Le compte $connexionData[username] n'existe pas"], 401);
            if(!Hash::check($connexionData["password"], $utilisateur->password)){
                return response(["message" => "Mot de passe incorrect"], 401);
            }

            $token = $utilisateur->createToken($request->username)->plainTextToken;
            $remember_me = $request->has('remember_me') ? true : false;

            return response([
                "utilisateur" => $utilisateur,
                "token" => $token,
                "remember_me" => $remember_me,
                "message" => "utilisateur connecté avec succes",
            ], 201);
        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }


    }

    public function Logout(User $user){
        $user->tokens()->delete();
        return response(["message"=>"vous etes deconnecte avec succes"], 200);
    }

}
