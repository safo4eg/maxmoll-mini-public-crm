<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// orders
Route::resource('orders', OrderController::class)
    ->only(['index', 'store', 'update']);

// product
Route::resource('products', ProductController::class)
    ->only(['index']);

// warehouse
Route::resource('warehouses', WarehouseController::class);
