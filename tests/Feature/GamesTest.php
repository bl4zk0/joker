<?php

namespace Tests\Feature;

use App\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GamesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_games()
    {
        $this->post('/games')->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_create_games()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $response = $this->post('/games');

        $game = Game::all();
        $response->assertRedirect($game[0]->path());
        $this->assertCount(1, $game);

        $this->assertCount(1, $game[0]->players);
    }

    /** @test */
    public function it_can_add_players()
    {
        $this->withoutExceptionHandling();
        $game = factory('App\Game')->create();

        $this->signIn();

        $this->get('/join/games/' . $game->id);

        $this->assertCount(2, $game->fresh()->players);
    }
}
