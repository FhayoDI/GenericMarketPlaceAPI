<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function registration(User $user, Request $request)
    {
        $request->validate([
            "name"=>"required|string",
            "email"=>"required|email",
            "password"=>"required|string",
        ]);
        $user = User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$request->password,
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
        $token = $user->createToken($user->name."-AuthToken")->plainTextToken;
        return response()->json([
            "message"=> "logado com sucesso!",
            "token"=> $token,
        ],200);
}}