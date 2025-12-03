<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\products;
use App\Http\Controllers\ProductsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/products', function (Request $request) {
//     return $request->products;
// });

Route::post('/products', [ProductsController::class, 'store']);

Route::get('/products', [ProductsController::class, 'index']);

Route::get('/products/{product}', [ProductsController::class, 'show']);

Route::put('/products/{product}', [App\Http\Controllers\ProductsController::class, 'update']);

Route::delete('/products/{product}', [ProductsController::class, 'destroy']);

Route::get('/products/low-stock', [ProductsController::class, 'lowStock']);
