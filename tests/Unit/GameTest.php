<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $game = factory('App\Game')->create();

        $this->assertEquals('/games/' . $game->id, $game->path());
    }

    /** @test */
    public function it_can_validate_game_type_and_penalty()
    {
        $this->signIn();

        $this->post('/games', ['type' => 3, 'penalty' => '-100'])
            ->assertSessionHasErrors(['type', 'penalty']);
    }

    /** @test */
    public function it_can_add_a_player()
    {
        $game = factory('App\Game')->create();

        $game->addPlayer(factory('App\User')->create());

        $this->assertCount(1, $game->players);
    }

    /** @test */
    public function it_determines_positions_correctly()
    {
        $user = factory('App\User')->create();
        $game = factory('App\Game')->create(['user_id' => $user->id]);
        $game->addPlayer($user);
        $this->assertEquals(1, $game->determinePosition());

        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());

        $this->assertEquals(3, $game->determinePosition());

        $game->players[1]->delete();

        $this->assertEquals(1, $game->fresh()->determinePosition());
    }

    /** @test */
    public function it_updates_turn_correctly()
    {
        $game = factory('App\Game')->create();

        $game->updateTurn();

        $this->assertEquals(1, $game->turn);

        $game->updateTurn();
        $game->updateTurn();
        $game->updateTurn();

        $this->assertEquals(0, $game->turn);
    }

    /** @test */
    public function it_determines_except_number_and_validates_call()
    {
        $user = factory('App\User')->create();
        $game = factory('App\Game')->create(['type' => 9, 'user_id' => $user->id, 'state' => 'call', 'call_count' => 3, 'quarter' => 1]);

        $game->addPlayer($user);
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());
        $game->addPlayer(factory('App\User')->create());

        $game->refresh();

        $game->players->each(function ($player) use ($game) {
            $game->scores()->create([
                'player_id' => $player->id,
                'position' => $player->position
            ]);
        });

        $game->scores[0]->createCall(1, 3);
        $game->scores[1]->createCall(1, 2);
        $game->scores[2]->createCall(1, 3);
        
        $this->assertEquals(1, $game->exceptCall());

    }

    /** @test */
    public function it_validates_call()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id, 'state' => 'call']);
        $game->addPlayer($user);

        $this->postJson('/call' . $game->path(), ['call' => 9])
            ->assertStatus(422);
    }

    /** @test */
    public function it_validates_trump()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id, 'type' => 9, 'state' => 'trump']);
        $game->addPlayer($user);

        $this->postJson('/trump' . $game->path(), ['trump' => 'foobar'])
            ->assertStatus(422);

        $this->postJson('/trump' . $game->path(), ['trump' => 'hearts']);

        $this->assertEquals(['strength' => 14, 'suit' => 'hearts'], $game->fresh()->trump);
    }

    /** @test */
    public function it_validates_card()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id, 'type' => 9, 'state' => 'card']);
        $game->addPlayer($user);
        $game->refresh();

        $user->player->update(['cards' => [['strength' => 12, 'suit' => 'hearts'], ['strength' => 7, 'suit' => 'hearts']]]);

        $this->postJson('/card' . $game->path(), ['card' => ['strength' => 14, 'suit' => 'hearts']])
            ->assertStatus(422);
    }

    /** @test */
    public function it_authorizes_call()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['state' => 'call']);
        $game->addPlayer($game->creator);

        $game->addPlayer($user);

        $this->postJson('/call' . $game->path())
            ->assertStatus(403);
    }

    /** @test */
    public function it_authorizes_trump()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['state' => 'trump']);
        $game->addPlayer($game->creator);

        $game->addPlayer($user);

        $this->postJson('/trump' . $game->path())
            ->assertStatus(403);
    }

    /** @test */
    public function it_authorizes_card()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['state' => 'card']);
        $game->addPlayer($game->creator);
        $game->addPlayer($user);

        $this->postJson('/card' . $game->path())
            ->assertStatus(403);
    }

    /** @test */
    public function when_not_card_state_players_cant_play_a_card()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id, 'state' => 'call']);

        $this->postJson('/card' . $game->path())
            ->assertStatus(403);
    }

    /** @test */
    public function when_creator_leaves_it_sets_other_player_as_creator()
    {
        // TODO
        $this->assertTrue(true);
    }

    /** @test */
    public function when_creator_leaves_and_no_other_players_left_game_is_deleted()
    {
        // TODO
        $this->assertTrue(true);
    }

    /** @test */
    public function it_authorizes_start()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['state' => '0']);

        $game->addPlayer($user);

        $this->postJson('/start' . $game->path())
            ->assertStatus(403);
    }

    /** @test */
    public function it_can_add_a_card()
    {
        $user = $this->signIn();

        $game = factory('App\Game')->create(['user_id' => $user->id, 'trump' => ['strength' => 14, 'suit' => 'hearts']]);
        $game->addPlayer($user);

        $game->refresh();

        $game->addCard(['strength' => 14, 'suit' => 'hearts'], $user->player);

        $this->assertTrue(in_array(['strength' => 14, 'suit' => 'hearts'], $game->cards, true));
        $this->assertEquals(['strength' => 14, 'suit' => 'hearts'], $game->players[0]->card);

        $game->update(['cards' => [
            ['strength' => 14, 'suit' => 'hearts'],
            ['strength' => 16, 'suit' => 'color_joker', 'action' => 'mojokra'],
            ['strength' => 11, 'suit' => 'hearts']
        ]]);

        $game->addCard(['strength' => 16, 'suit' => 'black_joker', 'action' => 'mojokra'], $user->player);

        $this->assertEquals(['strength' => 17, 'suit' => 'black_joker', 'action' => 'mojokra'], $game->highestCard());

        $game->update(['cards' => [
            ['strength' => 14, 'suit' => 'hearts'],
            ['strength' => 16, 'suit' => 'color_joker', 'action' => 'mojokra'],
            ['strength' => 11, 'suit' => 'hearts']
        ]]);

        $game->addCard(['strength' => 16, 'suit' => 'black_joker', 'action' => 'nije'], $user->player);

        $this->assertEquals(['strength' => 16, 'suit' => 'color_joker', 'action' => 'mojokra'], $game->highestCard());
    }

    /** @test */
    public function it_can_determine_highest_card()
    {
        $game = factory('App\Game')->create(['trump' => ['strength' => 16, 'suit' => 'black_joker']]);

        $game->update(['cards' =>[
            ['strength' => 14, 'suit' => 'hearts'],
            ['strength' => 8, 'suit' => 'hearts'],
            ['strength' => 9, 'suit' => 'hearts'],
            ['strength' => 11, 'suit' => 'hearts'],
        ]]);

        $this->assertEquals(['strength' => 14, 'suit' => 'hearts'], $game->highestCard());

        $game->update(['cards' =>[
            ['strength' => 14, 'suit' => 'hearts'],
            ['strength' => 8, 'suit' => 'hearts'],
            ['strength' => 9, 'suit' => 'spades'],
            ['strength' => 11, 'suit' => 'hearts'],
        ], 'trump' => ['strength' => 14, 'suit' => 'spades']]);

        $this->assertEquals(['strength' => 9, 'suit' => 'spades'], $game->highestCard());

        $game->update(['cards' =>[
            ['strength' => 16, 'suit' => 'color_joker', 'action' => 'caigos', 'actionsuit' => 'hearts'],
            ['strength' => 8, 'suit' => 'hearts'],
            ['strength' => 9, 'suit' => 'hearts'],
            ['strength' => 11, 'suit' => 'hearts'],
        ]]);

        $this->assertEquals(['strength' => 11, 'suit' => 'hearts'], $game->highestCard());

        $game->update(['cards' =>[
            ['strength' => 16, 'suit' => 'color_joker', 'action' => 'magali', 'actionsuit' => 'hearts'],
            ['strength' => 8, 'suit' => 'hearts'],
            ['strength' => 17, 'suit' => 'black_joker', 'action' => 'mojokra'],
            ['strength' => 11, 'suit' => 'hearts'],
        ]]);

        $this->assertEquals(['strength' => 17, 'suit' => 'black_joker', 'action' => 'mojokra'], $game->highestCard());
    }
}
