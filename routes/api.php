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

Route::post('/auth', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/auth/refresh', [AuthController::class, 'refresh']);
Route::middleware('auth:api')->post('/auth/logout', [AuthController::class, 'logout']);


Route::middleware('auth:api')->group(function () {
    Route::post('/comptes', [CompteController::class, 'store']);
    // Route::delete('/comptes/{compte}', [CompteController::class, 'destroy']);
    Route::get('/comptes', [CompteController::class, 'index']);
    Route::get('/comptes/{compte}', [CompteController::class, 'showBynumero']);
    Route::delete('/comptes/{compte}', [CompteController::class, 'destroy']);
    Route::get('/comptes/telephone/{telephone}', [CompteController::class, 'showBytelephone']);
});

// Routes pour les clients (si nécessaire pour différencier les accès)
// Route::middleware(['auth:api'])->group(function () {
//     Route::get('/client/comptes', [CompteController::class, 'index']);
// });
