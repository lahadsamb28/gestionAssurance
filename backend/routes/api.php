<?php

use App\Http\Controllers\AttestationController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
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
    Route::post('login', 'Login');
    Route::post('register', 'Register')->middleware(['auth:sanctum']);
    Route::get('profil/show', 'Show')->middleware(['auth:sanctum']);
    Route::get('profil/edit/{id}', 'Edit')->middleware(['auth:sanctum']);
    Route::put('profil/{id}', 'Update')->middleware(['auth:sanctum']);
    Route::delete('profil/delete/{id}', 'Delete')->middleware(['auth:sanctum']);
    Route::get('logout', 'Logout')->middleware(['auth:sanctum']);

});


Route::middleware(['auth:sanctum'])->group(function (){
    Route::controller(AttestationController::class)->group(function (){
        Route::post('attestation/add', 'AddAttestation');
        Route::get('attestation/show', 'ShowAttestations');
        Route::get('attestation/edit/{id}', 'GetAttestation');
        Route::put('attestation/update/{id}', 'UpdateAttestation');
        Route::delete('attestation/delete/{id}', 'DeleteAttestation');
        Route::delete('attestation/proprietaire/delete/{id}', 'DeleteAttestationProprio');
        Route::delete('attestation/vehicule/delete/{id}', 'DeleteAttestationVehicule');
    });


    Route::controller(StockController::class)->group(function (){
        Route::post('stock/add', 'AddStock');
        Route::get('stock/show', 'ShowStocks');
        Route::get('stock/edit/{id}', 'GetStock');
        Route::put('stock/update/{id}', 'UpdateStock');
        Route::delete('stock/delete/{id}', 'DeleteStock');

    });
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
