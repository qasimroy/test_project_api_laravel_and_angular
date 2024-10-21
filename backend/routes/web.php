<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware(['auth:sanctum'])->group(function () {
// });
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');
Route::delete('/products/{id}', [DashboardController::class, 'destroy']);

Route::get('/login', function(){
    return view('signin.signin');
});
