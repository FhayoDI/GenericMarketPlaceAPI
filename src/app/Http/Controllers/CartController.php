<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
class CartController extends Controller
{
    public function index()
    {
        $poggers = CartItem::all("name","quantity","unit_price");
        $user = auth()->user()->name;
        return response()->json([
            "usuario"=>$user,
            "carrinho"=>$poggers,
            
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
