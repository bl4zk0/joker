<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CardDealEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $user_id;
    public $cards;
    public $trump;

    /**
     * Create a new event instance.
     *
     * @param $user_id
     * @param $cards
     * @param bool $trump
     */
    public function __construct($user_id, $cards, $trump = false)
    {
        $this->user_id = $user_id;
        $this->cards = $cards;
        $this->trump = $trump;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user_id);
    }
}
