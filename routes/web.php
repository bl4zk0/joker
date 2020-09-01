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

Auth::routes(['verify' => true]);

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->where('provider','facebook|google');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->where('provider','facebook|google');

Route::view('/', 'home')->name('home')->middleware('disconnected');
Route::get('/contact', 'ContactMeController@index');
Route::post('/contact', 'ContactMeController@store')->name('contact');
Route::get('/user/{user}', 'UsersController@show');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/lobby', 'GamesController@index')->name('lobby')->middleware('verified');
    Route::get('/email/change', 'UsersController@emailChangeForm')->middleware('password.confirm');
    Route::post('/email/change', 'UsersController@emailChange')->middleware('password.confirm');
    Route::get('/password/change', 'UsersController@passwordChangeForm')->middleware('password.confirm');
    Route::post('/password/change', 'UsersController@passwordChange')->middleware('password.confirm');
    Route::post('/admin/start/games/{game}', 'AdminsController@start');
    Route::post('/admin/cards/games/{game}', 'AdminsController@cards');
});

Route::group(['middleware' => ['auth', 'disconnected']], function () {
    Route::get('/games/{game}', 'GamesController@show')->middleware('verified');
    Route::post('/games', 'GamesController@store')->middleware('verified');
    Route::post('/join/games/{game}', 'GamesController@join');
    Route::post('/start/games/{game}', 'GamesController@start');
    Route::post('/ready/games/{game}', 'GamesController@ready');
    Route::post('/call/games/{game}', 'GamesController@call');
    Route::post('/card/games/{game}', 'GamesController@card');
    Route::post('/trump/games/{game}', 'GamesController@trump');
    Route::post('/kick/games/{game}', 'GamesController@kick');
    Route::post('/leave/games/{game}', 'GamesController@leave');
    Route::post('/bot/games/{game}', 'GamesController@bot');
    Route::post('/message/games/{game}', 'GamesController@message')->middleware('throttle:15,1');
});
