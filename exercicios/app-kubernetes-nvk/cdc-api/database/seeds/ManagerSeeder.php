<?php

use App\City;
use App\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(Manager::class, 10)->create();

        $path = database_path('data');
        $file = File::get($path . '/managers.json');

        $citiesPath = database_path('data');

        $managers = collect(json_decode($file, true));

        $managers->each(function ($manager) {
            $newManager = Manager::create($manager);
        });
    }
}
