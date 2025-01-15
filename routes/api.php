<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::post('register', 'register');
    Route::post('login', 'login');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('logout', 'logout');
        Route::post('logout-all', 'logoutAll');
    });
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'categories', 'controller' => AdminCategoryController::class], function () {
        Route::get('', 'index');
        Route::post('', 'store');

        Route::group(['prefix' => '{category}'], function () {
            Route::get('', 'show');
            Route::put('', 'update');
        });
    });
});
