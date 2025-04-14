<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserAdressController;
use App\Http\Controllers\UserController;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Login e registro
Route::post('/registro', [LoginController::class, 'registration']);
Route::post('/login', [LoginController::class, 'login']);
//NAO PRECISA AUTENTICAÇÃO:
Route::post('/categorias/nova', [CategoryController::class, 'store']);
Route::get('/produtos', [ProductsController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    //USUARIO
    Route::put('/user/{user}/update', [UserController::class, 'update']);
    Route::put('/user/{user}/delete', [UserController::class, 'delete']);
    Route::get('/user/{user}', [UserController::class, 'show']);
    //ADMIN COMANDOS
    //GESTÃO DE USUARIOS:
    Route::middleware('is_admin')->group(function () {
        Route::post('/user/create', [UserController::class, 'create']);
        Route::post('/user/createMod', [UserController::class, 'createMod']);
        Route::get('/user', [UserController::class, 'index']);
        //CATEGORIAS ADMIN 
        Route::get('/categorias', [CategoryController::class, 'index']);
        Route::put('/categorias/{category}/atualizar', [CategoryController::class, 'update']);
        Route::put('/categorias/{category}/deletar', [CategoryController::class, 'delete']);

        //PRODUTOS ADMIN/MODERATOR
        Route::post('/produtos/add', [ProductsController::class, 'store']);
        Route::put('/produtos/{product}/atualizar', [ProductsController::class, 'update']);
        Route::put('/produtos/{product}/deletar', [ProductsController::class, 'destroy']);
    });
    // logout
    Route::post('/logout', [LoginController::class, 'logout']);

    //endereços
    Route::post('/user/adress', [UserAdressController::class, 'adress']);
    Route::get('/user/adress', [UserAdressController::class, 'index']);
    Route::patch('/user/adress/update', [UserAdressController::class, 'update']);
    Route::put('/user/adress/delete', [UserAdressController::class, 'destroy']);

    // Categorias


    // Produtos
    Route::get('/produtos', [ProductsController::class, 'index']);
    Route::get('/produtos/{product}', [ProductsController::class, 'show']);


    // Carrinho
    Route::get('/carrinho', [CartController::class, 'index']);
    Route::post('/carrinho/salvar', [CartController::class, 'store']);
    Route::post('/carrinho/add', [CartItemController::class, 'store']);

    // Pedidos
    Route::get('/pedidos', [OrderController::class, 'index']);
    Route::post('/pedidos/add', [OrderController::class, 'store']);
    Route::get('/pedidos/{id}', [OrderController::class, 'show']);
    Route::put('/pedidos/{id}/deletar', [OrderController::class, 'destroy']);
    Route::put('/pedidos/atualizar', [OrderController::class, 'update']);
});
