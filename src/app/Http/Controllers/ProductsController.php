<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductsRequest;
use App\Http\Requests\UpdateProductsRequest;
use App\Models\Category;
use App\Models\Products;
use App\Models\User;

class ProductsController extends Controller
{
    public function index()
    {
        return Products::all();
    }
        public function store(StoreProductsRequest $request, Products $products)
    {
        $userDataValidation = $request->validated([
            "category_name" => "required|string",
            "category_id" => "required|integer",
            "name" => "required|string",
            "stock" => "required|integer",
            "price" => "required|float",
            "description" => "nullable|string",
        ]);
        $products = Products::create($request->all($userDataValidation));
        return response()->json([
            "message" => "Criado com sucesso!",
            "product" => $products,
        ], 201);
    }
    public function show(Products $products)
    {
        return response()->json([
            "product" => $products,
        ]);
    }
    public function update(UpdateProductsRequest $request, Products $products)
    {
        $userDataValidation = $request->validated();
        if (!Category::find($userDataValidation["category_id"])){
            return response()->json([
                "message" => "Categoria não encontrada!",
            ],404);
        }
        $products->update($userDataValidation);
        return response()->json([
            "message"=> "Produto atualizado com sucesso!",
            "product"=>$products,
        ]);
    }
    public function destroy(Products $products)
{
        $products->delete();
        return response()->json([
            "message" => "Produto deletado com sucesso!",
        ], 204);
    }
}
