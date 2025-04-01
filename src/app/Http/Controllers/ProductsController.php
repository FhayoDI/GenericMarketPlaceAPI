<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductsRequest;
use App\Http\Requests\UpdateProductsRequest;
use App\Models\Products;

class ProductsController extends Controller
{
    public function index()
    {
        return Products::all();
    }
    public function create() {}
    public function store(StoreProductsRequest $request, Products $products)
    {
        $request->validated([
            "category_name" => "required|string",
            "category_id" => "required|integer",
            "name" => "required|string",
            "stock" => "required|integer",
            "price" => "required|float",
            "description" => "nullable|string",
        ]);
        $products = Products::create($request->all());
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
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductsRequest $request, Products $products)
    {
        $request->validated([
            "category_name",
            "category_id",
            "name",
            "stock",
            "price",
            "description",
        ]);
        $products = Products::update($request->all());
        return $products;
    }
    public function destroy(Products $products,$id)
    {
        $products = Products::find($id);
        if (!$products) {
            return response()->json([
                "message" => "Produto não encontrado!",
            ], 404);
        }
        $products->delete();
        return response()->json([
            "message" => "Produto deletado com sucesso!",
        ], 204);
    }
}
