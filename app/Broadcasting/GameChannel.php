<?php

namespace App\Broadcasting;

use App\Game;
use App\User;

class GameChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\User  $user
     * @return array|bool
     */
    public function join(User $user, Game $game)
    {
        return $game->players->contains($user->player);
    }
}
