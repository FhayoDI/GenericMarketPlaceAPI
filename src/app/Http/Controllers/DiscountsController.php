<?php

namespace App\Http\Controllers;

use App\Models\Discounts;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DiscountsController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            "productIds" => "required|array",
            "productIds.*" => "exists:products,id",
            "discount_percentage" => "required|numeric|min:0|max:100",
            "description" => "nullable|string",
            "start_date" => "required|date",
            "end_date" => "required|date|after_or_equal:start_date",
        ]);

        $discount = Discounts::create([
            "discount_percentage" => $data["discount_percentage"],
            "description" => $data["description"] ?? null,
            "start_date" => $data["start_date"],
            "end_date" => $data["end_date"],
        ]);

        $discount->products()->attach($data["productIds"]);

        return response()->json(["message" => "Desconto criado com sucesso."], 201);
    }

    public function update(Request $request, Discounts $discounts)
    {
        $data = $request->validate([
            "discount_percentage" => "required|numeric|min:0|max:100",
            "description" => "nullable|string",
            "start_date" => "required|date",
            "end_date" => "required|date|after_or_equal:start_date",
        ]);

        $discounts->update($data);

        if (Carbon::parse($discounts->end_date)->isToday()) {
            $discounts->delete();
            return response()->json(["message" => "Desconto expirado e removido."], 200);
        }

        return response()->json(["message" => "Desconto atualizado com sucesso."], 200);
    }

    public function destroy(Discounts $discounts)
    {
        $discounts->delete();
        return response()->json(["message" => "Desconto deletado com sucesso."], 200);
    }

}
