<?php

use Illuminate\Support\Facades\Route;
use Faker\Generator as Faker;
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
    return view('welcome');
});

Route::get('/test', function (Faker $faker) {

});

Auth::routes(['verify' => true]);

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->where('provider','facebook|google');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->where('provider','facebook|google');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user/{user}', 'ProfilesController@show');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/lobby', 'GamesController@index');
    Route::get('/games/{game}', 'GamesController@show');
    Route::post('/games', 'GamesController@store');
    Route::post('/start/games/{game}', 'GamesController@start');
    Route::post('/call/games/{game}', 'GamesController@call');
    Route::post('/card/games/{game}', 'GamesController@card');
    Route::post('/trump/games/{game}', 'GamesController@trump');
    Route::post('/leave/games/{game}', 'GamesController@leave');
});
