<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ScoresTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_game_started_empty_scores_are_created()
    {
        $game = factory('App\Game')->create();
        $game->addPlayer($game->creator);
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());

        Event::fake();

        $game->start();

        $this->assertDatabaseCount('scores', 4);
    }

    /** @test */
    public function when_user_calls_score_is_updated()
    {
        $this->withoutExceptionHandling();
        $game = factory('App\Game')->create(['state' => 'call']);
        $game->addPlayer($game->creator);
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());

        $this->signIn($game->creator);
        Event::fake();
        $game->fresh()->start();

        $this->postJson('call' . $game->path(), ['call' => 1]);

        $this->assertEquals(1, $game->scores[0]->data['q_1'][0]['call']);
    }
}
