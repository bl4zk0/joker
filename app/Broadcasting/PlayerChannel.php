<?php

namespace App\Broadcasting;

use App\Player;
use App\User;

class PlayerChannel
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
    public function join(User $user, Player $player)
    {
        return $user->id == $player->user_id;
    }
}
