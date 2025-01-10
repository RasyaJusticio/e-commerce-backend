<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});
