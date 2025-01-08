<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (! $rawToken = $request->cookie('jwt_token')) {
                return response()->json([
                    'status' => 'fail',
                    'data' => [
                        'errors' => [
                            'message' => 'Unauthenticated',
                        ],
                    ],
                ], 401);
            }

            $payload = FacadesJWTAuth::setToken($rawToken)->authenticate();

            Auth::loginUsingId($payload['sub']);
        } catch (TokenExpiredException) {
            // TODO: Implement token refresh
            return response()->json([
                'status' => 'fail',
                'data' => [
                    'errors' => [
                        'message' => 'Token expired',
                    ],
                ],
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'fail',
                'data' => [
                    'errors' => [
                        'message' => 'Unauthenticated',
                    ],
                ],
            ], 401);
        }

        return $next($request);
    }
}
