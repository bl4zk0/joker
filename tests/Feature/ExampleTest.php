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
        $this->signIn();

        $this->json('GET', '/api/user')->assertStatus(200);
    }
}
