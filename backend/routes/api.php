<?php

use App\Http\Controllers\AttestationController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
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

Route::controller(UserController::class)->group(function (){
    Route::post('inscription', 'Inscription');
    Route::post('connexion', 'Connexion');
});

Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('logout', [UserController::class, 'Logout']);


    Route::controller(AttestationController::class)->group(function (){

    });


    Route::controller(StockController::class)->group(function (){
        Route::post('ajouter_stock', 'AjoutStock');

    });
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
