<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Signup

    public function Signup(Request $request){
        $request->validate([
            'typeOfUser' => 'required|in:admin,simple',
            'NIN' => 'required|numeric|min:8',
            'name' => 'required|string',
            'gender' => 'required|in:HOMME,FEMME',
            'dateOfBirth' => 'required',
            'phone' => 'required|numeric|min:8',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'login' => 'required|unique:users,login|alpha_num|min:8',
            'password' => 'required|confirmed|alpha_num|between:8,30',
        ]);

        $inputs = $request->all();
        $inputs["password"] = bcrypt($inputs["password"]);
        $inputs["password_confirmation"] = bcrypt($inputs["password"]);
        // dd($inputs);

        $users = User::create($inputs);
        $token = $users->createToken($request->login)->plainTextToken;


        return response([
            "users" => $users,
            "token" => $token,
            "message" => "utilisateur enregistré avec success"
        ], 201);

    }


    /** Login */
    public function Login(Request $request)
    {
        try{
            $connexionData = $request->validate([
                "username" => ["required"],
                "password" => ["required"]
            ]);
            $utilisateur = User::where("email", $connexionData["username"])->orWhere("login", $connexionData["username"])->first();
            if(!$utilisateur) throw new Exception("Le compte $connexionData[username] n'existe pas", 401);
            if(!Hash::check($connexionData["password"], $utilisateur->password)){
                throw new Exception("Mot de passe incorrect", 400);
            }

            $token = $utilisateur->createToken($request->username)->plainTextToken;
            $remember_me = $request->has('remember_me') ? true : false;

            if($utilisateur && $token){
                return response([
                    "utilisateur" => $utilisateur,
                    "token" => $token,
                    "remember_me" => $remember_me,
                    "message" => "utilisateur connecté avec succes",
                ], 201);
            }else{
                throw new Exception('error in storage of data', 500);
            }
        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], $th->getCode());
        }


    }

    // *****logout************
    public function Logout(Request $request){
        $request->user()->tokens()->delete();
        return response(["message"=>"vous etes deconnecte avec succes"], 200);
    }

    public function Show(){
        try {
            $user = User::all();

            if(!$user) throw new Exception('internal error', 500);

            return response($user, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function Edit($id){
        try {
            $user = User::find($id);

            if($user == null) throw new Exception('user not found', 404);

            return response($user, 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function Update(Request $request, $id){
        try {
            $user = User::find($id);

            if($user == null){ throw new Exception('user not found', 404);}

            $user->update($request->all());
            $token = $user->createToken("user mis-a-jour")->plainTextToken;

            if(!$token){ throw new Exception('internal error', 500); }

            return response()->json([
                'message' => 'utilisateur mis a jour',
                'status' => true
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function Delete($id){
        try {
            $user = User::find($id);
            if($user == null){ throw new Exception('user not found', 404);}

            $user->delete();
            return response(null, 204);

        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], $th->getCode());
        }
    }

}
