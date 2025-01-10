<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\JSendResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use JSendResponse;

    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => ucfirst(explode('@', $validatedData['email'])[0]),
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ]);

        $token = $user->createToken('auth_token')->accessToken;

        $cookie = cookie('access_token', $token, 60 * 24 * 3, null, null, false, true);

        return $this->jsend_success(null, 201)->cookie($cookie);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if (! Auth::attempt($validatedData)) {
            return $this->jsend_fail([
                'message' => 'Email or password is incorrect',
            ], 401);
        }

        $token = Auth::user()->createToken('auth_token')->accessToken;

        $cookie = cookie('access_token', $token, 60 * 24 * 3, null, null, false, true);

        return $this->jsend_success(null, 200)->cookie($cookie);
    }
}
