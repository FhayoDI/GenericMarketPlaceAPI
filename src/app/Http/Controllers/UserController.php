<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
Use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
    public function createAdmin(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "email" => "required|string|email",
            "password" =>  "required|string",
            "is_admin" => "required|boolean",
        ]);
        $user = User::create($validated);
        return response()->json(data: [
            "message" => "Admin setado com sucesso!",
            "user" => $user
        ]);
    }
    public function showAdmin(User $user)
    {
        return response()->json([
            "message" => "Admin encontrado!",
            "user" => $user->admin,

        ]);
    }
    public function updateAdmin(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "email" => "required|string|email",
            "is_admin" => "required|boolean",
        ]);
        $user->update($validated);
        return response()->json([
            "message" => "Usuário atualizado com sucesso!"
        ]);
    }  
    public function delete(User $user)
    {
        $user->delete();
        return response()->json([
            "message" => "Usuário excluído com sucesso!"
        ]);
    }
}