<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => ucfirst(explode('@', $validatedData['email'])[0]),
            'email' => $validatedData['email'],
            'password' => $validatedData['password']
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'data' => null
        ])->cookie(
            'jwt_token',
            $token,
            120,
            '/',
            null,
            false,
            true
        );
    }
}
