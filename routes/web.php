<?php

use App\Deck;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

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

Route::post('/test', function () {

    request()->validate([
        'rank' => ['required', 'integer', 'min:0', "max:7"],
        'type' => ['required', 'in:1,9'],
        'penalty' => ['required', 'in:-200,-300,-400,-500,-900,-1000'],
        'password' => ['sometimes', 'accepted']
    ]);
    $password = request()->has('password') ? sprintf("%04d", rand(0, 9999)) : null;

    dd($password);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/lobby', 'GamesController@index');
    Route::get('/games/{game}', 'GamesController@show');
    Route::post('/games', 'GamesController@store');
    Route::post('/start/games/{game}', 'GamesController@start');
    Route::post('/call/games/{game}', 'GamesController@call');
    Route::post('/card/games/{game}', 'GamesController@card');
    Route::post('/trump/games/{game}', 'GamesController@trump');
});
