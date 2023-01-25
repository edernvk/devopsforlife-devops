<?php

use App\City;
use App\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class UnregisterCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pathname = database_path('data');
        $file = File::get($pathname . '/cities-unregister.json');

        $data = json_decode($file, true);

        collect($data)->each(function ($data) {
            $state = State::where('acronym', 'like', $data['uf'])->first();
            City::create([
                'name' => $data['cidade'],
                'state_id' => $state->id
            ]);
        });
    }
}
