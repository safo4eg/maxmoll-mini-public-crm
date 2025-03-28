<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\StockMoveController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// orders
Route::resource('orders', OrderController::class)
    ->only(['index', 'store', 'update']);
Route::name('orders.')->prefix('orders')->group(function () {
    Route::patch('/{order}/complete', [OrderController::class, 'complete'])
        ->name('complete');
    Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('cancel');
    Route::patch('/{order}/resume', [OrderController::class, 'resume'])
        ->name('resume');
});

// product
Route::resource('products', ProductController::class)
    ->only(['index']);

// warehouse
Route::resource('warehouses', WarehouseController::class);

// история остатков
Route::get('/stock_moves', [StockMoveController::class, 'index'])
    ->name('stock_move.index');
