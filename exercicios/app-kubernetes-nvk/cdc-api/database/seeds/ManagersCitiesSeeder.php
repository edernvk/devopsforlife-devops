<?php

use App\City;
use App\Manager;
use App\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ManagersCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = database_path('data');
        $file = File::get($path . '/managers-cities.json');


        $outherFile = File::get($path . '/managers-cities1.json');

        $outherData = collect(json_decode($outherFile, true));

        $outherData->each(function ($data) {
            $manager = Manager::find($data['manager_id']);
            $manager->cities()->attach($data['city_id']);
        });

        $data = collect(json_decode($file, true));

        $data->each(function ($data) {
            $manager = Manager::find($data['manager_id']);

            $state = State::where('acronym', 'like', $data['uf'])->first();

            $city = City::where('state_id', '=', $state->id)
                ->where('name', 'like', '%'. $data['name'] . '%')
                ->first();

            $manager->cities()->attach($city);
            sleep(0.5);
        });
    }
}
