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
Route::get('/cupons', [CouponController::class, 'index']);
Route::post('/cupons/validar', [CouponController::class, 'check']);
Route::get('/produtos', [ProductsController::class, 'index']);
Route::get('/produtos/{product}', [ProductsController::class, 'show']);

/* Rotas Autenticadas */
Route::middleware(['auth:sanctum'])->group(function () {

    // Usuário
    Route::prefix('/usuario')->group(function () {
        Route::get('/', [UserController::class, 'returnUser']);      // GET /usuario
        Route::delete('/', [UserController::class, 'delete']);     // DELETE /usuario
        Route::put('/', [UserController::class, 'update']);           // PUT /usuario
        Route::post('/sair', [LoginController::class, 'logoutin']);           // POST /usuario/sair
    });

    // Carrinho
    Route::prefix('/carrinho')->group(function () {
        Route::get('/', [CartController::class, 'index']);              // GET /carrinho
        Route::post('/itens', [CartItemController::class, 'store']); // POST /carrinho/itens
        Route::delete('/itens/{product}', [CartItemController::class, 'destroy']); // DELETE /carrinho/itens/{produto}
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

    // Pedidos
    Route::post('/pedidos', [OrderController::class, 'store']);            // POST /pedidos
    Route::get('/pedidos', [OrderController::class, 'index']);             // GET /pedidos
    Route::get('/pedidos/{order}', [OrderController::class, 'show']);  // GET /pedidos/{pedido}

    /* Rotas de Moderador */
    Route::middleware(['moderador'])->group(function () {
        // Categorias
        Route::post('/categorias', [CategoryController::class, 'store']);          // POST /categorias
        Route::put('/categorias/{id}', [CategoryController::class, 'update']); // PUT /categorias/{categoria}
        Route::delete('/categorias/{id}', [CategoryController::class, 'delete']); // DELETE /categorias/{categoria}

        // Produtos
        Route::put('/produtos/{product}', [ProductsController::class, 'update']); // PUT /produtos/{produto}

        // Pedidos
        Route::put('/pedidos/{order}', [OrderController::class, 'update']);    // PUT /pedidos/{pedido}
    });

    /* Rotas de Administrador */
    Route::middleware(['admin'])->group(function () {
        // Usuários
        Route::get('/usuarios', [UserController::class, 'index']);               // GET /usuarios
        Route::post('/usuarios', action: [UserController::class, 'create']);              // POST /usuarios
        Route::put('/usuario/permissoes', [UserController::class, 'setModerator']); // PUT /usuarios/{usuario}/permissoes

        // Produtos
        Route::post('/produtos', [ProductsController::class, 'store']);              // POST /produtos
        Route::delete('/produtos/{product}', [ProductsController::class, 'destroy']); // DELETE /produtos/{produto}

        // Cupons
        Route::post('/cupons', [CouponController::class, 'store']);                  // POST /cupons
        Route::delete('/cupons/{coupon}', [CouponController::class, 'destroy']);      // DELETE /cupons/{cupom}

        // Descontos
        Route::post('/descontos', [DiscountsController::class, 'store']);            // POST /descontos
        Route::put('/descontos/{discounts}', [DiscountsController::class, 'update']); // PUT /descontos/{desconto}
        Route::delete('/descontos/{discounts}', [DiscountsController::class, 'destroy']); // DELETE /descontos/{desconto}

        // Acesso administrativo
        Route::get('/admin/carrinhos/{user}', [CartController::class, 'adminIndex']); // GET /admin/carrinhos/{usuario}
        Route::delete('/pedidos/{order}', [OrderController::class, 'destroy']);   // DELETE /pedidos/{pedido}
    });
});
