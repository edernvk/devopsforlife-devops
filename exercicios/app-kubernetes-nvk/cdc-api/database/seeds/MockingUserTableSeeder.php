<?php

use App\Role;
use Illuminate\Database\Seeder;

class MockingUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleColaborator  = Role::where('name', 'Colaborador')->first();

        factory(App\User::class, 50)->make()->each(function ($user) use ($roleColaborator) {

            if (random_int(0,5) >= 4) {
                $user->approved = null;
            }
            $user->save();

            $user->roles()->attach($roleColaborator);

        });
    }
}
