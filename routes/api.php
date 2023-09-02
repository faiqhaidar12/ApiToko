<?php

use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\OrderController;
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

// Route::get('produk', [ProdukController::class, 'index']);
// Route::get('produk/{id}', [ProdukController::class, 'show']);
// Route::post('produk', [ProdukController::class, 'store']);
// Route::put('produk/{id}', [ProdukController::class, 'update']);
// Route::delete('produk/{id}', [ProdukController::class, 'destroy']);

Route::apiResource('produk', ProdukController::class);
Route::post('/checkout', [ProdukController::class, 'checkout']);
Route::post('midtrans-callback', [OrderController::class, 'callback']);
