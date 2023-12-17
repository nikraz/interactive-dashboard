<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Todo combine namespaces of auth
Route::post('/login', 'App\Http\Controllers\Auth\AuthController@login');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/protected-route', function () {
        return response()->json(['message' => 'This is a protected route']);
    });
    Route::post('/logout', 'App\Http\Controllers\Auth\AuthController@logout');
});

Route::post('/password/email', 'App\Http\Controllers\Auth\PasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'App\Http\Controllers\Auth\PasswordController@reset')->name('password.reset');
