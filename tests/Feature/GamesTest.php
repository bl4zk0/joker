<?php

namespace Tests\Feature;

use App\Events\GetReadyEvent;
use App\Events\UpdateLobbyEvent;
use App\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class GamesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_create_games()
    {
        $this->post('/games')->assertRedirect('/login');
    }

    /** @test */
    public function guests_cannot_join_games()
    {
        $game = factory(Game::class)->create();

        $this->get($game->path())->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_create_games_and_the_creator_is_added_as_a_player_0()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $response = $this->post('/games', ['rank' => 0, 'type' => 1, 'penalty' => '-200']);

        $games = Game::all();

        $response->assertRedirect($games[0]->path());

        $this->assertCount(1, $games);

        $this->assertCount(1, $games[0]->players);

        $this->assertEquals(0, $games[0]->players[0]->position);
    }

    /** @test */
    public function game_can_be_started_only_by_the_creator()
    {
        $creator = factory('App\User')->create();
        $game = factory('App\Game')->create(['user_id' => $creator->id]);

        $user = $this->signIn();

        $game->addPlayer($user);
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());

        $this->post('/start' . $game->path())->assertStatus(403);

        $this->signIn($creator);

        Event::fake();

        $this->post('/start' . $game->path());

        Event::assertDispatched(GetReadyEvent::class);
    }

    /** @test */
    public function game_cannot_be_started_until_4_players()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id]);

        $this->post('/start' . $game->path())->assertStatus(406);
    }

    /** @test */
    public function when_a_game_is_created_lobby_is_updated()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        Event::fake();

        $this->post('/games', ['rank' => 0, 'type' => 1, 'penalty' => '-200']);

        Event::assertDispatched(UpdateLobbyEvent::class);
    }

    /** @test */
    public function a_player_can_choose_trump()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['type' => 9, 'user_id' => $user->id, 'state' => 'trump']);

        $this->postJson('trump' . $game->path(), ['trump' => 'hearts']);

        $this->assertEquals(['strength' => 14, 'suit' => 'hearts'], $game->fresh()->trump);
    }

    public function when_a_player_joins_lobby_is_updated()
    {
        // TODO: maybe? lobbytest class?
    }

    public function authenticated_users_can_join_games()
    {
        // TODO: after implementing presence channels
    }

    /** @test */
    public function a_player_can_call()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id, 'state' => 'call']);

        $this->postJson('/call' . $game->path(), ['call' => 1]);

        $this->assertDatabaseHas('scores', ['call' => 1]);
    }

    /** @test */
    public function a_player_can_play_a_card()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id, 'state' => 'card']);

        $game->fresh()->players[0]->update(['cards' => [['strength' => 9, 'suit' => 'hearts']]]);

        $this->postJson('/card' . $game->path(), ['card' => ['strength' => 9, 'suit' => 'hearts']])->assertOk();
    }

    /** @test */
    public function check_take_updates_turn_if_not_4_cards()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id, 'cards' => [['strength' => 14, 'suit' => 'hearts']]]);
        $game->refresh();
        Event::fake();
        $this->assertEquals(0, $game->turn);

        $game->checkTake(['strength' => 14, 'suit' => 'hearts']);

        $this->assertEquals(1, $game->fresh()->turn);
    }

    /** @test */
    public function calculates_scores_after_each_hand()
    {
        $game = factory('App\Game')->create(['type' => 9]);
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());
        $game->refresh();

        $game->players[0]->scores()->create([
            'quarter' => 1,
            'call' => 9,
            'take' => 9
        ]);

        $game->players[1]->scores()->create([
            'quarter' => 1,
            'call' => 0,
            'take' => 0
        ]);

        $game->players[2]->scores()->create([
            'quarter' => 1,
            'call' => 0,
            'take' => 0
        ]);

        $game->players[3]->scores()->create([
            'quarter' => 1,
            'call' => 2,
            'take' => 0
        ]);

        $game->calcScoresAfterHand();

        //dd(\App\Score::all()->toArray());

        $this->assertEquals(900, $game->fresh()->players[0]->scores[0]->result);
        $this->assertEquals(50, $game->fresh()->players[1]->scores[0]->result);
        $this->assertEquals(50, $game->fresh()->players[2]->scores[0]->result);
        $this->assertEquals(-200, $game->fresh()->players[3]->scores[0]->result);
    }
}
