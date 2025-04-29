<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    LoginController,
    CartController,
    CartItemController,
    CategoryController,
    CouponController,
    DiscountsController,
    OrderController,
    ProductsController,
    UserAdressController,
    UserController
};

/* Rotas Públicas */

Route::post('/registrar', [LoginController::class, 'registration']);
Route::post('/entrar', [LoginController::class, 'login']);

// Catálogo
Route::get('/categorias', [CategoryController::class, 'index']);
Route::get('/categorias/{category}', [CategoryController::class, 'seeCategory']);
Route::get('/cupons', [CouponController::class, 'index']);
Route::post('/cupons/validar', [CouponController::class, 'check']);
Route::get('/produtos', [ProductsController::class, 'index']);
Route::get('/produtos/{product}', [ProductsController::class, 'show']);

/* Rotas Autenticadas */
Route::middleware(['auth:sanctum'])->group(function () {

    // Usuário
    Route::prefix('/usuario')->group(function () {
        Route::get('/', [UserController::class, 'returnUser']);    
        Route::delete('/', [UserController::class, 'delete']);     
        Route::put('/', [UserController::class, 'update']);          
        Route::post('/sair', [LoginController::class, 'logoutin']);  
    });

    // Carrinho
    Route::prefix('/carrinho')->group(function () {
        Route::get('/', [CartController::class, 'index']);           
        Route::post('/itens', [CartItemController::class, 'store']); 
        Route::delete('/itens/{product}', [CartItemController::class, 'destroy']); 
    });

    // Endereços
    // Listar endereços
    Route::get('/enderecos/meu', [UserAdressController::class, 'index']);

    // Criar endereço
    Route::post('/enderecos/criar', [UserAdressController::class, 'adress']);

    // Atualizar endereço
    Route::put('/enderecos/atualizar/{userAdress}', [UserAdressController::class, 'update']);
    
    // Excluir endereço
    Route::delete('/enderecos/{endereco}', [UserAdressController::class, 'destroy'])
        ->name('enderecos.excluir');

    // Pedidos - Importante: rotas específicas antes das genéricas
    Route::get('/pedidos/ativos', [OrderController::class, 'indexOpen']);  
    Route::post('/pedidos', [OrderController::class, 'store']);            
    Route::get('/pedidos', [OrderController::class, 'index']);             
    Route::get('/pedidos/{order}', [OrderController::class, 'show']);     

    /* Rotas de Moderador */
    Route::middleware(['is_moderator'])->group(function () {
        // Categorias
        Route::post('/categorias', [CategoryController::class, 'store']);         
        Route::put('/categorias/{id}', [CategoryController::class, 'update']); 
        Route::delete('/categorias/{id}', [CategoryController::class, 'delete']); 

        // Produtos
        Route::put('/produtos/{product}', [ProductsController::class, 'update']);

        // Pedidos
        Route::put('/pedidos/{order}', [OrderController::class, 'update']);   
    });
    
    /* Rotas de Administrador */
    Route::middleware(['is_admin'])->group(function () {
        // Usuários
        Route::get('/usuarios', [UserController::class, 'index']);               
        Route::post('/usuarios', action: [UserController::class, 'create']);              
        Route::put('/usuario/permissoes', [UserController::class, 'setModerator']); 

        // Produtos
        Route::post('/produtos', [ProductsController::class, 'store']);              
        Route::delete('/produtos/{product}', [ProductsController::class, 'destroy']); 
        
        // Cupons
        Route::post('/cupons', [CouponController::class, 'store']);                  
        Route::delete('/cupons/{coupon}', [CouponController::class, 'destroy']);     

        // Descontos
        Route::post('/descontos', [DiscountsController::class, 'store']);            
        Route::put('/descontos/{discounts}', [DiscountsController::class, 'update']); 
        Route::delete('/descontos/{discounts}', [DiscountsController::class, 'destroy']); 

        // Acesso administrativo 
        Route::get('/admin/carrinhos/{user}', [CartController::class, 'adminIndex']); 
        Route::get('/pedidos/adm/ativos', [OrderController::class, 'orderAllAdminActive']); 
        Route::delete('/pedidos/deletar/adm/{order}', [OrderController::class, 'destroy']);   
        Route::get('/pedidos/', [OrderController::class, 'orderAllAdmin']);      
    });
});