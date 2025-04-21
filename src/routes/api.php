<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DiscountsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\UserAdressController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

Route::post('/registro', [LoginController::class, 'registration']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/categorias', [CategoryController::class, 'index']);
Route::get('/produtos', [ProductsController::class, 'index']);
Route::get('/cupons', [CouponController::class, 'index']);


/*
|-------------------------------------------------------------------------
| Rotas Protegidas por Autenticação
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    
    // Informações do usuário autenticado
    Route::get('/usuario', fn (Request $request) => $request->user());
    
    // Perfil do usuário
    Route::prefix('/usuario')->group(function () {
        Route::get('/perfil', [UserController::class, 'ReturnUser']);
        Route::put('/atualizar', [UserController::class, 'update']);
        Route::delete('/excluir', [UserController::class, 'delete']);
    });

    // Logout
    Route::post('/logout', [LoginController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Endereços do Usuário
    |--------------------------------------------------------------------------
    */
    Route::prefix('/usuario/endereco')->group(function () {
        Route::post('/', [UserAdressController::class, 'adress']);
        Route::get('/', [UserAdressController::class, 'index']);
        Route::patch('/atualizar', [UserAdressController::class, 'update']);
        Route::delete('/excluir/{userAdress}', [UserAdressController::class, 'destroy']);
    });
        
    /*
    |--------------------------------------------------------------------------
    | Carrinho
    |--------------------------------------------------------------------------
    */
    Route::prefix('/carrinho')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/adicionar', [CartItemController::class, 'store']);
        Route::delete('/item/remover', [CartItemController::class, 'destroy']);
        Route::put('/item/atualizar', [CartItemController::class, 'update']);
    });

    /*
    |--------------------------------------------------------------------------
    | Pedidos
    |--------------------------------------------------------------------------
    */
    Route::prefix('/pedidos')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/novo', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::delete('/{id}/excluir', [OrderController::class, 'destroy']);
        Route::post('/pedidos/finalizar', [OrderController::class, 'closeOrder']); 
    });

    // Atualizar status do pedido (apenas moderador)
    Route::middleware('is_moderator')->put('/pedidos/{id}/status', [OrderController::class, 'updateStatus']);
    
    // Verificação de cupom (usuários autenticados)
    Route::post('/cupom/verificar', [CouponController::class, 'check']);    

    /*
    |--------------------------------------------------------------------------
    | Produtos
    |--------------------------------------------------------------------------
    */
    Route::prefix('/produtos')->group(function () {
        Route::get('/{product}', [ProductsController::class, 'show']);
        
        // Rotas apenas para moderador
        Route::middleware('is_moderator')->group(function () {
            Route::post('/novo', [ProductsController::class, 'store']);
            Route::put('/{product}/atualizar', [ProductsController::class, 'update']);
            Route::delete('/{product}/excluir', [ProductsController::class, 'destroy']);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Rotas exclusivas para ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('is_admin')->group(function () {
        // Gerenciamento de usuários
        Route::prefix('/usuarios')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/novo', [UserController::class, 'create']);
            Route::post('/novo-moderador', [UserController::class, 'createMod']);
        });

        // Gerenciamento de categorias
        Route::prefix('/categorias')->group(function () {
            Route::post('/nova', [CategoryController::class, 'store']);
            Route::put('/{id}/atualizar', [CategoryController::class, 'update']);
            Route::delete('/{id}/excluir', [CategoryController::class, 'delete']);
        });

        // Gerenciamento de cupons (NOVO)
        Route::prefix('/cupons')->group(function () {
            Route::post('/', [CouponController::class, 'store']);
            Route::delete('/{cupon}', [CouponController::class, 'destroy']);
        });

        // Gerenciamento de descontos (NOVO)
        Route::prefix('/descontos')->group(function () {
            Route::post('/', [DiscountsController::class, 'store']);
            Route::put('/{desconto}', [DiscountsController::class, 'update']);
            Route::delete('/{desconto}', [DiscountsController::class, 'destroy']);
        });
    });
});