<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('lobby', function ($user) {
    return auth()->check();
});
Broadcast::channel('game.{game}', \App\Broadcasting\GameChannel::class);
Broadcast::channel('player.{player}', \App\Broadcasting\PlayerChannel::class);
