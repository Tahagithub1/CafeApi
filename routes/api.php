<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\CategoryConrtoller;
use \App\Http\Controllers\ProductController;

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
    Route::post('/', [CategoryConrtoller::class , 'store']);
    Route::delete('/{id}', [CategoryConrtoller::class , 'destroy']);
    Route::get('/{id}', [CategoryConrtoller::class , 'show']);
    Route::get('/', [CategoryConrtoller::class , 'index']);
});

Route::prefix('products')->group(function (){
    Route::get('/' , [ProductController::class , 'index']);
    Route::get('/{id}' , [ProductController::class , 'show']);
    Route::post('/' , [ProductController::class , 'store']);
    Route::delete('/{id}' , [ProductController::class , 'destroy']);
});

