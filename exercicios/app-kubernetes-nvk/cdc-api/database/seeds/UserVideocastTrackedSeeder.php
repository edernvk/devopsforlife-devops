<?php

use Illuminate\Database\Seeder;

class UserVideocastTrackedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\UserVideocastTracked::class, 10)->create();
    }
}
