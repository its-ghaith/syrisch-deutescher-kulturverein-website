<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.app');
});
Route::get('/home', function () {
    return view('layouts.app');
});

Route::get('/login', function () {
    return view('layouts.Auth.login');
})->middleware('IsNotLogin');

Route::post('/login','Auth\UserController@login' );

Route::get('/getUserInfo','Auth\UserController@getUserInfo' );

Route::get('/register', function () {
    return view('layouts.Auth.register');
})->middleware('IsNotLogin');

Route::post('/register','Auth\UserController@register' );

Route::post('/logout','Auth\UserController@logout' )->middleware('IsLogin');


Route::get('/alsahaba_mosque', function () {
    return view('layouts.mosque.mosque_home');
});

Route::prefix('admin')->middleware('HasRole:admin')->group(function (){
    Route::get('/dashboard','Admin\PageController@dashboard');
    Route::get('/times_management/show_cities','Admin\PageController@showAllCities');
});
