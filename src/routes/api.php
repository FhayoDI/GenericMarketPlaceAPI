<?php

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
Route::post('/registro',[UserController::class,'registration']);
Route::post('/login',[UserController::class,'login']);
Route::post('/logout',[UserController::class,'logout']);

//endereços
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user/adress/all',[UserAdressController::class,'index']);
        
});