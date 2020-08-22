<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Score;
use Faker\Generator as Faker;

$factory->define(Score::class, function (Faker $faker) {
    $game = factory('App\Game')->create();
    $game->addPlayer($game->creator);
    return [
        'player_id' => $game->creator->player->id,
        'game_id' => $game->id,
        'position' => 0
    ];
});
