<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerCallEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $gameId;
    public $turn;
    public $state;
    public $score;
    public $position;
    public $except;

    /**
     * Create a new event instance.
     *
     * @param $game
     * @param $score
     * @param $position
     */
    public function __construct($game, $score, $position)
    {
        $this->gameId = $game->id;
        $this->except = $game->except;
        $this->turn = $game->turn;
        $this->state = $game->state;
        $this->score = $score;
        $this->position = $position;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('game.' . $this->gameId);
    }
}
