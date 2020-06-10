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
            'email' => 'ako@example.com'
        ]);

        factory('App\User')->create([
            'username' => 'Sally',
            'email' => 'sally@example.com'
        ]);

        factory('App\User')->create([
            'username' => 'John',
            'email' => 'john@example.com'
        ]);

        factory('App\User')->create([
            'username' => 'Jane',
            'email' => 'jane@example.com'
        ]);
    }
}
