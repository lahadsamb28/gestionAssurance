<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //Register

    public function Register(Request $request){

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
            $inputs["password_confirmation"] = bcrypt(($inputs["password"]));
            // dd($inputs);

            $users = User::create($inputs);
            $token = $users->createToken($request->login)->plainTextToken;

            if($users && $token){
                return response([
                    "users" => $users,
                    "access_token" => $token,
                    "token_type" => "Bearer",
                    "message" => "utilisateur enregistré avec success"
                ], 201);
            }

    }


    /** Login */
    public function Login(Request $request)
    {
        try{
            $connexionData = Validator::make($request->all(), [
                "username" => ["required"],
                "password" => ["required"]
            ]);

            if($connexionData->fails()){
                $errors = $connexionData->messages();
                throw new Exception($errors, 400);
            }

            $utilisateur = User::where("email", $request->username)->orWhere("login", $request->username)->first();
            if(!$utilisateur) throw new Exception("Le compte $request->username n'existe pas", 401);
            if(!Hash::check($request->password, $utilisateur->password)){
                throw new Exception("Mot de passe incorrect", 401);
            }

            $token = $utilisateur->createToken($request->username);
            $remember_me = $request->has('remember_me') ? true : false;

            if($utilisateur && $token){
                return response([
                    "user" => $utilisateur->name,
                    "user_type" => $utilisateur->typeOfUser,
                    "access_token" => $token->plainTextToken,
                    "token_expires_at" => $token->accessToken->expires_at,
                    "token_type" => "Bearer",
                    "remember_me" => $remember_me,
                    "message" => "utilisateur connecté avec succes",
                ], 201);}
            // }else{
            //     throw new Exception('error in storage of data', 500);
            // }
        }catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], $th->getCode());
        }


    }

    // *****logout************
    public function Logout(Request $request){
        $request->user()->currentAccessToken()->delete();
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
                'message' => 'user updated succesfully',
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

    public function forgotPassword(Request $request){
        try{
            $emailData = Validator::make($request->all(), [
                'email' => 'required|email:rfc,dns'
            ]);
            if($emailData->fails()) throw new Exception($emailData->messages(), 400);
            $status = Password::sendResetLink(
                $request->only('email')
            );
            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => __($status)])
                : throw new Exception(__($status), 302);
        }catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function resetPassword(Request $request){
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed'
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60)
                    ])->save();

                    $user->tokens()->delete();
                    event(new PasswordReset($user));
                }
            );

            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'Password reset successfully !'], 202)
                : response()->json(['message' => __($status)], 500);
    }





}
