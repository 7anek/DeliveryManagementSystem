<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ApiAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
// Route::get('/', [OrderController::class, 'index'])->name('orders');
// Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.view');
// Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.delete');
// Route::post('/orders/', [OrderController::class, 'store'])->name('orders.create');
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('orders', OrderController::class);
});

Route::post('/login', [ApiAuthController::class, 'login']);
