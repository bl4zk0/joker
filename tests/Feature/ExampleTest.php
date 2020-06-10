<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function exTest()
    {
//        $this->withoutExceptionHandling();
//
//        $user = $this->signIn();
//
//        $game = factory('App\Game')->create(['type' => 9, 'user_id' => $user->id, 'state' => 'card']);
//        auth()->user()->player->update(['cards' => [
//            ['strength' => 14, 'suit' => 'hearts'],
//            ['strength' => 11, 'suit' => 'spades'],
//            ['strength' => 7, 'suit' => 'clubs'],
//            ['strength' => 9, 'suit' => 'diamonds'],
//            ['strength' => 16, 'suit' => 'color_joker']
//        ]]);
//        $this->postJson('/test', ['card' => ['suit' => 'color_joker', 'strength' => 16], 'action' => 'mojokra', 'actionsuit' => 'hearts'])
//            ->assertStatus(422);
        //$this->get('/')->assertStatus(200);

        $this->assertTrue(true);
    }
}
