<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerJoinLeaveEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $gameId;
    public $username;
    public $eventName;
    public $players;
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @param $gameId
     * @param $username
     * @param $eventName
     * @param bool $players
     * @param bool $user_id
     */
    public function __construct($gameId, $username, $eventName, $players = false, $user_id = false)
    {
        $this->gameId = $gameId;
        $this->username = $username;
        $this->eventName = $eventName;
        $this->players = $players;
        $this->players = $players;
        $this->user_id = $user_id;
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
