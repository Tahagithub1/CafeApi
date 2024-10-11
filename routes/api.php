<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\CategoryConrtoller;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\CartContoller;

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
    Route::get('/', [CategoryConrtoller::class , 'index']);
    Route::get('/{id}', [CategoryConrtoller::class , 'show']);
    Route::post('/', [CategoryConrtoller::class , 'store']);
    Route::delete('/{id}', [CategoryConrtoller::class , 'destroy']);
});

Route::prefix('products')->group(function (){
    Route::get('/' , [ProductController::class , 'index']);
    Route::get('/{id}' , [ProductController::class , 'show']);
    Route::post('/' , [ProductController::class , 'store']);
    Route::delete('/{id}' , [ProductController::class , 'destroy']);
});

Route::prefix('carts')->group(function (){
    Route::post('/' , [CartContoller::class , 'createCart']);
    Route::post('/{cart}/items' , [CartContoller::class , 'addItem']);
    Route::get('{cart}' , [CartContoller::class , 'viewCart']);
});

