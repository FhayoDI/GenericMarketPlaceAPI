<?php

use App\Http\Controllers\LoginController;
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
        //logout
        Route::post('/logout', [LoginController::class, 'logout']);
        //endereços
        Route::post('/user/adress', [UserAdressController::class, 'adress']);
        Route::get('/user/adress', [UserAdressController::class, 'index']);
        Route::patch('/user/adress/update', [UserAdressController::class, 'update']);
        Route::put('/user/adress/delete', [UserAdressController::class, 'destroy']);
        Route::get('/user/adress/historico',[UserAdressController::class,'showHistoric']);
    }
);
