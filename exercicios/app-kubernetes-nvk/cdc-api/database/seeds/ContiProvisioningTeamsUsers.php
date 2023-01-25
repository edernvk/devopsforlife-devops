<?php

use App\City;
use App\Role;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ContiProvisioningTeamsUsers extends Seeder
{
    public function run()
    {
//        DB::table('teams')->delete();
//        DB::table('teams')->truncate();
        $json = File::get('database/data/conti-teams.json');
        $data = json_decode($json);

        foreach($data as $obj) {
            Team::create(array(
                'name' => $obj->name
            ));
        }

        $role  = Role::where('name', 'Colaborador')->first();
        $roleAdmin  = Role::where('name', 'Administrador')->first();

        $json = File::get('database/data/conti-users.json');
        $data = json_decode($json);

        foreach($data as $obj) {
            $user = User::create([
                'name' => $obj->name,
                'email' => $obj->email,
                'registration' => $obj->registration,
                'mobile' => $obj->mobile,
                'password' => Hash::make('cdcdigital2019'),
                'city_id' => City::where('name', $obj->city)->first()->id,
                'team_id' => Team::where('name', $obj->team)->first()->id,
                'approved' => Carbon::now()
            ]);

            if (isset($obj->admin) || array_key_exists('admin', $obj)) {
                $user->roles()->attach($roleAdmin);
            } else {
                $user->roles()->attach($role);
            }

        }
    }
}
