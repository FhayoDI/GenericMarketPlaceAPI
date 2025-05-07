<?php

namespace App\Http\Controllers;


use App\Models\UserAdress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAdressController extends Controller
{
    public function index()
    {
        return UserAdress::where("user_id", auth()->id())->get();
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


    public function update(Request $request, UserAdress $userAdress)
    {
        $user = auth()->user();

        if($userAdress->user_id != $user->id){
            return response()->json([
                "message" => "Não autorizado!"
            ], 400);
        }
        $request->validate([
            "street" => "required|string",
            "number" => "required|integer",
            "cep" => "required|integer",
            "city" => "required|string",
            "state" => "required|string",
            "country" => "required|string",
        ]);

        $adress = UserAdress::where('id', $userAdress->id)
            ->where('user_id', $user->id)->firstOrFail();
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
    public function destroy(UserAdress $userAdress){
        $user = auth()->user();

        if($userAdress->user_id != $user->id){
            return response()->json([
                "message" => "Não autorizado!"
            ], 400);
        }
        $userAdress->delete();
        return response()->json([
            "message" => "Endereço excluído com sucesso!"
        ]);
    }
}
