<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return response()->json([
             Category::all()
             
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
    
}
