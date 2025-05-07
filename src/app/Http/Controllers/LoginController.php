<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class   LoginController extends Controller
{
    public function registration(Request $request)
    {
        $request->validate([
            "name"=>"required|string",
            "email"=>"required|email",
            "password"=>"required|string|min:6",
        ]);
        $user = User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>Hash::make($request['password']),
        ]);

        return response()->json([
            "message"=>"Registrado com sucesso!",
        ],201);
    }
    public function login(User $user, Request $request){
        $userLoginData=$request->validate([
            "email"=>"required|email",
            "password"=>"required|string",
        ]);
        $user= User::where("email",$userLoginData["email"])->first();
        if (!$user || !Hash::check($userLoginData['password'],$user->password)){
            return response()->json([
                "message"=>"email ou senha invalidos!",
            ],401);
        }
        Cart::firstOrCreate([
            "user_id"=>$user->id,
        ]);
        $token = $user->createToken($user->name."-AuthToken")->plainTextToken;
        return response()->json([
            "message"=> "logado com sucesso!",
            "token"=> $token,
        ],200);
    }
        
        public function logoutin(Request $request){
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                "message"=>"você deslogou da plataforma!",
            ],200);
        }
}
