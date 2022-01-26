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
Route::prefix('v1')->group(function () {

    Route::prefix('user')->group(function () {
        Route::post('register', 'App\Http\Controllers\UserController@register');
        Route::post('login', 'App\Http\Controllers\UserController@login');

        Route::middleware(['auth:api'])->group(function () {
            Route::get('/', 'App\Http\Controllers\UserController@user');
            Route::get('logout', 'App\Http\Controllers\UserController@logout');
        });
    });

    Route::prefix('courses')->group(function () {

        Route::get('/', 'App\Http\Controllers\AdminController@showCourses');

        Route::middleware(['auth:api'])->group(function () {
            //create
            Route::post('/', 'App\Http\Controllers\AdminController@storeCourse');
            //show
            Route::get('/{id}', 'App\Http\Controllers\AdminController@showCourseById');
            //update
            Route::put('/{id}', 'App\Http\Controllers\AdminController@updateCourseById');
            //Delete
            Route::delete('/{id}', 'App\Http\Controllers\AdminController@destroyCourseById');
        });
    });

    Route::prefix('student')->group(function () {

        Route::middleware(['auth:api'])->group(function () {
            Route::get('/', 'App\Http\Controllers\UserController@user');
            //get all enrolling courses
            Route::get('/courses', 'App\Http\Controllers\StudentController@coursesShow');
            //enrolling course
            Route::post('/enrrolling/{courseId}', 'App\Http\Controllers\StudentController@enrolling');
            //unsubscribe course
            Route::post('/unsubscribe/{id}', 'App\Http\Controllers\StudentController@unsubscribe');
            //get enrolling course by Id
            Route::get('/courses/{courseId}', 'App\Http\Controllers\StudentController@courseShowById');
        });
    });
});


