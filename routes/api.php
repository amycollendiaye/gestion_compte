<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompteController;

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

// Routes pour les comptes
Route::post('/comptes/validate', [CompteController::class, 'validateCompte']);
Route::apiResource('comptes', CompteController::class)->except('show');
Route::get('comptes/{numero}', [CompteController::class, 'showByNumero']);
Route::get('comptes/telephone/{telephone}',[CompteController::class,"showBytelephone"]);






