<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\PlayerJoinLeaveEvent;
use App\Events\UpdateLobbyEvent;

class WShelperJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $toLobby;
    private $gameId;
    private $username;
    private $eventName;
    private $players;
    private $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $toLobby,
        $gameId = false,
        $username = false,
        $eventName = false,
        $players = false,
        $userId = false
    )
    {
        $this->toLobby = $toLobby;
        $this->gameId = $gameId;
        $this->username = $username;
        $this->eventName = $eventName;
        $this->players = $players;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->toLobby) {
            broadcast(new UpdateLobbyEvent());
        } else {
            broadcast(new PlayerJoinLeaveEvent(
                $this->gameId,
                $this->username,
                $this->eventName,
                $this->players,
                $this->userId
            ));
        }
    }
}
