<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::group(['middleware' => ['auth:sanctum', 'role:admin|manager']], function () {
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/orders', [OrderController::class, 'index']);
    // Route::post('/orders', [OrderController::class, 'store'])->middleware('role:admin|client')->middleware('can:create,App\Models\Order');
    Route::post('/orders', [OrderController::class, 'store'])->middleware('can:create,App\Models\Order');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('can:view,order');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->middleware('can:update,order');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->middleware('can:delete,order');
});
