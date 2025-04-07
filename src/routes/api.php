<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserAdressController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\UserAdress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//login e registro
Route::post('/registro', [LoginController::class, 'registration']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(
    function () {
        //USUARIO

        //logout
        Route::post('/logout', [LoginController::class, 'logout']);
        //endereços
        Route::post('/user/adress', [UserAdressController::class, 'adress']);
        Route::get('/user/adress', [UserAdressController::class, 'index']);
        Route::patch('/user/adress/update', [UserAdressController::class, 'update']);
        Route::put('/user/adress/delete', [UserAdressController::class, 'destroy']);
        //Categorias
        Route::get('/categorias',[CategoryController::class, 'index']);
        Route::post('/categorias/nova',[CategoryController::class, 'store']);
        //Produtos
        Route::get('/produtos',[ProductsController::class, 'index']);
        Route::post('/produtos/add', [ProductsController::class, 'store']);
        Route::get('/produtos/{product}',[ProductsController::class, 'show']);
        Route::put('/produtos/{product}/deletar',[ProductsController::class, 'destroy']);
        Route::put('/produtos/{product}/atualizar',[ProductsController::class, 'update']);
        //Carrinho

        //Pedidos
        Route::get('/pedidos',[OrderController::class, 'index']);
        Route::post('/pedidos/add', [OrderController::class, 'store']);
        Route::get('/pedidos/{id}',[OrderController::class, 'show']);
        Route::put('/pedidos/{id}/deletar',[OrderController::class, 'destroy']);
        Route::put('/pedidos/atualizar',[OrderController::class, 'update']);
        //Desconto

        //Cupom

    }
);
