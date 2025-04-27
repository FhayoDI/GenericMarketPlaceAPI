<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
class CartController extends Controller
{
    public function index()
    {
        $poggers = CartItem::all("product_id", "quantity", "unit_price");
        $user = auth()->user()->name;
        $totalValue = CartItem::sum("unit_price");
        return response()->json([
            "usuario"=>$user,
            "carrinho"=>$poggers,
            "totalValue"=>$totalValue,
            
        ]);

    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return response()->json([
            "message" => "Carrinho deletado com sucesso!"
        ]);
    }
}
