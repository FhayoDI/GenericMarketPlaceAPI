<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\UserAdress;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return response()->json([
            "message"=>"carrinho do usuario",
            "usuario"=>$user,
            CartItem::all("name","quantity","price"),
            
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
