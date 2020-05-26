<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_a_player()
    {
        $game = factory('App\Game')->create();

        $user = factory('App\User')->create();

        $game->addPlayer($user);

        $this->assertCount(2, $game->players);
    }

    /** @test */
    public function it_has_a_path()
    {
        $game = factory('App\Game')->create();

        $this->assertEquals('/games/' . $game->id, $game->path());
    }
}
