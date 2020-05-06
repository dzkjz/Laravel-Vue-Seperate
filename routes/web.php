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


//Route::get('/', 'Web\AppController@getApp')->middleware('auth');
Route::get('/', 'Web\AppController@getApp');
Route::get('/home', 'Web\AppController@getApp');
Route::get('/cafes/{cafe}', 'Web\AppController@getApp');
//Route::get('/login', 'Web\AppController@getLogin')
//    ->name('login')
//    ->middleware('guest');

Route::get('/auth/{social}', 'Web\AuthenticationController@getSocialRedirect')
    ->middleware('guest');

Route::get('/auth/{social}/callback', 'Web\AuthenticationController@getSocialCallback')
    ->middleware('guest');

//Route::get('/home', function () {
//    return view('app');
//});

//Route::get('geocode', function () {
//    return \App\Utilities\GaodeMaps::geocodeAddress('锦江区二环路东5段220号北90米', '成都市', '四川省');
//});
Route::get('logout', 'Web\AppController@getLogout')->middleware('auth');
