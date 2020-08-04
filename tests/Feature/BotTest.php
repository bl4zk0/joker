<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BotTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sets_trump()
    {
        $creator = factory('App\User')->create();
        $game = factory('App\Game')->create(['user_id' => $creator->id, 'state' => 'trump']);

        $game->refresh();

        $bot = new \App\PlayerBot($game->players[0], $game);

        $bot->trump();

        $this->assertEquals(['strength' => 16, 'suit' => 'black_joker'], $game->trump);
    }

    /** @test */
    public function it_can_call()
    {
        $creator = factory('App\User')->create();
        $game = factory('App\Game')->create(['user_id' => $creator->id, 'state' => 'trump']);

        $game->refresh();

        $player = $game->players[0];

        $bot = new \App\PlayerBot($player, $game);

        $bot->call();

        $this->assertEquals(0, $player->scores[0]->call);
    }
}
