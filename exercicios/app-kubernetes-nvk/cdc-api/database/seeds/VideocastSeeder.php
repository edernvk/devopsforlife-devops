<?php

use Illuminate\Database\Seeder;

class VideocastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Videocast::class, 10)->create();
    }
}
