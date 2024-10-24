<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\CategoryController;  // Fixed typo: Conrtoller -> Controller
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;      // Fixed typo: Contoller -> Controller


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

Route::prefix('/categories')->group(function (){
    Route::get('/', [CategoryController::class, 'index']);          // Fixed class name
    Route::get('/{id}', [CategoryController::class, 'show']);       // Fixed class name
    Route::post('/', [CategoryController::class, 'store']);         // Fixed class name
    Route::delete('/{id}', [CategoryController::class, 'destroy']); // Fixed class name
});

Route::prefix('products')->group(function (){
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::post('/', [ProductController::class, 'store']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);
});

Route::prefix('carts')->group(function (){
    Route::post('/', [CartController::class, 'createCart']);        // Fixed class name
    Route::post('/{cart}/items', [CartController::class, 'addItem']);
    Route::get('{cart}', [CartController::class, 'viewCart']);
    Route::post('{cart}/items/{item}/increase', [CartController::class, 'increaseItemQuantity']);
    Route::post('{cart}/items/{item}/decrease', [CartController::class, 'decreaseItemQuantity']);
});

