<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerKickedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $gameId;
    public $username;
    public $players;

    /**
     * Create a new event instance.
     *
     * @param $gameId
     * @param $username
     * @param $players
     */
    public function __construct($gameId, $username, $players)
    {
        $this->gameId = $gameId;
        $this->username = $username;
        $this->players = $players;
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
