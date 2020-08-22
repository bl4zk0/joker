<?php

namespace App\Broadcasting;

use App\User;

class UserChannel
{
    /**
     * Authenticate the user's access to the channel.
     *
     * @param  User  $user
     * @return bool
     */
    public function join(User $user, $user_id)
    {
        return $user->id === (int) $user_id;
    }
}
