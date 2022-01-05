<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;

Route::post('login', [AuthController::class, 'store'])->name('login');


Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('me', [AuthController::class, 'me'])->name('me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::apiResource('categories', CategoryController::class);
});