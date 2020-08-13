<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can start a game.
     * @param User $user
     * @return boolean
     */
    public function update(User $user)
    {
        return $user->id === Auth::id() && ! $user->socialite_account;
    }
}
