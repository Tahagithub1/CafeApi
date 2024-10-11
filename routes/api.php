<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Http\Controllers\CategoryConrtoller;

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

Route::post('/categories', [CategoryConrtoller::class , 'store']);
Route::delete('/categories/{id}', [CategoryConrtoller::class , 'destroy']);
Route::get('/categories/{id}', [CategoryConrtoller::class , 'show']);
Route::get('/categories', [CategoryConrtoller::class , 'index']);

