<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Traits\WithDatabaseDriver;
use App\State;

class CityTableSeeder extends Seeder
{
    use WithDatabaseDriver;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->delete();
        $json = File::get('database/data/cities.json');
        $data = json_decode($json);
        $states = collect($data->estados);

        $states->each(function ($state) {
            $stateId = State::where('state', $state->nome)->first()->id;
            $cities = collect($state->cidades);
            $stateDataset = $cities->map(function ($city) use ($stateId) {
                return array(
                    'name' => $city,
                    'state_id' => $stateId
                );
            });

            if ($this->databaseDriverIs('sqlite')) {
                $stateDataset->each(function($entry) {
                    DB::table('cities')->insert($entry);
                });
            } else {
                DB::table('cities')->insert($stateDataset->toArray());
            }

        });

    }
}
