<?php

use App\City;
use App\Role;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PenzeUsersSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataSet = [];
        $avatar = null;
        $password = Hash::make('cdcdigital2019');

        $dataSet[] = [
            'name' => 'Eder Fonseca',
            'email' => 'eder@penze.com.br',
            'registration' => '9999999992',
            'mobile' => '(18) 99658-0203',
            'cpf' => '99999999992',
            'avatar' => $avatar,
            'password' => $password,
            'city_id' => 4823,
            'team_id' => 1,
            'approved' => Carbon::now()
        ];
        $dataSet[] = [
            'name' => 'João Augusto',
            'email' => 'jaugusto@penze.com.br',
            'registration' => '9999999993',
            'cpf' => '99999999993',
            'mobile' => '(18) 99784-1244',
            'avatar' => $avatar,
            'password' => $password,
            'city_id' => 4823,
            'team_id' => 1,
            'approved' => Carbon::now()
        ];
        $dataSet[] = [
            'name' => 'Adenilton',
            'email' => 'adenilton@penze.com.br',
            'registration' => '9999999994',
            'cpf' => '99999999994',
            'mobile' => '(18) 99757-8184',
            'avatar' => $avatar,
            'password' => $password,
            'city_id' => 4823,
            'team_id' => 1,
            'approved' => Carbon::now()
        ];

        $role  = Role::where('name', 'Administrador')->first();

        foreach($dataSet as $obj) {
            $user = User::create([
                'name' => $obj['name'],
                'email' => $obj['email'],
                'registration' => $obj['registration'],
                'mobile' => $obj['mobile'],
                'avatar' => $obj['avatar'],
                'password' => $obj['password'],
                'city_id' => $obj['city_id'],
                'team_id' => $obj['team_id'],
                'approved' => $obj['approved']
            ]);

            $user->roles()->attach($role);
        }

//        DB::table('users')->insert($dataSet);
    }
}
