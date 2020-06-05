<?php

namespace Tests\Unit;

use App\Deck;
use PHPUnit\Framework\TestCase;

class DeckTest extends TestCase
{
    /** @test */
    public function deck_is_36_cards()
    {
        $deck = new Deck;

        $this->assertCount(36, $deck->cards);
    }

    /** @test */
    public function it_deals_cards()
    {
        $deck = new Deck;

        $this->assertCount(4, $deck->deal(4));
    }

    /** @test */
    public function it_can_determine_last_players_position()
    {
        $deck = new Deck;

        $lastPlayer = $deck->lastPlayer();

        $this->assertTrue(in_array($lastPlayer['pos'], [0, 1, 2, 3]));
        $this->assertIsArray($lastPlayer['cards']);
    }
}
