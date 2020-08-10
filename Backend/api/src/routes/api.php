<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post('register', 'Api\AuthController@register');
    Route::post('login', 'Api\AuthController@login');
    Route::post('password_reset', 'Api\AuthController@passwordReset');
    Route::post('change_password_reset_link', 'Api\AuthController@changePasswordResetLink');
    Route::post('isChangePasswordTokenValid', 'Api\AuthController@isChangePasswordTokenValid');

    Route::get('times/cities','Api\TimeController@index');

    Route::prefix('times')->group(function (){
        Route::get('{city}','Api\TimeController@show');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'Api\AuthController@logout');
        Route::post('user', 'Api\AuthController@user');

        Route::prefix('superadmin')->middleware('role:superadmin')->group(function (){
            Route::resource('users','Api\Superadmin\UserController');
            Route::put('users/{userId}/restore','Api\Superadmin\UserController@restore')->name('users.restore');
            Route::post('update-password','Api\Superadmin\UserController@updatePassword')->name('users.updatePassword');
            Route::post('users/{user}/update-password-user','Api\Superadmin\UserController@updateUserPassword')->name('users.updateUserPassword');
//            Route::get('users-trashed','Api\Superadmin\UserController@indexTrashed')->name('users.indexTrashed');

            Route::resource('cities','Api\Superadmin\CityController');
            Route::put('cities/{cityId}/restore','Api\Superadmin\CityController@restore')->name('cities.restore');
            Route::post('cities/{cityId}/put-photo','Api\Superadmin\CityController@putPhoto')->name('cities.putPhoto');
//            Route::get('cities-trashed','Api\Superadmin\CityController@indexTrashed');
        });

        Route::prefix('admin')->middleware('role:admin')->group(function (){
            Route::post('times','Api\Admin\TimeController@store');
            Route::delete('times/{city}','Api\Admin\TimeController@destroy');
            Route::get('cities','Api\Admin\TimeController@index');
            Route::get('{city}','Api\Admin\TimeController@show');
        });
    });
});


