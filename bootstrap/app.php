<?php

use App\Http\Middleware\AttachTokenFromCookie;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            AttachTokenFromCookie::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $errorMessage = $e->getMessage();

                if (str_contains($errorMessage, 'No query results for model')) {
                    preg_match('/\[App\\\\Models\\\\(\w+)\]/', $errorMessage, $matches);
                    $modelName = $matches[1] ?? 'Resource';

                    return response()->json([
                        'status' => 'fail',
                        'data' => [
                            'message' => $modelName . ' not found'
                        ]
                    ], 404);
                }

                if (
                    str_contains($errorMessage, 'The route') &&
                    str_contains($errorMessage, 'could not be found')
                ) {
                    return response()->json([
                        'status' => 'fail',
                        'data' => [
                            'message' => 'Route not found'
                        ]
                    ], 404);
                }
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'fail',
                    'data' => [
                        'message' => 'Unauthenticated'
                    ]
                ], 401);
            }
        });
    })->create();
