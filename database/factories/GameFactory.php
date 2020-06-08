<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Game;

$factory->define(Game::class, function () {
    return [
        'type' => 1,
        'penalty' => '-200',
        'user_id' => factory('App\User'),
        'rank' => 0
    ];
});
