<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;

class CartController extends Controller
{
    public function index()
    {
        $cartData = CartItem::all("product_id", "quantity", "unit_price");
        $user = auth()->user()->name;
        $totalValue = CartItem::sum("unit_price");
        return response()->json([
            "usuario" => $user,
            "carrinho" => $cartData,
            "totalValue" => $totalValue,

        ]);
    }
    public function adminIndex(User $user)
    {
        return $user->cart()->with('CartItems')->first();
    }
}
