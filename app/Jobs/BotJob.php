<?php

namespace App\Jobs;

use App\PlayerBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $player, $game;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($player, $game)
    {
        $this->player = $player;
        $this->game = $game;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bot = new PlayerBot($this->player, $this->game);
        $method = $this->game->state;
        $bot->$method();
    }
}
