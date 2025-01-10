<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\JSendResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
