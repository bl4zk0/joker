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
            'username' => 'user',
            'email' => 'user@example.com',
            'avatar_url' => Gravatar::url('user@example.com')
        ]);

        factory('App\User')->create([
            'username' => 'Sally',
            'email' => 'sally@example.com',
            'avatar_url' => Gravatar::url('sally@example.com')
        ]);

        factory('App\User')->create([
            'username' => 'John',
            'email' => 'john@example.com',
            'avatar_url' => Gravatar::url('john@example.com')
        ]);

        factory('App\User')->create([
            'username' => 'Jane',
            'email' => 'jane@example.com',
            'avatar_url' => Gravatar::url('jane@example.com')
        ]);

        factory('App\User')->create([
            'username' => 'Admin',
            'email' => 'admin@joker.local',
            'avatar_url' => Gravatar::url('admin@joker.local')
        ]);
    }
}
