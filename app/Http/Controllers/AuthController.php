<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function store(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'id' => $user->id,
        ];

        return response($response, 201);
    }

    public function me(): User
    {
        return auth()->user();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json('logout', 201);
    }
}
