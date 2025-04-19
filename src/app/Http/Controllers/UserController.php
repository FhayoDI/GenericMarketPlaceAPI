<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function ReturnUser()
    {
        $user = auth()->user();
        return response()->json([
            "user" => $user->name,
            "addres" => $user->adress,
        ]);
    }

    public function delete()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(["error" => "Usuário não autenticado"], 401);
        }
        auth()->logout();
        User::where('id', $user->id)->delete();

        return response()->json([
            "message" => "Usuário deletado com sucesso!",
        ]);
    }

    public function update(Request $request)
    {
        $User = Auth::user();
        if (!$User) {
            return response()->json(["error" => "Não autenticado"], 401);
        }
        $user = User::find($User->id);
        if (!$user) {
            return response()->json(["error" => "Usuário não encontrado"], 404);
        }
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6|confirmed'
        ]);
        
        DB::beginTransaction();
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
        $user->fill($validatedData)->save();
        DB::commit();
        $updatedUser = User::find($user->id);
        return response()->json([
            'message' => 'Atualizado com sucesso!',
            'user' => $updatedUser
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "password" => "required|string|min:6",
        ]);
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request['password']),
        ]);

        return response()->json([
            "message" => "Registrado com sucesso!",
        ], 201);
    }
    public function setModerator(Request $request){
        
        $userData = $request->validate([
            "user_id" => "required|integer|exists:users,id",
            "is_moderator" => "sometimes|boolean",
            "is_admin" => "sometimes|boolean",
        ]);
        $user = User::findOrFail($userData['user_id']);

        if (array_key_exists('is_moderator',$userData)){
            $user->is_moderator = $userData['is_moderator'];
        }
        if (array_key_exists('is_admin',$userData)){
            $user->is_admin = $userData['is_admin'];
        }
        $user->save();
        return response()->json([
            "message"=> "permissões atualizadas com sucesso!",
            "user" => $user->fresh(),
        ]);
    }
}   