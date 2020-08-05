<?php


namespace App;


use App\Events\CardDealEvent;
use App\Events\CardPlayEvent;
use App\Events\PlayerCallEvent;
use App\Events\UpdateGameEvent;
use App\Jobs\BotJob;

class PlayerBot
{

    private $player, $game;

    /**
     * PlayerBot constructor.
     */
    public function __construct($player, $game)
    {
        $this->player = $player;
        $this->game = $game;
    }

    public function trump()
    {
        $this->game->update(['state' => 'call', 'trump' => ['strength' => 16, 'suit' => 'black_joker']]);
        $this->game->players->each(function ($player) {
            broadcast(new CardDealEvent($player->user->id, $player->cards));
        });

        broadcast(new UpdateGameEvent($this->game));
    }

    public function call()
    {
        $except = $this->game->exceptCall();

        $jokCount = $this->player->jokInCards();

        if ($except != $jokCount) {
            $call = $jokCount;
        } else {
            $call = $except == 0 ? 1 : 0;
        }

        $score = $this->player->scores()->create([
            'quarter' => $this->game->quarter,
            'call' => $call,
            'color' => 'white'
        ]);

        $this->game->updateTurn();
        $this->game->updateCallCount();

        broadcast(new PlayerCallEvent($this->game, $score, $this->player->position));

        if ($this->game->players[$this->game->turn]->disconnected) {
            BotJob::dispatch($this->game->players[$this->game->turn], $this->game)->delay(now()->addSeconds(1));
        }
    }

    public function card()
    {
        foreach ($this->player->cards as $card) {
            if ($this->player->canPlay($card, $this->game->cards, $this->game->trump)) {
                $this->player->removeCard($card);
                if (empty($this->game->cards) && $card['strength'] == 16) {
                    $card['action'] = 'magali';
                    $card['actionsuit'] = 'hearts';
                } elseif ($card['strength'] == 16) {
                    $card['action'] = 'mojokra';
                }
                $card = $this->game->addCard($card, $this->player);
                $checkTake = $this->game->checkTake();
                broadcast (new CardPlayEvent($this->game->id, $this->player->position, $card, $checkTake));
                if ($checkTake !== false) $this->game->checkEndOfTheHand();
                break;
            }
        }

        if ($this->game->players[$this->game->turn]->disconnected) {
            BotJob::dispatch($this->game->players[$this->game->turn], $this->game)->delay(now()->addSeconds(1));
        }

    }
}
