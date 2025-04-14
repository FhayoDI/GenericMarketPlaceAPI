<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Products;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return CartItem::all()->where("user_id", $user->id,);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $cart = $user->cart;
        $validatedData = $request->validate([
            "product_id" => "required|integer|exists:products,id",
            "quantity" => "required|numeric|min:1",
            "unit_price" => "exists:products,price",
        ]);
        $product = Products::find($validatedData["product_id"]);
        if (!$product) {
            return response()->json([
                "message" => "produto inexistente",
            ]);
        }
        if ($validatedData["quantity"] > $product->stock) {
            return response()->json([
                "message" => "quantidade insuficiente",
            ]);
        }
        $cartItem = CartItem::create([
            "product_id" => $validatedData["product_id"],
            "quantity" => $validatedData["quantity"],
            "price" => $product->price,
            "cart_id" => $cart->id,
            "name"=>$product->name,
            
        ]);
        $product->decrement("stock",$validatedData["quantity"]);
        return response()->json([
            "message" => "Item adicionado com sucesso!",
            "cartItem" => $cartItem,
        ]);
    }
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
    }
    public function update(Request $request,CartItem $cartItem){
        $userData= $request->validate([
            "product_id" => "required|exists:products,id",
            "quantity" => "required|numeric|min:1",
        ]);
        $cartItem->update($userData);
        return response()->json([
            "message" => "Item atualizado com sucesso!",
            "cartItem" => $cartItem,
        ]);
        
    }
}
