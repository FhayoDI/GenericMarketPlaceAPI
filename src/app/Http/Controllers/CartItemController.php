<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function store(Request $request)
    {   
        $user = auth()->user();
        $cart = $user->cart;
        
        $validatedData = $request->validate([
            "product_id" => "required|integer|exists:products,id",
            "quantity" => "required|numeric|min:1",
        ]);
        
        $product = Products::find($validatedData["product_id"]);
        
        if ($validatedData["quantity"] > $product->stock) {
            return response()->json(["message" => "Quantidade insuficiente"], 400);
        }

        $unit_price = $product->price;
       // $discount = 0;
        
      //  if ($product->discount) {
        //    $discount = $product->discount;
          //  $unit_price = $product->price - $discount;
        //}
        
        $cartItem = CartItem::create([
            "product_id" => $validatedData["product_id"],
            "quantity" => $validatedData["quantity"],
            "unit_price" => $unit_price,
      //      "discount" => $discount, 
            "cart_id" => $cart->id,
           // "name" => $product->name, 
        ]);

        return response()->json([
            "message" => "Item adicionado com sucesso!",
            "item" => $cartItem,
            "preco_com_desconto" => $unit_price
        ]);
    }

    public function destroy(Cart $cart, Products $product, Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart;
        if(!$cart){
            return response()->json([
                "message" => "O carrinho nao existe!"
            ], 404);
        }
        $request->validate([
            'quantity' => 'nullable|integer|min:1'
        ]);
        $item = $cart->cartItems()->where('product_id', $product->id)->first();
        
        if(!$item){
            return response()->json([
                "message" => "Item não encontrado no carrinho!"
            ], 404);
        }
        $quantityToRemove = $request->input('quantity', $item->quantity); 
        $removedQuantity = $item->decreaseQuantity($quantityToRemove);
        return response()->json([
            'success' => true,
            'message' => "{$removedQuantity} itens removidos do carrinho",
            'remaining_quantity' => optional($item->fresh())->quantity ?? 0
        ]);
    }
}