<?php

use App\Team;
use Illuminate\Database\Seeder;

class MockingTeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Team::class, 10)->create();
    }
}
