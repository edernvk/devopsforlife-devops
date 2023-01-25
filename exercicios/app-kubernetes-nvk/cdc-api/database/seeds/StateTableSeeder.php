<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\State;
use Illuminate\Support\Facades\File;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->delete();
        $json = File::get('database/data/cities.json');
        $data = json_decode($json);

        foreach($data->estados as $obj) {
            State::create(array(
                'state' => $obj->nome,
                'acronym' => $obj->sigla
            ));
        }
    }
}
