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
        return $user->is($game->creator) && $game->state === 'start';
    }

    /**
     * Determine whether the user can get ready.
     *
     * @param User $user
     * @param Game $game
     * @return mixed
     */
    public function ready(User $user, Game $game)
    {
        return $game->players->contains($user->player) && $user->isNot($game->creator) &&
            $game->state == 'ready' && (! in_array($user->id, $game->ready['players']));
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
        return $game->players->contains($user->player) && $user->player->position === $game->turn && $game->state === 'trump';
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
        return $game->players->contains($user->player) && $user->player->position === $game->turn && $game->state === 'card';
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
        return $game->players->contains($user->player) && $user->player->position === $game->turn && $game->state === 'call';
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
     * Determine whether the user can kick a player.
     *
     * @param User $user
     * @param Game $game
     * @return mixed
     */
    public function kick(User $user, Game $game)
    {
        return ($user->is($game->creator) || $user->isAdmin) && $game->state === 'start';
    }

    /**
     * Determine whether the user can join the game.
     *
     * @param  \App\User  $user
     * @param  Game $game
     * @return mixed
     */
    public function join(User $user, Game $game)
    {
        return ! in_array($user->id, $game->kicked_users);
    }

    /**
     * Determine whether the user can activate the bot.???
     *
     * @param  \App\User  $user
     * @param Game $game
     * @return mixed
     */
    public function bot(User $user, Game $game)
    {
        $states = ['trump', 'call', 'card'];
        return $game->players->contains($user->player) && $user->player->position === $game->turn && in_array($game->state, $states);
    }

    /**
     * Determine whether the user can broadcast a message.
     *
     * @param \App\User $user
     * @param Game $game
     * @return mixed
     */
    public function message(User $user, Game $game)
    {
        return $game->players->contains($user->player);
    }
}
