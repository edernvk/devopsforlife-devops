<?php

use Illuminate\Database\Seeder;

class MockingMagazineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Magazine::class, 5)->create();
    }
}
