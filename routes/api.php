<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ResetController;
use Illuminate\Support\Facades\Route;

Route::prefix('/categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

Route::prefix('/products')->group(function () {
    // Move search route BEFORE the {id} route to prevent conflict
    Route::get('/search', [ProductController::class, 'search']);
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});


Route::prefix('/carts')->group(function () {
    Route::post('/', [CartController::class, 'createCart']);
    Route::post('/{cart}/items', [CartController::class, 'addItem']);
    Route::get('/{cart}', [CartController::class, 'viewCart']);
    Route::post('/{cart}/items/{item}/increase', [CartController::class, 'increaseItemQuantity']);
    Route::post('/{cart}/items/{item}/decrease', [CartController::class, 'decreaseItemQuantity']);
    Route::delete('/{cart}/items/{item}', [CartController::class, 'removeItem']);
//    Route::post('/{table_number}/complete-order', [CartController::class,'completeorders'])->name('cart.completeorders');
    Route::get('/{cart}/viewcartorder' , [CartController::class,'viewcartorder'])->name('cart.viewcartorder');
//    Route::delete('/{id}/reset_storage', [ResetController::class, 'Delete']);
    Route::get('/get_status/{table_number}' , [CartController::class,'get_status']);
    Route::post('/clear/{table_number}' , [CartController::class,'clearcart']);

    Route::get('/table-token/{table_number}', [CartController::class, 'getCartToken']);
    Route::post('/items', [CartController::class, 'addItem']);
    Route::post('/createOrRetrieveCart', [CartController::class, 'createOrRetrieveCart']);
    Route::post('/{toke}/completeorders', [CartController::class,'completeorders'])->name('cart.completeorders');



});


