<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => ucfirst(explode('@', $validatedData['email'])[0]),
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'data' => null,
        ])->cookie(
            'jwt_token',
            $token,
            120,
            null,
            null,
            false,
            true
        );
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        if (! $token = JWTAuth::attempt($validatedData)) {
            return response()->json([
                'status' => 'fail',
                'data' => [
                    'errors' => [
                        'message' => 'Email or password is incorrect',
                    ],
                ],
            ], 401);
        }

        // $user = JWTAuth::toUser($token);
        // $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

        return response()->json([
            'status' => 'success',
            'data' => null,
        ])->cookie(
            'jwt_token',
            $token,
            120,
            null,
            null,
            false,
            true
        );
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'status' => 'success',
            'data' => null,
        ]);
    }
}
