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

        $this->assertCount(36, $deck->deal(36));
    }
}
