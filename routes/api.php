<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;

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
        Route::post('register/{isAdmin?}', 'App\Http\Controllers\UserController@register');
        Route::post('login', 'App\Http\Controllers\UserController@login');

        Route::middleware(['auth:api'])->group(function () {
            //store Users as Admin
            Route::post('register', 'App\Http\Controllers\AdminController@registerAsAdmin');
            //show current user
            Route::get('/', 'App\Http\Controllers\UserController@user');

            Route::get('logout', 'App\Http\Controllers\UserController@logout');
        });
    });

    Route::prefix('admin')->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::post('register', 'App\Http\Controllers\AdminController@registerAsAdmin');
            Route::get('teachers', 'App\Http\Controllers\AdminController@showTeachers');
            Route::get('students', 'App\Http\Controllers\AdminController@showStudents');
            Route::get('users', 'App\Http\Controllers\AdminController@showUsersByRole');
        });
    });

    Route::prefix('courses')->group(function () {
        //Show Courses
        Route::get('/', 'App\Http\Controllers\AdminController@showCourses');
        //create
        Route::post('/', 'App\Http\Controllers\AdminController@storeCourse');
        //create and add teacher
        Route::post('/{teacherId}', 'App\Http\Controllers\AdminController@storeCourseAndAddTeacher');
        //show by Id
        Route::get('/{id}', 'App\Http\Controllers\AdminController@showCourseById');
        //update
        Route::put('/{id}', 'App\Http\Controllers\AdminController@updateCourseById');
        //Delete
        Route::delete('/{id}', 'App\Http\Controllers\AdminController@destroyCourseById');
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

    Route::prefix('teacher')->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::get('/', 'App\Http\Controllers\UserController@user');
            //get all enrolling courses
            // Route::get('/courses', 'App\Http\Controllers\TeacherController@showCourses');
            //dictate course
            Route::post('/dictate/{courseId}/{teacherId?}', 'App\Http\Controllers\TeacherController@dictateCourse');
            //destroy dictate course
            Route::delete('/dictate/{courseId}', 'App\Http\Controllers\TeacherController@destroyDictateCourse');
            //get dictate course by tacher Id
            Route::get('/courses/{courseId}/{teacherId}/', 'App\Http\Controllers\TeacherController@getCoursesByIdAndTeacherId');
            //get all dictate courses
            Route::get('/courses', 'App\Http\Controllers\TeacherController@showCourses');
            //get dictate course by Id
            Route::get('/courses/{courseId}', 'App\Http\Controllers\TeacherController@showCoursesByCourseId');
        });
    });
});


