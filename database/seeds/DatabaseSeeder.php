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
            'name' => 'Ako',
            'email' => 'ako@example.com'
        ]);

        factory('App\User')->create([
            'name' => 'Sally',
            'email' => 'sally@example.com'
        ]);

        factory('App\User')->create([
            'name' => 'John',
            'email' => 'john@example.com'
        ]);

        factory('App\User')->create([
            'name' => 'Jane',
            'email' => 'jane@example.com'
        ]);
    }
}
