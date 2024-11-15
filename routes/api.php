<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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
 
});
