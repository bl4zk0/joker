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
            'username' => 'Ako',
            'email' => 'ako@example.com',
            'avatar_url' => Gravatar::url('ako@example.com')
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
            'email' => 'admin@mojokre.dev',
            'avatar_url' => Gravatar::url('admin@mojokre.dev')
        ]);
    }
}
