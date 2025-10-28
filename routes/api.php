<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

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
Route::post('/auth', [AuthController::class, 'login']);
Route::post('comptes',[CompteController::class,"store"]);
Route::apiResource('comptes', CompteController::class)->except('show');
Route::get('comptes/{numero}', [CompteController::class, 'showByNumero']);
Route::get('comptes/telephone/{telephone}',[CompteController::class,"showBytelephone"]);

// Routes pour les clients





