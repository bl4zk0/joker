<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gameTables()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/lobby');

        $response->assertStatus(200);
    }
}
