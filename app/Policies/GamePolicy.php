<?php

namespace App\Policies;

use App\Game;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can start a game.
     *
     * @param User $user
     * @param Game $game
     * @return mixed
     */
    public function start(User $user, Game $game)
    {
        return $user->is($game->creator) && $game->state === '0';
    }

    /**
     * Determine whether the user can set trump.
     *
     * @param  User  $user
     * @param  Game  $game
     * @return mixed
     */
    public function trump(User $user, Game $game)
    {
        return $game->players->contains($user->player) &&
            $user->player->position === $game->turn &&
            $game->state === 'trump';
    }

    /**
     * Determine whether the user can play a card.
     *
     * @param User $user
     * @param Game $game
     * @return mixed
     */
    public function card(User $user, Game $game)
    {
        return $game->players->contains($user->player) &&
            $user->player->position === $game->turn &&
            $game->state === 'card';
    }

    /**
     * Determine whether the user can call.
     *
     * @param  User  $user
     * @param  Game  $game
     * @return mixed
     */
    public function call(User $user, Game $game)
    {
        return $game->players->contains($user->player) &&
            $user->player->position === $game->turn &&
            $game->state === 'call';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User  $user
     * @param  Game  $game
     * @return mixed
     */
    public function leave(User $user, Game $game)
    {
        return $game->players->contains($user->player);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Game  $game
     * @return mixed
     */
    public function restore(User $user, Game $game)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Game  $game
     * @return mixed
     */
    public function forceDelete(User $user, Game $game)
    {
        //
    }
}
