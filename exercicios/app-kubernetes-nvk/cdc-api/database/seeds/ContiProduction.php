<?php

use App\City;
use App\Role;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ContiProduction extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/data/conti-teams.json');
        $data = json_decode($json);

        foreach($data as $obj) {
            Team::create(array(
                'name' => $obj->name
            ));
        }

        $role  = Role::where('name', 'Colaborador')->first();

        $json = File::get('database/data/conti-production.json');
        $data = json_decode($json);

        foreach($data as $obj) {
            $user = User::create([
                'name' => $obj->name,
                'email' => $obj->email,
                'registration' => $obj->registration,
                'cpf' => $obj->cpf,
                'mobile' => $obj->mobile,
                'password' => Hash::make('cdcdigital2019'),
                'city_id' => City::where('name', $obj->city)->first()->id,
                'team_id' => Team::where('name', $obj->team)->first()->id,
                'approved' => Carbon::now()
            ]);

            $user->roles()->attach($role);
        }
    }
}
