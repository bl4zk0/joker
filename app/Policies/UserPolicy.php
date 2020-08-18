<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update profile
     * @param User $user
     * @param User $requestUser
     * @return boolean
     */
    public function update(User $user, User $requestUser)
    {
        return $user->id === $requestUser->id && ! $user->socialite_account;
    }
}
