<?php

use App\Gravatar;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory('App\User')->create([
            'username' => 'bot1',
            'email' => 'bot1@jokerbot.local',
            'avatar_url' => Gravatar::url('bot1@joker.local')
        ]);

        factory('App\User')->create([
            'username' => 'bot2',
            'email' => 'bot2@jokerbot.local',
            'avatar_url' => Gravatar::url('bot2@joker.local')
        ]);

        factory('App\User')->create([
            'username' => 'bot3',
            'email' => 'bot3@jokerbot.local',
            'avatar_url' => Gravatar::url('bot3@joker.local')
        ]);

        factory('App\User')->create([
            'username' => 'bot',
            'email' => 'bot@jokerbot.local',
            'avatar_url' => Gravatar::url('bot@joker.local')
        ]);

        factory('App\User')->create([
            'username' => 'user',
            'email' => 'user@joker.local',
            'avatar_url' => Gravatar::url('user@joker.local')
        ]);

        factory('App\User')->create([
            'username' => 'Admin',
            'email' => 'admin@joker.local',
            'avatar_url' => Gravatar::url('admin@joker.local')
        ]);

    }
}
