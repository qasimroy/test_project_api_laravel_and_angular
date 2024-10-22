<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('products', ProductController::class);
Route::apiResource('tags', TagController::class);
Route::apiResource('categories', CategoryController::class);

Route::post('login', [AuthController::class, 'login'])->name('api.login');
Route::get('logout', [AuthController::class, 'destroy'])->name('api.logout');


