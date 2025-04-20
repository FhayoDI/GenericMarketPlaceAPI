<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   public function index()
   {
      return response()->json([
         Category::with('products')->get(),
      ]);
   }

   public function  store(Request $request)
   {
      $request->validate([
         "name" => "required|string",
         "description" => "required|string",
      ]);
      $category = Category::create($request->all());
      return response()->json([
         "message" => "categoria criada com sucesso!",
         "category" => $category,
      ]);
   }
   public function update(Request $request, $id)
   {
      if (!$request->has(['name', 'description'])) {
         return response()->json([
            "message" => "Dados incompletos! Envie name e description",
            "received_data" => $request->all() // Mostra o que realmente chegou
         ], 400);
      }

      $validated = $request->validate([
         "name" => "required|string|max:255",
         "description" => "required|string"
      ]);

      $category = Category::find($id);

      if (!$category) {
         return response()->json([
            "message" => "Categoria não encontrada!"
         ], 404);
      }

      $updated = $category->update([
         'name' => $validated['name'],
         'description' => $validated['description']
      ]);

      if (!$updated) {
         return response()->json([
            "message" => "Falha ao atualizar categoria"
         ], 500);
      }

      return response()->json([
         "message" => "Categoria atualizada com sucesso!",
         "category" => $category->fresh()
      ]);
   }
   public function delete($id)
   {
      $category = Category::find($id);

      if (!$category) {
         return response()->json([
            "message" => "Categoria não encontrada!"
         ], 404);
      }

      $category->delete();

      return response()->json([
         "message" => "Categoria excluída com sucesso!"
      ]);
   }
}
