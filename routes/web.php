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

Route::get('/test', function () {
    $g = \App\Game::find(1);

    $ss = $g->scores()->latest()->limit(4)->get()->sortByDesc('result');
    $ss->each(function ($s) {
        $s->append('position');
    });
    broadcast(new \App\Events\GameOverEvent($g, $ss));
    return 'ok';
});

Auth::routes(['verify' => true]);

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->where('provider','facebook|google');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->where('provider','facebook|google');

Route::view('/', 'home')->name('home');
Route::get('/contact', 'ContactMeController@index');
Route::post('/contact', 'ContactMeController@store');
Route::get('/user/{user}', 'ProfilesController@show');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/lobby', 'GamesController@index')->middleware('verified');
    Route::get('/games/{game}', 'GamesController@show')->middleware('verified');
    Route::post('/games', 'GamesController@store')->middleware('verified');
    Route::post('/start/games/{game}', 'GamesController@start');
    Route::post('/ready/games/{game}', 'GamesController@ready');
    Route::post('/call/games/{game}', 'GamesController@call');
    Route::post('/card/games/{game}', 'GamesController@card');
    Route::post('/trump/games/{game}', 'GamesController@trump');
    Route::post('/kick/games/{game}', 'GamesController@kick');
    Route::post('/leave/games/{game}', 'GamesController@leave');
    Route::get('/email/change', 'ProfilesController@emailChangeForm')->middleware('password.confirm');
    Route::post('/email/change', 'ProfilesController@emailChange')->middleware('password.confirm');
    Route::get('/password/change', 'ProfilesController@passwordChangeForm')->middleware('password.confirm');
    Route::post('/password/change', 'ProfilesController@passwordChange')->middleware('password.confirm');
});
