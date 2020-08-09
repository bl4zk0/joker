<?php

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
            'email' => 'https://www.gravatar.com/avatar/' . md5('ako@example.com') . '?s=50&d=retro&f=y'
        ]);

        factory('App\User')->create([
            'username' => 'Sally',
            'email' => 'https://www.gravatar.com/avatar/' . md5('sally@example.com') . '?s=50&d=retro&f=y'
        ]);

        factory('App\User')->create([
            'username' => 'John',
            'email' => 'https://www.gravatar.com/avatar/' . md5('john@example.com') . '?s=50&d=retro&f=y'
        ]);

        factory('App\User')->create([
            'username' => 'Jane',
            'email' => 'https://www.gravatar.com/avatar/' . md5('jane@example.com') . '?s=50&d=retro&f=y'
        ]);
    }
}
