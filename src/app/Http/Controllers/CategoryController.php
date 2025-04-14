<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return response()->json([
             Category::with('products')->get(),
            ]);

    }

    public function  store (Request $request){
     $request->validate([
        "name"=>"required|string",
        "description"=>"required|string",
     ]);
     $category = Category::create($request->all());
     return response()->json([
        "message"=>"categoria criada com sucesso!",
        "category"=>$category,
     ]);   
    }
    public function update(Request $request){
      $user = auth()->user();
      $request->validate([
         "name"=>"required|string",
         "description"=>"required|string",
      ]);
      $category = Category::where('name', $request->name)->first();
      if (!$category) {
          return response()->json([
              "message" => "Não foi possível atualizar a categoria!"
          ], 404);
      }
      $category->update($request->only([
         "name",
         "description"
      ]));
      return response()->json([
          "message" => "Categoria atualizada com sucesso!"
      ]);
   }
   public function delete(Category $category ){
      $category->delete();
   }
    
}
