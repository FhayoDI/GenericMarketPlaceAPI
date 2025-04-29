<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        return Products::with('category')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string',
            'stock' => 'required|integer|min:1',
            'price' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        $product = Products::create($validatedData);

        return response()->json([
            "message" => "Criado com sucesso!",
            "product" => $product,
        ], 201);
    }

    public function show(Products $product)
    {
        return response()->json([
            "message" => "Produto encontrado!",
            "category" => optional($product->category)->name,
            "product" => [
                "name" => $product->name,
                "stock" => $product->stock,
                "price" => $product->price,
            ],
        ], 200);
    }

    public function update(Request $request, Products $product)
    {
        $userDataValidation = $request->validate([
            "category_id" => "integer",
            "name" => "string",
            "stock" => "integer",
            "price" => "numeric",
        ]);

        $product->update($userDataValidation);

        return response()->json([
            "message" => "Produto atualizado com sucesso!",
            "product" => $product,
        ]);
    }

    public function destroy(Products $product)
    {
        $product->delete();

        return response()->json([
            "message" => "Produto deletado com sucesso!",
        ], 202);
    }
}
