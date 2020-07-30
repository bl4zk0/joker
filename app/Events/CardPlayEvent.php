<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardPlayEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $gameId;
    public $position;
    public $card;
    public $take;

    /**
     * Create a new event instance.
     *
     * @param $gameId
     * @param $position
     * @param $card
     * @param bool $take
     */
    public function __construct($gameId, $position, $card, $take = false)
    {
        $this->gameId = $gameId;
        $this->position = $position;
        $this->card = $card;
        $this->take = $take;
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
