<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\PageController;

Route::post('login', [AuthController::class, 'login']);

// Route::post('/login', function (Request $request) {
//     $data = $request->validate([
//         'email' => 'required|email',
//         'password' => 'required'
//     ]);

//     $user = User::where('email', $request->email)->first();

//     if (!$user || !Hash::check($request->password, $user->password)) {
//         return response([
//             'message' => ['These credentials do not match our records.']
//         ], 404);
//     }

//     $token = $user->createToken('my-app-token')->plainTextToken;

//     $response = [
//         'user' => $user,
//         'token' => $token
//     ];

//     return response($response, 201);
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
