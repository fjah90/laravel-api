<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('user')->group(function () {

    Route::post('register', 'App\Http\Controllers\UserController@register');
    Route::post('login', 'App\Http\Controllers\UserController@login');

    // passport auth api
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/', 'App\Http\Controllers\UserController@user');
        Route::get('logout', 'App\Http\Controllers\UserController@logout');
    });
});


