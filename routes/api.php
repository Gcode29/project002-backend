<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\AuthController;

Route::post('login', [AuthController::class, 'login']);


Route::group(['middleware'=>'auth:sanctum'], function() {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
//     return $request->user();
// });
