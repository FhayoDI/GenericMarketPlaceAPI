<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserAdressRequest;
use App\Models\UserAdress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAdressController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return UserAdress::where("user_id")->get();
    }
    public function adress(Request $request)
    {
        $validated = $request->validate([
            "street" => "required|string",
            "number" => "required|integer",
            "cep" => ["required", "string", "regex:/^\d{8}$/"],
            "city" => "required|string",
            "state" => "required|string",
            "country" => "required|string",
        ]);

        $validated['user_id'] = Auth::id();

        $adress = UserAdress::create($validated);

        return response($adress, 201);
    }


    public function update(UpdateUserAdressRequest $request, UserAdress $userAdress)
    {
        $user = auth()->user();

        $request->validate([
            "street" => "required|string",
            "number" => "required|integer",
            "cep" => "required|integer",
            "city" => "required|string",
            "state" => "required|string",
            "country" => "required|string",
        ]);

        $adress = UserAdress::where('user_id', $user->id,)->first();
        if (!$adress) {
            return response()->json([
                "message" => "Não foi possível atualizar o endereço!"
            ], 404);
        }
        $adress->update($request->only([
            "street",
            "number",
            "cep",
            "city",
            "state",
            "country",
        ]));
        return response()->json([
            "message" => "Endereço atualizado com sucesso!"
        ]);
    }
}
