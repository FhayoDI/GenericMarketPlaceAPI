<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Products;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $user=auth()->user();
        return CartItem::all()->where("user_id", $user->id,);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "product_id"=>"required|exists:products,id",
            "quantity"=>"required|numeric|min:1",
        ]);
        if (!Products::where("id")->$validatedData["product_id"]){
            return response()->json([
                "message"=> "Produto não existe!"
            ],404)
        }
        $cartItem= CartItem::create($validatedData);
        Products::where("id",$validatedData["product_id"])->decrement("stock",$validatedData["stock"]);    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $userDataValidation = $request->validate([
            "product_id"=>"required|exists:products,id",
            "quantity"=>"required|numeric|min:1",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
    }
}
