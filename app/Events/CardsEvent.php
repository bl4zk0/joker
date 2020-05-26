<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $playerId;
    public $cards;
    public $trump;

    /**
     * Create a new event instance.
     *
     * @param $playerId
     * @param $cards
     * @param bool $trump
     */
    public function __construct($playerId, $cards, $trump = false)
    {
        $this->cards = $cards;
        $this->playerId = $playerId;
        $this->trump = $trump;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('player.' . $this->playerId);
    }
}
