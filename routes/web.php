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

//Route::get('/', function () {
//    return view('welcome');
//});

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');


Route::get('/', 'Web\AppController@getApp')->middleware('auth');

Route::get('/login', 'Web\AppController@getLogin')
    ->name('login')
    ->middleware('guest');

Route::get('/auth/{social}', 'Web\AuthenticationController@getSocialRedirect')
    ->middleware('guest');

Route::get('/auth/{social}/callback', 'Web\AuthenticationController@getSocialCallback')
    ->middleware('guest');

//Route::get('/home', function () {
//    return view('app');
//});
