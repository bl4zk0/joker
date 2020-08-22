<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_call()
    {
        $score = factory('App\Score')->create();
        $score->refresh();

        $score->createCall(1, 1);

        $this->assertEquals(1, $score->data['q_1'][0]['call']);
    }

    /** @test */
    public function it_can_get_call()
    {
        $score = factory('App\Score')->create();
        $score->refresh();

        $score->createCall(1, 1);

        $this->assertEquals(1, $score->getData('call', 1, 1));
    }

    /** @test */
    public function it_can_increment_take()
    {
        $score = factory('App\Score')->create();
        $score->refresh();

        $score->createCall(1, 1);

        $this->assertEquals(0, $score->getData('take', 1, 1));
        $score->incrementTake(1);
        $this->assertEquals(1, $score->getData('take', 1, 1));
    }

    /** @test */
    public function game_gets_except_attribute_correctly_from_scores()
    {
        $score = factory('App\Score')->create();
        $score->refresh();
        $score->createCall(1, 3);
        $game = $score->game;
        $game->addPlayer($user = factory('App\User')->create());
        $game->scores()->create(['player_id' => $user->player->id, 'position' => 1]);
        $score2 = \App\Score::find(2);
        $score2->createCall(1, 3);
        $game->refresh();
        $game->type = 9;
        $game->call_count = 3;
        $this->assertEquals(3, $game->except);
    }

    /** @test */
    public function it_can_update_data()
    {
        $score = factory('App\Score')->create();
        $score->refresh();

        $score->createCall(1, 3);

        $score->incrementTake(1);
        $score->incrementTake(1);
        $score->calcHandResult(1, 9, -500);
        $score->updateColor(1, 'y', 20);
        $score->createCall(1, 1);
        $score->incrementTake(1);
        $score->calcHandResult(1, 9, -500);
        $this->assertEquals(100, $score->fresh()->maxResult(1));
    }
}
