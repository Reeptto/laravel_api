<?php
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\ProdukController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;


Route::apiResource('users', UserApiController::class);
Route::apiResource('bukus', BukuController::class);
Route::apiResource('produks', ProdukController::class);

Route::post('/produks/{id}/images', [ProdukController::class, 'uploadImages']);
Route::put('produk-images/{id}', [ProdukController::class, 'updateImage']);
Route::delete('produk-images/{id}', [ProdukController::class, 'deleteImage']);

Route::apiResource('orders', OrdersController::class);
Route::put('orders/{id}/status',[OrdersController::class,'updateStatus']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function(Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});